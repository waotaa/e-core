<?php

namespace Vng\EvaCore\Services\Cognito;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Vng\EvaCore\Models\Environment;
use Vng\EvaCore\Models\Professional;
use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient;
use Aws\Laravel\AwsFacade;
use Aws\Result;

class UserPoolService
{
    const DEFAULT_POOL_SETTINGS = [
        'AccountRecoverySetting' => [
            'RecoveryMechanisms' => [
                [
                    'Name' => 'verified_email',
                    'Priority' => 1,
                ]
            ],
        ],
        'AdminCreateUserConfig' => [
            'AllowAdminCreateUserOnly' => true,
            'InviteMessageTemplate' => [
                'EmailMessage' => 'Beste professional, Er is een account voor je aangemaakt voor instrumentengids Eva met gebruikersnaam {username}. Uw tijdelijke wachtwoord is {####}',
                'EmailSubject' => 'Uitnodiging instrumentengids Eva',
                'SMSMessage' => 'Er is een account voor je aangemaakt voor instrumentengids Eva met gebruikersnaam {username}. Uw tijdelijke wachtwoord is {####}',
            ],
        ],
        'AutoVerifiedAttributes' => ['email'],
        'DeviceConfiguration' => [
            'ChallengeRequiredOnNewDevice' => false,
            'DeviceOnlyRememberedOnUserPrompt' => false,
        ],
        'EmailConfiguration' => [
//            'ConfigurationSet' => '<string>',
            'EmailSendingAccount' => 'DEVELOPER',
            'From' => 'professional@instrumentengids-eva.nl',
            'ReplyToEmailAddress' => 'no-reply@instrumentengids-eva.nl',
            'SourceArn' => 'arn:aws:ses:eu-west-1:163631682148:identity/professional@instrumentengids-eva.nl',
        ],
//        'EmailVerificationMessage' => 'Uw gebruikersnaam is {username} en uw tijdelijke wachtwoord is {####}',
//        'EmailVerificationSubject' => 'Uitnodiging Instrumentengids Eva',
//        'LambdaConfig' => [
//            'CreateAuthChallenge' => '<string>',
//            'CustomEmailSender' => [
//                'LambdaArn' => '<string>', // REQUIRED
//                'LambdaVersion' => 'V1_0', // REQUIRED
//            ],
//            'CustomMessage' => '<string>',
//            'CustomSMSSender' => [
//                'LambdaArn' => '<string>', // REQUIRED
//                'LambdaVersion' => 'V1_0', // REQUIRED
//            ],
//            'DefineAuthChallenge' => '<string>',
//            'KMSKeyID' => '<string>',
//            'PostAuthentication' => '<string>',
//            'PostConfirmation' => '<string>',
//            'PreAuthentication' => '<string>',
//            'PreSignUp' => '<string>',
//            'PreTokenGeneration' => '<string>',
//            'UserMigration' => '<string>',
//            'VerifyAuthChallengeResponse' => '<string>',
//        ],
        'MfaConfiguration' => 'OFF', //'OPTIONAL', (Can't be set to optional right away)
        'Policies' => [
            'PasswordPolicy' => [
                'MinimumLength' => 8,
                'RequireLowercase' => true,
                'RequireNumbers' => true,
                'RequireSymbols' => false,
                'RequireUppercase' => true,
                'TemporaryPasswordValidityDays' => 1
            ],
        ],
        'PoolName' => null,
        'Schema' => [
            [
                'AttributeDataType' => 'DateTime',
                'Mutable' => true,
                'Name' => 'password_updated_at',
                'Required' => false,
            ],
            // ...
        ],
//        'SmsAuthenticationMessage' => 'Uw login code is {####}',
//        'SmsConfiguration' => [
//            'ExternalId' => '<string>',
//            'SnsCallerArn' => '<string>', // REQUIRED
//        ],
//        'SmsVerificationMessage' => 'Verifiëer uw telefoon met code {####}',
        'UsernameAttributes' => ['email'],
        'UsernameConfiguration' => [
            'CaseSensitive' => false,
        ],
//        'UserPoolAddOns' => [
//            'AdvancedSecurityMode' => 'OFF|AUDIT|ENFORCED', // REQUIRED
//        ],
//        'UserPoolTags' => ['<string>', ...],
        'VerificationMessageTemplate' => [
            'DefaultEmailOption' => 'CONFIRM_WITH_CODE',
            'EmailMessage' => 'Uw herstelcode is {####}.',
            'EmailMessageByLink' => 'Klik op de onderstaande link om uw e-mailadres te verifiëren. {##Verifieer Email##}',
            'EmailSubject' => 'Herstelcode Instrumentengids Eva',
            'EmailSubjectByLink' => 'Herstellink Instrumentengids Eva',
//            'SmsMessage' => 'SMS Bericht voor wat?',
        ]
    ];

