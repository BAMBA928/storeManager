<?php

namespace src\Model\Entity;

final class Fournisseur
{
    public function __construct(private int $id,private string $nomComplet,private string $email,private string $telephone) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getNomComplet(): string
    {
        return $this->nomComplet;
    }

    public function getTel(): string
    {
        return $this->telephone;
    }

        public function getEmail(): string
    {
        return $this->email;
    }

       public function definirId(int $id): void
    {
        $this->id = $id;
    }

}