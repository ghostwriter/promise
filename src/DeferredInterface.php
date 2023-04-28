<?php

declare(strict_types=1);

namespace Ghostwriter\Promise;

use Throwable;

interface DeferredInterface
{
    public function promise(): PromiseInterface;

    public function reject(Throwable $reason): void;

    public function resolve(mixed $value): void;
}
