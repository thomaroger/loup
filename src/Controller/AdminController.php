<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Slot;
use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminController extends AbstractController
{

    #[Route('/admin', name: 'admin_index')]
    public function index(): Response
    {
        return $this->redirectToRoute('admin_slots');
    }

    #[Route('/admin/slots', name: 'admin_slots')]
    public function slots(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $slots = $em->getRepository(Slot::class)->findBy([], ['startAt' => 'ASC']);
        return $this->render('admin/slots.html.twig', ['slots' => $slots, 'path' => 'admin_slots', 'user' => $user]);
    }

    #[Route('/admin/slots/{id}/arbitre', name: 'admin_slot_arbitre')]
    public function arbitreSlot(Slot $slot, ReservationRepository $rr): Response
    {
        $user = $this->getUser();
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $reservations = $rr->createQueryBuilder('r')
            ->andWhere('r.slot = :slot')
            ->setParameter('slot', $slot)
            ->leftJoin('r.user','u')
            ->addSelect('u')
            ->getQuery()
            ->getResult();

        return $this->render('admin/slot_arbitre.html.twig', ['slot' => $slot, 'reservations' => $reservations, 'path' => 'admin_slot_arbitre', 'user' => $user]);
    }

    #[Route('/admin/reservations/{id}/choose', name: 'admin_choose_reservation', methods:['POST'])]
    public function chooseReservation(Reservation $reservation, EntityManagerInterface $em, ReservationRepository $rr): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $slot = $reservation->getSlot();

        $em->getConnection()->beginTransaction();
        try {
            $rr->rejectAllForSlot($slot);
            $reservation->setStatus('SELECTIONNE');
            $em->flush();
            $em->getConnection()->commit();
        } catch (\Exception $e) {
            $em->getConnection()->rollBack();
            throw $e;
        }

        $this->addFlash('success', 'Arbitrage fait pour le créneau : ' . $slot->getLabel());
        return $this->redirectToRoute('admin_slots');
    }

    #[Route('/admin/export', name: 'admin_export')]
    public function export(ReservationRepository $rr): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $rows = $rr->findAllWithSlotAndUser();

        $response = new StreamedResponse(function() use ($rows) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Slot','Start','End','Parent','Child','Justification','Status']);
            foreach ($rows as $r) {
                fputcsv($handle, [
                    $r->getSlot()->getLabel(),
                    $r->getSlot()->getStartAt()->format('Y-m-d H:i'),
                    $r->getSlot()->getEndAt()->format('Y-m-d H:i'),
                    $r->getUser()->getEmail(),
                    $r->getChild()?->getFirstName(),
                    $r->getJustification(),
                    $r->getStatus(),
                ]);
            }
            fclose($handle);
        });

        $response->headers->set('Content-Type','text/csv');
        $response->headers->set('Content-Disposition','attachment; filename="reservations.csv"');

        return $response;
    }
}