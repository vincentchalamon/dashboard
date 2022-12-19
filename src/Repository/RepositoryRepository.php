<?php

declare(strict_types=1);

namespace App\Repository;

use App\Metadata\Loader\LoaderInterface;
use App\Metadata\RepositoryMetadataInterface;
use App\Model\Repository;
use App\Server\ServerInterface;
use Iterator;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
final class RepositoryRepository
{
    public function __construct(
        private readonly LoaderInterface $loader,
        private readonly ServerInterface $server,
        private readonly PropertyAccessorInterface $propertyAccessor,
        private readonly WorkflowRepository $workflowRepository
    ) {
    }

    public function find(string $name): ?object
    {
        foreach ($this->findBy(['name' => $name]) as $repository) {
            return $repository;
        }

        return null;
    }

    public function findAll(): Iterator
    {
        yield from $this->findBy([]);
    }

    public function findBy(array $criteria): Iterator
    {
        foreach ($this->loader->load() as $repositories) {
            foreach ($repositories as $repository) {
                foreach ($criteria as $criterion => $value) {
                    if ($value !== $this->propertyAccessor->getValue($repository, $criterion)) {
                        continue 2;
                    }
                }

                yield $this->buildRepository($repository);
            }
        }

        yield from [];
    }

    private function buildRepository(RepositoryMetadataInterface $repository): Repository
    {
        return new Repository(
            $repository->getGroup(),
            $repository->getName(),
            $repository->getUri(),
            $repository->getBranch() ?: $this->server->getDefaultBranch($repository),
            $this->workflowRepository->findAll($repository),
            $this->server->getStars($repository)
        );
    }
}
