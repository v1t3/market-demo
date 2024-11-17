<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\Admin\EditCategoryFormType;
use App\Form\DTO\EditCategoryModel;
use App\Form\Handler\CategoryFormHandler;
use App\Repository\CategoryRepository;
use App\Utils\Manager\CategoryManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Service\Attribute\Required;

/**
 *
 */
#[Route('/admin/category', name: 'admin_category_')]
class CategoryController extends BaseAdminController
{
    /**
     * @var CategoryRepository
     */
    private CategoryRepository $categoryRepository;

    /**
     * @param CategoryRepository $categoryRepository
     *
     * @return $this
     */
    #[Required]
    public function setCategoryRepository(CategoryRepository $categoryRepository): CategoryController
    {
        $this->categoryRepository = $categoryRepository;

        return $this;
    }

    /**
     * @return Response
     */
    #[Route('/list', name: 'list')]
    public function list(): Response
    {
        $categories = $this->categoryRepository->findBy(['isDeleted' => false], ['id' => 'DESC']);

        return $this->render('admin/category/list.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * Используется избиратель src/Security/Voters/AdminOrderEditVoter
     */
    #[Route('/edit/{id}', name: 'edit')]
    #[Route('/add', name: 'add')]
    public function edit(
        Request $request,
        CategoryFormHandler $categoryFormHandler,
        ?Category $category = null
    ): Response {
        $editCategoryModel = EditCategoryModel::makeFromCategory($category);

        $form = $this->createForm(EditCategoryFormType::class, $editCategoryModel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$this->checkTheAccessLevel()) {
                return $this->redirect($request->server->get('HTTP_REFERER'));
            }

            $category = $categoryFormHandler->processEditForm($editCategoryModel);
            $this->addFlash('success', 'Изменения сохранены!');

            return $this->redirectToRoute('admin_category_edit', ['id' => $category->getId()]);
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('warning', 'Что-то пошло не так. Проверьте корректность данных!');
        }

        return $this->render('admin/category/edit.html.twig', [
            'category' => $category,
            'form'     => $form->createView(),
        ]);
    }

    /**
     * @param Request         $request
     * @param Category        $category
     * @param CategoryManager $categoryManager
     *
     * @return Response
     */
    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Request $request, Category $category, CategoryManager $categoryManager): Response
    {
        $id = $category->getId();
        $title = $category->getTitle();

        if (!$this->checkTheAccessLevel()) {
            return $this->redirect($request->server->get('HTTP_REFERER'));
        }

        $categoryManager->remove($category);
        $this->addFlash('warning', "[Soft delete] The category (title: $title / ID: $id) was successfully deleted!");

        return $this->redirectToRoute('admin_category_list');
    }
}
