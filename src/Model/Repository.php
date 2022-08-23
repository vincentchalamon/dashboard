<?php

declare(strict_types=1);

namespace App\Model;

/**
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
final class Repository
{
    public function __construct(
        private readonly string $group,
        private readonly string $name,
        private readonly string $uri,
        private readonly string $branch,
        private readonly iterable $workflows,
        private readonly int $stars
    ) {
    }

    public function getGroup(): string
    {
        return $this->group;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getBranch(): string
    {
        return $this->branch;
    }

    /**
     * @return Workflow[]
     */
    public function getWorkflows(): iterable
    {
        return $this->workflows;
    }

    public function getStars(): int
    {
        return $this->stars;
    }
}
