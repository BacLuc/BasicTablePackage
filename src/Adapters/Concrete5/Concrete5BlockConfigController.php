<?php

namespace BaclucC5Crud\Adapters\Concrete5;

use BaclucC5Crud\Controller\ActionRegistryFactory;
use BaclucC5Crud\Controller\Validation\ValidationResult;
use BaclucC5Crud\Controller\Validation\ValidationResultItem;
use Closure;
use Concrete\Core\Block\BlockController;
use Concrete\Core\Error\ErrorList\ErrorList;
use DI\DependencyException;
use DI\NotFoundException;

trait Concrete5BlockConfigController {
    private BlockController $blockController;

    private Closure $configController;

    private string $blockId;

    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function add() {
        ProcessAction::processAction(
            $this->blockController,
            call_user_func($this->configController)
                ->getActionFor(ActionRegistryFactory::ADD_NEW_ROW_FORM, $this->blockId)
        );
    }

    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function edit() {
        ProcessAction::processAction(
            $this->blockController,
            call_user_func($this->configController)
                ->getActionFor(ActionRegistryFactory::EDIT_ROW_FORM, $this->blockId)
        );
    }

    /**
     * @param null|array|string $args
     *
     * @return bool|ErrorList|void
     *
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function validate($args) {
        /** @var ValidationResult $validationResult */
        $validationResult = ProcessAction::processAction(
            $this->blockController,
            call_user_func($this->configController)
                ->getActionFor(ActionRegistryFactory::VALIDATE_FORM, $this->blockId),
            $this->blockId
        );

        /** @var ErrorList $e */
        $e = $this->app->make(ErrorList::class);
        foreach ($validationResult as $validationResultItem) {
            /** @var ValidationResultItem $validationResultItem */
            foreach ($validationResultItem->getMessages() as $message) {
                $e->add(
                    $validationResultItem->getName().': '.$message,
                    $validationResultItem->getName(),
                    $validationResultItem->getName()
                );
            }
        }

        return $e;
    }

    /**
     * @param array $args
     *
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function save($args) {
        parent::save($args);
        ProcessAction::processAction(
            $this->blockController,
            call_user_func($this->configController)
                ->getActionFor(ActionRegistryFactory::POST_FORM, $this->blockId),
            $this->blockId
        );
    }

    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function delete() {
        parent::delete();
        ProcessAction::processAction(
            $this->blockController,
            call_user_func($this->configController)
                ->getActionFor(ActionRegistryFactory::DELETE_ENTRY, $this->blockId),
            $this->blockId
        );
    }

    private function initializeConfig($blockController, callable $crudController, $blockId) {
        $this->blockController = $blockController;
        $this->configController = $crudController;
        $this->blockId = $blockId;
    }
}
