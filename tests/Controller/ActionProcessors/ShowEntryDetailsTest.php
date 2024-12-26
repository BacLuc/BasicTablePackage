<?php

namespace BaclucC5Crud\Test\Controller\ActionProcessors;

use BaclucC5Crud\Controller\ActionRegistryFactory;
use BaclucC5Crud\Controller\CrudController;
use BaclucC5Crud\Entity\ExampleEntity;
use BaclucC5Crud\Test\Constraints\Matchers;
use BaclucC5Crud\Test\DIContainerFactory;
use DI\Container;
use DI\DependencyException;
use DI\NotFoundException;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class ShowEntryDetailsTest extends TestCase {
    /**
     * @var CrudController
     */
    private $crudController;

    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    protected function setUp(): void {
        /** @var EntityManager $entityManager */
        $entityManager = $this->createMock(EntityManager::class);

        /** @var Container $container */
        $container = DIContainerFactory::createContainer($entityManager, ExampleEntity::class);
        ExampleEntityConstants::addReferencedEntityTestValues($container);
        $this->crudController = $container->get(CrudController::class);
        $this->crudController->getActionFor(ActionRegistryFactory::POST_FORM, '0')
            ->process([], ExampleEntityConstants::ENTRY_1_POST)
        ;
    }

    public function testShowDetails() {
        ob_start();
        $this->crudController->getActionFor(ActionRegistryFactory::SHOW_ENTRY_DETAILS, '0')
            ->process([], [], 1)
        ;

        $output = ob_get_clean();
        $this->assertThat($output, Matchers::stringContainsKeysAndValues(ExampleEntityConstants::getValues()));
        $this->assertStringNotContainsString('<form', $output);
    }
}
