<?php

declare(strict_types=1);

namespace Ghostwriter\Promise\Tests\Unit;

use Ghostwriter\Promise\Deferred;
use Ghostwriter\Promise\Promise;
use Ghostwriter\Promise\PromiseInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Deferred::class)]
#[UsesClass(Promise::class)]
final class DeferredTest extends TestCase
{
    public function testReturnsDeferredInstance()
    {
        self::assertInstanceOf(Deferred::class, new Deferred());
    }

    public function testReturnsPromise()
    {
        $deferred = new Deferred();
        $promise = $deferred->promise();
        self::assertInstanceOf(Promise::class, $promise);
        self::assertInstanceOf(PromiseInterface::class, $promise);
    }
}
