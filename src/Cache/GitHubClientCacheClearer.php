<?php

declare(strict_types=1);

namespace App\Cache;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpKernel\CacheClearer\CacheClearerInterface;
use Symfony\Component\HttpKernel\CacheClearer\Psr6CacheClearer;

/**
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
final class GitHubClientCacheClearer implements CacheClearerInterface
{
    public function __construct(
        #[Autowire('@cache.global_clearer')] private readonly Psr6CacheClearer $poolClearer
    ) {
    }

    public function clear(string $cacheDir): void
    {
        $this->poolClearer->clearPool('cache.github');
    }
}
