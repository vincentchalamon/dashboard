<?php

declare(strict_types=1);

namespace App\Repository;

use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

/**
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
final class CachedRepository implements RepositoryInterface
{
    private RepositoryInterface $decorated;
    private CacheInterface $cache;

    public function __construct(RepositoryInterface $decorated, TagAwareCacheInterface $repositoriesCache)
    {
        $this->decorated = $decorated;
        $this->cache = $repositoriesCache;
    }

    public function exists(string $name): bool
    {
        return $this->cache->get(sprintf('repository.%s.exists', str_replace('/', '-', $name)), function (ItemInterface $item) use ($name) {
            $item->tag(str_replace('/', '-', $name));

            return $this->decorated->exists($name);
        });
    }

    public function getDefaultBranch(string $name): string
    {
        return $this->cache->get(sprintf('repository.%s.default_branch', str_replace('/', '-', $name)), function (ItemInterface $item) use ($name) {
            $item->tag(str_replace('/', '-', $name));

            return $this->decorated->getDefaultBranch($name);
        });
    }

    public function getUrl(string $name): string
    {
        return $this->cache->get(sprintf('repository.%s.url', str_replace('/', '-', $name)), function (ItemInterface $item) use ($name) {
            $item->tag(str_replace('/', '-', $name));

            return $this->decorated->getUrl($name);
        });
    }

    public function getWorkflows(string $name): iterable
    {
        return $this->cache->get(sprintf('repository.%s.workflows', str_replace('/', '-', $name)), function (ItemInterface $item) use ($name) {
            $item->tag(str_replace('/', '-', $name));

            return $this->decorated->getWorkflows($name);
        });
    }
}
