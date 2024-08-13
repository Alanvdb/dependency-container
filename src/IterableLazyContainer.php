<?php declare(strict_types=1);

namespace AlanVdb\Dependency;

use Iterator;

use AlanVdb\Dependency\Exception\IdNotFoundException;
use AlanVdb\Dependency\Exception\InvalidContainerParamException;

/**
 * IterableLazyContainer class extending LazyContainer and implementing the Iterator interface.
 *
 * This class provides the ability to lazily load services and iterate over them.
 */
class IterableLazyContainer extends LazyContainer implements Iterator
{
    /**
     * @var int Current position in the iterator.
     */
    protected $position = 0;

    /**
     * @var array Keys of the services for iteration.
     */
    protected $keys = [];

    /**
     * {@inheritdoc}
     */
    public function add(string $id, callable $generator)
    {
        parent::add($id, $generator);
        $this->updateKeys();
    }

    /**
     * Updates the keys for iteration.
     *
     * This method merges the keys from both generators and instances to refresh the iteration keys.
     */
    protected function updateKeys(): void
    {
        $this->keys = array_merge(array_keys($this->generators), array_keys($this->instances));
    }

    /**
     * Returns the current element.
     *
     * @return mixed The current service instance.
     * @throws IdNotFoundException If the identifier is not found.
     */
    public function current(): mixed
    {
        return $this->get($this->key());
    }

    /**
     * Returns the key of the current element.
     *
     * @return mixed The key of the current element.
     */
    public function key(): mixed
    {
        return $this->keys[$this->position];
    }

    /**
     * Moves forward to the next element.
     *
     * Advances the internal pointer of the iterator to the next element.
     */
    public function next(): void
    {
        ++$this->position;
    }

    /**
     * Rewinds back to the first element of the iterator.
     *
     * Resets the internal pointer of the iterator to the first element.
     */
    public function rewind(): void
    {
        $this->position = 0;
    }

    /**
     * Checks if the current position is valid.
     *
     * @return bool True if the current position is valid, false otherwise.
     */
    public function valid(): bool
    {
        return array_key_exists($this->position, $this->keys);
    }
}
