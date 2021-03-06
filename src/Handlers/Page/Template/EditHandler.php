<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2016, notadd.com
 * @datetime 2016-11-25 15:16
 */
namespace Notadd\Content\Handlers\Page\Template;

use Illuminate\Container\Container;
use Notadd\Content\Models\PageTemplate;
use Notadd\Foundation\Passport\Abstracts\SetHandler;

/**
 * Class EditHandler.
 */
class EditHandler extends SetHandler
{
    /**
     * EditHandler constructor.
     *
     * @param \Illuminate\Container\Container     $container
     * @param \Notadd\Content\Models\PageTemplate $pageTemplate
     */
    public function __construct(
        Container $container,
        PageTemplate $pageTemplate
    ) {
        parent::__construct($container);
        $this->errors->push($this->translator->trans('content::page_template.update.fail'));
        $this->messages->push($this->translator->trans('content::page_template.update.success'));
        $this->model = $pageTemplate;
    }

    /**
     * Execute Handler.
     *
     * @return bool
     */
    public function execute()
    {
        $pageTemplate = $this->model->newQuery()->find($this->request->input('id'));
        if ($pageTemplate === null) {
            return false;
        }
        $pageTemplate->update($this->request->all());

        return true;
    }
}
