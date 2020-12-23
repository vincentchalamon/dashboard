<?php

declare(strict_types=1);

namespace App\Twig;

use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
final class RepositoryExtension extends AbstractExtension
{
    private string $projectDir;

    public function __construct(string $projectDir)
    {
        $this->projectDir = $projectDir;
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

    /**
     * @SuppressWarnings("PHPMD.StaticAccess")
     */
    public function getRepositories(): array
    {
        try {
            return array_map(function (string $repository) {
                return preg_replace('/^https:\/\/[^\/]+\/(.*)(?:\.git|\/)?$/', '$1', $repository);
            }, Yaml::parseFile("$this->projectDir/repositories.yaml")['repositories'] ?? []);
        } catch (ParseException $exception) {
            return [];
        }
    }
}
