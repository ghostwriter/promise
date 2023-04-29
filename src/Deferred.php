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

    public function getPromise(): PromiseInterface
    {
        /**
         * @template TValue
         *
         * @param TValue $value
         *
         * @return TValue
         */
        return $this->promise;
        return $this->promise->then(
            fn (mixed $value): mixed => $this->resolve($value),
            fn (Throwable $reason): mixed => $this->reject($reason)
        );
    }

    public function reject(Throwable $throwable): void
    {
        $this->promise->reject($throwable);
    }

    public function resolve(mixed $value): void
    {
        $this->promise->resolve($value);
    }
}
