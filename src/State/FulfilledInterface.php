<?php

declare(strict_types=1);

namespace Ghostwriter\Promise\State;

/**
 * @template TValue
 *
 * @extends StateInterface<TValue>
 */
interface FulfilledInterface extends StateInterface
{
    /** @return TValue */
    public function getValue(): mixed;
}
