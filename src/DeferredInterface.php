<?php

declare(strict_types=1);

namespace Ghostwriter\Promise;

use Throwable;

interface DeferredInterface
{
    /**
     * Returns a Promise for the completion of the deferred task.
     *
     * @template TPromise
     *
     * @return PromiseInterface<Throwable|TPromise>
     */
    public function getPromise(): PromiseInterface;

    /**
     * Rejects the promise with an error.
     */
    public function reject(Throwable $throwable): void;

    /**
     * Fulfills the promise with a value.
     *
     * @template TValue
     *
     * @param TValue $value
     */
    public function resolve(mixed $value): void;
}
