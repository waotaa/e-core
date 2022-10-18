<?php

namespace Vng\EvaCore\Services\Cognito;

use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient;
use Aws\Laravel\AwsFacade;
use Aws\Result;
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

    public static function ensureUserPoolClient(Environment $environment): ?UserPoolClientModel
    {
        $userPoolClient = static::getUserPoolClientByEnvironment($environment);
        if (!is_null($userPoolClient)) {
            return $userPoolClient;
        }
        $result = static::createUserPoolClient($environment);
        $userPoolId = $result['UserPoolClient']['UserPoolId'];
        $userPoolClientId = $result['UserPoolClient']['ClientId'];
        return static::getUserPoolClientByIds($userPoolId, $userPoolClientId);
    }

    protected static function createUserPoolClient(Environment $environment): Result
    {
        /** @var CognitoIdentityProviderClient $cognitoClient */
        $cognitoClient = AwsFacade::createClient('CognitoIdentityProvider');
        return $cognitoClient->createUserPoolClient(static::getUserPoolClientArgs($environment));
    }

    protected static function getUserPoolClientArgs(Environment $environment): array
    {
        $userPool = UserPoolService::getUserPoolByEnvironment($environment);

        $args = static::DEFAULT_SETTINGS;
        $args['ClientName'] = static::getUserPoolClientName($environment);
        $args['UserPoolId'] = $userPool->getId();
        return $args;
    }

    private static function getUserPoolClientName(Environment $environment): string
    {
        return $environment->deriveUserPoolName() . '-Client';
    }

    public static function getUserPoolClientByEnvironment(Environment $environment): ?UserPoolClientModel
    {
        if (is_null($environment->user_pool_id) || is_null($environment->user_pool_client_id)){
            return null;
        }
        return self::getUserPoolClientByIds($environment->user_pool_id, $environment->user_pool_client_id);
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

    protected static function getUserPoolClientByIds(string $userPoolId, string $userPoolClientId)
    {
        $userPoolClientDescription = self::describeUserPoolClient($userPoolId, $userPoolClientId);
        return UserPoolClientModel::create($userPoolClientDescription['UserPoolClient']);
    }

    public static function describeUserPoolClient(string $userPoolId, string $userPoolClientId): Result
    {
        /** @var CognitoIdentityProviderClient $cognitoClient */
        $cognitoClient = AwsFacade::createClient('CognitoIdentityProvider');

        return $cognitoClient->describeUserPoolClient([
            'UserPoolId' => $userPoolId,
            'ClientId' => $userPoolClientId
        ]);
    }

}
