<?php

declare(strict_types=1);

namespace App\Repository;

/**
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 *
 * @method string getDefaultBranch()
 * @method string getUrl()
 * @method iterable getWorkflows()
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

    public function __call(string $method, array $arguments)
    {
        if (!method_exists($this->repository, $method)) {
            $method = 'get'.ucfirst($method);
        }

        return call_user_func([$this->repository, $method], $this->getName());
    }
}
