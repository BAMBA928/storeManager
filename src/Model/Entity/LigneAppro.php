<?php

namespace src\Model\Entity;

final class LigneAppro
{
    public function __construct(private int $id,private int $approId,private int $produitId,private int $qteAppro,private int $qteRecu,private float $prixReel) {
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

    public function getQteAppro(): int
    {
        return $this->qteAppro;
    }

    public function getQteRecu(): int
    {
        return $this->qteRecu;
    }



    public function calculerSousTotal(): float
    {
        return $this->qteRecu * $this->prixReel;
    }

    public function ecartQuantite(): int
    {
        return $this->qteRecu - $this->qteAppro;
    }
}