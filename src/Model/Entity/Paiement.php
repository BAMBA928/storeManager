<?php

namespace src\Model\Entity;

final class Paiement
{
    public function __construct(private ?int $id,private int $detteId,private int $modePaiementId,private int $utilisateurId,private \DateTime $datePaiement,private float $montant) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function definirId(int $id): void
    {
        $this->id = $id;
    }

    public function getDetteId(): int
    {
        return $this->detteId;
    }

    public function getMontant(): float
    {
        return $this->montant;
    }
}