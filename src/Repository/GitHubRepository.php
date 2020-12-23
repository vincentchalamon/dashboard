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
    private HttpClientInterface $httpClient;
    private string $token;

    public function __construct(HttpClientInterface $httpClient, string $token)
    {
        $this->httpClient = $httpClient;
        $this->token = $token;
    }

    public function isValid(string $name): bool
    {
        try {
            $this->httpClient->request('GET', "https://api.github.com/repos/$name", [
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

    public function getUrl(string $name): string
    {
        return "https://github.com/$name";
    }

    public function getWorkflows(string $name): iterable
    {
        $uri = "https://api.github.com/repos/$name";

        // Get default branch
        try {
            $defaultBranch = $this->httpClient->request('GET', $uri, [
                    'headers' => [
                        'Accept' => 'application/vnd.github.v3+json',
                        'Authorization' => "token $this->token",
                    ],
                ])->toArray()['default_branch'] ?? 'main';
        } catch (ExceptionInterface $exception) {
            return [];
        }

        // List workflows
        $uri .= '/actions/workflows';
        try {
            $collection = $this->httpClient->request('GET', "$uri?branch=$defaultBranch", [
                    'headers' => [
                        'Accept' => 'application/vnd.github.v3+json',
                        'Authorization' => "token $this->token",
                    ],
                ])->toArray()['workflows'] ?? [];
        } catch (ExceptionInterface $exception) {
            return [];
        }

        // Get workflows status
        $workflows = [];
        foreach ($collection as $item) {
            if (empty($item['path']) || 'active' !== $item['state']) {
                continue;
            }

            try {
                $runs = array_reverse(array_slice(
                    $this->httpClient->request('GET', "$uri/$item[id]/runs?branch=$defaultBranch", [
                        'headers' => [
                            'Accept' => 'application/vnd.github.v3+json',
                            'Authorization' => "token $this->token",
                        ],
                    ])->toArray()['workflow_runs'] ?? [],
                    0,
                    10
                ));
            } catch (ExceptionInterface $exception) {
                continue;
            }

            $workflows[$item['id']] = $runs;
        }

        return $workflows;
    }
}
