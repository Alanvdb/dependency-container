<?php declare(strict_types=1);

namespace AlanVdb\Dependency\Factory;

use AlanVdb\Dependency\Definition\ContainerFactoryInterface;
use AlanVdb\Dependency\Definition\LazyContainerInterface;
use AlanVdb\Dependency\LazyContainer;
use AlanVdb\Dependency\IterableLazyContainer;
use Iterator;

/**
 * Class ContainerFactory
 *
 * Implements the ContainerFactoryInterface to create different types of container instances.
 */
class ContainerFactory implements ContainerFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createLazyContainer(): LazyContainerInterface
    {
        return new LazyContainer();
    }

    /**
     * {@inheritdoc}
     */
    public function createIterableLazyContainer(): LazyContainerInterface & Iterator
    {
        return new IterableLazyContainer();
    }
}
