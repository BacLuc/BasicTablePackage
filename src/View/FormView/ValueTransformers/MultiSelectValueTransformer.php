<?php

namespace BaclucC5Crud\View\FormView\ValueTransformers;

use BaclucC5Crud\Entity\Identifiable;
use Doctrine\Common\Collections\ArrayCollection;

class MultiSelectValueTransformer implements ValueTransformer {
    public function transform($persistenceValue) {
        $arrayCollection = $persistenceValue ?: new ArrayCollection();

        return $sqlValue = collect($arrayCollection->toArray())->keyBy(function (Identifiable $value) {
            return $value->getId();
        })->toArray();
    }
}
