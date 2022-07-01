<?php

declare(strict_types=1);

namespace App\Loader;

use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

/**
 * @author Vincent Chalamon <vincent.chalamon@ext.arte.tv>
 */
final class RepositoryLoader implements LoaderInterface
{
    private ?array $repositories = null;

    public function __construct(private readonly string $projectDir)
    {
    }

    /**
     * @SuppressWarnings("PHPMD.StaticAccess")
     */
    public function load(): array
    {
        if (null === $this->repositories) {
            try {
                $repositories = Yaml::parseFile(sprintf('%s/repositories.yaml', $this->projectDir))['repositories'] ?? [];

                // Detect if groups aren't used in repositories declaration
                if (array_keys($repositories) === range(0, count($repositories) - 1)) {
                    $repositories = ['Default' => $repositories];
                }

                $this->repositories = array_map(
                    fn ($repositories): array => array_map(
                        fn (string $repository): string => preg_replace('#^https:\/\/[^\/]+\/(.*)(?:\.git|\/)?$#', '$1', $repository),
                        $repositories
                    ),
                    $repositories
                );
            } catch (ParseException) {
                $this->repositories = [];
            }
        }

        return $this->repositories;
    }
}
