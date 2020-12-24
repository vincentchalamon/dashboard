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
    private LoaderInterface $loader;

    public function __construct(LoaderInterface $loader)
    {
        $this->loader = $loader;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('getRepositories', [$this, 'getRepositories']),
        ];
    }

    public function getRepositories(): array
    {
        return $this->loader->load();
    }
}
