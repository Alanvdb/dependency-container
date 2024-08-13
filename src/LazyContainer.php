<?php declare(strict_types=1);

namespace AlanVdb\Dependency;

use AlanVdb\Dependency\Definition\LazyContainerInterface;

use Psr\Container\NotFoundExceptionInterface;
use AlanVdb\Dependency\Exception\IdNotFoundException;
use AlanVdb\Dependency\Exception\InvalidContainerParamException;

/**
 * Class LazyContainer
 *
 * A container that lazily instantiates services when they are requested.
 */
class LazyContainer implements LazyContainerInterface
{
    /**
     * @var callable[] $generators Array of service generators.
     */
    protected $generators = [];

    /**
     * @var mixed[] $instances Array of instantiated services.
     */
    protected $instances = [];

    /**
     * Adds a new generator to the container.
     *
     * @param string $id The ID of the service.
     * @param callable $generator A callable that returns the service instance.
     * @throws InvalidContainerParamException If the ID is empty or already exists.
     */
    public function add(string $id, callable $generator)
    {
        if (empty($id)) {
            throw new InvalidContainerParamException("Provided ID is empty.");
        }
        if ($this->has($id)) {
            throw new InvalidContainerParamException("Callable ID '$id' provided twice.");
        }
        $this->generators[$id] = $generator;
    }

    /**
     * Retrieves a service by its ID.
     *
     * @param string $id The ID of the service.
     * @return mixed The service instance.
     * @throws NotFoundExceptionInterface If no service was found for the ID.
     */
    public function get(string $id)
    {
        if (!$this->has($id)) {
            throw new IdNotFoundException("Container ID not found: '$id'.");
        }
        if (!array_key_exists($id, $this->instances)) {
            $this->instances[$id] = $this->generators[$id]($this);
            unset($this->generators[$id]);
        }
        return $this->instances[$id];
    }

    /**
     * Checks if the container has a service for the given ID.
     *
     * @param string $id The ID of the service.
     * @return bool True if the service exists, false otherwise.
     */
    public function has(string $id): bool
    {
        return array_key_exists($id, $this->generators) || array_key_exists($id, $this->instances);
    }
}
