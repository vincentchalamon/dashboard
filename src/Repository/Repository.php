<?php

declare(strict_types=1);

namespace App\Repository;

/**
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 *
 * @method string   getDefaultBranch()
 * @method string   getUrl()
 * @method iterable getWorkflows()
 * @method int      getStars()
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

    /**
     * @return false|mixed
     */
    public function __call(string $method, array $arguments)
    {
        if (!method_exists($this->repository, $method)) {
            $method = 'get'.ucfirst($method);
        }

        /* @phpstan-ignore-next-line */
        return call_user_func([$this->repository, $method], $this->getName());
    }
}
