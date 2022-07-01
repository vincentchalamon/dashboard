<?php

declare(strict_types=1);

namespace App\Repository;

use Github\Client as GitHubClient;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Log\LoggerInterface;

/**
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
final class GitHubRepository implements RepositoryInterface
{
    private array $repositories = [];

    public function __construct(private readonly GitHubClient $client, private readonly LoggerInterface $logger)
    {
    }

    public function exists(string $name): bool
    {
        try {
            $this->initialize($name);

            return true;
        } catch (ClientExceptionInterface) {
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
        $this->initialize($name);

        if (array_key_exists('workflows', $this->repositories[$name])) {
            return $this->repositories[$name]['workflows'];
        }

        $this->repositories[$name]['workflows'] = [];
        list($org, $repo) = explode('/', $name);

        // List workflows
        try {
            $workflows = $this->client->api('repo')->workflows()->all($org, $repo)['workflows'];
        } catch (ClientExceptionInterface $e) {
            $this->logger->error($e->getMessage());

            return [];
        }

        // Get workflows runs
        foreach ($workflows as $workflow) {
            if (empty($workflow['name']) || 'active' !== $workflow['state']) {
                continue;
            }

            try {
                $runs = array_map(
                    fn (array $run): array => array_intersect_key($run, array_flip(['html_url', 'conclusion', 'updated_at'])),
                    $this->client->api('repo')->workflowRuns()->listRuns($org, $repo, (string) $workflow['id'], ['per_page' => 10])['workflow_runs']
                );
            } catch (ClientExceptionInterface $e) {
                $this->logger->error($e->getMessage());

                continue;
            }

            $this->repositories[$name]['workflows'][$workflow['id']] = ['name' => $workflow['name'], 'runs' => $runs];
        }

        dump($this->repositories[$name]['workflows']);
        return $this->repositories[$name]['workflows'];
    }

    public function getStars(string $name): int
    {
        $this->initialize($name);

        return $this->repositories[$name]['stargazers_count'];
    }

    /**
     * @throws ClientExceptionInterface
     */
    private function initialize(string $name): void
    {
        if (!empty($this->repositories[$name])) {
            return;
        }

        list($org, $repo) = explode('/', $name);

        $this->repositories[$name] = array_intersect_key(
            $this->client->api('repo')->show($org, $repo),
            array_flip(['default_branch', 'stargazers_count'])
        );
    }
}
