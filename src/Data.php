<?php

namespace OlegV\Components;

use ArrayObject;

/**
 * @template TKey as array-key
 * @template TValue
 * @extends  ArrayObject<TKey, TValue>
 */
class Data extends ArrayObject
{
    /**
     * @param  array<TKey, TValue>  $data
     */
    public function __construct(array $data)
    {
        parent::__construct($data);
    }

    /**
     * @param  TKey  $name
     * @return string|int|float|null
     */
    public function __get(mixed $name): string|int|float|null
    {
        if ($this->offsetExists($name)) {
            $value = $this->offsetGet($name);
            if (is_string($value) || is_int($value) || is_float($value)) {
                return $value;
            }
        }
        return null;
    }

    /**
     * @param  TKey  $key
     * @return string|int|float|null
     */
    public function offsetGet(mixed $key): string|int|float|null
    {
        if ($this->offsetExists($key)) {
            $value = parent::offsetGet($key);
            if (is_string($value) || is_int($value) || is_float($value)) {
                return $value;
            }
        }
        return null;
    }
}