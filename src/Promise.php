<?php

declare(strict_types=1);

namespace Ghostwriter\Promise;

use Throwable;

/** @see PromiseTest */
final class Promise implements PromiseInterface
{
    public const FULFILLED = 'fulfilled';

    public const PENDING = 'pending';

    public const REJECTED = 'rejected';

    private array $fulfilledCallbacks = [];

    private array $rejectedCallbacks = [];

    private mixed $result = null;

    private string $state = self::PENDING;

    public function catch(callable $onRejected): PromiseInterface
    {
        return $this->then(null, $onRejected);
    }

    public function finally(callable $onFulfilledOrRejected): PromiseInterface
    {
        return $this->then($onFulfilledOrRejected, $onFulfilledOrRejected);
    }

    public function reject($reason)
    {
        if ($this->state === self::PENDING) {
            $this->state = self::REJECTED;
            $this->result = $reason;
            foreach ($this->rejectedCallbacks as [$callback, $promise]) {
                $this->executeCallback($callback, $promise, $this->result);
            }
        }
    }

    public function resolve($value)
    {
        if ($this->state === self::PENDING) {
            $this->state = self::FULFILLED;
            $this->result = $value;
            foreach ($this->fulfilledCallbacks as [$callback, $promise]) {
                $this->executeCallback($callback, $promise, $this->result);
            }
        }
    }

    public function then(callable $onFulfilled = null, callable $onRejected = null): PromiseInterface
    {
        switch ($this->state) {
            case self::FULFILLED:
                $this->executeCallback($onFulfilled, $this, $this->result);
                break;
            case self::REJECTED:
                $this->executeCallback($onRejected, $this, $this->result);
                break;
            case self::PENDING:
                $this->fulfilledCallbacks[] = [$onFulfilled, $this];
                $this->rejectedCallbacks[] = [$onRejected, $this];
                break;
        }

        return $this;
    }

    private function executeCallback(?callable $callback, self $promise, $value)
    {
        if (! is_callable($callback)) {
            if ($this->state === self::FULFILLED) {
                $promise->resolve($value);
            } else {
                $promise->reject($value);
            }
            return;
        }
        try {
            $result = $callback($value);
            if ($result instanceof PromiseInterface) {
                $result->then([$promise, 'resolve'], [$promise, 'reject']);
            } else {
                $promise->resolve($result);
            }
        } catch (Throwable $e) {
            $promise->reject($e);
        }
    }
}
