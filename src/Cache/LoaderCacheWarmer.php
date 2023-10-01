<?php

declare(strict_types=1);

namespace App\Cache;

use App\Metadata\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;

/**
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
final class LoaderCacheWarmer implements CacheWarmerInterface
{
    public function __construct(private readonly LoaderInterface $loader)
    {
    }

    public function warmUp(string $cacheDir): array
    {
        $this->loader->load();

        return [];
    }

    public function isOptional(): bool
    {
        return true;
    }
}
