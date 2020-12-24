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
        return $this->getCacheItem($name, 'exists', function () use ($name) {
            return $this->decorated->exists($name);
        });
    }

    public function getDefaultBranch(string $name): string
    {
        return $this->getCacheItem($name, 'default_branch', function () use ($name) {
            return $this->decorated->getDefaultBranch($name);
        });
    }

    public function getUrl(string $name): string
    {
        return $this->getCacheItem($name, 'url', function () use ($name) {
            return $this->decorated->getUrl($name);
        });
    }

    public function getWorkflows(string $name): iterable
    {
        return $this->getCacheItem($name, 'workflows', function () use ($name) {
            return $this->decorated->getWorkflows($name);
        });
    }

    public function getStars(string $name): int
    {
        return $this->getCacheItem($name, 'stars', function () use ($name) {
            return $this->decorated->getStars($name);
        });
    }

    /**
     * @return mixed
     */
    private function getCacheItem(string $name, string $suffix, callable $callback)
    {
        return $this->cache->get(
            sprintf('repository.%s.%s', str_replace('/', '-', $name), $suffix),
            function (ItemInterface $item) use ($name, $callback) {
                $item->tag(str_replace('/', '-', $name));

                return $callback();
            }
        );
    }
}
