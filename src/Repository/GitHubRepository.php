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
    private const API_URL = 'https://api.github.com';

    private HttpClientInterface $httpClient;
    private string $token;
    private ?string $defaultBranch = null;
    private ?iterable $workflows = null;

    public function __construct(HttpClientInterface $httpClient, string $token)
    {
        $this->httpClient = $httpClient;
        $this->token = $token;
    }

    public function exists(string $name): bool
    {
        try {
            $this->httpClient->request('GET', sprintf('%s/repos/%s', self::API_URL, $name), [
                'headers' => [
                    'Accept' => 'application/vnd.github.v3+json',
                    'Authorization' => "token $this->token",
                ],
            ])->toArray();

            return true;
        } catch (ExceptionInterface $exception) {
            return false;
        }
    }

    /**
     * @throws ExceptionInterface
     */
    public function getDefaultBranch(string $name): string
    {
        if (null === $this->defaultBranch) {
            $this->defaultBranch = $this->httpClient->request('GET', "https://api.github.com/repos/$name", [
                    'headers' => [
                        'Accept' => 'application/vnd.github.v3+json',
                        'Authorization' => "token $this->token",
                    ],
                ])->toArray()['default_branch'] ?? 'main';
        }

        return $this->defaultBranch;
    }

    public function getUrl(string $name): string
    {
        return "https://github.com/$name";
    }

    public function getWorkflows(string $name): iterable
    {
        if (null === $this->workflows) {
            // List workflows
            try {
                $collection = $this->httpClient->request(
                    'GET',
                    sprintf('%s/repos/%s/actions/workflows?branch=%s', self::API_URL, $name, $this->getDefaultBranch($name)),
                    [
                        'headers' => [
                            'Accept' => 'application/vnd.github.v3+json',
                            'Authorization' => "token $this->token",
                        ],
                    ]
                )->toArray()['workflows'] ?? [];
            } catch (ExceptionInterface $exception) {
                return $this->workflows = [];
            }

            // Get workflows status
            $this->workflows = [];
            foreach ($collection as $item) {
                if (empty($item['path']) || 'active' !== $item['state']) {
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
                                    'Authorization' => "token $this->token",
                                ],
                            ]
                        )->toArray()['workflow_runs'] ?? [],
                        0,
                        10
                    ));
                } catch (ExceptionInterface $exception) {
                    continue;
                }

                $this->workflows[$item['id']] = $runs;
            }
        }

        return $this->workflows;
    }
}
