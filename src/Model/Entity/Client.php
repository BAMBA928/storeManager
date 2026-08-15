<?php

namespace src\Model\Entity;

class Client
{
    private int $id;
    private string $nom;
    private string $prenom;
    private string $email;
    private string $telephone;
    private float $limiteCredit;

    public function __construct(int $id,string $nom,string $prenom,string $email,string $telephone,float $limiteCredit = 0) {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->telephone = $telephone;
        $this->limiteCredit = $limiteCredit;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getTel(): string
    {
        return $this->telephone;
    }

    public function getLimiteCredit(): float
    {
        return $this->limiteCredit;
    }
       public function definirId(int $id): void
    {
        $this->id = $id;
    }

}