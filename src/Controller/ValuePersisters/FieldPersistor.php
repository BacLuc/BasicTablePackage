<?php

namespace BaclucC5Crud\Controller\ValuePersisters;

interface FieldPersistor {
    /**
     * @param mixed $valueMap
     * @param mixed $toEntity
     */
    public function persist($valueMap, $toEntity);
}
