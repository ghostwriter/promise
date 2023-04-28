<?php

declare(strict_types=1);

namespace Ghostwriter\Promise;

use Throwable;

final class Deferred implements DeferredInterface
{
    public function __construct(
        private readonly PromiseInterface $promise = new Promise()
    ) {
    }

    public function promise(): PromiseInterface
    {
        return $this->promise;
    }

    public function reject(Throwable $reason): void
    {
        $this->promise->reject($reason);
    }

    public function resolve(mixed $value): void
    {
        $this->promise->reject($value);
    }
}
