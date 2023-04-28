# Promise

[![Compliance](https://github.com/ghostwriter/promise/actions/workflows/compliance.yml/badge.svg)](https://github.com/ghostwriter/promise/actions/workflows/compliance.yml)
[![Supported PHP Version](https://badgen.net/packagist/php/ghostwriter/promise?color=8892bf)](https://www.php.net/supported-versions)
[![Code Coverage](https://codecov.io/gh/ghostwriter/promise/branch/main/graph/badge.svg)](https://codecov.io/gh/ghostwriter/promise)
[![Type Coverage](https://shepherd.dev/github/ghostwriter/promise/coverage.svg)](https://shepherd.dev/github/ghostwriter/promise)
[![Latest Version on Packagist](https://badgen.net/packagist/v/ghostwriter/promise)](https://packagist.org/packages/ghostwriter/promise)
[![Downloads](https://badgen.net/packagist/dt/ghostwriter/promise?color=blue)](https://packagist.org/packages/ghostwriter/promise)

Promise implementation for PHP

> **Warning**
>
> This project is not finished yet, work in progress.


## Installation

You can install the package via composer:

``` bash
composer require ghostwriter/promise
```

## Usage

```php
$deferred = new Ghostwriter\Promise\Deferred();

$promise = $deferred->promise();

/** @var mixed $value */
$value = '#BlackLivesMatter';

$deferred->resolve($value);

/** @var \Throwable $reason */
$reason = new BlackLivesMatterException();

$deferred->reject($reason);
```

## API

```php
interface PromiseInterface
{
    public function catch(callable $onRejected): self;

    public function finally(callable $onFulfilledOrRejected): self;

    public function then(callable|null $onFulfilled = null, callable|null $onRejected = null): self;
}
```

```php
interface DeferredInterface
{
    public function promise(): PromiseInterface;

    public function reject(Throwable $reason): void;

    public function resolve(mixed $value): void;
}
```

```php
interface RejectedInterface extends PromiseInterface
{
    public function getReason(): Throwable;
}
```

```php
interface FulfilledInterface extends PromiseInterface
{
    public function getValue(): mixed;
}
```

## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG.md](./CHANGELOG.md) for more information what has changed recently.

## Security

If you discover any security related issues, please email `nathanael.esayeas@protonmail.com` instead of using the issue tracker.

## Support

[[`Become a GitHub Sponsor`](https://github.com/sponsors/ghostwriter)]

## Credits

- [Nathanael Esayeas](https://github.com/ghostwriter)
- [All Contributors](https://github.com/ghostwriter/promise/contributors)

## License

The BSD-3-Clause. Please see [License File](./LICENSE) for more information.
