# vaibhavpandeyvpz/soochak
A simple but useful events manager based on [PSR-14](https://github.com/php-fig/fig-standards/blob/master/proposed/event-manager.md) draft for [PHP](http://php.net/) >= 5.3.

> Soochak: `सूचक` (Notifier)

[![Build status][build-status-image]][build-status-url]
[![Code Coverage][code-coverage-image]][code-coverage-url]
[![Latest Version][latest-version-image]][latest-version-url]
[![Downloads][downloads-image]][downloads-url]
[![PHP Version][php-version-image]][php-version-url]
[![License][license-image]][license-url]

[![SensioLabsInsight][insights-image]][insights-url]

Install
---
```bash
composer require vaibhavpandeyvpz/soochak
```

Usage
---
```php
<?php

$em = new Soochak\EventManager();

// Attach a callback to 'login.success' event
$em->attach('login.success', function ($event) {
    /**
     * Perform your event logic like sending notification email
     * You can optionally stop an event from further propagation.
     */
    $event->stopPropagation(true);
});

// Anywhere in your app, trigger 'login.success' event
$em->trigger('login.success');
```

License
---
See [LICENSE.md][license-url] file.

[build-status-image]: https://img.shields.io/travis/vaibhavpandeyvpz/soochak.svg?style=flat-square
[build-status-url]: https://travis-ci.org/vaibhavpandeyvpz/soochak
[code-coverage-image]: https://img.shields.io/codecov/c/github/vaibhavpandeyvpz/soochak.svg?style=flat-square
[code-coverage-url]: https://codecov.io/gh/vaibhavpandeyvpz/soochak
[latest-version-image]: https://img.shields.io/github/release/vaibhavpandeyvpz/soochak.svg?style=flat-square
[latest-version-url]: https://github.com/vaibhavpandeyvpz/soochak/releases
[downloads-image]: https://img.shields.io/packagist/dt/vaibhavpandeyvpz/soochak.svg?style=flat-square
[downloads-url]: https://packagist.org/packages/vaibhavpandeyvpz/soochak
[php-version-image]: http://img.shields.io/badge/php-5.3+-8892be.svg?style=flat-square
[php-version-url]: https://packagist.org/packages/vaibhavpandeyvpz/soochak
[license-image]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[license-url]: LICENSE.md
[insights-image]: https://insight.sensiolabs.com/projects/1951857b-a41d-4513-89df-96c161572799/small.png
[insights-url]: https://insight.sensiolabs.com/projects/1951857b-a41d-4513-89df-96c161572799
