<?php

namespace Vng\EvaCore\Services\Cognito;

use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient;
use Aws\Laravel\AwsFacade;
use Aws\Result;

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

    public static function ensureUserPoolClient()
    {
        $userPool = static::getUserPoolClient();
        if (!is_null($userPool)) {
            return;
        }
        static::createUserPoolClient();
    }

    protected static function createUserPoolClient(): Result
    {
        /** @var CognitoIdentityProviderClient $cognitoClient */
        $cognitoClient = AwsFacade::createClient('CognitoIdentityProvider');
        return $cognitoClient->createUserPoolClient(static::getUserPoolClientArgs());
    }

    protected static function getUserPoolClientArgs()
    {
        $userpool = UserPoolService::getUserPool();

        $args = static::DEFAULT_SETTINGS;
        $args['ClientName'] = static::getUserPoolClientName();
        $args['UserPoolId'] = $userpool->getId();
        return $args;
    }

    private static function getUserPoolClientName(): string
    {
        return UserPoolService::getUserPoolName() . '-Client';
    }

    public static function getUserPoolClient(): ?UserPoolClientModel
    {
        return self::getUserPoolClientByName(static::getUserPoolClientName());
    }

    protected static function getUserPoolClientByName($name, string $nextToken = null): ?UserPoolClientModel
    {
        $result = static::listUserPoolClients($nextToken);
        $userPools = $result['UserPoolClients'];
        if (!count($userPools)) {
            return null;
        }

        $matchingPools = array_filter($userPools, fn ($pool) => $pool['ClientName'] === $name);
        if (empty($matchingPools)) {
            if ($result['NextToken']) {
                return static::getUserPoolClientByName($name, $result['NextToken']);
            }
            return null;
        }

        return UserPoolClientModel::create(reset($matchingPools));
    }

    protected static function listUserPoolClients(string $nextToken = null): Result
    {
        /** @var CognitoIdentityProviderClient $cognitoClient */
        $cognitoClient = AwsFacade::createClient('CognitoIdentityProvider');

        $args = [
            'MaxResults' => 10,
            'UserPoolId' => UserPoolService::getUserPool()->getId(),
        ];
        if (!is_null($nextToken)) {
            $args['NextToken'] = $nextToken;
        }
        return $cognitoClient->ListUserPoolClients($args);
    }
}
