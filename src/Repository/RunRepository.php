<?php

declare(strict_types=1);

namespace App\Repository;

use App\Metadata\RepositoryMetadataInterface;
use App\Model\Run;
use App\Server\ServerInterface;
use DateTimeImmutable;

/**
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
final class RunRepository
{
    public function __construct(private readonly ServerInterface $server)
    {
    }

    public function findAll(RepositoryMetadataInterface $repository, string $workflowId): iterable
    {
        foreach ($this->server->getRuns($repository, $workflowId) as $run) {
            list($uri, $state, $updatedAt) = $run;

            yield new Run($uri, $state, new DateTimeImmutable($updatedAt));
        }
    }
}
