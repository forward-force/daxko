<?php

namespace ForwardForce\Daxko\Entities;

use ForwardForce\Daxko\Contracts\ApiAwareContract;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Message;

class DaxkoUser extends DaxkoEntity implements ApiAwareContract
{
    /**
     * Available properties on the Class entitity(`/api/v1/classes`)
     *
     * @see https://docs.partners.daxko.com/openapi/zenPlanner/#tag/Classes
     */
    protected array $properties = [
        'id' => null,
    ];

    /**
     * Retrieve a list of users
     *
     * @param  array $params Request paramaeters
     *
     * @return array [just empty for now]
     */
    public function all(array $params =[]): array
    {
        return [];
    }

    /**
     * Get a user based on provided parameters
     *
     * This method expect the following parameters:
     * - `email => <user@example.com>`
     * - `firstName => <Jhon>`
     * - `lastName => <Doe>`
     *
     * @param  string $id    The ID of the class
     * @param  array $params Optional request paramaeters
     *
     * @return static
     */
    public function get(?string $id, array $params =[]): self
    {
        $uri = 'api/v1/users';

        try {
            $options = [
                'query' => [
                    'email' => $params['email'],
                    'firstName' => $params['firstName'],
                    'lastName' => $params['lastName'],
                ]
            ];
            $this->response = $this->client->request('GET', $uri, $options);

            /* set defined properties value from response */
            $this->setProperties();

        } catch (RequestException $e) {
            $error['request'] = Message::toString($e->getRequest());

            if ($e->hasResponse()) {
                $error['response'] = Message::toString($e->getResponse());
            }

            $this->errors = $error;

        } catch (GuzzleException $e) {
            $this->errors['message'] = $e->getMessage();
        }

        return $this;
    }
}
