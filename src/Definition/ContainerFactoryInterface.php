<?php declare(strict_types=1);


namespace AlanVdb\Dependency\Definition;


use Iterator;


interface ContainerFactoryInterface
{
    /**
     * @return LazyContainerInterface
     */
    public function createLazyContainer() : LazyContainerInterface;

    /**
     * @return LazyContainerInterface&Iterator
     */
    public function createIterableLazyContainer() : LazyContainerInterface & Iterator;
}