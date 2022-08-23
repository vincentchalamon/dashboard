<?php

declare(strict_types=1);

namespace App\Metadata\Loader;

use App\Metadata\RepositoryMetadata;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
#[AsDecorator(RepositoriesLoader::class)]
final class RepositoryMetadataLoader implements LoaderInterface
{
    public function __construct(
        private readonly LoaderInterface $decorated,
        private readonly PropertyAccessorInterface $propertyAccessor
    ) {
    }

    public function load(): array
    {
        $repositories = $this->decorated->load();

        foreach ($repositories as $group => $repos) {
            $data = [];
            foreach ($repos as $config) {
                $repository = new RepositoryMetadata(
                    $config['group'],
                    $config['name'],
                    $config['uri']
                );
                unset($config['group'], $config['name'], $config['uri']);

                foreach ($config as $key => $value) {
                    $this->propertyAccessor->setValue($repository, $key, $value);
                }

                $data[] = $repository;
            }

            $repositories[$group] = $data;
        }

        return $repositories;
    }
}
