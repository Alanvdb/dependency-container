<?php declare(strict_types=1);

namespace AlanVdb\Tests\Dependency;

use PHPUnit\Framework\TestCase;
use AlanVdb\Dependency\IterableLazyContainer;
use AlanVdb\Dependency\Throwable\IdNotFoundException;
use AlanVdb\Dependency\Throwable\InvalidContainerParamException;

class IterableLazyContainerTest extends TestCase
{
    public function testSetAndGet()
    {
        $container = new IterableLazyContainer();
        $container->set('service1', function() {
            return 'Service 1';
        });

        $this->assertTrue($container->has('service1'));
        $this->assertEquals('Service 1', $container->get('service1'));
    }

    public function testSetDuplicateServiceThrowsException()
    {
        $this->expectException(InvalidContainerParamException::class);

        $container = new IterableLazyContainer();
        $container->set('service1', function() {
            return 'Service 1';
        });

        $container->set('service1', function() {
            return 'Service 1 Duplicate';
        });
    }

    public function testGetNonExistentServiceThrowsException()
    {
        $this->expectException(IdNotFoundException::class);

        $container = new IterableLazyContainer();
        $container->get('non_existent_service');
    }

    public function testServiceIsSingleton()
    {
        $container = new IterableLazyContainer();
        $container->set('service1', function() {
            return new \stdClass();
        });

        $service1 = $container->get('service1');
        $service2 = $container->get('service1');

        $this->assertSame($service1, $service2);
    }

    public function testIteration()
    {
        $container = new IterableLazyContainer();
        $container->set('service1', function() {
            return 'Service 1';
        });
        $container->set('service2', function() {
            return 'Service 2';
        });

        $expectedServices = [
            'service1' => 'Service 1',
            'service2' => 'Service 2'
        ];
        $actualServices = [];

        foreach ($container as $key => $service) {
            $actualServices[$key] = $service;
        }

        $this->assertEquals($expectedServices, $actualServices);
    }

    public function testRewind()
    {
        $container = new IterableLazyContainer();
        $container->set('service1', function() {
            return 'Service 1';
        });
        $container->set('service2', function() {
            return 'Service 2';
        });

        $container->next(); // Advance the iterator

        $this->assertEquals('Service 2', $container->current());

        $container->rewind(); // Rewind to the beginning

        $this->assertEquals('Service 1', $container->current());
    }

    public function testValid()
    {
        $container = new IterableLazyContainer();
        $container->set('service1', function() {
            return 'Service 1';
        });

        $container->rewind();
        $this->assertTrue($container->valid());

        $container->next();
        $this->assertFalse($container->valid());
    }
}
