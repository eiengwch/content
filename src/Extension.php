<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-10-08 17:12
 */
namespace Notadd\Content;
use Notadd\Content\Events\RegisterArticleTemplate;
use Notadd\Content\Events\RegisterArticleType;
use Notadd\Content\Events\RegisterCategoryTemplate;
use Notadd\Content\Events\RegisterCategoryType;
use Notadd\Content\Events\RegisterPageTemplate;
use Notadd\Content\Events\RegisterPageType;
use Notadd\Content\Listeners\RouteRegister;
use Notadd\Content\Managers\ArticleManager;
use Notadd\Content\Managers\CategoryManager;
use Notadd\Content\Managers\PageManager;
use Notadd\Extension\Abstracts\ExtensionRegistrar;
/**
 * Class Module
 * @package Notadd\Content
 */
class Extension extends ExtensionRegistrar {
    /**
     * @return string
     */
    protected function getExtensionName() {
        return 'notadd/content';
    }
    /**
     * @return string
     */
    protected function getExtensionPath() {
        return realpath(__DIR__ . '/../');
    }
    /**
     * @return void
     */
    public function register() {
        $this->container->alias('article.manager', ArticleManager::class);
        $this->container->alias('category.manager', CategoryManager::class);
        $this->container->alias('page.manager', PageManager::class);
        $this->container->singleton('article.manager', function ($app) {
            $manager = new ArticleManager($app, $app['events']);
            $this->events->fire(new RegisterArticleTemplate($app, $manager));
            $this->events->fire(new RegisterArticleType($app, $manager));
            return $manager;
        });
        $this->container->singleton('category.manager', function ($app) {
            $manager = new CategoryManager($app, $app['events']);
            $this->events->fire(new RegisterCategoryTemplate($app, $manager));
            $this->events->fire(new RegisterCategoryType($app, $manager));
            return $manager;
        });
        $this->container->singleton('page.manager', function ($app) {
            $manager = new PageManager($app, $app['events']);
            $this->events->fire(new RegisterPageTemplate($app, $manager));
            $this->events->fire(new RegisterPageType($app, $manager));
            return $manager;
        });
        $this->events->subscribe(RouteRegister::class);
    }
}