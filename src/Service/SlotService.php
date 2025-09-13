<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Reservation;
use App\Entity\Slot;
use App\Entity\User;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class SlotService
{
    public function __construct(
        private EntityManagerInterface $em,
        private ReservationRepository $reservationRepository,
        private LoggerInterface $logger
    ) {
    }

    /**
     * Récupère tous les slots actifs avec leurs réservations en une seule requête
     */
    public function getActiveSlotsWithReservations(): array
    {
        return $this->em->createQueryBuilder()
            ->select('s', 'r', 'u', 'c')
            ->from(Slot::class, 's')
            ->leftJoin('s.reservations', 'r')
            ->leftJoin('r.user', 'u')
            ->leftJoin('r.child', 'c')
            ->where('s.active = :active')
            ->setParameter('active', true)
            ->orderBy('s.startAt', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Vérifie si un utilisateur peut réserver un slot
     */
    public function canUserReserveSlot(User $user, Slot $slot): array
    {
        $errors = [];

        // Vérifier si l'utilisateur a déjà 3 réservations sur ce type de slot
        $activeReservationsCount = $this->reservationRepository->countActiveByUser($user, $slot->getType());
        if ($activeReservationsCount >= 3) {
            $errors[] = 'Vous avez déjà 3 réservations sur un ' . strtolower($slot->getType());
        }

        // Vérifier si l'utilisateur a déjà réservé ce slot
        if ($this->reservationRepository->existsForUserAndSlot($user, $slot)) {
            $errors[] = 'Vous avez déjà réservé ce créneau : ' . $slot->getLabel();
        }

        return $errors;
    }

    /**
     * Crée une réservation pour un slot
     */
    public function createReservation(User $user, Slot $slot, Reservation $reservation): void
    {
        $this->em->getConnection()
            ->beginTransaction();

        try {
            // Vérifier le nombre de réservations non rejetées pour ce slot
            $othersCount = $this->reservationRepository->countNonRejectedBySlot($slot);
            $reservation->setStatus('NON SELECTIONNE');

            $reservation->setSlot($slot);
            $reservation->setUser($user);

            $this->em->persist($reservation);
            $this->em->flush();
            $this->em->getConnection()
                ->commit();

            $this->logger->info('Réservation créée', [
                'user_id' => $user->getId(),
                'slot_id' => $slot->getId(),
                'status' => $reservation->getStatus(),
            ]);

        } catch (\Exception $e) {
            $this->em->getConnection()
                ->rollBack();
            $this->logger->error('Erreur lors de la création de réservation', [
                'user_id' => $user->getId(),
                'slot_id' => $slot->getId(),
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Récupère les statistiques d'un slot
     */
    public function getSlotStats(Slot $slot): array
    {
        $reservations = $this->reservationRepository->createQueryBuilder('r')
            ->select('COUNT(r.id) as total', 'COUNT(CASE WHEN r.status = \'SELECTIONNE\' THEN 1 END) as selected')
            ->where('r.slot = :slot')
            ->setParameter('slot', $slot)
            ->getQuery()
            ->getSingleResult();

        return [
            'total_reservations' => (int) $reservations['total'],
            'selected_reservations' => (int) $reservations['selected'],
        ];
    }
}
