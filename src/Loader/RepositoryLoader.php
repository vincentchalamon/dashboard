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
                $this->repositories = array_map(function (string $repository): ?string { /* @phpstan-ignore-line */
                    return preg_replace('#^https:\/\/[^\/]+\/(.*)(?:\.git|\/)?$#', '$1', $repository);
                }, Yaml::parseFile(sprintf('%s/repositories.yaml', $this->projectDir))['repositories'] ?? []); /* @phpstan-ignore-line */
            } catch (ParseException) {
                $this->repositories = [];
            }
        }

        return $this->repositories;
    }
}
