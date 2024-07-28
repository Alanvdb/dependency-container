<?php declare(strict_types=1);

namespace AlanVdb\Dependency;

use AlanVdb\Dependency\Definition\LazyContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use AlanVdb\Dependency\Throwable\IdNotFoundException;
use AlanVdb\Dependency\Throwable\InvalidContainerParamException;
use function \array_key_exists;
use function \empty;
use function \unset;

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
     * {@inheritdoc}
     */
    public function set(string $id, callable $generator)
    {
        if (empty($id)) {
            throw new InvalidContainerParamException("Provided ID is empty.", InvalidContainerParamException::EMPTY_ID);
        }
        if ($this->has($id)) {
            throw new InvalidContainerParamException("Callable ID '$id' provided twice.", InvalidContainerParamException::DUPLICATED_ID);
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
