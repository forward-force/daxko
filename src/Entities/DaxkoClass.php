<?php

namespace ForwardForce\Daxko\Entities;

use ForwardForce\Daxko\Client;
use ForwardForce\Daxko\Contracts\ApiAwareContract;
use ForwardForce\Daxko\Response;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Message;

class DaxkoClass extends DaxkoEntity implements ApiAwareContract
{
    /**
     * Available properties on the Class entitity(`/api/v1/classes`)
     *
     * @see https://docs.partners.daxko.com/openapi/zenPlanner/#tag/Classes
     */
    protected array $properties = [
        'id' => null,
        'name' => null,
        'startDateTime' => null,
        'endDateTime' => null,
        'locationId' => null,
        'location' => null,
        'category' => null,
        'studio' => null,
        'instructor' => null,
        'activity' => null,
        'free' => null,
        'reservable' => null,
        'virtual' => null,
        'details' => null,
        'bookingDetails' => null,
        'attendeeBookingDetails' => null,
    ];


    /**
     * {@inheritDoc}
     */
    protected function setProperties(array $propertiesName =[])
    {
        $properties = $this->responseToArray();
        $this->properties = array_merge($this->properties, $properties['brief']);

        parent::setProperties($propertiesName);
    }


    /**
     * Add user to class
     */
    public function addAttendee(int $id, int $attendee)
    {
        $uri = 'api/v1/classes/' . $id . '/attendees/' . $attendee;

        try {
            $this->response = $this->client->request('POST', $uri);

            /* set defined properties value from response */
            $this->setProperties([ 'attendeeBookingDetails' ]);

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

    /**
     * Remove user from class
     */
    public function removeAttendee(int $id, int $attendee)
    {
        $uri = 'api/v1/classes/' . $id . '/attendees/' . $attendee;

        try {
            $this->response = $this->client->request('DELETE', $uri);

            /* set defined properties value from response */
            $this->setProperties([ 'attendeeBookingDetails' ]);

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

    /**
     * Add user to class waitlist
     */
    public function addToWaitlist(int $id, int $attendee)
    {
        $uri = 'api/v1/classes/' . $id . '/waitlist/attendees/' . $attendee;

        try {
            $this->response = $this->client->request('POST', $uri);

            /* set defined properties value from response */
            $this->setProperties([ 'attendeeBookingDetails' ]);

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


    /**
     * Remove user from waitlist
     */
    public function removeFromWaitlist(int $id, int $attendee)
    {
        $uri = 'api/v1/classes/' . $id . '/attendees/' . $attendee;

        try {
            $this->response = $this->client->request('DELETE', $uri);

            /* set defined properties value from response */
            $this->setProperties([ 'attendeeBookingDetails' ]);

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

    /**
     * Retrieve a list of classes
     *
     * This method expect the following parameters:
     * - `startDate => <2020-02-09>`
     * - `endDate => <2021-02-09>`
     * - `locationId => <5506>`
     *
     * @param  array $params Request paramaeters
     *
     * @return array A array of `DaxkoClass` entities
     *
     * @throws \Exception   If any required parameter is missing
     */
    public function all(array $params =[]): array
    {
        $uri = 'api/v1/classes/';
        $requestParams = [ 'startDate', 'endDate', 'locationId' ];
        $missingParams = array_diff($requestParams, array_keys($params));

        if (!empty($missingParams)) {
            throw new \Exception(
                sprintf('Missing "%s" parameter(s).', implode(',', $missingParams))
            );
        }

        try {
            $this->response = $this->client
                ->request('GET', $uri, [ 'query' => $params ]);

            $classes = [];
            $apiClasses = $this->responseToArray();

            // foreach ($apiClasses as $class) {
            //     $instance = new static($this->client);
            //     $instance->response->withBody(new Message());
            //     $instance->setProperties();
            //     $classes[] = $instance;
            // }
                    $classes = $apiClasses;
        } catch (RequestException $e) {
            $error['request'] = Message::toString($e->getRequest());

            if ($e->hasResponse()) {
                $error['response'] = Message::toString($e->getResponse());
            }

            $this->errors = $error;

        } catch (GuzzleException $e) {
            $this->errors['message'] = $e->getMessage();
        }

        return $classes;
    }

    /**
     * Get a single class by ID
     *
     * @param  string $id    The ID of the class
     * @param  array $params Optional request paramaeters
     *
     * @return static
     */
    public function get(?string $id, array $params =[]): self
    {
        $uri = 'api/v1/classes/' . $id;

        try {
            $this->response = $this->client->request('GET', $uri);

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
