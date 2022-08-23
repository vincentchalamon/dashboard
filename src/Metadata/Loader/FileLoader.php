<?php

declare(strict_types=1);

namespace App\Metadata\Loader;

use App\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Yaml\Yaml;

/**
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
#[AsDecorator(RepositoriesLoader::class, priority: 55)]
final class FileLoader implements LoaderInterface
{
    public function __construct(
        private readonly LoaderInterface $decorated,
        #[Autowire('%kernel.project_dir%')] private readonly string $projectDir
    ) {
    }

    /**
     * @SuppressWarnings("PHPMD.StaticAccess")
     */
    public function load(): array
    {
        // If "repositories.yaml" file exists and is valid, it overrides the environment variable
        $filename = sprintf('%s/repositories.yaml', $this->projectDir);
        if (file_exists($filename)) {
            $yaml = Yaml::parseFile($filename);
            if (!isset($yaml['repositories'])) {
                throw new InvalidConfigurationException('File "repositories.yaml" is invalid.');
            }

            return $yaml['repositories'];
        }

        return $this->decorated->load();
    }
}