    protected ?UserPoolModel $userPool = null;

    public function __construct(
        protected Environment $environment
    )
    {}

    public static function make(Environment $environment): static
    {
        return new static($environment);
    }

    public function ensureUserPool(): UserPoolModel
    {
        $userPool = $this->getUserPool();
        if (is_null($userPool)) {
            // No user pool exists yet, create one
            $result = $this->createUserPool();
            $userPoolId = $result['UserPool']['Id'];
            $userPool = $this->getUserPoolById($userPoolId);
        } else {
            // User pool exists, update settings and schema
            $this->updateUserPool($userPool);
            $this->ensureUserPoolSchema($userPool);
        }

        $this->setupMfaConfig($userPool->getId());
        return $userPool;
    }

    // >> Creating or updating userpool

    private function createUserPool(): Result
    {
        Log::info('AWS SDK - user pool: createUserPool');

        /** @var CognitoIdentityProviderClient $cognitoClient */
        $cognitoClient = AwsFacade::createClient('CognitoIdentityProvider');
        return $cognitoClient->createUserPool($this->getUserPoolArgs());
    }

    private function updateUserPool(UserPoolModel $userPoolModel): void
    {
        Log::info('AWS SDK - user pool: updateUserPool');
        /** @var CognitoIdentityProviderClient $cognitoClient */
        $cognitoClient = AwsFacade::createClient('CognitoIdentityProvider');
        $args = $this->getUserPoolArgs();
        $args['UserPoolId'] = $userPoolModel->getId();
        // 15 requests per second
        $cognitoClient->updateUserPool($args);
    }

    private function getUserPoolArgs(): array
    {
        $args = static::DEFAULT_POOL_SETTINGS;
        $args['PoolName'] = $this->environment->deriveUserPoolName();
        $args['AdminCreateUserConfig']['InviteMessageTemplate']['EmailMessage'] = $this->getInvitationEmail();
        $args['VerificationMessageTemplate']['EmailMessage'] = $this->getValidationMessage();

        if (App::environment('local')) {
            $args['EmailConfiguration'] = [
                'EmailSendingAccount' => 'COGNITO_DEFAULT',
//                'ReplyToEmailAddress' => 'no-reply@instrumentengids-eva.nl',
            ];
        }

        return $args;
    }

