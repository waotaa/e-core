<?php

namespace Vng\EvaCore\Services\Cognito;

use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient;
use Aws\CognitoIdentityProvider\Exception\CognitoIdentityProviderException;
use Carbon\Carbon;
use Vng\EvaCore\Models\Environment;
use Vng\EvaCore\Models\Professional;
use Aws\Laravel\AwsFacade;
use Aws\Result;
use Illuminate\Support\Collection;

class CognitoService
{
    /**
     * Sync flow: (daily cron to sync professionals table)
     *
     * Get pool id from pool name
     * Get all users in pool
     * Check if user is found in professional table
     * If not, create professional entry
     * For professionals found in userpool update last_found column with current date
     * Remove all professionals who don't exist in user pool (last_found !== current date)
     * Or update status. Gives admin the power to either remove or re-add
     */

    /**
     * User creation
     * On Professional observer, add user to pool on creation
     * Ask for verification code / url (Possible? > Seems not)
     * Send verification mail from nova to ensure same template as other notifications (Handled by AWS)
     */

    /**
     * Password reset - handled by AWS
     * Password reset nova action triggers email notification
     * Professional gets url to reset page (is it easy to create reset page next to our current user reset page?)
     * https://medium.com/backenders-club/password-brokers-reset-passwords-on-multiple-tables-in-laravel-73068542925c#id_token=eyJhbGciOiJSUzI1NiIsImtpZCI6IjEzZThkNDVhNDNjYjIyNDIxNTRjN2Y0ZGFmYWMyOTMzZmVhMjAzNzQiLCJ0eXAiOiJKV1QifQ.eyJpc3MiOiJodHRwczovL2FjY291bnRzLmdvb2dsZS5jb20iLCJuYmYiOjE2MTc0NDA4MTcsImF1ZCI6IjIxNjI5NjAzNTgzNC1rMWs2cWUwNjBzMnRwMmEyamFtNGxqZGNtczAwc3R0Zy5hcHBzLmdvb2dsZXVzZXJjb250ZW50LmNvbSIsInN1YiI6IjEwNjA5MDQ1NDkwOTIyODk2ODU2NCIsImVtYWlsIjoid291dGVyc3RlZ2dlcmRhQGdtYWlsLmNvbSIsImVtYWlsX3ZlcmlmaWVkIjp0cnVlLCJhenAiOiIyMTYyOTYwMzU4MzQtazFrNnFlMDYwczJ0cDJhMmphbTRsamRjbXMwMHN0dGcuYXBwcy5nb29nbGV1c2VyY29udGVudC5jb20iLCJuYW1lIjoiV291dGVyIFN0ZWdnZXJkYSIsInBpY3R1cmUiOiJodHRwczovL2xoMy5nb29nbGV1c2VyY29udGVudC5jb20vYS0vQU9oMTRHaWVfdGVVQ21Nc3luV20zTXFwSnV3a09YZW1CMHNCdlVWWUxQZW9uOUU9czk2LWMiLCJnaXZlbl9uYW1lIjoiV291dGVyIiwiZmFtaWx5X25hbWUiOiJTdGVnZ2VyZGEiLCJpYXQiOjE2MTc0NDExMTcsImV4cCI6MTYxNzQ0NDcxNywianRpIjoiMGRmMjBmYTEyZWVlNTQxMjQ3NWQ5N2E0ZWIzM2NiZGNjN2FkZmQxNCJ9.md6bZc4hBSH_mCS-XnSlHoitfqxrwotY_jaim-maHXVBfVdrNQ_uMGHsr_d06IfsqrcYoEUW8b3_UFSDGKG4XCawfFnLB_PqRO892fSt7IbbPDUoJMyCpriSn0lOw7agoQP07dVkhdKZvuBvYtv05VvWG_FJZntwzQJLxWb-TFlaOKjhAweQvE-do4ehy7u5HoXB0koqw48pPBWfS_HH5lfM1iM_2HwnCy7eVJslG17OuPbpKnJi502p7FBM9AaXmD0uAntrtJLNqneNZHHz1HsvRHFwlFkV-jRIcQdv39oJdxHhb5BF73wXIlpFYDULn2ToqAGdrjSxoBz05lSTow
     * Reset password with API call
     */

