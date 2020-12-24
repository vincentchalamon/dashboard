<?php

declare(strict_types=1);

namespace App\Loader;

/**
 * @author Vincent Chalamon <vincent.chalamon@ext.arte.tv>
 */
interface LoaderInterface
{
    public function load(): array;
}
