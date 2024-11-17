<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 *
 */
class ArticleController extends AbstractController
{
    /**
     * @param Request           $request
     * @param ArticleRepository $repository
     *
     * @return Response
     */
    #[Route('/blog', name: 'app_article_list')]
    public function index(Request $request, ArticleRepository $repository): Response
    {
        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $repository->getPaginator($offset);

        return $this->render('front/blog/article/index.html.twig', [
            'elements' => $paginator,
            'previous' => $offset - ArticleRepository::getPageSize(),
            'next'     => min(count($paginator), $offset + ArticleRepository::getPageSize()),
            'pageSize' => ArticleRepository::getPageSize(),
            'page'     => [
                'title' => 'Блог',
            ],
        ]);
    }

    /**
     * @param Article $article
     *
     * @return Response
     */
    #[Route('/blog/{code}', name: 'app_article_detail')]
    public function detail(Article $article): Response
    {
        return $this->render('front/blog/article/detail.html.twig', [
            'element' => $article,
        ]);
    }
}
