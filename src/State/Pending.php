<?php

declare(strict_types=1);

namespace Ghostwriter\Promise\State;

final class Pending implements PendingInterface
{
    public function getValue(): mixed
    {
        return null;
    }
}
