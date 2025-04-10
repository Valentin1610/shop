<?php

namespace App\Controller;

use App\Entity\Orders;
use App\Entity\Status;
use App\Repository\OrdersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OrdersController extends AbstractController
{
    #[Route('/orders', name: 'orders')]
    public function orders(OrdersRepository $ordersRepository, EntityManagerInterface $entityManager): Response
    {

        $statuses = $entityManager->getRepository(Status::class)->findAll();

        $orders = $ordersRepository->findAll();
        return $this->render('orders/index.html.twig', [
            'orders' => $orders,
            'statuses' => $statuses
        ]);
    }

    #[Route('/orders/create', name: 'orders_create')]
    public function createOrder(EntityManagerInterface $entityManager): Response
    {

        $user = $this->getUser();

        if (!$user) {
            throw new \Exception('Aucun utilisateur connecté.');
        }
        // Récupérer un statut valide depuis la base de données (par exemple, le statut avec id = 1)
        $status = $entityManager->getRepository(Status::class)->find(1); // Assurez-vous que le statut existe

        if (!$status) {
            // Si aucun statut n'est trouvé, on pourrait lancer une exception ou définir un statut par défaut
            throw new \Exception('Le statut n\'existe pas.');
        }

        // Créer une nouvelle commande
        $order = new Orders();
        $order->setOrderDate(new \DateTime());
        $order->setPreparationDate(new \DateTime()); // Exemple, à adapter
        $order->setDateDepartureQuay(new \DateTime()); // Exemple, à adapter
        $order->setStatus($status);
        $order->setOrderNumber(12345); // Exemple, à adapter
        $order->setTotal('100.00'); // Exemple, à adapter
        $order->setUser($user);

        // Assigner le statut à la commande
        $order->setIdStatus($status); // Assigner un statut valide

        // Persister la commande dans la base de données
        $entityManager->persist($order);
        $entityManager->flush();

        return new Response('Commande crée avec succès !');
    }

    #[Route('/orders/{id}/edit', name: 'order_edit')]
    public function editOrder(Request $request, Orders $order, EntityManagerInterface $entityManager): Response
    {
        // Vérifier si le statut est déjà "Expédié"
        if ($order->getStatus()->getStatus() === 'Expédié') {
            $this->addFlash('error', 'La commande ne peut pas être modifiée car elle a déjà été expédiée.');
            return $this->redirectToRoute('orders');
        }

        // Récupérer tous les statuts disponibles
        $statuses = $entityManager->getRepository(Status::class)->findAll();

        if ($request->isMethod('POST')) {
            $status = $entityManager->getRepository(Status::class)->find($request->request->get('status'));

            if (!$status) {
                $this->addFlash('error', 'Statut invalide.');
                return $this->redirectToRoute('orders');
            }

            $order->setStatus($status);
            if ($status->getStatus() === 'En préparation') {
                $order->setPreparationDate(new \DateTime());
            }

            $entityManager->flush();

            return $this->redirectToRoute('orders');
        }

        return $this->render('orders/edit.html.twig', [
            'order' => $order,
            'statuses' => $statuses // Correction : on passe bien les statuts à la vue
        ]);
    }

    #[Route('/orders/{id}/remove', name: 'order_delete', methods: ['POST'])]
    public function deleteOrder(int $id, EntityManagerInterface $entityManager)
    {
        $order = $entityManager->getRepository(Orders::class)->find($id);


        if (!$order) {
            $this->addFlash('danger', 'Commande introuvable');
            return $this->redirectToRoute('orders');
        }

        $entityManager->remove($order);
        $entityManager->flush();

        $this->addFlash('success', 'Commande supprimée avec succès.');
        return $this->redirectToRoute('orders');
    }

    #[Route('/orders/user', name: 'orders_user')]
    public function userOrders(OrdersRepository $ordersRepository, EntityManagerInterface $entityManager): Response
    {

        $statuses = $entityManager->getRepository(Status::class)->findAll();

        $user = $this->getUser();
        if (!$user) {
            throw new Exception("Aucun utilisateur connecté");
        }
        $orders = $ordersRepository->findBy(['user' => $user]);

        return $this->render('orders/order.user.html.twig', [
            'orders' => $orders,
            'statuses' => $statuses
        ]);
    }

    #[Route('/orders/{id}/update-status', name: 'update_order_status', methods: ['POST'])]
    public function updateStatus(Request $request, Orders $order, OrdersRepository $ordersRepository, EntityManagerInterface $entityManager, $id): Response
    {
        $order = $ordersRepository->find($id);
        if (!$order) {
            $this->addFlash('error', "Commande introuvable.");
            return $this->redirectToRoute('order_list');
        }

        $user = $this->getUser();

        if (!$user || (!in_array('Logisticien', $user->getRoles()) && !in_array('Gestionnaire', $user->getRoles()))) {
            $this->addFlash('error', "Vous n'êtes pas autorisé à modifier le statut de cette commande.");
            return $this->redirectToRoute('order_list');
        }

        $statusId = $request->request->get('status');
        $status = $entityManager->getRepository(Status::class)->find($statusId);

        if (!$status) {
            $this->addFlash('error', "Statut invalide.");
            return $this->redirectToRoute('order_list');
        }

        // Mise à jour du statut
        $order->setStatus($status);

        // Si le gestionnaire choisit "Expédiée", il peut aussi modifier la date du départ du quai
        if (in_array('Gestionnaire', $user->getRoles()) && $status->getStatus() == "Expédiée") {
            $dateDepartureQuay = $request->request->get('dateDepartureQuay');
            if ($dateDepartureQuay) {
                $order->setDateDepartureQuay(new \DateTime($dateDepartureQuay));
            } else {
                $order->setDateDepartureQuay(new \DateTime()); // Défaut : aujourd'hui
            }
        }

        $entityManager->persist($order);
        $entityManager->flush();

        $this->addFlash('success', "Statut mis à jour avec succès.");
        return $this->redirectToRoute('orders');
    }
}
