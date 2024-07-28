<?php declare(strict_types=1);

namespace AlanVdb\Dependency\Throwable;

use Psr\Container\NotFoundExceptionInterface;
use InvalidArgumentException;

class IdNotFoundException
    extends InvalidArgumentException
    implements NotFoundExceptionInterface
{}
