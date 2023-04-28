<?php

declare(strict_types=1);

namespace Ghostwriter\Promise\Tests\Unit;

use Ghostwriter\Promise\Promise;
use Ghostwriter\Promise\PromiseInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Promise::class)]
final class PromiseTest extends TestCase
{
    public function testReturnsPromiseInstance(): void
    {
        $promise = new Promise();
        self::assertInstanceOf(Promise::class, $promise);
        self::assertInstanceOf(PromiseInterface::class, $promise);
    }
}
