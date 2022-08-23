<?php

declare(strict_types=1);

namespace App\Metadata\Loader;

use Symfony\Component\DependencyInjection\Attribute\AsDecorator;

/**
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
#[AsDecorator(RepositoriesLoader::class, priority: 34)]
final class GroupLoader implements LoaderInterface
{
    public function __construct(private readonly LoaderInterface $decorated)
    {
    }

    public function load(): array
    {
        $repositories = $this->decorated->load();

        // Detect if groups aren't used in repositories declaration
        if (array_keys($repositories) === range(0, count($repositories) - 1)) {
            return ['Default' => $repositories];
        }

        return $repositories;
    }
}
