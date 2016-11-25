<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-10-08 17:26
 */
namespace Notadd\Content\Controllers;

use Notadd\Content\Handlers\Creators\ArticleCreatorHandler;
use Notadd\Content\Handlers\Deleters\ArticleDeleterHandler;
use Notadd\Content\Handlers\Finders\ArticleFinderHandler;
use Notadd\Foundation\Routing\Abstracts\Controller;

/**
 * Class ArticleController.
 */
class ArticleController extends Controller
{
    public function destroy(ArticleDeleterHandler $handler)
    {
        $response = $handler->toResponse($this->request);

        return $response->generateHttpResponse();
    }

    /**
     * @param \Notadd\Content\Handlers\Finders\ArticleFinderHandler $handler
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(ArticleFinderHandler $handler)
    {

        return $this->view('');
    }

    /**
     * @param \Notadd\Content\Handlers\Creators\ArticleCreatorHandler $handler
     *
     * @return \Psr\Http\Message\ResponseInterface|\Zend\Diactoros\Response
     */
    public function create(ArticleCreatorHandler $handler)
    {
        $response = $handler->toResponse($this->request);

        return $response->generateHttpResponse();
    }

    /**
     * @param \Notadd\Content\Handlers\Finders\ArticleFinderHandler $handler
     *
     * @return \Psr\Http\Message\ResponseInterface|\Zend\Diactoros\Response
     */
    public function show(ArticleFinderHandler $handler)
    {
        $response = $handler->toResponse();

        return $response->generateHttpResponse();
    }
}
