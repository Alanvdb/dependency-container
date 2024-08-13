<?php declare(strict_types=1);

namespace AlanVdb\Dependency\Exception;

use Psr\Container\ContainerExceptionInterface;
use InvalidArgumentException;

class InvalidContainerParamException
    extends InvalidArgumentException
    implements ContainerExceptionInterface
{}
