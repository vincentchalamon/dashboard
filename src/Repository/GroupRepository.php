<?php

declare(strict_types=1);

namespace App\Repository;

use App\Metadata\Loader\LoaderInterface;
use App\Model\Group;
use Iterator;

/**
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
final class GroupRepository
{
    public function __construct(private readonly LoaderInterface $loader, private readonly RepositoryRepository $repositoryRepository)
    {
    }

    public function findAll(): Iterator
    {
        foreach (array_keys($this->loader->load()) as $group) {
            yield new Group($group, $this->repositoryRepository->findBy(['group' => $group]));
        }

        yield from [];
    }
}
