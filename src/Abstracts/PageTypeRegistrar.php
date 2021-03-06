<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2016, notadd.com
 * @datetime 2016-10-10 17:23
 */
namespace Notadd\Content\Abstracts;

use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Notadd\Content\Events\RegisterPageType as RegisterTypeEvent;
use Notadd\Content\Managers\PageManager;
use Notadd\Foundation\Event\Abstracts\EventSubscriber;

/**
 * Class PageTypeRegistrar.
 */
abstract class PageTypeRegistrar extends EventSubscriber
{
    /**
     * @var \Notadd\Content\Managers\PageManager
     */
    protected $manager;

    /**
     * PageTypeRegistrar constructor.
     *
     * @param \Illuminate\Container\Container      $container
     * @param \Illuminate\Events\Dispatcher        $events
     * @param \Notadd\Content\Managers\PageManager $manager
     */
    public function __construct(Container $container, Dispatcher $events, PageManager $manager)
    {
        parent::__construct($container, $events);
        $this->manager = $manager;
    }

    /**
     * Name of event.
     *
     * @return mixed
     */
    protected function getEvent()
    {
        return RegisterTypeEvent::class;
    }

    /**
     * Registrar handler.
     *
     * @return void
     */
    abstract public function handle();
}
