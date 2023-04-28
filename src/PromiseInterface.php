<?php

declare(strict_types=1);

namespace Ghostwriter\Promise;

interface PromiseInterface
{
    public function catch(callable $onRejected): self;

    public function finally(callable $onFulfilledOrRejected): self;

    public function then(callable|null $onFulfilled = null, callable|null $onRejected = null): self;
}
