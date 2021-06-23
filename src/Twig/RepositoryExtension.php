<?php

declare(strict_types=1);

namespace App\Twig;

use App\Loader\LoaderInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
final class RepositoryExtension extends AbstractExtension
{
    public function __construct(private LoaderInterface $loader)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('getRepositories', function (): array {
                return $this->getRepositories();
            }),
        ];
    }

    public function getRepositories(): array
    {
        return $this->loader->load();
    }
}
