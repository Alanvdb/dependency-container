<?php declare(strict_types=1);

namespace AlanVdb\Tests\Dependency;

use PHPUnit\Framework\TestCase;
use AlanVdb\Dependency\Factory\ContainerFactory;
use AlanVdb\Dependency\LazyContainer;
use AlanVdb\Dependency\IterableLazyContainer;
use AlanVdb\Dependency\Definition\LazyContainerInterface;
use Iterator;

class ContainerFactoryTest extends TestCase
{
    public function testCreateLazyContainer()
    {
        $factory = new ContainerFactory();
        $container = $factory->createLazyContainer();
        
        $this->assertInstanceOf(LazyContainerInterface::class, $container);
        $this->assertInstanceOf(LazyContainer::class, $container);
    }

    public function testCreateIterableLazyContainer()
    {
        $factory = new ContainerFactory();
        $container = $factory->createIterableLazyContainer();
        
        $this->assertInstanceOf(LazyContainerInterface::class, $container);
        $this->assertInstanceOf(Iterator::class, $container);
        $this->assertInstanceOf(IterableLazyContainer::class, $container);
    }
}
