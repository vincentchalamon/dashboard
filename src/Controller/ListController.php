<?php

namespace App\Controller;

use App\Repository\RepositoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

/**
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
#[AsController]
final class ListController
{
    #[Route(path: '/')]
    public function __invoke(Environment $twig, RepositoryRepository $repositoryRepository): Response
    {
        return new Response($twig->render('list.html.twig', [
            'repositories' => $repositoryRepository->findAll(),
        ]));
    }
}
