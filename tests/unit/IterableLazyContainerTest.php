<?php declare(strict_types=1);

namespace AlanVdb\Tests\Dependency;

use AlanVdb\Dependency\IterableLazyContainer;
use AlanVdb\Dependency\Exception\IdNotFoundException;
use AlanVdb\Dependency\Exception\InvalidContainerParamException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;
use TypeError;
use stdClass;

#[CoversClass(IterableLazyContainer::class)]
class IterableLazyContainerTest extends TestCase
{
    #[\PHPUnit\Test]
    public function testAddAndGetService()
    {
        $container = new IterableLazyContainer();

        $service = new stdClass();
        $service->name = 'TestService';

        $container->add('test.service', fn() => $service);

        $retrievedService = $container->get('test.service');
        
        $this->assertSame($service, $retrievedService);
    }

    #[\PHPUnit\Test]
    public function testHasService()
    {
        $container = new IterableLazyContainer();

        $this->assertFalse($container->has('nonexistent.service'));

        $container->add('test.service', fn() => new stdClass());

        $this->assertTrue($container->has('test.service'));
    }

    #[\PHPUnit\Test]
    public function testGetServiceThrowsExceptionForUnknownId()
    {
        $this->expectException(IdNotFoundException::class);

        $container = new IterableLazyContainer();
        $container->get('unknown.service');
    }

    #[\PHPUnit\Test]
    public function testAddServiceThrowsExceptionForEmptyId()
    {
        $this->expectException(InvalidContainerParamException::class);

        $container = new IterableLazyContainer();
        $container->add('', fn() => new stdClass());
    }

    #[\PHPUnit\Test]
    public function testAddServiceThrowsExceptionForDuplicatedId()
    {
        $this->expectException(InvalidContainerParamException::class);

        $container = new IterableLazyContainer();
        $container->add('test.service', fn() => new stdClass());
        $container->add('test.service', fn() => new stdClass());
    }

    #[\PHPUnit\Test]
    public function testAddServiceThrowsTypeErrorForInvalidCallable()
    {
        $this->expectException(TypeError::class);

        $container = new IterableLazyContainer();
        $container->add('test.service', new stdClass());
    }

    #[\PHPUnit\Test]
    #[\PHPUnit\Depends('testAddAndGetService')]
    public function testServiceIsInstantiatedLazily()
    {
        $container = new IterableLazyContainer();

        $isInstantiated = false;
        $container->add('lazy.service', function () use (&$isInstantiated) {
            $isInstantiated = true;
            return new stdClass();
        });

        $this->assertFalse($isInstantiated);

        $container->get('lazy.service');
        
        $this->assertTrue($isInstantiated);
    }

    #[\PHPUnit\Test]
    public function testIterationOverServices()
    {
        $container = new IterableLazyContainer();

        $service1 = new stdClass();
        $service1->name = 'Service1';
        $service2 = new stdClass();
        $service2->name = 'Service2';

        $container->add('service1', fn() => $service1);
        $container->add('service2', fn() => $service2);

        $expectedServices = [
            'service1' => $service1,
            'service2' => $service2,
        ];

        foreach ($container as $key => $service) {
            $this->assertSame($expectedServices[$key], $service);
        }
    }

    #[\PHPUnit\Test]
    public function testIteratorRewind()
    {
        $container = new IterableLazyContainer();

        $container->add('service1', fn() => new stdClass());
        $container->add('service2', fn() => new stdClass());

        $container->rewind();
        $firstKey = $container->key();

        $container->next();
        $container->rewind();
        $rewoundKey = $container->key();

        $this->assertSame($firstKey, $rewoundKey);
    }
}
