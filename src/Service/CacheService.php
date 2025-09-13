<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Contracts\Cache\CacheInterface;

class CacheService
{
    public function __construct(
        private CacheInterface $cache
    ) {
    }

    /**
     * Met en cache les slots actifs avec leurs réservations
     */
    public function getCachedActiveSlots(callable $callback, int $ttl = 30): array
    {
        return $this->cache->get('active_slots_with_reservations', function () use ($callback) {
            return $callback();
        }, $ttl);
    }

    /**
     * Met en cache les statistiques d'un slot
     */
    public function getCachedSlotStats(int $slotId, callable $callback, int $ttl = 30): array
    {
        return $this->cache->get("slot_stats_{$slotId}", function () use ($callback) {
            return $callback();
        }, $ttl);
    }

    /**
     * Invalide le cache des slots
     */
    public function invalidateSlotsCache(): void
    {
        $this->cache->delete('active_slots_with_reservations');
    }

    /**
     * Invalide le cache des statistiques d'un slot
     */
    public function invalidateSlotStatsCache(int $slotId): void
    {
        $this->cache->delete("slot_stats_{$slotId}");
    }
}
