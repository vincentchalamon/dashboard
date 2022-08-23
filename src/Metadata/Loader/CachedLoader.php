<?php

declare(strict_types=1);

namespace App\Metadata\Loader;

use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\Cache\CacheInterface;

/**
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
#[AsDecorator(RepositoriesLoader::class, priority: 13)]
final class CachedLoader implements LoaderInterface
{
    public function __construct(
        private readonly LoaderInterface $decorated,
        #[Autowire('@cache.repository')] private readonly CacheInterface $cache
    ) {
    }

    public function load(): array
    {
        return $this->cache->get('loader.repositories', function (): iterable {
            return $this->decorated->load();
        });
    }
}
