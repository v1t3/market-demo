<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\Admin\EditArticleFormType;
use App\Form\Admin\FilterType\ArticleFilterFormType;
use App\Form\Handler\ArticleFormHandler;
use App\Utils\Manager\ArticleManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 *
 */
#[Route('/admin/article', name: 'admin_article_')]
class ArticleController extends BaseAdminController
{
    /**
     * @param Request            $request
     * @param ArticleFormHandler $articleFormHandler
     *
     * @return Response
     */
    #[Route(['/list', ''], name: 'list')]
    public function list(Request $request, ArticleFormHandler $articleFormHandler): Response
    {
        $filterForm = $this->createForm(ArticleFilterFormType::class, new Article());
        $filterForm->handleRequest($request);

        $pagination = $articleFormHandler->processOrderFiltersForm($request, $filterForm);

        return $this->render('admin/article/list.html.twig', [
            'pagination' => $pagination,
            'uploadPath' => $this->getParameter('uploads_images_url') . '/',
            'form'       => $filterForm->createView(),
        ]);
    }

    /**
     * @param Request            $request
     * @param ArticleFormHandler $articleFormHandler
     *
     * @return Response
     */
    #[Route('/add', name: 'add')]
    public function add(Request $request, ArticleFormHandler $articleFormHandler): Response
    {
        $article = new Article();

        $form = $this->createForm(EditArticleFormType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('warning', 'Something went wrong. Please check!');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$this->checkTheAccessLevel()) {
                return $this->redirect($request->server->get('HTTP_REFERER'));
            }

            $article->setCreatedAt(new \DateTimeImmutable()); //todo временно
            $article->setUpdatedAt(new \DateTimeImmutable());
            $article = $articleFormHandler->processEditForm($form, $article);

            $this->addFlash('success', 'Your changes were saved!');

            return $this->redirectToRoute('admin_article_edit', ['id' => $article->getId()]);
        }

        return $this->render('admin/article/add.html.twig', [
            'article' => $article,
            'form'    => $form->createView(),
        ]);
    }

    /**
     * @param Request            $request
     * @param ArticleFormHandler $articleFormHandler
     * @param Article|null       $article
     *
     * @return Response
     */
    #[Route('/edit/{id}', name: 'edit')]
    public function edit(
        Request $request,
        ArticleFormHandler $articleFormHandler,
        Article $article = null,
    ): Response {
        if ($article === null) {
            return $this->render('admin/404.html.twig', [
                'message' => 'Элемент не найден'
            ]);
        }

        $form = $this->createForm(EditArticleFormType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('warning', 'Something went wrong. Please check!');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$this->checkTheAccessLevel()) {
                return $this->redirect($request->server->get('HTTP_REFERER'));
            }

            $article = $articleFormHandler->processEditForm($form, $article);

            $this->addFlash('success', 'Your changes were saved!');

            return $this->redirectToRoute('admin_article_edit', ['id' => $article->getId()]);
        }

        if ($article->getPreviewImage()) {
            $article->setPreviewImage(
                $this->getParameter('uploads_images_url') . '/' . $article->getPreviewImage()
            );
        }
        if ($article->getDetailImage()) {
            $article->setDetailImage(
                $this->getParameter('uploads_images_url') . '/' . $article->getDetailImage()
            );
        }

        return $this->render('admin/article/edit.html.twig', [
            'article' => $article,
            'form'    => $form->createView(),
        ]);
    }

    /**
     * @param Request        $request
     * @param Article        $article
     * @param ArticleManager $articleManager
     *
     * @return Response
     */
    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Request $request, Article $article, ArticleManager $articleManager): Response
    {
        $id = $article->getId();
        $title = $article->getTitle();

        if (!$this->checkTheAccessLevel()) {
            return $this->redirect($request->server->get('HTTP_REFERER'));
        }

        $articleManager->remove($article);
        $this->addFlash('warning', "The article (title: $title / ID: $id) was successfully deleted!");

        return $this->redirectToRoute('admin_article_list');
    }
}
