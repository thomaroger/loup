<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Reservation;
use App\Entity\User;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class NotificationService
{
    public function __construct(
        private MailerInterface $mailer,
        private LoggerInterface $logger
    ) {
    }

    /**
     * Envoie une notification de réservation créée
     */
    public function sendReservationCreatedNotification(Reservation $reservation): void
    {
        $user = $reservation->getUser();
        $slot = $reservation->getSlot();

        $this->logger->info('Notification de réservation créée', [
            'reservation_id' => $reservation->getId(),
            'user_id' => $user->getId(),
            'slot_id' => $slot->getId(),
            'status' => $reservation->getStatus(),
        ]);

        // Ici vous pourriez ajouter l'envoi d'email
        // $this->sendEmail($user, 'Réservation créée', $this->getReservationEmailTemplate($reservation));
    }

    /**
     * Envoie une notification de réservation sélectionnée
     */
    public function sendReservationSelectedNotification(Reservation $reservation): void
    {
        $user = $reservation->getUser();
        $slot = $reservation->getSlot();

        $this->logger->info('Notification de réservation sélectionnée', [
            'reservation_id' => $reservation->getId(),
            'user_id' => $user->getId(),
            'slot_id' => $slot->getId(),
        ]);

        // Ici vous pourriez ajouter l'envoi d'email
        // $this->sendEmail($user, 'Réservation sélectionnée', $this->getSelectionEmailTemplate($reservation));
    }

    /**
     * Envoie une notification de réservation rejetée
     */
    public function sendReservationRejectedNotification(Reservation $reservation): void
    {
        $user = $reservation->getUser();
        $slot = $reservation->getSlot();

        $this->logger->info('Notification de réservation rejetée', [
            'reservation_id' => $reservation->getId(),
            'user_id' => $user->getId(),
            'slot_id' => $slot->getId(),
        ]);

        // Ici vous pourriez ajouter l'envoi d'email
        // $this->sendEmail($user, 'Réservation rejetée', $this->getRejectionEmailTemplate($reservation));
    }

    /**
     * Envoie un email (méthode privée pour l'implémentation future)
     */
    private function sendEmail(User $user, string $subject, string $body): void
    {
        $email = (new Email())
            ->from('noreply@loup.com')
            ->to($user->getEmail())
            ->subject($subject)
            ->html($body);

        try {
            $this->mailer->send($email);
        } catch (\Exception $e) {
            $this->logger->error('Erreur lors de l\'envoi d\'email', [
                'user_id' => $user->getId(),
                'subject' => $subject,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Génère le template email pour une réservation créée
     */
    private function getReservationEmailTemplate(Reservation $reservation): string
    {
        $slot = $reservation->getSlot();
        $user = $reservation->getUser();

        return "
            <h2>Réservation créée</h2>
            <p>Bonjour {$user->getName()},</p>
            <p>Votre réservation pour le créneau <strong>{$slot->getLabel()}</strong> a été créée avec succès.</p>
            <p>Statut : <strong>{$reservation->getStatus()}</strong></p>
            <p>Date : {$slot->getStartAt()
            ->format('d/m/Y H:i')}</p>
        ";
    }

    /**
     * Génère le template email pour une réservation sélectionnée
     */
    private function getSelectionEmailTemplate(Reservation $reservation): string
    {
        $slot = $reservation->getSlot();
        $user = $reservation->getUser();

        return "
            <h2>Réservation sélectionnée</h2>
            <p>Bonjour {$user->getName()},</p>
            <p>Félicitations ! Votre réservation pour le créneau <strong>{$slot->getLabel()}</strong> a été sélectionnée.</p>
            <p>Date : {$slot->getStartAt()
            ->format('d/m/Y H:i')}</p>
        ";
    }

    /**
     * Génère le template email pour une réservation rejetée
     */
    private function getRejectionEmailTemplate(Reservation $reservation): string
    {
        $slot = $reservation->getSlot();
        $user = $reservation->getUser();

        return "
            <h2>Réservation rejetée</h2>
            <p>Bonjour {$user->getName()},</p>
            <p>Votre réservation pour le créneau <strong>{$slot->getLabel()}</strong> n'a pas été sélectionnée.</p>
            <p>Date : {$slot->getStartAt()
            ->format('d/m/Y H:i')}</p>
        ";
    }
}
