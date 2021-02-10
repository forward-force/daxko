<?php

namespace ForwardForce\Daxko\Traits;

trait Pagable
{
    protected int $records = 10;
    protected int $offset = 0;

    /**
     * Offset for pagination
     *
     * @param  int $offset
     * @return $this
     */
    public function skip(int $offset): self
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * Limit for pagination
     *
     * @param  int $records
     * @return $this
     */
    public function take(int $records): self
    {
        $this->records = $records;
        return $this;
    }
}
