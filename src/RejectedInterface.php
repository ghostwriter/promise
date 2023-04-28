<?php

declare(strict_types=1);

namespace Ghostwriter\Promise;

use Throwable;

interface RejectedInterface extends PromiseInterface
{
    public function getReason(): Throwable;
}
