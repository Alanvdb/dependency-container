# Dependency Container
A PHP Dependency Injection System

## Overview

This project provides a lightweight dependency injection system for PHP. It includes the following main components:

1. `LazyContainer`: A container that lazily instantiates services.
2. `IterableLazyContainer`: An extension of `LazyContainer` that implements the `Iterator` interface.
3. `ContainerFactory`: A factory to create instances of `LazyContainer` and `IterableLazyContainer`.

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

$container->set('service1', function() {
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

$container->set('service1', function() {
    return new Service1();
});

$container->set('service2', function() {
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

## API Documentation

### LazyContainer

#### Methods

- `set(string $id, callable $generator)`: Adds a new service to the container.
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

## Contributing

Contributions are welcome! Please submit a pull request or open an issue to discuss any changes or improvements.