<?php

declare(strict_types=1);

namespace App\Server;

use App\Metadata\RepositoryMetadataInterface;
use Github\Client as GitHubClient;
use Iterator;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Log\LoggerInterface;

/**
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
final class GitHubServer implements ServerInterface
{
    public function __construct(private readonly GitHubClient $gitHubClient, private readonly LoggerInterface $logger)
    {
    }

    public function getDefaultBranch(RepositoryMetadataInterface $repository): string
    {
        return $this->getData($repository->getName())['default_branch'];
    }

    public function getWorkflows(RepositoryMetadataInterface $repository): Iterator
    {
        list($org, $repo) = explode('/', $repository->getName());

        // List workflows
        try {
            foreach ($this->gitHubClient->api('repo')->workflows()->all($org, $repo)['workflows'] as $workflow) {
                // Workflow is wrongly configured
                if (empty($workflow['name'])) {
                    continue;
                }

                if (empty($workflow['path'])) {
                    continue;
                }

                // Workflow is inactive
                if ('active' !== $workflow['state']) {
                    continue;
                }

                // Workflow is ignored
                $workflows = $repository->getWorkflows();
                if ([] !== $workflows && !in_array($workflow['name'], $workflows, true)) {
                    continue;
                }

                yield [(string) $workflow['id'], $workflow['name']];
            }
        } catch (ClientExceptionInterface $clientException) {
            $this->logger->error($clientException->getMessage());
        }

        yield from [];
    }

    public function getRuns(RepositoryMetadataInterface $repository, string $workflowId): Iterator
    {
        list($org, $repo) = explode('/', $repository->getName());

        try {
            foreach ($this->gitHubClient->api('repo')->workflowRuns()->listRuns($org, $repo, $workflowId, [
                'per_page' => 10,
                'branch' => $repository->getBranch() ?: $this->getDefaultBranch($repository),
            ])['workflow_runs'] as $run) {
                yield [$run['html_url'], $run['conclusion'], $run['updated_at']];
            }
        } catch (ClientExceptionInterface $clientException) {
            $this->logger->error($clientException->getMessage());
        }

        yield from [];
    }

    public function getStars(RepositoryMetadataInterface $repository): int
    {
        return $this->getData($repository->getName())['stargazers_count'];
    }

    public function supports(string $uri): bool
    {
        return str_starts_with($uri, 'https://github.com/');
    }

    private function getData(string $name): array
    {
        list($org, $repo) = explode('/', $name);

        return array_intersect_key(
            $this->gitHubClient->api('repo')->show($org, $repo),
            array_flip(['default_branch', 'stargazers_count'])
        );
    }
}
