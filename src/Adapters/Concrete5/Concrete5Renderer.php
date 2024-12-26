<?php

namespace BaclucC5Crud\Adapters\Concrete5;

use BaclucC5Crud\Controller\Renderer;
use Concrete\Core\Block\BlockController;
use Concrete\Package\BaclucC5Crud\Controller;

class Concrete5Renderer implements Renderer {

    public function __construct(private BlockController $blockController,private string $packagePath) {
    }

    public function render(string $path) {
        if (file_exists($this->packagePath.'/resources/'.$path.'.php')) {
            $packageHandle = basename($this->packagePath);
            $this->blockController->render('../../../'.$packageHandle.'/resources/'.$path);
        } else {
            $this->blockController->render('../../../'.Controller::PACKAGE_HANDLE.'/resources/'.$path);
        }
    }

    public function action(string $action) {
        throw new \BadMethodCallException('this method is not implemented');
    }
}
