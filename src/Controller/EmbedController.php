<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Service\SiteSettingsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Service\Attribute\Required;

/**
 *
 */
class EmbedController extends AbstractController
{
    /**
     * @var UrlGeneratorInterface
     */
    private UrlGeneratorInterface $urlGenerator;

    /**
     * @param UrlGeneratorInterface $urlGenerator
     *
     * @return $this
     */
    #[Required]
    public function setUrlGenerator(UrlGeneratorInterface $urlGenerator): EmbedController
    {
        $this->urlGenerator = $urlGenerator;

        return $this;
    }

    /**
     * @param ProductRepository $productRepository
     * @param int               $productCount
     * @param int|null          $categoryId
     *
     * @return Response
     */
    public function showSimilarProducts(
        ProductRepository $productRepository,
        int $productCount = 2,
        ?int $categoryId = null
    ): Response {
        $products = $productRepository->findByCategoryAndCount($categoryId, $productCount);

        return $this->render('front/_embed/_similar_products.html.twig', [
            'products' => $products,
        ]);
    }

    /**
     * @param CategoryRepository $categoryRepository
     * @param string|null        $isActiveItemMenu
     *
     * @return Response
     */
    public function showHeaderMenu(CategoryRepository $categoryRepository, ?string $isActiveItemMenu): Response
    {
        $preparedListCategory = [];

        /** @var Category $category */
        foreach ($categoryRepository->findActiveCategoryWithJoinProduct() as $category) {
            $preparedListCategory[] = [
                'title' => $category->getTitle(),
                'url'   => $this->urlGenerator->generate(
                    'main_category_show',
                    ['slug' => $category->getSlug()],
                    UrlGeneratorInterface::ABSOLUTE_URL
                ),
            ];
        }

        return $this->render('front/_embed/_menu/_menu_nav_item.twig', [
            'navCategories'    => $preparedListCategory,
            'isActiveItemMenu' => $isActiveItemMenu,
        ]);
    }

    /**
     * @param SiteSettingsService $service
     *
     * @return Response
     */
    public function showSiteLogo(SiteSettingsService $service): Response
    {
        return $this->render('front/_embed/_menu/_logo.html.twig', [
            'logo' => $service->getSiteLogo(),
            'name' => $service->getSiteName(),
        ]);
    }
}
