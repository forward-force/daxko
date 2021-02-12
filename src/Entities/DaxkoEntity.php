<?php

namespace ForwardForce\Daxko\Entities;

use ForwardForce\Daxko\Client;
use Psr\Http\Message\ResponseInterface;

abstract class DaxkoEntity
{
    protected Client $client;
    protected ?ResponseInterface $response;
    protected ?array $errors;
    protected array $responseArray = [];


    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->response = null;
        $this->errors = null;
    }


    /**
     * Returns the raw API response
     *
     * @return ResponseInterface
     */
    // public function getRawResponse(): ResponseInterface
    // {
    //     return $this->response;
    // }

    /**
     * Check if API returns any errors
     */
    public function hasErrors(): bool
    {
        return $this->errors !== null;
    }

    /**
     * Returns API response errors(if any)
     */
    public function getErrors(): array
    {
        return $this->errors ?? [];
    }

    /**
     * Returns entity's fields as an array
     */
    public function toArray(): array
    {
        return $this->properties;
    }


    /**
     * Returns an array representation of response body
     *
     * @return array
     *
     * @throws \Exception
     */
    protected function responseToArray(): array
    {
        if (!$this->response) {
            throw new \Exception(
                'Can\'t get content from empty response.'
            );
        }

        if (!$this->responseArray) {
            try {
                $json = json_decode(
                    $this->response->getBody()->getContents(),
                    true,
                    JSON_THROW_ON_ERROR
                );

            } catch (\JsonException $e) {
                $this->errors['json_decode'] = [
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                ];
            }

            $this->responseArray = $json ?? [];
        }

        return $this->responseArray;
    }

    /**
     * Set given properties value to their corresponding fields in API reponse
     *
     * @param array $properties     A list of properties to set from API response
     *
     * @return void
     *
     * @throws \Exception
     */
    protected function setProperties(array $propertiesName = [])
    {
        if (!$this->response) {
            throw new \Exception(
                'Cannot set entity property - response is null.'
            );
        }

        $responseData = $this->responseToArray();
        $properties = array_merge(array_keys($this->properties), $propertiesName);

        foreach ($properties as $property => $name) {
            if (isset($responseData[$name])) {
                $this->properties[$name] = $responseData[$name];
            }
        }
    }

    public function __set(string $name, $value): void
    {
        // if (!array_key_exists($name, $this->properties)) {
        //     throw new \Exception(
        //         sprintf('Cannot set undefined form property: "%s".', $name)
        //     );
        // }

        // $this->properties[$name] = $value;
    }

    public function __get($name)
    {
        if (!array_key_exists($name, $this->properties)) {
            throw new \Exception(
                sprintf('Cannot access undefined form property: "%s".', $name)
            );
        }

        return $this->properties[$name];
    }

    public function __isset (string $name): bool
    {
        return isset($this->properties[$name]);
    }

    public function __unset (string $name): void { }
}
