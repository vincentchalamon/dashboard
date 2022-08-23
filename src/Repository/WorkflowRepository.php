<?php

declare(strict_types=1);

namespace App\Repository;

use App\Metadata\RepositoryMetadataInterface;
use App\Model\Workflow;
use App\Server\ServerInterface;

/**
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
final class WorkflowRepository
{
    public function __construct(private readonly ServerInterface $server, private readonly RunRepository $runRepository)
    {
    }

    public function findAll(RepositoryMetadataInterface $repository): iterable
    {
        foreach ($this->server->getWorkflows($repository) as $workflow) {
            list($id, $name) = $workflow;

            yield new Workflow($id, $name, $this->runRepository->findAll($repository, $id));
        }
    }
}
