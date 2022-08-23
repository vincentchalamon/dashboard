<?php

namespace App\Controller;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\CacheClearer\Psr6CacheClearer;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
#[AsController]
final class CacheController
{
    #[Route(path: '/clear-cache', condition: 'request.isXmlHttpRequest()', name: 'app.cache')]
    public function __invoke(
        #[Autowire('@cache.global_clearer')] Psr6CacheClearer $poolClearer
    ): Response {
        $poolClearer->clearPool('cache.repository');

        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
