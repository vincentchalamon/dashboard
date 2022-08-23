<?php

declare(strict_types=1);

namespace App\Metadata\Loader;

use Symfony\Component\DependencyInjection\Attribute\AsDecorator;

/**
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
#[AsDecorator(RepositoriesLoader::class, priority: 21)]
final class DefaultsLoader implements LoaderInterface
{
    /**
     * @var string
     */
    private const PATTERN = '#^https:\/\/[^\/]+\/(.*)(?:\.git|\/)?$#';

    public function __construct(private readonly LoaderInterface $decorated)
    {
    }

    public function load(): array
    {
        $repositories = $this->decorated->load();

        foreach ($repositories as $group => $repos) {
            $data = [];
            foreach ($repos as $uri => $config) {
                /*
                 * Converts this format:
                 * <code>
                 * repositories:
                 *     group:
                 *         - api-platform/core
                 * </code>
                 * to this format
                 * <code>
                 * repositories:
                 *     group:
                 *         - uri: https://github.com/api-platform/core
                 *           name: api-platform/core
                 * </code>
                 */
                if (is_string($config)) {
                    $uri = $config;
                    $config = [];
                }

                if (!preg_match(self::PATTERN, $uri)) {
                    $uri = sprintf('https://github.com/%s', $uri);
                }

                $data[] = [
                    'group' => $group,
                    'uri' => $uri,
                    'name' => preg_replace(self::PATTERN, '$1', $uri),
                ] + ($config ?: []);
            }

            $repositories[$group] = $data;
        }

        return $repositories;
    }
}
