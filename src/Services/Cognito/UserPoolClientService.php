<?php

namespace Vng\EvaCore\Services\Cognito;

use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient;
use Aws\Laravel\AwsFacade;
use Aws\Result;
use Illuminate\Support\Facades\Log;
use Vng\EvaCore\Models\Environment;

class UserPoolClientService
{
    const DEFAULT_SETTINGS = [
        'AccessTokenValidity' => 1,
//        'AllowedOAuthFlows' => ['<string>', ...],
//        'AllowedOAuthFlowsUserPoolClient' => true || false,
//        'AllowedOAuthScopes' => ['<string>', ...],
//        'AnalyticsConfiguration' => [
//            'ApplicationArn' => '<string>',
//            'ApplicationId' => '<string>',
//            'ExternalId' => '<string>',
//            'RoleArn' => '<string>',
//            'UserDataShared' => true || false,
//        ],
//        'CallbackURLs' => ['<string>', ...],
        'ClientName' => null,
//        'DefaultRedirectURI' => '<string>',
//        'ExplicitAuthFlows' => ['<string>', ...],
//        'GenerateSecret' => true || false,
//        'IdTokenValidity' => <integer>,
//        'LogoutURLs' => ['<string>', ...],
//        'PreventUserExistenceErrors' => 'LEGACY|ENABLED',
//        'ReadAttributes' => ['<string>', ...],
        'RefreshTokenValidity' => 24,
//        'SupportedIdentityProviders' => ['<string>', ...],
        'TokenValidityUnits' => [
            'AccessToken' => 'hours',
//            'IdToken' => 'seconds|minutes|hours|days',
            'RefreshToken' => 'hours',
        ],
        'UserPoolId' => null,
//        'WriteAttributes' => ['<string>', ...],
    ];

    protected ?UserPoolClientModel $userPoolClient = null;

    public function __construct(
        protected Environment $environment,
    )
    {}

    public static function make(Environment $environment): static
    {
        return new static($environment);
    }

    public function ensureUserPoolClient(): ?UserPoolClientModel
    {
        $userPoolClient = $this->getUserPoolClient();
        if (is_null($userPoolClient)) {
            // No user pool client exists yet, create one
            $result = $this->createUserPoolClient();
            $userPoolId = $result['UserPoolClient']['UserPoolId'];
            $userPoolClientId = $result['UserPoolClient']['ClientId'];
            $userPoolClient = $this->getUserPoolClientByIds($userPoolId, $userPoolClientId);
        }

        return $userPoolClient;
    }

    private function createUserPoolClient(): Result
    {
        Log::info('AWS SDK - user pool client: createUserPoolClient');
        /** @var CognitoIdentityProviderClient $cognitoClient */
        $cognitoClient = AwsFacade::createClient('CognitoIdentityProvider');
        return $cognitoClient->createUserPoolClient(static::getUserPoolClientArgs());
    }

    private function getUserPoolClientArgs(): array
    {
        $userPoolId = $this->environment->user_pool_id;

        if (is_null($userPoolId)) {
            Log::warning('Non optimal execution, make sure the user_pool_id is available on environment');
            $userPoolService = UserPoolService::make($this->environment);
            $userPool = $userPoolService->getUserPool();
            $userPoolId = $userPool->getId();
        }

        $args = static::DEFAULT_SETTINGS;
        $args['ClientName'] = static::getUserPoolClientName();
        $args['UserPoolId'] = $userPoolId;
        return $args;
    }

    private function getUserPoolClientName(): string
    {
        return $this->environment->deriveUserPoolName() . '-Client';
    }

    public function getUserPoolClient(): ?UserPoolClientModel
    {
        if (!is_null($this->userPoolClient)) {
            return $this->userPoolClient;
        }

        return $this->getUserPoolClientByEnvironment();
    }

    private function getUserPoolClientByEnvironment(): ?UserPoolClientModel
    {
        $userPoolId = $this->environment->getUserPoolId();
        $userPoolClientId = $this->environment->getUserPoolClientId();
        if (is_null($userPoolId) || is_null($userPoolClientId)){
            return null;
        }
        return $this->getUserPoolClientByIds($userPoolId, $userPoolClientId);
    }

    private function getUserPoolClientByIds(string $userPoolId, string $userPoolClientId): UserPoolClientModel
    {
        $userPoolClientDescription = $this->describeUserPoolClient(
            $userPoolId,
            $userPoolClientId
        );
        $this->userPoolClient = UserPoolClientModel::create(
            $userPoolClientDescription['UserPoolClient']
        );
        return $this->userPoolClient;
    }

//    protected static function getUserPoolClientByName($name, string $nextToken = null): ?UserPoolClientModel
//    {
//        $result = static::listUserPoolClients($nextToken);
//        $userPools = $result['UserPoolClients'];
//        if (!count($userPools)) {
//            return null;
//        }
//
//        $matchingPools = array_filter($userPools, fn ($pool) => $pool['ClientName'] === $name);
//        if (empty($matchingPools)) {
//            if ($result['NextToken']) {
//                return static::getUserPoolClientByName($name, $result['NextToken']);
//            }
//            return null;
//        }
//
//        return UserPoolClientModel::create(reset($matchingPools));
//    }
//
//    protected static function listUserPoolClients(string $nextToken = null): Result
//    {
//        /** @var CognitoIdentityProviderClient $cognitoClient */
//        $cognitoClient = AwsFacade::createClient('CognitoIdentityProvider');
//
//        $args = [
//            'MaxResults' => 10,
//            'UserPoolId' => UserPoolService::getUserPool()->getId(),
//        ];
//        if (!is_null($nextToken)) {
//            $args['NextToken'] = $nextToken;
//        }
//        return $cognitoClient->ListUserPoolClients($args);
//    }

    private function describeUserPoolClient(string $userPoolId, string $userPoolClientId): Result
    {
        Log::info('AWS SDK - user pool client: describeUserPoolClient');
        /** @var CognitoIdentityProviderClient $cognitoClient */
        $cognitoClient = AwsFacade::createClient('CognitoIdentityProvider');

        return $cognitoClient->describeUserPoolClient([
            'UserPoolId' => $userPoolId,
            'ClientId' => $userPoolClientId
        ]);
    }

}
