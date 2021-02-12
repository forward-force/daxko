<?php

namespace ForwardForce\Daxko;

use ForwardForce\Daxko\Entities\DaxkoClass;
use ForwardForce\Daxko\Entities\DaxkoUser;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Message;


class Daxko
{
    const BASE_URL = 'https://api.partners.daxko.com';
    const API_VERSION = '/api/v1';


    /**
     * An HTTPClient to query Daxko's API
     */
    protected Client $client;


    public function __construct(string $accessToken)
    {
        $clientConfig = [
            'base_uri' => self::BASE_URL,
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $accessToken,
            ]
        ];

        $this->client = new Client($clientConfig);
    }

    /**
     * Get API access token
     *
     * This method authenticates a client with either client credentials or
     * a refresh token, which at that point would return a JWT token as well as
     * a refresh token for subsequent re-authentication.
     *
     * [The access token can be stored in session or simmilar storage - use when creating new Daxko instance]
     *
     * @param string $clientId 	    The provided username when generating your Daxko API credentials
     * @param string $clientSecret 	The provided password  when generating your Daxko API credentials
     * @param string $scope 	    The customer/client you are trying to programmatically interact with
     * // @param string $grantType 	This will always be set to client_credentials when getting a new token.
     *
     * @return array
     */
    public static function getToken(string $clientId, string $clientSecret, string $scope, string $grantType = ''): array
    {
        try {
            $response = (new Client())
                ->post(self::BASE_URL . '/auth/token', [
                    'headers' => [ 'Content-Type' => 'application/json' ],
                    'json' => [
                        'client_id' => $clientId,
                        'client_secret' => $clientSecret,
                        'scope' => $scope,
                        'grant_type' => 'client_credentials',
                    ]
                ]);

        } catch (RequestException $e) {
            $error['request'] = Message::toString($e->getRequest());

            if ($e->hasResponse()) {
                $error['response'] = Message::toString($e->getResponse());
            }

            return $error;

        } catch (GuzzleException $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

        return json_decode($response->getBody()->getContents(), true) ?? [];
    }

    /**
     * Refresh expired API access token
     *
     * @param string $clientId 	    The provided username when generating your Daxko API credentials
     * @param string $refreshToken 	A refresh token as returned by `getToken()` under `refresh_token` key
     *
     * @return array
     */
    public static function refreshToken(string $clientId, string $refreshToken): array
    {
        try {
            $response = (new Client())
                ->post(self::BASE_URL . '/auth/token', [
                    'headers' => [ 'Content-Type' => 'application/json' ],
                    'json' => [
                        'client_id' => $clientId,
                        'refresh_token' => $refreshToken,
                        'grant_type' => 'refresh_token',
                    ]
                ]);

        } catch (RequestException $e) {
            $error['request'] = Message::toString($e->getRequest());

            if ($e->hasResponse()) {
                $error['response'] = Message::toString($e->getResponse());
            }

            return $error;

        } catch (GuzzleException $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

        return json_decode($response->getBody()->getContents(), true) ?? [];
    }


    /**
     * Get a Daxko Class entity
     *
     * @return DaxkoClass
     */
    public function classes(): DaxkoClass
    {
        $class = new DaxkoClass($this->client);

        return $class;
    }

    /**
     * Get a Daxko User entity
     *
     * @return DaxkoUser
     */
    public function users(): DaxkoUser
    {
        $class = new DaxkoUser($this->client);

        return $class;
    }
}
