<?php

declare(strict_types=1);

namespace App\Repository;

/**
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
final class Repository
{
    private RepositoryInterface $repository;
    private string $name;

    public function __construct(RepositoryInterface $repository, string $name)
    {
        $this->repository = $repository;
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUrl(): string
    {
        return $this->repository->getUrl($this->getName());
    }

    public function getWorkflows(): iterable
    {
        return $this->repository->getWorkflows($this->getName());
    }
}
