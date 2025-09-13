<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Reservation;
use App\Entity\Slot;
use App\Entity\User;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class ReservationService
{
    public function __construct(
        private EntityManagerInterface $em,
        private ReservationRepository $reservationRepository,
        private LoggerInterface $logger
    ) {
    }

    /**
     * Récupère toutes les réservations avec leurs relations en une seule requête
     */
    public function getAllReservationsWithDetails(): array
    {
        return $this->reservationRepository->createQueryBuilder('r')
            ->addSelect('s', 'u', 'c')
            ->leftJoin('r.slot', 's')
            ->leftJoin('r.user', 'u')
            ->leftJoin('r.child', 'c')
            ->orderBy('s.startAt', 'ASC')
            ->addOrderBy('r.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupère les réservations d'un slot avec les détails utilisateur
     */
    public function getReservationsForSlot(Slot $slot): array
    {
        return $this->reservationRepository->createQueryBuilder('r')
            ->addSelect('u', 'c')
            ->leftJoin('r.user', 'u')
            ->leftJoin('r.child', 'c')
            ->where('r.slot = :slot')
            ->setParameter('slot', $slot)
            ->orderBy('r.createdAt', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Choisit une réservation (arbitrage admin)
     */
    public function chooseReservation(Reservation $reservation): void
    {
        $slot = $reservation->getSlot();

        $this->em->getConnection()
            ->beginTransaction();

        try {
            // Rejeter toutes les autres réservations du slot
            $this->reservationRepository->rejectAllForSlot($slot);

            // Sélectionner la réservation choisie
            $reservation->setStatus('SELECTIONNE');

            $this->em->flush();
            $this->em->getConnection()
                ->commit();

            $this->logger->info('Réservation choisie par arbitrage', [
                'reservation_id' => $reservation->getId(),
                'slot_id' => $slot->getId(),
                'user_id' => $reservation->getUser()
                    ->getId(),
            ]);

        } catch (\Exception $e) {
            $this->em->getConnection()
                ->rollBack();
            $this->logger->error('Erreur lors de l\'arbitrage', [
                'reservation_id' => $reservation->getId(),
                'slot_id' => $slot->getId(),
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Exporte les réservations en format CSV
     */
    public function exportReservationsToCsv(): \Generator
    {
        $reservations = $this->getAllReservationsWithDetails();

        // En-têtes CSV
        yield ['Slot', 'Start', 'End', 'Parent', 'Child', 'Justification', 'Status'];

        foreach ($reservations as $reservation) {
            yield [
                $reservation->getSlot()
                    ->getLabel(),
                $reservation->getSlot()
                    ->getStartAt()
                    ->format('Y-m-d H:i'),
                $reservation->getSlot()
                    ->getEndAt()
                    ->format('Y-m-d H:i'),
                $reservation->getUser()
                    ->getEmail(),
                $reservation->getChild()?->getFirstName() ?? '',
                $reservation->getJustification() ?? '',
                $reservation->getStatus(),
            ];
        }
    }

    /**
     * Récupère les statistiques des réservations par utilisateur
     */
    public function getUserReservationStats(User $user): array
    {
        $stats = $this->reservationRepository->createQueryBuilder('r')
            ->select('s.type', 'COUNT(r.id) as count')
            ->join('r.slot', 's')
            ->where('r.user = :user')
            ->andWhere('r.status IN (:statuses)')
            ->setParameter('user', $user)
            ->setParameter('statuses', ['SELECTIONNE', 'NON SELECTIONNE'])
            ->groupBy('s.type')
            ->getQuery()
            ->getResult();

        $result = [];
        foreach ($stats as $stat) {
            $result[$stat['type']] = (int) $stat['count'];
        }

        return $result;
    }
}
