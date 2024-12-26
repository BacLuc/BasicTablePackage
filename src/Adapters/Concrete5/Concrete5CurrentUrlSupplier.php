<?php

namespace BaclucC5Crud\Adapters\Concrete5;

use BaclucC5Crud\Controller\CurrentUrlSupplier;
use Concrete\Core\Block\BlockController;

class Concrete5CurrentUrlSupplier implements CurrentUrlSupplier {

    public function __construct(private BlockController $blockController) {
    }

    public function getUrl() {
        return $this->blockController->getRequest()
            ->getPathInfo()
        ;
    }
}