    /**
     * User deletion
     * On Professional observer, remove user from pool on deletion
     * Send confirmation mail? If accidental do X
     */


    /**
     * Nova actions
     * Sync now
     * Reset password -> invalidate current password
     */

    public function __construct(
        protected Environment $environment
    )
    {}

    public static function make(Environment $environment): static
    {
        return new static($environment);
    }

    public function ensureSetup(): Environment
    {
        $userPoolModel = UserPoolService::ensureUserPool($this->environment);
        $this->environment->user_pool_id = $userPoolModel->getId();

        $userPoolClientModel = UserPoolClientService::ensureUserPoolClient($this->environment);
        $this->environment->user_pool_client_id = $userPoolClientModel->getClientId();

        return $this->environment;
    }

    public function fetchNewCognitoUsers()
    {
        $users = $this->findNewCognitoUsers();
        $users->each(function (UserModel $user) {
            $this->updateOrCreateProfessionalQuietly($user);
        });
    }

    public function findNewCognitoUsers()
    {
        $users = $this->getUsers();
        if (is_null($users)) {
            return null;
        }
        return $users->filter(function (UserModel $user) {
            return is_null($this->findProfessional($user));
        });
    }

    /**
     * Gets all users from the idp and updates the database accordingly
     */
    public function syncProfessionals($destructive = true): void
    {
        $this->fetchNewCognitoUsers();

        $professionals = $this->environment->professionals()->get();
        if (is_null($professionals)) {
            return;
        }

        $professionals->each(fn (Professional $p) => $this->syncProfessional($p, $destructive));
    }

    /**
     * Sync the given professional with the idp data
     * Deletes the professional when not present (if destructive is true)
     */
    public function syncProfessional(Professional $professional, $destructive = true): ?Professional
    {
        $user = $this->getUser($professional);
        if (is_null($user)) {
            if ($destructive) {
                $professional->delete();
            }
            return null;
        }
        return $this->updateOrCreateProfessionalQuietly($user);
    }

    protected function findProfessional(UserModel $user): ?Professional
    {
        /** @var Professional|object|static|null $professional */
        $professional = Professional::query()->where([
            'environment_id' => $this->environment->id,
            'email' => $user->getEmail(),
        ])->first();
        return $professional;
    }

    protected function updateOrCreateProfessionalQuietly(UserModel $user): ?Professional
    {
        return Professional::withoutEvents(function() use ($user) {
            return Professional::query()->updateOrCreate([
                'environment_id' => $this->environment->id,
                'email' => $user->getEmail(),
            ],[
                'last_seen_at' => now(),
                'username' => $user->getUsername(),
                'email_verified' => $user->isEmailVerified(),
                'enabled' => $user->isEnabled(),
                'user_status' => $user->getUserStatus(),
            ]);
        });
    }

    public function resetExpiredPasswords()
    {
        $users = $this->getUsers();
        if (is_null($users)) {
            return;
        }
        $users->each(function (UserModel $user) {
            $this->checkPasswordExpiration($user);
        });
    }

    public function checkPasswordExpiration(UserModel $user)
    {
        $pwUpdatedAt = $user->getPasswordUpdatedAtDate();
        if (is_null($pwUpdatedAt)){
            // set a password_updated_at date if no current data is available
            $userPool = UserPoolService::getUserPoolByEnvironment($this->environment);
            static::updatePasswordUpdatedAtAttribute($userPool, $user->getUsername());
        } elseif ($user->isPasswordExpired()){
            $this->resetPassword($user->getUsername());
        }
    }

//    protected static function getProfessional(UserModel $user): ?Professional
//    {
//        /** @var Professional|null $professional */
//        $professional = Professional::query()->where('email', $user->getEmail())->first();
//        return $professional;
//    }

    protected function removeProfessionalsNotSeenNow()
    {
        Professional::withoutEvents(function() {
            $missingProfessionals = Professional::query()
                ->where('last_seen_at', '<', now())
                ->where('environment_id', $this->environment->id)
                ->get();
            $missingProfessionals->each(fn (Professional $prof) => $prof->delete());
        });
    }

