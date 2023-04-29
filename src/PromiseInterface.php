<?php

declare(strict_types=1);

namespace Ghostwriter\Promise;

use Closure;
use RuntimeException;
use Throwable;

/**
 * @template TValue
 */
interface PromiseInterface
{
    /**
     * @template TCatch
     *
     * Appends a rejection handler callback to the promise, and returns a new promise resolving to the return value
     * of the callback if it is called, or to its original settled value if the promise was not rejected (i.e. if the
     * rejection handler is not a function).
     *
     * @param Closure(Throwable):TCatch $onRejected called when the promise is rejected
     *
     * @return self<TCatch|Throwable>
     */
    public function catch(Closure $onRejected): self;

    /**
     * @template TFinally
     *
     * Appends a fulfillment handler callback to the promise, and returns a new promise resolving to the return value
     * of the callback if it is called, or to its original settled value if the promise was not fulfilled (i.e. if the
     * fulfillment handler is not a function).
     *
     * Appends a rejection handler callback to the promise, and returns a new promise resolving to the return value
     * of the callback if it is called, or to its original settled value if the promise was not rejected (i.e. if the
     * rejection handler is not a function).
     *
     * @param Closure(Throwable|TValue):void $onFulfilledOrRejected called when the promise is fulfilled or rejected
     *
     * @return self<TValue>
     */
    public function finally(Closure $onFulfilledOrRejected): self;

    /**
     * Returns the reason that the promise was rejected with.
     *
     * @throws RuntimeException if the promise has not yet been rejected
     */
    public function getReason(): Throwable;

    /**
     * Returns the value that the promise was fulfilled with.
     *
     * @throws RuntimeException if the promise has not yet been fulfilled
     *
     * @return TValue
     */
    public function getResult(): mixed;

    /**
     * Returns whether the promise has been fulfilled.
     */
    public function isFulfilled(): bool;

    /**
     * Returns whether the promise is pending.
     */
    public function isPending(): bool;

    /**
     * Returns whether the promise has been rejected.
     */
    public function isRejected(): bool;

    /**
     * @template TMap
     *
     * Appends a fulfillment handler callback to the promise, and returns a new promise resolving to the return value
     * of the callback if it is called, or to its original settled value if the promise was not fulfilled (i.e. if the
     * fulfillment handler is not a function).
     *
     * @param Closure(TValue):TMap $onFulfilled called when the promise is fulfilled
     *
     * @return self<TMap|TValue>
     */
    public function map(Closure $onFulfilled): self;

    public function reject(Throwable $reason): void;

    /**
     * @template TResolve
     *
     * @param TResolve $value
     */
    public function resolve(mixed $value): void;

    /**
     * Appends fulfillment and rejection handlers to the promise,
     * and returns a new promise resolving to the return value of the called handler,
     * or to its original settled value if the promise was not handled
     * (i.e. if the relevant handler onFulfilled or onRejected is not a function).
     *
     * @template TResult
     *
     * @param Closure(TValue):TResult    $onFulfilled called when the promise is fulfilled
     * @param Closure(Throwable):TResult $onRejected  called when the promise is rejected
     *
     * @return self<TResult>
     */
    public function then(Closure $onFulfilled, Closure $onRejected): self;

    /**
     * Wait for the promise to be settled and return the result.
     *
     * @throws Throwable if the promise was rejected with a reason
     *
     * @return TValue the result of the promise
     */
    public function wait(): mixed;
}
