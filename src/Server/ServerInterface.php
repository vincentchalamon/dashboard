<?php

declare(strict_types=1);

namespace App\Server;

use App\Metadata\RepositoryMetadataInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
#[AutoconfigureTag]
interface ServerInterface
{
    public function getDefaultBranch(RepositoryMetadataInterface $repository): string;

    public function getRuns(RepositoryMetadataInterface $repository, string $workflowId): iterable;

    public function getWorkflows(RepositoryMetadataInterface $repository): iterable;

    public function getStars(RepositoryMetadataInterface $repository): int;

    public function supports(string $uri): bool;
}
