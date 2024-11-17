<?php

declare(strict_types=1);

namespace App\Form\Handler;

use App\Entity\Article;
use App\Utils\Manager\ArticleManager;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Lexik\Bundle\FormFilterBundle\Filter\FilterBuilderUpdater;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 *
 */
class ArticleFormHandler
{
    /**
     * @param ArticleManager        $articleManager
     * @param PaginatorInterface    $paginator
     * @param FilterBuilderUpdater  $filterBuilderUpdater
     * @param SluggerInterface      $slugger
     * @param ParameterBagInterface $bag
     */
    public function __construct(
        private readonly ArticleManager $articleManager,
        private readonly PaginatorInterface $paginator,
        private readonly FilterBuilderUpdater $filterBuilderUpdater,
        private readonly SluggerInterface $slugger,
        private readonly ParameterBagInterface $bag
    ) {
    }

    /**
     * @param FormInterface $form
     * @param Article       $article
     *
     * @return Article
     */
    public function processEditForm(FormInterface $form, Article $article): Article
    {
        /** @var UploadedFile $previewImageFile */
        $previewImageFile = $form->get('previewImageFile')->getData();
        if ($previewImageFile) {
            if ($article->getPreviewImage()) {
                unlink($this->bag->get('uploads_images_dir') . '/' . $article->getPreviewImage());
            }
            $originalFilename = pathinfo($previewImageFile->getClientOriginalName(), PATHINFO_FILENAME);

            $safeFilename = $this->slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $previewImageFile->guessExtension();
            $previewImageFile->move($this->bag->get('uploads_images_dir'), $newFilename);

            $article->setPreviewImage($newFilename);
        }

        /** @var UploadedFile $detailImageFile */
        $detailImageFile = $form->get('detailImageFile')->getData();
        if ($detailImageFile) {
            if ($article->getDetailImage()) {
                unlink($this->bag->get('uploads_images_dir') . '/' . $article->getDetailImage());
            }
            $originalFilename = pathinfo($detailImageFile->getClientOriginalName(), PATHINFO_FILENAME);

            $safeFilename = $this->slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $detailImageFile->guessExtension();
            $detailImageFile->move($this->bag->get('uploads_images_dir'), $newFilename);

            $article->setDetailImage($newFilename);
        }

        $this->articleManager->persist($article);
        $this->articleManager->flush();

        return $article;
    }

    /**
     * @param Request       $request
     * @param FormInterface $filterForm
     *
     * @return PaginationInterface
     */
    public function processOrderFiltersForm(Request $request, FormInterface $filterForm): PaginationInterface
    {
        $queryBuilder = $this->articleManager->getQueryBuilder();

        if ($filterForm->isSubmitted()) {
            $this->filterBuilderUpdater->addFilterConditions($filterForm, $queryBuilder);
        }

        return $this->paginator->paginate($queryBuilder->getQuery(), $request->query->getInt('page', 1));
    }
}
