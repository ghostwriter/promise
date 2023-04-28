<?php

declare(strict_types=1);

namespace Ghostwriter\Promise;

interface FulfilledInterface extends PromiseInterface
{
    public function getValue(): mixed;
}
