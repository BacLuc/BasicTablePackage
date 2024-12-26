<?php

namespace BaclucC5Crud\Controller\ActionProcessors;

use BaclucC5Crud\Controller\ActionConfiguration;
use BaclucC5Crud\Controller\ActionProcessor;
use BaclucC5Crud\Controller\ActionRegistryFactory;
use BaclucC5Crud\Controller\CurrentUrlSupplier;
use BaclucC5Crud\Controller\PaginationParser;
use BaclucC5Crud\Controller\Renderer;
use BaclucC5Crud\Controller\RowActionConfiguration;
use BaclucC5Crud\Controller\VariableSetter;
use BaclucC5Crud\TableViewService;
use BaclucC5Crud\View\FormView\IntegerField;

class ShowTable implements ActionProcessor {
    public const TABLE_VIEW = 'view/table';

    /**
     * @var TableViewService
     */
    private $tableViewService;

    /**
     * @var VariableSetter
     */
    private $variableSetter;

    /**
     * @var Renderer
     */
    private $renderer;

    /**
     * @var ActionConfiguration
     */
    private $actionConfiguration;

    /**
     * @var RowActionConfiguration
     */
    private $rowActionConfiguration;

    /**
     * @var PaginationParser
     */
    private $paginationParser;

    /**
     * @var CurrentUrlSupplier
     */
    private $currentUrlSupplier;

    public function __construct(
        TableViewService $tableViewService,
        VariableSetter $variableSetter,
        Renderer $renderer,
        ActionConfiguration $actionConfiguration,
        RowActionConfiguration $rowActionConfiguration,
        PaginationParser $paginationParser,
        CurrentUrlSupplier $currentUrlSupplier
    ) {
        $this->tableViewService = $tableViewService;
        $this->variableSetter = $variableSetter;
        $this->renderer = $renderer;
        $this->actionConfiguration = $actionConfiguration;
        $this->rowActionConfiguration = $rowActionConfiguration;
        $this->paginationParser = $paginationParser;
        $this->currentUrlSupplier = $currentUrlSupplier;
    }

    public function getName(): string {
        return ActionRegistryFactory::SHOW_TABLE;
    }

    public function process(array $get, array $post, ...$additionalParameters) {
        $paginationConfiguration = $this->paginationParser->parse($get);
        $tableView = $this->tableViewService->getTableView($paginationConfiguration);
        $this->variableSetter->set('headers', $tableView->getHeaders());
        $this->variableSetter->set('rows', $tableView->getRows());
        $this->variableSetter->set('actions', $this->actionConfiguration->getActions());
        $this->variableSetter->set('rowactions', $this->rowActionConfiguration->getActions());
        $this->variableSetter->set('count', $tableView->getCount());
        $this->variableSetter->set('currentPage', $paginationConfiguration->getCurrentPage());
        $this->variableSetter->set('pageSize', $paginationConfiguration->getPageSize());
        $pageSizeField = new IntegerField('Entries to display', 'pageSize', $paginationConfiguration->getPageSize());
        $this->variableSetter->set('pageSizeField', $pageSizeField);
        $this->variableSetter->set('currentURL', $this->currentUrlSupplier->getUrl());
        $this->renderer->render(self::TABLE_VIEW);
    }
}
