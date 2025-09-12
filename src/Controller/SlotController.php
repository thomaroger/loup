<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Slot;
use App\Entity\Reservation;
use App\Form\ReservationType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use Symfony\Component\Form\FormFactoryInterface;

class SlotController extends AbstractController
{
    private FormFactoryInterface $formFactory;

    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        if($this->getUser() && $this->getUser()->hasRole('ROLE_ADMIN')) {
            return $this->redirectToRoute('admin_index');
        }
        return $this->redirectToRoute('slot_index');
    }

    #[Route('/slots', name: 'slot_index')]
    public function slots(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_light_login');
        }

        $slots = $em->getRepository(Slot::class)->findBy(['active' => true], ['startAt' => 'ASC']);
        return $this->render('slot/index.html.twig', ['slots' => $slots, 'path' => 'slot_index', 'user' => $user]);
    }

    #[Route('/slots/{id}/reserve', name: 'slot_reserve')]
    public function reserve(Slot $slot, Request $request, EntityManagerInterface $em, ReservationRepository $rr): Response
    {
        $user = $this->getUser();
        if (!$user) { return $this->redirectToRoute('app_login'); }

        if ($rr->countActiveByUser($user) >= 3) {
            $this->addFlash('danger', 'Vous avez déjà 3 réservations.');
            return $this->redirectToRoute('slot_index');
        }

        if ($rr->existsForUserAndSlot($user, $slot)) {
            $this->addFlash('danger', 'Vous avez déjà réservé ce créneau : ' . $slot->getLabel());
            return $this->redirectToRoute('slot_index');
        }

        $reservation = new Reservation();
        $form = $this->formFactory->create(ReservationType::class, $reservation, ['parent' => $user]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $others = $rr->countNonRejectedBySlot($slot);
            $reservation->setStatus($others > 0 ? 'NON SELECTIONNE' : 'SELECTIONNE');
            $reservation->setSlot($slot);
            $reservation->setUser($user);
            $em->persist($reservation);
            $em->flush();

            $this->addFlash('success', 'Réservation enregistrée : ' . $slot->getLabel());
            return $this->redirectToRoute('slot_index');
        }

        return $this->render('slot/reserve.html.twig', ['slot' => $slot, 'form' => $form->createView(), 'path' => 'slot_reserve', 'user' => $user]);
    }
}