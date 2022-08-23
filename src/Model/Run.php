<?php

declare(strict_types=1);

namespace App\Model;

use DateTimeInterface;

/**
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
final class Run
{
    public function __construct(
        private readonly string $uri,
        private readonly string $state,
        private readonly DateTimeInterface $updatedAt
    ) {
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }
}
