<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 *
 */
#[Route('/admin')]
class DashboardController extends BaseAdminController
{
    /**
     * @return Response
     */
    #[Route('/', name: 'admin_index')]
    public function index(): Response
    {
        return $this->redirectToRoute('admin_dashboard_show');
    }

    /**
     * @return Response
     */
    #[Route('/dashboard', name: 'admin_dashboard_show')]
    public function dashboard(): Response
    {
        return $this->render('admin/pages/dashboard.html.twig');
    }
}
