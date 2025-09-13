<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class SecurityService
{
    public function __construct(
        private LoggerInterface $logger
    ) {
    }

    /**
     * Valide et nettoie les données d'entrée
     */
    public function sanitizeInput(string $input, int $maxLength = 255): string
    {
        $sanitized = trim($input);
        $sanitized = htmlspecialchars($sanitized, ENT_QUOTES, 'UTF-8');

        if (strlen($sanitized) > $maxLength) {
            $sanitized = substr($sanitized, 0, $maxLength);
        }

        return $sanitized;
    }

    /**
     * Valide un email
     */
    public function isValidEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Valide un nom (lettres, espaces, tirets, apostrophes)
     */
    public function isValidName(string $name): bool
    {
        return preg_match('/^[a-zA-ZÀ-ÿ\s\-\']+$/', $name) === 1;
    }

    /**
     * Vérifie les tentatives de connexion suspectes
     */
    public function checkSuspiciousActivity(Request $request, User $user = null): void
    {
        $ip = $request->getClientIp();
        $userAgent = $request->headers->get('User-Agent');

        // Log des tentatives de connexion
        $this->logger->info('Tentative de connexion', [
            'ip' => $ip,
            'user_agent' => $userAgent,
            'user_id' => $user?->getId(),
            'timestamp' => new \DateTimeImmutable(),
        ]);
    }

    /**
     * Vérifie les permissions d'accès
     */
    public function checkAccess(User $user, string $requiredRole): void
    {
        if (! $user->hasRole($requiredRole)) {
            $this->logger->warning('Tentative d\'accès non autorisé', [
                'user_id' => $user->getId(),
                'required_role' => $requiredRole,
                'user_roles' => $user->getRoles(),
            ]);

            throw new AccessDeniedException('Accès refusé');
        }
    }

    /**
     * Génère un token CSRF simple
     */
    public function generateCsrfToken(): string
    {
        return bin2hex(random_bytes(32));
    }

    /**
     * Valide un token CSRF
     */
    public function validateCsrfToken(string $token, string $sessionToken): bool
    {
        return hash_equals($sessionToken, $token);
    }
}
