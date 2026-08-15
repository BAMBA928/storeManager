<?php

namespace src\Model\Entity;

class Utilisateur
{
    public function __construct(private int $id,private int $roleId,private string $nomComplet,private string $email,private string $motPasse ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoleId(): int
    {
        return $this->roleId;
    }

    public function getNomComplet(): string
    {
        return $this->nomComplet;
    }

    public function getEmail(): string
    {
        return $this->email;
    }


}