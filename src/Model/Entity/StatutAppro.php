<?php

namespace src\Model\Entity;

class StatutAppro
{
    public function __construct(
        private int $id,
        private string $nom
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }
}