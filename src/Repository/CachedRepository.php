<?php

declare(strict_types=1);

namespace App\Repository;

use App\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

/**
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
final class CachedRepository implements RepositoryInterface, CacheWarmerInterface
{
    private RepositoryInterface $decorated;
    private CacheInterface $cache;
    private LoaderInterface $loader;

    public function __construct(RepositoryInterface $decorated, TagAwareCacheInterface $cache, LoaderInterface $loader)
    {
        $this->decorated = $decorated;
        $this->cache = $cache;
        $this->loader = $loader;
    }

    public function exists(string $name): bool
    {
        return $this->getCacheItem($name, 'exists', function () use ($name) {
            return $this->decorated->exists($name);
        });
    }

    public function getDefaultBranch(string $name): string
    {
        return $this->getCacheItem($name, 'defaultBranch', function () use ($name) {
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
     * {@inheritdoc}
     */
    public function isOptional(): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function warmUp(string $cacheDir)
    {
        foreach ($this->loader->load() as $repository) {
            foreach (['exists', 'defaultBranch', 'url', 'workflows', 'stars'] as $method) {
                $this->getCacheItem($repository, $method, function () use ($method, $repository) {
                    if (!method_exists($this->decorated, $method)) {
                        $method = 'get'.ucfirst($method);
                    }

                    /* @phpstan-ignore-next-line */
                    return call_user_func([$this->decorated, $method], $repository);
                });
            }
        }

        return [];
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
