<?php declare(strict_types=1);

namespace AlanVdb\Tests\Dependency;

use PHPUnit\Framework\TestCase;
use AlanVdb\Dependency\LazyContainer;
use AlanVdb\Dependency\Throwable\IdNotFoundException;
use AlanVdb\Dependency\Throwable\InvalidContainerParamException;

class LazyContainerTest extends TestCase
{
    public function testSetAndGet()
    {
        $container = new LazyContainer();
        $container->set('service1', function() {
            return 'Service 1';
        });

        $this->assertTrue($container->has('service1'));
        $this->assertEquals('Service 1', $container->get('service1'));
    }

    public function testSetDuplicateServiceThrowsException()
    {
        $this->expectException(InvalidContainerParamException::class);
        $this->expectExceptionMessage("Callable ID 'service1' provided twice.");

        $container = new LazyContainer();
        $container->set('service1', function() {
            return 'Service 1';
        });

        $container->set('service1', function() {
            return 'Service 1 Duplicate';
        });
    }

    public function testSetWithEmptyIdThrowsException()
    {
        $this->expectException(InvalidContainerParamException::class);
        $this->expectExceptionMessage("Provided ID is empty.");

        $container = new LazyContainer();
        $container->set('', function() {
            return 'Service 1';
        });
    }

    public function testGetNonExistentServiceThrowsException()
    {
        $this->expectException(IdNotFoundException::class);
        $this->expectExceptionMessage("Container ID not found: 'non_existent_service'.");

        $container = new LazyContainer();
        $container->get('non_existent_service');
    }

    public function testServiceIsSingleton()
    {
        $container = new LazyContainer();
        $container->set('service1', function() {
            return new \stdClass();
        });

        $service1 = $container->get('service1');
        $service2 = $container->get('service1');

        $this->assertSame($service1, $service2);
    }
}
