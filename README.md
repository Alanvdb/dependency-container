
# Dependency Container
A PHP PSR-11 Dependency Injection System

## Overview

This project provides a lightweight dependency injection system for PHP. It includes the following main components:

1. `LazyContainer`: A container that lazily instantiates services.
2. `IterableLazyContainer`: An extension of `LazyContainer` that implements the `Iterator` interface.
3. `ContainerFactory`: A factory to create instances of `LazyContainer` and `IterableLazyContainer`.

This library is fully compatible with the PSR-11 standard, which defines a common interface for dependency injection containers. This ensures that your dependency injection containers are interoperable with other PSR-11 compliant libraries.

## Installation

To install the dependency-container, you can use Composer:

```bash
composer require alanvdb/dependency-container
```

## Usage

### LazyContainer

The `LazyContainer` class allows you to register services with a callable that will be invoked only when the service is requested. This provides lazy instantiation of services, which can improve performance by deferring the creation of services until they are actually needed.

#### Example

```php
use AlanVdb\Dependency\LazyContainer;

$container = new LazyContainer();

$container->add('service1', function() {
    return new Service1();
});

if ($container->has('service1')) {
    $service1 = $container->get('service1');
}
```

### IterableLazyContainer

The `IterableLazyContainer` extends `LazyContainer` and implements the `Iterator` interface, allowing you to iterate over the registered services.

#### Example

```php
use AlanVdb\Dependency\IterableLazyContainer;

$container = new IterableLazyContainer();

$container->add('service1', function() {
    return new Service1();
});

$container->add('service2', function() {
    return new Service2();
});

foreach ($container as $serviceId => $service) {
    // Process each service
}
```

### ContainerFactory

The `ContainerFactory` class provides methods to create instances of `LazyContainer` and `IterableLazyContainer`.

#### Example

```php
use AlanVdb\Dependency\Factory\ContainerFactory;

$factory = new ContainerFactory();

$lazyContainer = $factory->createLazyContainer();
$iterableLazyContainer = $factory->createIterableLazyContainer();
```

## PSR-11 Compatibility

This project adheres to the [PSR-11 Container Interface](https://www.php-fig.org/psr/psr-11/), ensuring that the `LazyContainer` and `IterableLazyContainer` classes conform to the standard. This allows for seamless integration with other libraries and frameworks that support PSR-11.

### Key PSR-11 Methods

#### `LazyContainer` and `IterableLazyContainer`

These classes implement the following PSR-11 methods:

- `get(string $id)`: Retrieves an entry from the container by its identifier.
  - If the identifier is not found, an exception implementing `Psr\Container\NotFoundExceptionInterface` is thrown.
- `has(string $id): bool`: Returns true if the container can return an entry for the given identifier.

By adhering to PSR-11, these containers can be used wherever a PSR-11 compliant container is expected.

## API Documentation

### LazyContainer

#### Methods

- `add(string $id, callable $generator)`: Adds a new service to the container.
  - `string $id`: The service identifier.
  - `callable $generator`: A callable that returns the service instance.

- `get(string $id)`: Retrieves a service from the container.
  - `string $id`: The service identifier.
  - Returns the service instance.
  - Throws `IdNotFoundException` if the service identifier is not found.

- `has(string $id): bool`: Checks if a service identifier exists in the container.
  - `string $id`: The service identifier.
  - Returns `true` if the service exists, `false` otherwise.

### IterableLazyContainer

The `IterableLazyContainer` inherits all methods from `LazyContainer` and implements additional methods from the `Iterator` interface:

- `current()`: Returns the current element.
- `key()`: Returns the key of the current element.
- `next()`: Moves forward to the next element.
- `rewind()`: Rewinds back to the first element.
- `valid()`: Checks if the current position is valid.

### ContainerFactory

#### Methods

- `createLazyContainer(): LazyContainerInterface`: Creates a new instance of `LazyContainer`.
- `createIterableLazyContainer(): LazyContainerInterface & Iterator`: Creates a new instance of `IterableLazyContainer`.

## Testing

To run the tests, use the following command:

```bash
vendor/bin/phpunit
```

The tests are located in the `tests` directory and cover the functionality of `LazyContainer`, `IterableLazyContainer`, and `ContainerFactory`.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.
