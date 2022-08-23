<?php

namespace App\Controller;

use App\Repository\RepositoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

/**
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
#[AsController]
final class RepositoryController
{
    #[Route(path: '/{name}', condition: 'request.isXmlHttpRequest()', requirements: ['name' => '.+'], name: 'app.repository')]
    public function __invoke(Environment $twig, RepositoryRepository $repositoryRepository, string $name): Response
    {
        $repository = $repositoryRepository->find($name);
        if (null === $repository) {
            throw new NotFoundHttpException();
        }

        return new Response($twig->render('repository.html.twig', [
            'repository' => $repository,
        ]));
    }
}
