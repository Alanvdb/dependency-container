<?php declare(strict_types=1);

namespace AlanVdb\Dependency;

use AlanVdb\Dependency\Definition\LazyContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use AlanVdb\Dependency\Throwable\IdNotFoundException;
use AlanVdb\Dependency\Throwable\InvalidContainerParamException;
use function \array_key_exists;
use function \empty;
use function \unset;

class LazyContainer implements LazyContainerInterface
{
    /**
     * @param callable[] $generators
     */
    protected $generators = [];

    /**
     * @param mixed[] $instances
     */
    protected $instances = [];

    /**
     * Adds a new generator to the generator container.
     *
     * @param string $id Generator ID.
     * @param callable $generator callable returning a value
     * @throws InvalidContainerParamException On invalid param provided.
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
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     * @return mixed Entry.
     * @throws NotFoundExceptionInterface If no entry was found for the identifier.
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
     * Returns true if the container can return an entry for the given identifier.
     *
     * @param string $id Identifier of the entry to look for.
     * @return bool
     */
    public function has(string $id): bool
    {
        return array_key_exists($id, $this->generators) || array_key_exists($id, $this->instances);
    }
}
