<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Repository\CartRepository;
use App\Utils\Manager\OrderManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 *
 */
class CartController extends AbstractController
{
    /**
     * @param Request        $request
     * @param CartRepository $cartRepository
     *
     * @return Response
     */
    #[Route('/cart', name: 'main_cart_show')]
    public function show(Request $request, CartRepository $cartRepository): Response
    {
        $cartToken = $request->cookies->get('CART_TOKEN');
        $cart = $cartRepository->findOneBy(['token' => $cartToken]);

        return $this->render('front/cart/show.html.twig', [
            'cart' => $cart,
        ]);
    }

    /**
     * @param Request      $request
     * @param OrderManager $orderManager
     *
     * @return Response
     */
    #[Route('/cart/create', name: 'main_cart_create')]
    public function create(Request $request, OrderManager $orderManager): Response
    {
        $cartToken = $request->cookies->get('CART_TOKEN');

        /** @var User $user */
        $user = $this->getUser();

        $orderManager->createOrderFromCartByToken($cartToken, $user);

        $redirectUrl = $this->generateUrl('main_cart_show');

        // Пример удаления куки 'CART_TOKEN' через контроллер
        $response = new RedirectResponse($redirectUrl);
        $response->headers->clearCookie('CART_TOKEN', '/', null);

        return $response;
    }
}
