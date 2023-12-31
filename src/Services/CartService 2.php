<?php

namespace App\Services;

use App\Entity\Cart;
use App\Repository\CartRepository;
use App\Services\SimpleFormHandlerService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Ce service sert à gérer l'ajout d'un article au panier avec le formulaire.
 * La méthode addToCartHandle fait deux choses :
 * 1- Elle lie la soumission au formulaire (ajouter au panier) au panier actuel de l'utilisateurice
 * 2- Elle appelle elle même le SimpleFormHandlerService pour persister l'ajout au panier
 */

class CartService {

    public function __construct(
        private Security $security,
        private CartRepository $cartRepo,
        private SimpleFormHandlerService $simpleService,
    )
    {
        // Empty
    }

    public function addToCartHandle(FormInterface $form, Request $request): bool
    {
        if (($customer = $this->security->getUser()) === NULL) {
            return false;
        }
        $cartLine = $form->getData();
        if (($currentCart = $this->cartRepo->getLastCart($customer)) === NULL) {
            $currentCart = new Cart();
        }
        $cartLine->setCart($currentCart);
        $form->setData($cartLine);
        return $this->simpleService->handleForm($form, $request);
    }

}