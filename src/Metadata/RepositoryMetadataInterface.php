<?php

declare(strict_types=1);

namespace App\Metadata;

/**
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
interface RepositoryMetadataInterface
{
    public function getGroup(): string;

    public function getName(): string;

    public function getUri(): string;

    public function getBranch(): ?string;

    public function getWorkflows(): array;
}
