<?php

namespace BaclucC5Crud\Controller\ActionProcessors;

use BaclucC5Crud\Controller\ActionProcessor;
use BaclucC5Crud\Controller\BlockIdSupplier;

class BlockIdAwareActionProcessor implements ActionProcessor {

    public function __construct(
        private BlockIdSupplier $blockIdSupplier,
        private string $blockIdOfRequest,
        private ActionProcessor $successAction,
        private ActionProcessor $failAction
    ) {
    }

    public function getName(): string {
        return $this->successAction->getName();
    }

    public function process(array $get, array $post, ...$additionalParameters) {
        $args = func_get_args();
        if ($this->blockIdSupplier->getBlockId() === $this->blockIdOfRequest) {
            return call_user_func_array([$this->successAction, 'process'], $args);
        }

        return call_user_func_array([$this->failAction, 'process'], $args);
    }
}
