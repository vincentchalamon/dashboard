<?php

declare(strict_types=1);

namespace App\Metadata\Loader;

use App\Metadata\RepositoryMetadataInterface;

/**
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
interface LoaderInterface
{
    /**
     * @return array<string, array<RepositoryMetadataInterface>>
     */
    public function load(): array;
}
