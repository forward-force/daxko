<?php

namespace ForwardForce\Daxko;

use ForwardForce\Daxko\Entities\DaxkoClass;
use ForwardForce\Daxko\Entities\DaxkoUser;
use ForwardForce\Daxko\Entities\Registrations;
use ForwardForce\Daxko\Entities\Leads;
use ForwardForce\Daxko\Entities\TimeOff;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\TransferException;
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
     */
    public static function getToken(string $clientId, string $clientSecret, string $scope, string $grantType)
    {
        try {
            $response = (new Client())
                ->post(self::BASE_URL . '/auth/token', [
                    'headers' => [ 'Content-Type' => 'application/json' ],
                    'json' => [
                        'client_id' => $clientId,
                        'client_secret' => $clientSecret,
                        'scope' => $scope,
                        'grant_type' => $grantType,
                    ]
                ]);

        } catch (RequestException $e) {
            $error['request'] = Message::toString($e->getRequest());

            if ($e->hasResponse()) {
                $error['response'] = Message::toString($e->getResponse());
            }

            return $error;

        } catch (GuzzleException $e) {
            return $e->getMessage();
        }

        return json_decode($response->getBody()->getContents(), true);
    }


    public static function refreshToken(string $clientId, string $refreshToken)
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
            return $e->getMessage();
        }

        return json_decode($response->getBody()->getContents(), true);
    }


    /**
     * Get registrations from API
     *
     * @return DaxkoClass
     */
    public function classes(): DaxkoClass
    {
        $class = new DaxkoClass($this->client);

        return $class;
    }

    /**
     * Get registrations from API
     *
     * @return DaxkoUser
     */
    public function users(): DaxkoUser
    {
        $class = new DaxkoUser($this->client);

        return $class;
    }
}
