<?php

namespace BaclucC5Crud\Lib;

trait ImmutableArrayAccessTrait {
    /**
     * @var array
     */
    private $values;

    /**
     * FieldTypeOverride constructor.
     * private, do not use directly.
     */
    public function initialize(array $values) {
        $this->values = $values;
    }

    public function offsetExists($offset) {
        return array_key_exists($offset, $this->values);
    }

    public function offsetGet($offset) {
        return $this->values[$offset];
    }

    public function offsetSet($offset, $value) {
        throw new \BadMethodCallException('this array is immutable, cannot set value');
    }

    public function offsetUnset($offset) {
        throw new \BadMethodCallException('this array is immutable, cannot set value');
    }
}
