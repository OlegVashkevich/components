<?php

namespace OlegV\Components;

use ArrayAccess;

/**
 * @template TKey as array-key
 * @template TValue
 * @implements  ArrayAccess<TKey , TValue>
 */
class Data implements ArrayAccess
{
    private int $offsetCounter = 0;

    /**
     * @param  array<TKey , TValue>  $storage
     * @param  bool  $debug  - ignore array offset warnings
     */
    public function __construct(private array $storage, private readonly bool $debug = false) {}

    /**
     * @param  mixed  $offset
     * @return mixed
     */
    public function offsetGet(mixed $offset): mixed
    {
        $this->offsetCounter++;
        $value = null;
        if (isset($this->storage[$offset])) {
            $value = $this->storage[$offset];
        } elseif (!$this->debug) {
            // @phpstan-ignore-next-line
            $this->storage[$offset] = new Data([]);
        }
        return $value;
    }

    /**
     * @param  mixed  $offset
     * @return bool
     */
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->storage[$offset]);
    }

    /**
     * @param  mixed  $offset
     * @param  mixed  $value
     * @return void
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (is_null($offset)) {
            $this->storage[] = $value;
        } else {
            $this->storage[$offset] = $value;
        }
    }

    /**
     * @param  mixed  $offset
     * @return void
     */
    public function offsetUnset(mixed $offset): void
    {
        unset($this->storage[$offset]);
    }
}