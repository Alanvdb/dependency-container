<?php declare(strict_types=1);

namespace AlanVdb\Dependency\Definition;

use Iterator;

/**
 * Interface ContainerFactoryInterface
 *
 * Provides methods to create different types of container instances.
 */
interface ContainerFactoryInterface
{
    /**
     * Creates a new instance of LazyContainerInterface.
     *
     * @return LazyContainerInterface The created lazy container instance.
     */
    public function createLazyContainer(): LazyContainerInterface;

    /**
     * Creates a new instance of a container that is both LazyContainerInterface and Iterator.
     *
     * @return LazyContainerInterface&Iterator The created iterable lazy container instance.
     */
    public function createIterableLazyContainer(): LazyContainerInterface & Iterator;
}
