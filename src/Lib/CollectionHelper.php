<?php

namespace BaclucC5Crud\Lib;

use Illuminate\Support\Collection;

class CollectionHelper {}

function collect($value = null) {
    return new Collection($value);
}
