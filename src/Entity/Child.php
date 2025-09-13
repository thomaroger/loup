<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints;

#[ORM\Entity]
class Child
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 100, unique: true)]
    #[UniqueEntity(fields: ['firstName'], message: 'Cet enfant est déjà inscrit.')]
    private ?string $firstName = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $parent = null;

    public function getId(): ?int { return $this->id; }
    public function getFirstName(): ?string { return $this->firstName; }
    public function setFirstName(string $n): self { $this->firstName = $n; return $this; }
    public function getParent(): ?User { return $this->parent; }
    public function setParent(?User $p): self { $this->parent = $p; return $this; }
}