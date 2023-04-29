<?php

declare(strict_types=1);

namespace Ghostwriter\Promise\Tests\Unit;

use Ghostwriter\Promise\Deferred;
use Ghostwriter\Promise\DeferredInterface;
use Ghostwriter\Promise\Promise;
use Ghostwriter\Promise\PromiseInterface;
use Ghostwriter\Promise\State\Fulfilled;
use Ghostwriter\Promise\State\Rejected;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Throwable;

#[CoversClass(Deferred::class)]
#[UsesClass(Promise::class)]
#[UsesClass(Rejected::class)]
#[UsesClass(Fulfilled::class)]
final class DeferredTest extends TestCase
{
    private DeferredInterface $deferred;

    protected function setUp(): void
    {
        $this->deferred = new Deferred();
    }

    protected function tearDown(): void
    {
        $this->deferred = new Deferred();
    }

    public function testInstance(): void
    {
        self::assertInstanceOf(Deferred::class, $this->deferred);
        self::assertInstanceOf(DeferredInterface::class, $this->deferred);
    }

    public function testRejectPromise(): void
    {
        $this->deferred->getPromise()
            ->then(
                static function (mixed $value): void {
                    self::fail('Should not happen! got: ' . get_debug_type($value));
                },
                static function (Throwable $throwable): void {
                    self::assertSame('fail.', $throwable->getMessage());
                }
            );
        $this->deferred->reject(new RuntimeException('fail.'));
    }

    public function testResolvePromise(): void
    {
        $deferred = new Deferred();

        $deferred->getPromise()
            ->then(
                static function (mixed $value): void {
                    self::assertTrue($value);
                    self::assertFalse($value);
                    self::assertSame('wth?', $value);
                },
                static function (Throwable $throwable): void {
                    self::fail($throwable->getMessage());
                }
            );

        $deferred->resolve('pass');
    }

    public function testReturnDeferredPromise(): void
    {
        $promise = $this->deferred->getPromise();
        self::assertInstanceOf(Promise::class, $promise);
        self::assertInstanceOf(PromiseInterface::class, $promise);
    }
}
