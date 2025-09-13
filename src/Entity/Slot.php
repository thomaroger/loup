<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Slot
{
    public const TYPE_MERCREDI = 'mercredi';

    public const TYPE_WEEKEND = 'weekend';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $label = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $startAt = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $endAt = null;

    #[ORM\Column(type: 'boolean')]
    private bool $active = true;

    #[ORM\OneToMany(mappedBy: 'slot', targetEntity: \App\Entity\Reservation::class, orphanRemoval: true)]
    private Collection $reservations;

    #[ORM\Column(length: 20)]
    private ?string $type = null;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $l): self
    {
        $this->label = $l;
        return $this;
    }

    public function getStartAt(): ?\DateTimeInterface
    {
        return $this->startAt;
    }

    public function setStartAt(\DateTimeInterface $d): self
    {
        $this->startAt = $d;
        return $this;
    }

    public function getEndAt(): ?\DateTimeInterface
    {
        return $this->endAt;
    }

    public function setEndAt(\DateTimeInterface $d): self
    {
        $this->endAt = $d;
        return $this;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $b): self
    {
        $this->active = $b;
        return $this;
    }

    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function getSeletedReservation(): string
    {
        if ($this->getReservations()->count() === 1) {
            return $this->getReservations()
                ->first()
                ->getUser()
                ->getName();
        }
        $reservations = $this->getReservations()
            ->filter(function (Reservation $r) {
                return $r->getStatus() === 'SELECTIONNE';
            });
        if (empty($reservations) || $reservations->count() === 0) {
            return '';
        }
        return $reservations->first()
            ->getUser()
            ->getName();
    }

    public static function getTypes(): array
    {
        return [
            'Mercredi' => self::TYPE_MERCREDI,
            'Weekend' => self::TYPE_WEEKEND,
        ];
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
}
