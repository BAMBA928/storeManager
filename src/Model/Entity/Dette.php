<?php

namespace src\Model\Entity;

class Dette
{
    private int $id;
    private int $commandeId;
    private int $clientId;
    private float $montantInitial;
    private float $montantRestant;
    private string $statut;

    public function __construct(int $id,int $commandeId,int $clientId,float $montantInitial,float $montantRestant,string $statut) {

        $this->id = $id;
        $this->commandeId = $commandeId;
        $this->clientId = $clientId;
        $this->montantInitial = $montantInitial;
        $this->montantRestant = $montantRestant;
        $this->statut = $statut;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCommandeId(): int
    {
        return $this->commandeId;
    }

    public function getClientId(): int
    {
        return $this->clientId;
    }

    public function getMontantInitial(): float
    {
        return $this->montantInitial;
    }

    public function getMontantRestant(): float
    {
        return $this->montantRestant;
    }

    public function getStatut(): string
    {
        return $this->statut;
    }


}