<?php

namespace BaclucC5Crud\Entity;

use BaclucC5Crud\Lib\ValueSupplierTrait;

class ExampleEntityDropdownValueSupplier implements ValueSupplier {
    use ValueSupplierTrait;

    public const KEY_5 = 'five';
    public const KEY_6 = 'six';
    public const VALUE_5 = 'dropdownvalue5';
    public const DROPDOWN_VALUE_6 = 'dropdownvalue6';

    public function __construct() {
        $this->initialize([
            'zero' => 'dropdownvalue0',
            'one' => 'dropdownvalue1',
            self::KEY_5 => self::VALUE_5,
            self::KEY_6 => self::DROPDOWN_VALUE_6,
        ]);
    }
}
