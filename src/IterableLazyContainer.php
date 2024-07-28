<?php declare(strict_types=1);

namespace AlanVdb\Dependency;

use Iterator;
use AlanVdb\Dependency\Throwable\IdNotFound;
use AlanVdb\Dependency\Throwable\InvalidContainerParamException;

class IterableLazyContainer extends LazyContainer implements Iterator
{
    protected $position = 0;
    protected $keys = [];

    /**
     * Updates the keys for iteration.
     */
    protected function updateKeys(): void
    {
        $this->keys = array_merge(array_keys($this->generators), array_keys($this->instances));
    }

    /**
     * Adds a new service to the service container.
     *
     * @param string $id Service ID.
     * @param callable $generator Service generator.
     * @throws InvalidContainerParamException If the service ID is already used.
     */
    public function set(string $id, callable $generator)
    {
        parent::set($id, $generator);
        $this->updateKeys();
    }

    /**
     * Returns the current element.
     *
     * @return mixed
     * @throws IdNotFound If the identifier is not found.
     */
    public function current(): mixed
    {
        return $this->get($this->key());
    }

    /**
     * Returns the key of the current element.
     *
     * @return mixed
     */
    public function key(): mixed
    {
        return $this->keys[$this->position];
    }

    /**
     * Moves forward to the next element.
     */
    public function next(): void
    {
        ++$this->position;
    }

    /**
     * Rewinds back to the first element of the iterator.
     */
    public function rewind(): void
    {
        $this->position = 0;
    }

    /**
     * Checks if the current position is valid.
     *
     * @return bool
     */
    public function valid(): bool
    {
        return array_key_exists($this->position, $this->keys);
    }
}
