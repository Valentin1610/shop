<?php

namespace App\Controller;

use App\Entity\Products;
use App\Entity\Orders;
use App\Entity\Status;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{

    private $entityManager;

    // Injection de l'interface EntityManagerInterface
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/cart', name: 'cart')]
    public function showCart(Request $request): Response
    {
        // Récupérez le panier depuis la session
        $session = $request->getSession();
        $cart = $session->get('cart', []);

        // Calculez le total du panier
        $total = 0;
        $totalArticles = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
            $totalArticles += $item['quantity'];
        }

        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
            'total' => $total,
            'totalArticles' => $totalArticles, // Passez le total des articles à la vue
        ]);
    }

    #[Route('/cart/remove/{id}', name: 'cart_remove', methods: ['POST'])]
    public function removeFromCart(int $id, Request $request)
    {
        // Récupérez le panier dans la session
        $session = $request->getSession();
        $cart = $session->get('cart', []);

        // Vérifiez si l'article existe dans le panier
        if (isset($cart[$id])) {
            unset($cart[$id]); // Supprimez l'article
            $session->set('cart', $cart); // Mettez à jour le panier dans la session

            // Ajoutez un message flash pour confirmer la suppression
            $this->addFlash('success', 'Article supprimé du panier.');
        }

        return $this->redirectToRoute('cart'); // Redirigez vers la page du panier
    }

    #[Route('/cart/add/{id}', name: 'cart_add', methods: ['POST'])]
    public function addToCart(int $id, Request $request, EntityManagerInterface $entityManager): RedirectResponse
    {
        $quantity = (int) $request->request->get('quantity', 1);

        // Récupérez le produit depuis la base de données
        $product = $entityManager->getRepository(Products::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException('Produit non trouvé.');
        }

        // Récupérez ou initialisez le panier dans la session
        $session = $request->getSession();
        $cart = $session->get('cart', []);

        // Ajoutez ou mettez à jour le produit dans le panier
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $quantity;
        } else {
            $cart[$id] = [
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'quantity' => $quantity,
            ];
        }

        // Sauvegardez le panier dans la session
        $session->set('cart', $cart);

        // Ajoutez un message flash pour informer l'utilisateur
        $this->addFlash('success', 'Produit ajouté au panier.');

        return $this->redirectToRoute('description_product', ['id' => $id]);
    }

    #[Route('/cart_order', name: 'cart_order', methods: ['POST'])]
    public function order(Request $request)
    {
        $session = $request->getSession();
        $cart = $request->getSession()->get('cart', []);

        if (empty($cart)) {
            $this->addFlash('error', 'Votre panier est vide.');
            return $this->redirectToRoute('cart');
        }

        $status = $this->entityManager->getRepository(Status::class)->findOneBy(['status' => 'En attente de confirmation']);

        if(!$status){
            throw new \Exception("Le statut 'En attente' n'existe pas en base de données.");
        }

        $user = $this->getUser();
        if (!$user) {
            throw new \Exception("Utilisateur non connecté");
        }

        $order = new Orders();
        $order->setOrderDate(new \DateTime());  // Date de la commande
        $order->setStatus($status);  // Statut initial de la commande
        $order->setOrderNumber((int) (time() . rand(1000, 9999)));  // Génération d'un numéro unique basé sur un timestamp
        $order->setTotal(array_sum(array_map(function ($item) {
            return $item['price'] * $item['quantity'];
        }, $cart)));
        $user = $this->getUser();
        $order->setUser($user);

        // Sauvegarde la commande en base de données
        
        $this->entityManager->persist($order);
        $this->entityManager->flush();

        $session->remove('cart');

        $this->addFlash('success', 'Votre commande a été passée avec succès !');

        return $this->redirectToRoute('cart');

    }
}