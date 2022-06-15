<?php

declare(strict_types=1);

namespace App\Repository;

use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
final class GitHubRepository implements RepositoryInterface
{
    /**
     * @var string
     */
    private const API_URL = 'https://api.github.com';

    private array $repositories = [];

    public function __construct(private readonly HttpClientInterface $httpClient, private readonly string $token)
    {
    }

    public function exists(string $name): bool
    {
        try {
            $this->initialize($name);

            return true;
        } catch (ExceptionInterface) {
            return false;
        }
    }

    public function getDefaultBranch(string $name): string
    {
        $this->initialize($name);

        return $this->repositories[$name]['default_branch'];
    }

    public function getUrl(string $name): string
    {
        return sprintf('https://github.com/%s', $name);
    }

    public function getWorkflows(string $name): iterable
    {
        if (!empty($this->repositories[$name]['workflows'])) {
            return $this->repositories[$name]['workflows'];
        }

        // List workflows
        try {
            $collection = $this->httpClient->request(
                    'GET',
                    sprintf('%s/repos/%s/actions/workflows?branch=%s', self::API_URL, $name, $this->getDefaultBranch($name)),
                    [
                        'headers' => [
                            'Accept' => 'application/vnd.github.v3+json',
                            'Authorization' => sprintf('token %s', $this->token),
                        ],
                    ]
                )->toArray()['workflows'] ?? [];
        } catch (ExceptionInterface) {
            return [];
        }

        // Get workflows status
        foreach ($collection as $item) {
            if (empty($item['path'])) {
                continue;
            }

            if (empty($item['name'])) {
                continue;
            }

            if ('active' !== $item['state']) {
                continue;
            }

            try {
                $runs = array_reverse(array_slice(
                    $this->httpClient->request(
                        'GET',
                        sprintf('%s/repos/%s/actions/workflows/%d/runs?branch=%s', self::API_URL, $name, $item['id'], $this->getDefaultBranch($name)),
                        [
                            'headers' => [
                                'Accept' => 'application/vnd.github.v3+json',
                                'Authorization' => sprintf('token %s', $this->token),
                            ],
                        ]
                    )->toArray()['workflow_runs'] ?? [],
                    0,
                    10
                ));
            } catch (ExceptionInterface) {
                continue;
            }

            $this->repositories[$name]['workflows'][$item['id']] = $runs;
        }

        return $this->repositories[$name]['workflows'];
    }

    public function getStars(string $name): int
    {
        $this->initialize($name);

        return $this->repositories[$name]['stargazers_count'];
    }

    /**
     * @throws ExceptionInterface
     */
    private function initialize(string $name): void
    {
        if (!empty($this->repositories[$name])) {
            return;
        }

        $this->repositories[$name] = $this->httpClient->request('GET', sprintf('https://api.github.com/repos/%s', $name), [
            'headers' => [
                'Accept' => 'application/vnd.github.v3+json',
                'Authorization' => sprintf('token %s', $this->token),
            ],
        ])->toArray();
    }
}
