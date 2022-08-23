<?php

declare(strict_types=1);

namespace App\Metadata;

/**
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
final class RepositoryMetadata implements RepositoryMetadataInterface
{
    public function __construct(
        private readonly string $group,
        private readonly string $name,
        private readonly string $uri,
        private ?string $branch = null,
        private array $workflows = []
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

    public function setBranch(string $branch): self
    {
        $this->branch = $branch;

        return $this;
    }

    public function getBranch(): ?string
    {
        return $this->branch;
    }

    public function setWorkflows(array $workflows): self
    {
        $this->workflows = $workflows;

        return $this;
    }

    public function getWorkflows(): array
    {
        return $this->workflows;
    }
}