    // User pool invitation email config
    private function getInvitationEmail(): string
    {
        $message = "Beste professional, <br><br>Er is een account voor je aangemaakt voor instrumentengids Eva.<br>";

        $environmentUrl = $this->environment->url;
        if (!is_null($environmentUrl)) {
            $message .= "Je kan inloggen op <a href='". $environmentUrl ."'>" . $environmentUrl . "</a> om de instrumentengids te raadplegen over jullie instrumentenaanbod.";
        }

        $message .= "<br><br>
            Jouw gebruikersnaam is: {username}<br>
            Jouw tijdelijke wachtwoord is: {####}<br>
            <br>
            Na de eerste keer inloggen wordt je meteen gevraagd je wachtwoord te wijzigen, daarna kan je al aan de slag! Neem voor vragen contact op met je teamleider of de contactpersoon voor Eva binnen jullie gemeente.<br>
            <br>
            Let op: dit tijdelijke wachtwoord is slechts 24 uur geldig. Als je tijdelijke wachtwoord verlopen is zal de beheerder van jullie instrumentengids je opnieuw moeten uitnodigen. Stem indien nodig een handig moment af.<br>
            <br>
            Veel succes met Eva!";
        return $message;
    }

    // User pool validation message config
    private function getValidationMessage(): string
    {
        $url = $this->environment->url;

        $message = "
        Beste professional,<br>
        <br>
        U ontvangt deze mail omdat het wachtwoord van uw Eva account (opnieuw) ingesteld dient te worden.<br>
        Dit komt omdat:<br>
        <ul>
            <li>Uzelf of uw beheerder een wachtwoord herstel voor uw account heeft aangevraagd.</li>
        </ul>
        Of
        <ul>
            <li>Uw wachtwoord al zes maanden ongewijzigd is, en daardoor automatisch gereset wordt.</li>
        </ul>
        <br>
        U kunt uw wachtwoord opnieuw instellen met herstelcode: {####}<br>";

        if (!is_null($url)) {
            $message .= "
            Ga naar <a href='". $url ."'>" . $url . "</a><br>
            ";
        }

        $message .= "
        Klik op “Stel wachtwoord opnieuw in” en vervolgens op “Ik heb al een herstelcode en wil mijn wachtwoord wijzigen”.<br>
        <br>
        <b>Let op;</b> de herstelcode is 1 uur geldig. Mocht deze verlopen zijn dan kunt u een nieuwe aanvragen.
        Klik op “Stel wachtwoord opnieuw in” en vul vervolgens uw e-mailadres in waarop uw account geregistreerd is.<br>
        <br>
        <br>
        Neem voor vragen contact op met je teamleider of de contactpersoon voor Eva binnen jullie gemeente.<br>
        <br>
        Veel succes met Eva!
        ";

        return $message;
    }

    // >> Ensuring user pool schema

    private function ensureUserPoolSchema(UserPoolModel $userPool)
    {
        $missingAttributes = $this->findMissingAttributes($userPool);
        if (count($missingAttributes) === 0) {
            return;
        }
        $this->addCustomAttributes($userPool, $missingAttributes);
    }

    private function findMissingAttributes(UserPoolModel $userPool): array
    {
        $args = static::DEFAULT_POOL_SETTINGS;
        $schema = $args['Schema'];
        $userPoolDescription = static::describeUserPool($userPool->getId());
        $attributes = $userPoolDescription['UserPool']['SchemaAttributes'];
        $attributeNames = collect($attributes)->map(fn ($a) => $a['Name'])->toArray();
        return array_filter($schema, function ($schemaAttribute) use ($attributeNames) {
            return !in_array('custom:' . $schemaAttribute['Name'], $attributeNames);
        });
    }

    private function addCustomAttributes(UserPoolModel $userPool, array $attributesSchema): void
    {
        Log::info('AWS SDK - user pool: addCustomAttributes');
        /** @var CognitoIdentityProviderClient $cognitoClient */
        $cognitoClient = AwsFacade::createClient('CognitoIdentityProvider');
        $cognitoClient->addCustomAttributes([
            'UserPoolId' => $userPool->getId(),
            'CustomAttributes' => $attributesSchema
        ]);
    }

    // >> Multi factor setup

    private function setupMfaConfig($userPoolId): void
    {
        Log::info('AWS SDK - user pool: setupMfaConfig');
        /** @var CognitoIdentityProviderClient $cognitoClient */
        $cognitoClient = AwsFacade::createClient('CognitoIdentityProvider');
        $cognitoClient->setUserPoolMfaConfig([
            "MfaConfiguration" => 'OPTIONAL',
//            "SmsMfaConfiguration" => [
//                "SmsAuthenticationMessage" => "a message with the token: {####}",
//                "SmsConfiguration" => [
//                    "ExternalId" => "string",
//                    "SnsCallerArn" => "string"
//                ]
//            ],
            "SoftwareTokenMfaConfiguration" => [
                "Enabled" => true
            ],
            'UserPoolId' => $userPoolId
        ]);
    }

    // Getting the user pool model

    public function getUserPool(): ?UserPoolModel
    {
        if (!is_null($this->userPool)) {
            return $this->userPool;
        }

        return $this->getUserPoolByEnvironment();
    }

    private function getUserPoolByEnvironment(): ?UserPoolModel
    {
        $userPoolId = $this->environment->user_pool_id;
        if (is_null($userPoolId)) {
            return null;
        }
        return $this->getUserPoolById($userPoolId);
    }

    private function getUserPoolById(string $userPoolId): UserPoolModel
    {
        $userPoolDescription = self::describeUserPool($userPoolId);
        $this->userPool = UserPoolModel::create($userPoolDescription['UserPool']);
        return $this->userPool;
    }

//    No need to get user pool by name

//    private function getUserPoolByName(string $name, string $nextToken = null): ?UserPoolModel
//    {
//        $result = $this->listUserPools($nextToken);
//        $userPools = $result['UserPools'];
//        if (!count($userPools)) {
//            return null;
//        }
//
//        $matchingPools = array_filter($userPools, fn ($pool) => $pool['Name'] === $name);
//        if (empty($matchingPools)) {
//            if ($result['NextToken']) {
//                static::sleepForRateLimit(15);
//                return $this->getUserPoolByName($name, $result['NextToken']);
//            }
//            return null;
//        }
//
//        return UserPoolModel::create(reset($matchingPools));
//    }

//    Only used by: getUserPoolByName which is currently not used
//
//    private function listUserPools(string $nextToken = null): Result
//    {
//        Log::info('AWS SDK - user pool: ListUserPools');
//        /** @var CognitoIdentityProviderClient $cognitoClient */
//        $cognitoClient = AwsFacade::createClient('CognitoIdentityProvider');
//
//        $args = [
//            'MaxResults' => 60,
//        ];
//        if (!is_null($nextToken)) {
//            $args['NextToken'] = $nextToken;
//        }
//        return $cognitoClient->ListUserPools($args);
//    }

    /**
     * AWS endpoint to get UserPool details.
     * See getUserPool method to get the general UserPool details
     * getUserPool is preferred since it uses caching
     * This method is used by some other method who seek additional details
     */
    public static function describeUserPool(string $userPoolId): Result
    {
        Log::info('AWS SDK - user pool: describeUserPool');
        /** @var CognitoIdentityProviderClient $cognitoClient */
        $cognitoClient = AwsFacade::createClient('CognitoIdentityProvider');
        self::sleepForRateLimit(15); // max 15 requests per second
        return $cognitoClient->describeUserPool([
            'UserPoolId' => $userPoolId
        ]);
    }

    public static function getUserPoolMfaConfig(string $userPoolId): Result
    {
        Log::info('AWS SDK - user pool: getUserPoolMfaConfig');
        /** @var CognitoIdentityProviderClient $cognitoClient */
        $cognitoClient = AwsFacade::createClient('CognitoIdentityProvider');
        return $cognitoClient->getUserPoolMfaConfig([
            'UserPoolId' => $userPoolId
        ]);
    }

    public static function resendConfirmationCode(Professional $professional): Result
    {
        Log::info('AWS SDK - user pool: resendConfirmationCode');
        /** @var CognitoIdentityProviderClient $cognitoClient */
        $cognitoClient = AwsFacade::createClient('CognitoIdentityProvider');
        return $cognitoClient->resendConfirmationCode([
            'Username' => $professional->email
        ]);
    }

    private static function sleepForRateLimit($requestPerSecond, $tolerance = 100)
    {
        $milliseconds = ceil(1 / $requestPerSecond * 1000) + $tolerance;
        $microseconds = $milliseconds * 1000;
        usleep($microseconds);
    }
}
