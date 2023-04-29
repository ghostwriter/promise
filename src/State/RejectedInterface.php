<?php

declare(strict_types=1);

namespace Ghostwriter\Promise\State;

use Throwable;

/**
 * @extends StateInterface<Throwable>
 */
interface RejectedInterface extends StateInterface
{
    public function getReason(): Throwable;
}
