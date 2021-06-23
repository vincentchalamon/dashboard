<?php

namespace App\Controller;

use App\Repository\Repository;
use App\Repository\RepositoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

/**
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
final class RepositoryController
{
    #[Route(path: '/{name}', condition: 'request.isXmlHttpRequest()', requirements: ['name' => '.+'], name: 'app.repository')]
    public function __invoke(Environment $twig, RepositoryInterface $repository, string $name): Response
    {
        if (!$repository->exists($name)) {
            throw new NotFoundHttpException();
        }

        return new Response($twig->render('repository.html.twig', [
            'repository' => new Repository($repository, $name),
        ]));
    }
}
