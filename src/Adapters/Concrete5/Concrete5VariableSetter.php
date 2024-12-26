<?php

namespace BaclucC5Crud\Adapters\Concrete5;

use BaclucC5Crud\Controller\VariableSetter;
use Concrete\Core\Block\BlockController;

class Concrete5VariableSetter implements VariableSetter {
    public function __construct(private BlockController $blockController) {
        $this->blockController = $blockController;
    }

    public function set(string $name, $value) {
        $this->blockController->set($name, $value);
    }
}
