<?php

namespace Vng\EvaCore\Services\Cognito;

use Vng\EvaCore\Models\Professional;
use Vng\EvaCore\CognitoIdentityProvider\CognitoIdentityProviderClient;
use Aws\Laravel\AwsFacade;
use Aws\Result;
use DateTime;
use Illuminate\Support\Collection;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

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

    public static function ensureSetup()
    {
        UserPoolService::ensureUserPool();
        UserPoolClientService::ensureUserPoolClient();
    }

    /**
     * Gets all users from the idp and updates the database accordingly
     */
    public static function syncProfessionals(): void
    {
        $users = static::getUsers();
        $users->each(function (UserModel $user) {
            static::updateOrCreateProfessionalQuietly($user);
        });
    }

    /**
     * Sync the given professional with the idp data
     * Deletes the professsional when not present
     */
    public static function syncProfessional(Professional $professional): ?Professional
    {
        $userPool = UserPoolService::getUserPool();
        if (is_null($userPool)) {
            return null;
        }

        try {
            $userData = static::adminGetUser($userPool, $professional->email);
            if ($userData) {
                $user = UserModel::create($userData);
                return static::updateOrCreateProfessionalQuietly($user);
            }
        } catch (ResourceNotFoundException $e) {
            $professional->delete();
        }
        return null;
    }

    protected static function updateOrCreateProfessionalQuietly(UserModel $user): ?Professional
    {
        return Professional::withoutEvents(function() use ($user) {
            return Professional::query()->updateOrCreate([
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

    public static function resetExpiredPasswords()
    {
        $users = static::getUsers();
        $users->each(function (UserModel $user) {
            static::checkPasswordExpiration($user);
        });
    }

    protected static function checkPasswordExpiration(UserModel $user)
    {
        $pwUpdatedAt = $user->getPasswordUpdatedAtDate();
        if (is_null($pwUpdatedAt)){
            // set a password_updated_at date if no current data is available
            $userPool = UserPoolService::getUserPool();
            static::updatePasswordUpdatedAtAttribute($userPool, $user->getUsername());
        } elseif ($user->isPasswordExpired()){
            static::resetPassword($user->getUsername());
        }
    }

    protected static function getProfessional(UserModel $user): ?Professional
    {
        /** @var Professional|null $professional */
        $professional = Professional::query()->where('email', $user->getEmail())->first();
        return $professional;
    }

    protected static function removeProfessionalsNotSeenNow()
    {
        Professional::withoutEvents(function() {
            $missingProfessionals = Professional::query()->where('last_seen_at', '<', now())->get();
            $missingProfessionals->each(fn (Professional $prof) => $prof->delete());
        });
    }

    protected static function removeProfessionalsNotSeenInLastIteration()
    {
        Professional::withoutEvents(function() {
            $latestSeenDate = Professional::query()->max('last_seen_at');
            if (!is_null($latestSeenDate)) {
                $missingProfessionals = Professional::query()->where('last_seen_at', '<', $latestSeenDate)->get();
                $missingProfessionals->each(fn (Professional $prof) => $prof->delete());
            }
        });
    }

    protected static function getUsers(string $paginationToken = null): ?Collection
    {
        $userPool = UserPoolService::getUserPool();
        if (is_null($userPool)) {
            return null;
        }

        $listUsersResponse = static::listUsers($userPool, $paginationToken);
        $users = collect($listUsersResponse['Users'])->map(fn ($user) => UserModel::create($user));

        if ($listUsersResponse['PaginationToken']) {
            $users->merge(static::getUsers($listUsersResponse['PaginationToken']));
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

    protected static function adminGetUser(UserPoolModel $userPool, string $username)
    {
        /** @var CognitoIdentityProviderClient $cognitoClient */
        $cognitoClient = AwsFacade::createClient('CognitoIdentityProvider');
        return $cognitoClient->adminGetUser([
            'UserPoolId' => $userPool->getId(),
            'Username' => $username,
        ]);
    }

    public static function createProfessional(Professional $professional)
    {
        $userPool = UserPoolService::getUserPool();
        $result = static::adminCreateUser($userPool, static::getDefaultAdminCreateUserArgs($professional));
        return static::updateOrCreateProfessionalQuietly(UserModel::create($result['User']));
    }

    public static function resendInvitationNotification(Professional $professional)
    {
        $userPool = UserPoolService::getUserPool();
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
    public static function resetPassword(string $username): void
    {
        $userPool = UserPoolService::getUserPool();
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
                'Value' => (new DateTime())->format('Y-m-d H:i:s e')
            ]
        ];
        return $cognitoClient->adminUpdateUserAttributes(
            $args
        );
    }

    public static function confirmEmail(string $confirmationCode)
    {
        $userPool = UserPoolService::getUserPool();
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

    public static function deleteProfessional(Professional $professional)
    {
        $userPool = UserPoolService::getUserPool();
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