    protected function removeProfessionalsNotSeenInLastIteration()
    {
        Professional::withoutEvents(function() {
            $latestSeenDate = Professional::query()->max('last_seen_at');
            if (!is_null($latestSeenDate)) {
                $missingProfessionals = Professional::query()
                    ->where('last_seen_at', '<', $latestSeenDate)
                    ->where('environment_id', $this->environment->id)
                    ->get();
                $missingProfessionals->each(fn (Professional $prof) => $prof->delete());
            }
        });
    }

    public function getUser(Professional $professional): ?UserModel
    {
        $userPool = UserPoolService::getUserPoolByEnvironment($this->environment);
        if (is_null($userPool)) {
            return null;
        }
        try {
            $user = static::adminGetUser($userPool->getId(), $professional->username);
        } catch (CognitoIdentityProviderException $e) {
            if ($e->getAwsErrorCode() === 'UserNotFoundException') {
                return null;
            }
        }
        return UserModel::create($user);
    }

    protected function getUsers(): ?Collection
    {
        $userPool = UserPoolService::getUserPoolByEnvironment($this->environment);
        if (is_null($userPool)) {
            return null;
        }

        return static::getUsersForUserpool($userPool);
    }

    protected static function getUsersForUserpool(UserPoolModel $userPool, string $paginationToken = null): ?Collection
    {
        $listUsersResponse = static::listUsers($userPool, $paginationToken);
        $users = collect($listUsersResponse['Users'])->map(fn($user) => UserModel::create($user));

        if ($listUsersResponse['PaginationToken']) {
            static::sleepForRateLimit(30);
            $users->merge(static::getUsersForUserpool($userPool, $listUsersResponse['PaginationToken']));
        }
        return $users;
    }

    protected static function listUsers(UserPoolModel $userPool, string $paginationToken = null): Result
    {
        /** @var CognitoIdentityProviderClient $cognitoClient */
        $cognitoClient = AwsFacade::createClient('CognitoIdentityProvider');
        $args = [
            'UserPoolId' => $userPool->getId(),
        ];
        if (!is_null($paginationToken)) {
            $args['PaginationToken'] = $paginationToken;
        }
        return $cognitoClient->listUsers($args);
    }

    protected static function adminGetUser($userPoolId, string $username): Result
    {
        /** @var CognitoIdentityProviderClient $cognitoClient */
        $cognitoClient = AwsFacade::createClient('CognitoIdentityProvider');
        return $cognitoClient->adminGetUser([
            'UserPoolId' => $userPoolId,
            'Username' => $username,
        ]);
    }

    public function createProfessional(Professional $professional)
    {
        $userPool = UserPoolService::getUserPoolByEnvironment($this->environment);
        $result = static::adminCreateUser($userPool, static::getDefaultAdminCreateUserArgs($professional));
        return static::updateOrCreateProfessionalQuietly(UserModel::create($result['User']));
    }

    public function resendInvitationNotification(Professional $professional)
    {
        $userPool = UserPoolService::getUserPoolByEnvironment($this->environment);
        $args = static::getDefaultAdminCreateUserArgs($professional);
        $args['MessageAction'] = 'RESEND';
        $result = static::adminCreateUser($userPool, $args);
        return static::updateOrCreateProfessionalQuietly(UserModel::create($result['User']));
    }

    // https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_AdminCreateUser.html
    protected static function getDefaultAdminCreateUserArgs(Professional $professional): array
    {
        return [
            'DesiredDeliveryMediums' => ['EMAIL'],
            'ForceAliasCreation' => true,
            'UserAttributes' => [
                [
                    'Name' => 'email',
                    'Value' => $professional->email,
                ],
                [
                    'Name' => 'email_verified',
                    'Value' => 'true',
                ],
            ],
            'Username' => $professional->email,
        ];
    }

    protected static function adminCreateUser(UserPoolModel $userPool, array $args = []): Result
    {
        /** @var CognitoIdentityProviderClient $cognitoClient */
        $cognitoClient = AwsFacade::createClient('CognitoIdentityProvider');
        $args['UserPoolId'] = $userPool->getId();
        return $cognitoClient->adminCreateUser($args);
    }

