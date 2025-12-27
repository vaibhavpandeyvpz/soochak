# Soochak

[![Latest Version](https://img.shields.io/packagist/v/vaibhavpandeyvpz/soochak.svg?style=flat-square)](https://packagist.org/packages/vaibhavpandeyvpz/soochak)
[![Downloads](https://img.shields.io/packagist/dt/vaibhavpandeyvpz/soochak.svg?style=flat-square)](https://packagist.org/packages/vaibhavpandeyvpz/soochak)
[![PHP Version](https://img.shields.io/packagist/php-v/vaibhavpandeyvpz/soochak.svg?style=flat-square)](https://packagist.org/packages/vaibhavpandeyvpz/soochak)
[![License](https://img.shields.io/packagist/l/vaibhavpandeyvpz/soochak.svg?style=flat-square)](LICENSE.md)
[![Code Coverage](https://img.shields.io/badge/coverage-100%25-brightgreen.svg?style=flat-square)](https://github.com/vaibhavpandeyvpz/soochak)

> **Soochak** (`सूचक`) - A modern, PSR-14 compliant event dispatcher for PHP 8.2+

Soochak is a lightweight, high-performance event management library that implements the [PSR-14 Event Dispatcher](https://www.php-fig.org/psr/psr-14/) standard. It provides a simple yet powerful API for implementing the observer pattern in your PHP applications.

## Features

- ✅ **PSR-14 Compliant** - Full implementation of EventDispatcherInterface and ListenerProviderInterface
- ✅ **Backward Compatible** - Legacy API maintained for existing codebases
- ✅ **Priority Support** - Control listener execution order with priority queues
- ✅ **Event Propagation** - Stop event propagation when needed
- ✅ **Type Safe** - Full PHP 8.2+ type hints and union types
- ✅ **Memory Efficient** - Uses generators for lazy listener iteration
- ✅ **100% Test Coverage** - Comprehensive test suite with 50+ tests
- ✅ **Zero Dependencies** - Only requires PSR-14 interfaces

## Requirements

- PHP 8.2 or higher
- Composer

## Installation

Install via Composer:

```bash
composer require vaibhavpandeyvpz/soochak
```

## Quick Start

### Basic Usage

```php
<?php

use Soochak\EventManager;
use Soochak\Event;

$em = new EventManager();

// Attach a listener to an event
$em->attach('user.created', function (EventInterface $event) {
    $user = $event->getParam('user');
    echo "User {$user['name']} was created!";
});

// Trigger the event with parameters
$em->trigger('user.created', [
    'user' => ['name' => 'John Doe', 'email' => 'john@example.com']
]);
```

### Using PSR-14 Standard API

```php
<?php

use Soochak\EventManager;
use Soochak\Event;

$em = new EventManager();
$event = new Event('user.created', ['user_id' => 123]);

// Attach listener
$em->attach('user.created', function (object $event) {
    // Handle the event
});

// Dispatch using PSR-14 interface
$em->dispatch($event);
```

## Usage Examples

### Priority-Based Listeners

Listeners with higher priority values are executed first:

```php
$em = new EventManager();

// Lower priority (executed last)
$em->attach('order.completed', function (EventInterface $event) {
    echo "Sending email notification...\n";
}, 10);

// Higher priority (executed first)
$em->attach('order.completed', function (EventInterface $event) {
    echo "Updating inventory...\n";
}, 20);

$em->trigger('order.completed');
// Output:
// Updating inventory...
// Sending email notification...
```

### Event Propagation Control

Stop further listeners from executing:

```php
$em = new EventManager();

$em->attach('request.validate', function (EventInterface $event) {
    if ($event->getParam('invalid')) {
        $event->stopPropagation(true);
        echo "Validation failed, stopping propagation\n";
    }
});

$em->attach('request.validate', function (EventInterface $event) {
    echo "This won't execute if propagation was stopped\n";
});

$event = new Event('request.validate', ['invalid' => true]);
$em->trigger($event);
```

### Working with Event Objects

```php
use Soochak\Event;

// Create an event with parameters
$event = new Event('payment.processed', [
    'amount' => 99.99,
    'currency' => 'USD',
    'transaction_id' => 'txn_123'
]);

// Set event target (optional)
$event->setTarget($paymentGateway);

// Check and modify event
if ($event->hasParam('amount')) {
    $amount = $event->getParam('amount');
    echo "Processing payment of {$amount}\n";
}

// Trigger the event
$em->trigger($event);
```

### Custom Event Objects

You can use any object as an event:

```php
class UserRegisteredEvent
{
    public function __construct(
        public readonly int $userId,
        public readonly string $email
    ) {}
}

$em = new EventManager();

// Attach listener using class name
$em->attach(UserRegisteredEvent::class, function (UserRegisteredEvent $event) {
    echo "User {$event->userId} registered with email {$event->email}\n";
});

// Or attach using instance
$event = new UserRegisteredEvent(123, 'user@example.com');
$em->attach($event, function (UserRegisteredEvent $e) {
    // Handle event
});

// Dispatch
$em->dispatch($event);
```

### Managing Listeners

```php
$em = new EventManager();

$listener = function (EventInterface $event) {
    echo "Listener executed\n";
};

// Attach
$em->attach('test.event', $listener);

// Detach
$em->detach('test.event', $listener);

// Clear all listeners for an event
$em->clearListeners('test.event');
```

### Getting Listeners for an Event

```php
$em = new EventManager();

$em->attach('test', function () {}, 10);
$em->attach('test', function () {}, 20);

$event = new Event('test');
$listeners = iterator_to_array($em->getListenersForEvent($event));

echo count($listeners); // 2
```

## API Reference

### EventManager

The main event manager class implementing PSR-14 interfaces.

#### Methods

- `attach(string|object $event, callable $callback, int $priority = 0): void` - Attach a listener to an event
- `detach(string|object $event, callable $callback): bool` - Remove a specific listener
- `clearListeners(string|object $event): void` - Clear all listeners for an event
- `trigger(string|EventInterface $event, array $params = []): object` - Trigger an event (legacy API)
- `dispatch(object $event): object` - Dispatch an event (PSR-14)
- `getListenersForEvent(object $event): iterable` - Get all listeners for an event (PSR-14)

### Event

Standard event implementation.

#### Methods

- `getName(): string` - Get the event name
- `getParam(string $name): mixed` - Get a parameter value
- `getParams(): array` - Get all parameters
- `hasParam(string $key): bool` - Check if a parameter exists
- `setName(string $name): void` - Set the event name
- `setParams(array $params): void` - Set all parameters
- `getTarget(): string|object|null` - Get the event target
- `setTarget(string|object|null $target): void` - Set the event target
- `isPropagationStopped(): bool` - Check if propagation is stopped
- `stopPropagation(bool $flag = true): void` - Stop event propagation

## PSR-14 Compliance

Soochak fully implements the PSR-14 Event Dispatcher standard:

- ✅ `Psr\EventDispatcher\EventDispatcherInterface`
- ✅ `Psr\EventDispatcher\ListenerProviderInterface`
- ✅ `Psr\EventDispatcher\StoppableEventInterface` (via EventInterface)

You can use Soochak with any PSR-14 compatible library or framework.

## Testing

The project includes a comprehensive test suite with 100% code coverage:

```bash
# Run tests
vendor/bin/phpunit

# Run with coverage
XDEBUG_MODE=coverage vendor/bin/phpunit --coverage-text

# Generate HTML coverage report
XDEBUG_MODE=coverage vendor/bin/phpunit --coverage-html coverage
```

## Performance

Soochak is designed for performance:

- **Lazy Evaluation** - Uses generators to avoid loading all listeners into memory
- **Direct Callbacks** - Uses direct function calls instead of `call_user_func()`
- **Efficient Queues** - Priority queues with FIFO ordering for same-priority listeners
- **Minimal Overhead** - Zero dependencies beyond PSR-14 interfaces

## Architecture

### Components

- **EventManager** - Main dispatcher implementing PSR-14 interfaces
- **ListenerProvider** - Manages listener registration and retrieval
- **Event** - Standard event implementation
- **EventListenerQueue** - Priority queue for listener ordering

### Design Patterns

- **Observer Pattern** - Event/listener architecture
- **Strategy Pattern** - Pluggable listener providers
- **Priority Queue** - Efficient listener ordering

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details.

## Author

**Vaibhav Pandey**

- Email: contact@vaibhavpandey.com
- Homepage: https://github.com/vaibhavpandeyvpz/soochak

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## Changelog

### Version 2.0.0 (Current)

- ✅ Upgraded to PHP 8.2+
- ✅ Full PSR-14 compliance
- ✅ Modern type hints and union types
- ✅ 100% test coverage
- ✅ Performance optimizations
- ✅ Comprehensive documentation

### Version 1.0.0 (Legacy)

- Initial release with PHP 5.3+ support
- Basic event management functionality

---

**Soochak** - Simple, powerful, and standards-compliant event management for PHP.
