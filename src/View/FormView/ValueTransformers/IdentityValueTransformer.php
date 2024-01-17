<?php

namespace BaclucC5Crud\View\FormView\ValueTransformers;

class IdentityValueTransformer implements ValueTransformer {
    public function transform($persistenceValue) {
        return $persistenceValue;
    }
}