    /**
     * Resets the specified user's password in a user pool as an administrator. Works on any user. When a developer
     * calls this API, the current password is invalidated, so it must be changed.
     * If a user tries to sign in after the API is called, the app will get a PasswordResetRequiredException exception
     * back and should direct the user down the flow to reset the password, which is the same as the forgot password
     * flow. In addition, if the user pool has phone verification selected and a verified phone number exists for the
     * user, or if email verification is selected and a verified email exists for the user, calling this API will also
     * result in sending a message to the end user with the code to change their password.
     *
     * @param string $username
     */
    public function resetPassword(string $username): void
    {
        $userPool = UserPoolService::getUserPoolByEnvironment($this->environment);
        static::adminResetUserPassword($userPool, $username);
        // Might be nice to update this field on pw reset in front-end
        static::updatePasswordUpdatedAtAttribute($userPool, $username);
    }

    protected static function adminResetUserPassword(UserPoolModel $userPool, string $username, array $args = []): Result
    {
        /** @var CognitoIdentityProviderClient $cognitoClient */
        $cognitoClient = AwsFacade::createClient('CognitoIdentityProvider');
        $args['UserPoolId'] = $userPool->getId();
        $args['Username'] = $username;
        return $cognitoClient->adminResetUserPassword($args);
    }

    protected static function updatePasswordUpdatedAtAttribute(UserPoolModel $userPool, string $username, array $args = []): Result
    {
        /** @var CognitoIdentityProviderClient $cognitoClient */
        $cognitoClient = AwsFacade::createClient('CognitoIdentityProvider');
        $args['UserPoolId'] = $userPool->getId();
        $args['Username'] = $username;
        $args['UserAttributes'] = [
            [
                'Name' => 'custom:password_updated_at',
                'Value' => Carbon::now()->format('Y-m-d H:i:s O')
            ]
        ];
        return $cognitoClient->adminUpdateUserAttributes(
            $args
        );
    }

    public function confirmEmail(string $confirmationCode)
    {
        $userPool = UserPoolService::getUserPoolByEnvironment($this->environment);
        static::confirmSignUp($userPool, $confirmationCode);
    }

    protected static function confirmSignUp(UserPoolModel $userPool, string $confirmationCode, array $args = []): Result
    {
        /** @var CognitoIdentityProviderClient $cognitoClient */
        $cognitoClient = AwsFacade::createClient('CognitoIdentityProvider');
        $args['UserPoolId'] = $userPool->getId();
        $args['ConfirmationCode'] = $confirmationCode;
        return $cognitoClient->confirmSignUp($args);
    }

    public function deleteProfessional(Professional $professional)
    {
        $user = $this->getUser($professional);
        if (is_null($user)) {
            // Professional not found so no need to delete here
            return null;
        }

        $userPool = UserPoolService::getUserPoolByEnvironment($this->environment);
        static::adminDeleteUser($userPool, [
            'Username' => $professional->username,
        ]);
    }

    protected static function adminDeleteUser(UserPoolModel $userPool, array $args = []): Result
    {
        /** @var CognitoIdentityProviderClient $cognitoClient */
        $cognitoClient = AwsFacade::createClient('CognitoIdentityProvider');
        $args['UserPoolId'] = $userPool->getId();
        return $cognitoClient->adminDeleteUser($args);
    }

    private static function sleepForRateLimit($requestPerSecond, $tolerance = 100)
    {
        $milliseconds = ceil(1 / $requestPerSecond * 1000) + $tolerance;
        $microseconds = $milliseconds * 1000;
        usleep($microseconds);
    }

    /**
     * Interesting endpoints:
     *
     * AdminResetUserPassword   // Invalidates password, force new one
     * AdminSetUserPassword     // Just set the password
     * ChangePassword           // Change password with old password as check
     * ConfirmForgotPassword    // Reset password with forgotten pw code
     * ConfirmSignUp
     * DeleteUser               // Allows a user to delete himself
     * DescribeIdentityProvider
     * DescribeUserPool
     * ForgotPassword
     * GetUser
     * GlobalSignOut
     * ListUserPools
     * ResendConfirmationCode
     * SignUp
     */
}
