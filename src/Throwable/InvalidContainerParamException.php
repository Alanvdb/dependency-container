<?php declare(strict_types=1);

namespace AlanVdb\Dependency\Throwable;

use Psr\Container\ContainerExceptionInterface;
use InvalidArgumentException;

class InvalidContainerParamException
    extends InvalidArgumentException
    implements ContainerExceptionInterface
{
    const EMPTY_ID = 1;
    const DUPLICATED_ID = 2;
}
