<?php

declare(strict_types=1);

namespace App\Loader;

use Symfony\Component\Yaml\Yaml;

/**
 * @author Vincent Chalamon <vincent.chalamon@ext.arte.tv>
 */
final class RepositoryLoader implements LoaderInterface
{
    public function __construct(private readonly string $projectDir, private readonly array $repositories = [])
    {
    }

    /**
     * @SuppressWarnings("PHPMD.StaticAccess")
     */
    public function load(): array
    {
        $repositories = $this->repositories;

        // If "repositories.yaml" file exists and is valid, it overrides the environment variable
        $filename = sprintf('%s/repositories.yaml', $this->projectDir);
        if (file_exists($filename)) {
            $repositories = Yaml::parseFile($filename)['repositories'] ?? $repositories;
        }

        // Detect if groups aren't used in repositories declaration
        if (array_keys($repositories) === range(0, count($repositories) - 1)) {
            $repositories = ['Default' => $repositories];
        }

        return array_map(
            fn ($repositories): array => array_map(
                fn (string $repository): string => preg_replace('#^https:\/\/[^\/]+\/(.*)(?:\.git|\/)?$#', '$1', $repository),
                $repositories
            ),
            $repositories
        );
    }
}
