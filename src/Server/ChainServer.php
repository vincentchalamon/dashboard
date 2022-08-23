<?php

declare(strict_types=1);

namespace App\Server;

use App\Exception\ServerNotFoundException;
use App\Metadata\RepositoryMetadataInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

/**
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
final class ChainServer implements ServerInterface
{
    public function __construct(
        #[TaggedIterator(tag: ServerInterface::class, exclude: self::class)] private readonly iterable $servers
    ) {
    }

    public function getDefaultBranch(RepositoryMetadataInterface $repository): string
    {
        foreach ($this->servers as $server) {
            if ($server->supports($repository->getUri())) {
                return $server->getDefaultBranch($repository);
            }
        }

        throw new ServerNotFoundException(sprintf('No server found to support repository "%s".', $repository->getUri()));
    }

    public function getWorkflows(RepositoryMetadataInterface $repository): iterable
    {
        foreach ($this->servers as $server) {
            if ($server->supports($repository->getUri())) {
                return $server->getWorkflows($repository);
            }
        }

        throw new ServerNotFoundException(sprintf('No server found to support repository "%s".', $repository->getUri()));
    }

    public function getRuns(RepositoryMetadataInterface $repository, string $workflowId): iterable
    {
        foreach ($this->servers as $server) {
            if ($server->supports($repository->getUri())) {
                return $server->getRuns($repository, $workflowId);
            }
        }

        throw new ServerNotFoundException(sprintf('No server found to support repository "%s".', $repository->getUri()));
    }

    public function getStars(RepositoryMetadataInterface $repository): int
    {
        foreach ($this->servers as $server) {
            if ($server->supports($repository->getUri())) {
                return $server->getStars($repository);
            }
        }

        throw new ServerNotFoundException(sprintf('No server found to support repository "%s".', $repository->getUri()));
    }

    public function supports(string $uri): bool
    {
        foreach ($this->servers as $server) {
            if ($server->supports($uri)) {
                return true;
            }
        }

        return false;
    }
}
