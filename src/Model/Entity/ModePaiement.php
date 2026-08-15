<?php

namespace src\Model\Entity;

class ModePaiement
{
    public function __construct(
        private int $id,
        private string $libelle
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getLibelle(): string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): void
    {
        $this->libelle = $libelle;
    }
}