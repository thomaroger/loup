<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\Slot;
use App\Repository\UserRepository;
use App\Service\ReservationService;
use App\Service\SlotService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    public function __construct(
        private SlotService $slotService,
        private ReservationService $reservationService
    ) {
    }

    #[Route('/admin', name: 'admin_index')]
    public function index(): Response
    {
        return $this->redirectToRoute('admin_slots');
    }

    #[Route('/admin/slots', name: 'admin_slots')]
    public function slots(): Response
    {
        $user = $this->getUser();
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Utilisation du service optimisé
        $slots = $this->slotService->getActiveSlotsWithReservations();

        return $this->render('admin/slots.html.twig', [
            'slots' => $slots,
            'path' => 'admin_slots',
            'user' => $user,
        ]);
    }

    #[Route('/admin/slots/{id}/arbitre', name: 'admin_slot_arbitre')]
    public function arbitreSlot(Slot $slot): Response
    {
        $user = $this->getUser();
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Utilisation du service optimisé
        $reservations = $this->reservationService->getReservationsForSlot($slot);

        return $this->render('admin/slot_arbitre.html.twig', [
            'slot' => $slot,
            'reservations' => $reservations,
            'path' => 'admin_slot_arbitre',
            'user' => $user,
        ]);
    }

    #[Route('/admin/reservations/{id}/choose', name: 'admin_choose_reservation', methods: ['POST'])]
    public function chooseReservation(Reservation $reservation): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        try {
            // Utilisation du service pour l'arbitrage avec gestion des transactions
            $this->reservationService->chooseReservation($reservation);

            $this->addFlash('success', 'Arbitrage fait pour le créneau : ' . $reservation->getSlot()->getLabel());
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Une erreur est survenue lors de l\'arbitrage. Veuillez réessayer.');
        }

        return $this->redirectToRoute('admin_slots');
    }

    #[Route('/admin/export', name: 'admin_export')]
    public function export(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $response = new StreamedResponse(function () {
            $handle = fopen('php://output', 'w');

            // Utilisation du service optimisé pour l'export
            foreach ($this->reservationService->exportReservationsToCsv() as $row) {
                fputcsv($handle, $row);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="reservations.csv"');

        return $response;
    }

    #[Route('/admin/parents', name: 'admin_parents')]
    public function parents(UserRepository $userRepository): Response
    {
        $user = $this->getUser();
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $parents = $userRepository->getParents();

        return $this->render('admin/parents.html.twig', [
            'parents' => $parents,
            'user' => $user,
            'path' => 'admin_parents',
        ]);
    }
}
