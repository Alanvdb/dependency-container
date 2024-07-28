<?php declare(strict_types=1);


namespace AlanVdb\Dependency\Definition;


use Psr\Container\ContainerInterface;


interface LazyContainerInterface extends ContainerInterface
{
    /**
     * @param string $id Generator id
     * @param callable $generator Generator
     */
    public function set(string $id, callable $generator);
}