<?php

declare(strict_types=1);

namespace App\Model;

/**
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
final class Group
{
    public function __construct(
        private readonly string $name,
        private readonly iterable $repositories
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Repository[]
     */
    public function getRepositories(): iterable
    {
        return $this->repositories;
    }
}
