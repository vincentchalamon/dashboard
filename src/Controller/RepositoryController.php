<?php

namespace App\Controller;

use App\Repository\GitHubRepository;
use App\Repository\Repository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

/**
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
final class RepositoryController
{
    /**
     * @Route("/{name}", condition="request.isXmlHttpRequest()", requirements={"name"=".+"})
     */
    public function __invoke(Environment $twig, GitHubRepository $repository, string $name): Response
    {
        if (!$repository->exists($name)) {
            throw new NotFoundHttpException();
        }

        return new Response($twig->render('repository.html.twig', [
            'repository' => new Repository($repository, $name),
        ]));
    }
}
