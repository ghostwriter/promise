<?php

declare(strict_types=1);

namespace Ghostwriter\Promise\State;

use Throwable;

final class Rejected implements RejectedInterface
{
    public function __construct(
        private readonly Throwable $throwable
    ) {
    }

    public function getReason(): Throwable
    {
        return $this->throwable;
    }

    public function getValue(): mixed
    {
        return $this->throwable;
    }
}
