<?php

namespace src\Model\Entity;

class Appro
{
    public function __construct(private int $id,private int $fournisseurId,private int $statutApproId,private int $utilisateurId,private string $refBl,private \DateTime $dateAppro,private float $montantTotal) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getFournisseurId(): int
    {
        return $this->fournisseurId;
    }

    public function getStatutApproId(): int
    {
        return $this->statutApproId;
    }


    public function getRefBl(): string
    {
        return $this->refBl;
    }
}