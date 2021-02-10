<?php

namespace ForwardForce\Daxko\Contracts;


interface ApiAwareContract
{
    /**
     * Get all entities of a that type
     *
     * @param array $params
     *
     * @return array
     */
    public function all(array $params =[]): array;

    /**
     * Get a single entity of a that type, specified by ID
     *
     * @param  string $id
     *
     * @return static
     */
    public function get(?string $id, array $params =[]): self;

    /**
     * Returns the entity's properties as array
     *
     * @return array
     */
    public function toArray(): array;
}
