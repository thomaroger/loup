<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\Slot;
use App\Form\ReservationType;
use App\Service\SlotService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SlotController extends AbstractController
{
    public function __construct(
        private FormFactoryInterface $formFactory,
        private SlotService $slotService
    ) {
    }

    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        if ($this->getUser() && $this->getUser()->hasRole('ROLE_ADMIN')) {
            return $this->redirectToRoute('admin_index');
        }
        return $this->redirectToRoute('slot_index');
    }

    #[Route('/slots', name: 'slot_index')]
    public function slots(): Response
    {
        $user = $this->getUser();
        if (! $user) {
            return $this->redirectToRoute('app_light_login');
        }

        // Utilisation du service optimisé pour éviter les requêtes N+1
        $slots = $this->slotService->getActiveSlotsWithReservations();

        return $this->render('slot/index.html.twig', [
            'slots' => $slots,
            'path' => 'slot_index',
            'user' => $user,
        ]);
    }

    #[Route('/slots/{id}/reserve', name: 'slot_reserve')]
    public function reserve(Slot $slot, Request $request): Response
    {
        $user = $this->getUser();
        if (! $user) {
            return $this->redirectToRoute('app_login');
        }

        // Vérification centralisée des règles métier
        $errors = $this->slotService->canUserReserveSlot($user, $slot);
        if (! empty($errors)) {
            foreach ($errors as $error) {
                $this->addFlash('danger', $error);
            }
            return $this->redirectToRoute('slot_index');
        }

        $reservation = new Reservation();
        $form = $this->formFactory->create(ReservationType::class, $reservation, [
            'parent' => $user,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                // Utilisation du service pour créer la réservation avec gestion des transactions
                $this->slotService->createReservation($user, $slot, $reservation);

                $this->addFlash('success', 'Réservation enregistrée : ' . $slot->getLabel());
                return $this->redirectToRoute('slot_index');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Une erreur est survenue lors de la réservation. Veuillez réessayer.');
            }
        }

        return $this->render('slot/reserve.html.twig', [
            'slot' => $slot,
            'form' => $form->createView(),
            'path' => 'slot_reserve',
            'user' => $user,
        ]);
    }
}
