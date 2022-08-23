<?php

declare(strict_types=1);

namespace App\Metadata\Loader;

use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
final class RepositoriesLoader implements LoaderInterface
{
    public function __construct(
        #[Autowire('%env(json:APP_REPOSITORIES)%')] private readonly array $repositories = []
    ) {
    }

    public function load(): array
    {
        return $this->repositories;
    }
}
