<?php declare(strict_types=1);

namespace AlanVdb\Tests\Dependency;

use AlanVdb\Dependency\LazyContainer;
use AlanVdb\Dependency\Exception\IdNotFoundException;
use AlanVdb\Dependency\Exception\InvalidContainerParamException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;
use TypeError;
use stdClass;

#[CoversClass(LazyContainer::class)]
class LazyContainerTest extends TestCase
{
    #[\PHPUnit\Test]
    public function testAddAndGetService()
    {
        $container = new LazyContainer();

        $service = new stdClass();
        $service->name = 'TestService';

        $container->add('test.service', fn() => $service);

        $retrievedService = $container->get('test.service');
        
        $this->assertSame($service, $retrievedService);
    }

    #[\PHPUnit\Test]
    public function testHasService()
    {
        $container = new LazyContainer();

        $this->assertFalse($container->has('nonexistent.service'));

        $container->add('test.service', fn() => new stdClass());

        $this->assertTrue($container->has('test.service'));
    }

    #[\PHPUnit\Test]
    public function testGetServiceThrowsExceptionForUnknownId()
    {
        $this->expectException(IdNotFoundException::class);

        $container = new LazyContainer();
        $container->get('unknown.service');
    }

    #[\PHPUnit\Test]
    public function testAddServiceThrowsExceptionForEmptyId()
    {
        $this->expectException(InvalidContainerParamException::class);

        $container = new LazyContainer();
        $container->add('', fn() => new stdClass());
    }

    #[\PHPUnit\Test]
    public function testAddServiceThrowsExceptionForDuplicatedId()
    {
        $this->expectException(InvalidContainerParamException::class);

        $container = new LazyContainer();
        $container->add('test.service', fn() => new stdClass());
        $container->add('test.service', fn() => new stdClass());
    }

    #[\PHPUnit\Test]
    public function testAddServiceThrowsTypeErrorForInvalidCallable()
    {
        $this->expectException(TypeError::class);

        $container = new LazyContainer();
        $container->add('test.service', new stdClass());
    }

    #[\PHPUnit\Test]
    #[\PHPUnit\Depends('testAddAndGetService')]
    public function testServiceIsInstantiatedLazily()
    {
        $container = new LazyContainer();

        $isInstantiated = false;
        $container->add('lazy.service', function () use (&$isInstantiated) {
            $isInstantiated = true;
            return new stdClass();
        });

        $this->assertFalse($isInstantiated);

        $container->get('lazy.service');
        
        $this->assertTrue($isInstantiated);
    }
}
