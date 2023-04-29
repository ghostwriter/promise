<?php

declare(strict_types=1);

namespace Ghostwriter\Promise\State;

/**
 * @template TFulfilled
 *
 * @implements FulfilledInterface<TFulfilled>
 */
final class Fulfilled implements FulfilledInterface
{
    /**
     * @template TValue
     *
     * @param TValue $value
     */
    public function __construct(
        private readonly mixed $value
    ) {
    }

    /** @return TFulfilled */
    public function getValue(): mixed
    {
        return $this->value;
    }
}
