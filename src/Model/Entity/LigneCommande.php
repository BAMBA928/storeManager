<?php

namespace src\Model\Entity;

final class LigneCommande
{
    public function __construct(private ?int $id,private int $commandeId,private int $produitId,private int $quantite,private float $prixUnitaire) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function definirId(int $id): void
    {
        $this->id = $id;
    }

    public function getProduitId(): int
    {
        return $this->produitId;
    }

    public function getQuantite(): int
    {
        return $this->quantite;
    }

        public function getcommandeId(): int
    {
        return $this->commandeId;
    }

          public function getprixUnitaire(): int
    {
        return $this->prixUnitaire;
    }

    public function calculerSousTotal(): float
    {
        return $this->quantite * $this->prixUnitaire;
    }
}