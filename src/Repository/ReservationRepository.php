<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Reservation;
use App\Entity\Slot;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    public function countActiveByUser(User $user, string $type): int
    {
        return (int) $this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->join('r.slot', 's')
            ->where('r.user = :user')
            ->andWhere('r.status IN (:states)')
            ->andWhere('s.type = :type')
            ->setParameter('user', $user)
            ->setParameter('states', ['SELECTIONNE', 'NON SELECTIONNE'])
            ->setParameter('type', $type)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function existsForUserAndSlot(User $user, Slot $slot): bool
    {
        $c = (int) $this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->where('r.user = :user')
            ->andWhere('r.slot = :slot')
            ->setParameter('user', $user)
            ->setParameter('slot', $slot)
            ->getQuery()
            ->getSingleScalarResult();
        return $c > 0;
    }

    public function countNonRejectedBySlot(Slot $slot): int
    {
        return (int) $this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->where('r.slot = :slot')
            ->andWhere('r.status != :rej')
            ->setParameter('slot', $slot)
            ->setParameter('rej', 'NON SELECTIONNE')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function rejectAllForSlot(Slot $slot): int
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $q = $qb->update(Reservation::class, 'r')
            ->set('r.status', ':rej')
            ->where('r.slot = :slot')
            ->setParameter('rej', 'NON SELECTIONNE')
            ->setParameter('slot', $slot)
            ->getQuery();
        return $q->execute();
    }

    public function findAllWithSlotAndUser(): array
    {
        return $this->createQueryBuilder('r')
            ->addSelect('s', 'u', 'c')
            ->leftJoin('r.slot', 's')
            ->leftJoin('r.user', 'u')
            ->leftJoin('r.child', 'c')
            ->getQuery()
            ->getResult();
    }
}
