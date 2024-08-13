<?php declare(strict_types=1);

namespace AlanVdb\Dependency\Definition;

use Psr\Container\ContainerInterface;
use AlanVdb\Dependency\Throwable\InvalidContainerParamException;

interface LazyContainerInterface extends ContainerInterface
{
    /**
     * Adds a new generator to the container.
     *
     * @param string $id The ID of the service.
     * @param callable $generator A callable that returns the service instance.
     * @throws InvalidContainerParamException If the ID is empty or already exists.
     */
    public function add(string $id, callable $generator);
}