<?php

declare(strict_types=1);

namespace Ghostwriter\Promise;

use Closure;
use Ghostwriter\Promise\State\Fulfilled;
use Ghostwriter\Promise\State\FulfilledInterface;
use Ghostwriter\Promise\State\Pending;
use Ghostwriter\Promise\State\PendingInterface;
use Ghostwriter\Promise\State\Rejected;
use Ghostwriter\Promise\State\RejectedInterface;
use Ghostwriter\Promise\State\StateInterface;
use RuntimeException;
use Throwable;

/**
 * @template TPromise
 *
 * @implements PromiseInterface<TPromise>
 *
 * @see PromiseTest
 */
final class Promise implements PromiseInterface
{
    /** @var Closure(Throwable):never */
    private Closure $defaultReject;

    /** @var Closure(TPromise):TPromise */
    private Closure $defaultResolve;

    /**
     * @var array<array-key,Closure(TPromise):TPromise>
     */
    private array $finallyCallbacks = [];

    /**
     * @var array<array-key,Closure(TPromise):TPromise>
     */
    private array $fulfilledCallbacks = [];

    /**
     * @var array<array-key,Closure(Throwable):Throwable>
     */
    private array $rejectedCallbacks = [];

    private StateInterface $state;

    /**
     *
     * @template TPromise
     * @template TPromiseResult
     * @template TSuccessResult
     *
     * @var Closure(TPromise):null|TSuccessResult
     * @var Closure(Throwable):null|never
     *
     * @param null|Closure(Closure(TPromise):TPromiseResult,Closure(Throwable):never):void $resolver
     * @param null|Closure                                                                 $canceller
     */
    public function __construct(
        private Closure|null $resolver = null,
        private Closure|null $canceller = null
    ) {
        $this->transitionTo(new Pending());

        /** @var Closure(TPromise):TPromise $this->defaultResolve */
        $this->defaultResolve = static fn (
            /**
             * @param TPromise $reason
             *
             * @return TPromise
             */
            mixed $value
        ): mixed => $value;

        $this->defaultReject = static fn (Throwable $reason): never => throw $reason;

        //
        //        $onFulfilled ??= static fn (mixed $value): mixed => $value;
        //
        //        $onRejected ??= static fn (Throwable $throwable): never => throw $throwable;

        //        $executor ??= static fn (Closure $_, Closure $__): mixed => null;
        //
        //        try {
        //            $executor($this->defaultResolve, $this->defaultReject);
        //        } catch (Throwable $throwable) {
        //            $this->reject($throwable);
        //        }
    }

    public static function all(iterable $promises): PromiseInterface
    {
        return new self();
    }

    public static function any(iterable $promises): PromiseInterface
    {
        return new self();
    }

    public function catch(Closure $onRejected): self
    {
        return $this->then($this->defaultResolve, $onRejected);
    }

    public function finally(Closure $onFulfilledOrRejected): self
    {
        $this->finallyCallbacks[] = $onFulfilledOrRejected;

        return $this->then($onFulfilledOrRejected, $onFulfilledOrRejected);
    }

    public function getReason(): Throwable
    {
        return $this->result;
    }

    public function getResult(): mixed
    {
        return $this->result;
    }

    public function getState(): StateInterface
    {
        return $this->state;
    }

    public function isFulfilled(): bool
    {
        return $this->state instanceof FulfilledInterface;
    }

    public function isPending(): bool
    {
        return $this->state instanceof PendingInterface;
    }

    public function isRejected(): bool
    {
        return $this->state instanceof RejectedInterface;
    }

    public function map(Closure $onFulfilled): self
    {
        return $this->then($onFulfilled, $this->defaultReject);
    }

    public static function race(iterable $promises): PromiseInterface
    {
        return new self();
    }

    public function reject(Throwable $throwable): void
    {
        $state = &$this->state;
        if (! $state instanceof PendingInterface) {
            return;
        }

        $state = new Rejected($throwable);

        array_map([$this, 'executeCallback'], $this->rejectedCallbacks);
        //        array_walk($this->rejectedCallbacks, [$this, 'executeCallback']);
        //        foreach ($this->rejectedCallbacks as $callback) {
        //            $this->executeCallback($callback);
        //        }
    }

    public function resolve(mixed $value): void
    {
        $state = &$this->state;
        if (! $state instanceof PendingInterface) {
            return;
        }
        $state = new Fulfilled($value);

        array_map([$this, 'executeCallback'], $this->fulfilledCallbacks);
        //        foreach ($this->fulfilledCallbacks as $callback) {
        //            $this->executeCallback($callback);
        //        }
    }

    /**
     * @template TResult
     *
     * @param Closure(TPromise):null|TResult  $onFulfilled
     * @param Closure(Throwable):null|TResult $onRejected
     *
     * @param-out self<TPromise|TResult|Throwable>
     *
     * @return self<Throwable|TPromise|TResult>
     */
    public function then(Closure $onFulfilled, Closure $onRejected): self
    {
        switch (true) {
            case $this->state instanceof FulfilledInterface:
                $this->executeCallback($onFulfilled);
                break;
            case $this->state instanceof RejectedInterface:
                $this->executeCallback($onRejected);
                break;
            case $this->state instanceof PendingInterface:
                $this->fulfilledCallbacks[] = $onFulfilled;
                $this->rejectedCallbacks[] = $onRejected;
                break;
            default:
                throw new RuntimeException('Shold not happen!');
        }

        return $this;
    }

    public function wait(): mixed
    {
        $state = $this->state;

        return match (true) {
            $state instanceof FulfilledInterface => $state->getValue(),
            $state instanceof RejectedInterface => $state->getReason(),
            default => null
        };
    }

    /**
     * @template TResult of Throwable|TPromise
     *
     * @param null|Closure(Throwable|TPromise):TResult $callback
     */
    private function executeCallback(Closure $callback): void
    {
        /** @var FulfilledInterface|RejectedInterface $state */
        $state = $this->state;
        if ($state instanceof PendingInterface) {
            throw new RuntimeException('Should not happen.');
        }

        try {
            $result = $callback(match (true) {
                $state instanceof FulfilledInterface => $state->getValue(),
                $state instanceof RejectedInterface => $state->getReason(),
            });

            if ($result instanceof PromiseInterface) {
                $result->then([$this, 'resolve'], [$this, 'reject']);
                return;
            }

            $this->resolve($result);
        } catch (Throwable $throwable) {
            $this->reject($throwable);
        }
    }

    private function transitionTo(StateInterface $state): void
    {
        $this->state = $state;
    }
}
