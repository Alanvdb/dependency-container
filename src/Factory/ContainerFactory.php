<?php declare(strict_types = 1);

namespace AlanVdb\Dependency\Factory;

use AlanVdb\Dependency\Definition\ContainerFactoryInterface;
use AlanVdb\Dependency\Definition\LazyContainerInterface;
use AlanVdb\Dependency\LazyContainer;
use AlanVdb\Dependency\IterableLazyContainer;
use Iterator;

class ContainerFactory implements ContainerFactoryInterface
{
    /**
     * @return LazyContainerInterface
     */
    public function createLazyContainer() : LazyContainerInterface
    {
        return new LazyContainer();
    }

    /**
     * @return LazyContainerInterface&Iterator
     */
    public function createIterableLazyContainer() : LazyContainerInterface & Iterator
    {
        return new IterableLazyContainer();
    }
}
