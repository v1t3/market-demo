<?php

declare(strict_types=1);

namespace App\Controller;

use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 *
 */
class SitemapController extends AbstractController
{
    /**
     * @return Response
     */
    #[Route('/sitemap.xml', name: 'main_sitemap')]
    public function index(): Response
    {
        $mainPageInfo = [
            'loc'        => $this->generateUrl('main_homepage', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'lastmod'    => (new DateTimeImmutable())->format('Y-m-d'),
            'changefreq' => 'weekly',
            'priority'   => 1,
        ];

        return $this->render('front/sitemap.xml.twig', [
            'data' => $mainPageInfo,
        ]);
    }
}
