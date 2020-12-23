<?php

declare(strict_types=1);

namespace App\Repository;

/**
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
interface RepositoryInterface
{
    public function isValid(string $name): bool;

    public function getDefaultBranch(string $name): string;

    public function getUrl(string $name): string;

    public function getWorkflows(string $name): iterable;
}
