<?php


namespace BasicTablePackage\Controller;


class ActionRegistryFactory
{
    const SHOW_TABLE       = "show_table";
    const ADD_NEW_ROW_FORM = "add_new_row_form";
    const POST_FORM        = "post_form";
    const CANCEL_FORM      = "cancel_form";

    /**
     * @var ActionProcessor[]
     */
    private $actions;

    /**
     * ActionRegistryFactory constructor.
     */
    public function __construct (ShowTableActionProcessor $showTableActionProcessor,
                                 ShowFormActionProcessor $showFormActionProcessor,
                                 PostFormActionProcessor $postFormActionProcessor)
    {
        $this->actions = [
            $showTableActionProcessor, $showFormActionProcessor, $postFormActionProcessor,
        ];
    }


    public function createActionRegistry (): ActionRegistry
    {
        return new ActionRegistry($this->actions);
    }
}