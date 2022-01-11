<?php

namespace BaclucC5Crud\FieldTypeDetermination;

use ReflectionClass;

class PersistenceFieldTypes {
    public const INTEGER = 'integer';
    public const STRING = 'string';
    public const DATE = 'date';
    public const DATETIME = 'datetime';
    public const TEXT = 'text';
    public const MANY_TO_ONE = 'manyToOne';
    public const MANY_TO_MANY = 'manyToMany';

    /**
     * @throws \ReflectionException
     */
    public static function getTypes() {
        $reflectionClass = new ReflectionClass(new self());

        return $reflectionClass->getConstants();
    }
}
