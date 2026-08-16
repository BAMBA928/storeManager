<?php

namespace src\Model\Entity;

class Commande
{
    private ?int $id;
    private int $clientId;
    private int $utilisateurId;
    private int $modePaiementId;
    private \DateTime $dateCommande;
    private float $montantTotal;
    private float $montantPaye;
    private bool $estCredit;

    public function __construct( int $id,int $clientId,int $utilisateurId,int $modePaiementId,\DateTime $dateCommande,float $montantTotal,float $montantPaye = 0,bool $estCredit = false) {
 

        $this->id = $id;
        $this->clientId = $clientId;
        $this->utilisateurId = $utilisateurId;
        $this->modePaiementId = $modePaiementId;
        $this->dateCommande = $dateCommande;
        $this->montantTotal = $montantTotal;
        $this->montantPaye = $montantPaye;
        $this->estCredit = $estCredit;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getClientId(): int
    {
        return $this->clientId;
    }

    public function getUtilisateurId(): int
    {
        return $this->utilisateurId;
    }

    public function getModePaiementId(): int
    {
        return $this->modePaiementId;
    }

    public function getDateCommande(): \DateTime
    {
        return $this->dateCommande;
    }

    public function getMontantTotal(): float
    {
        return $this->montantTotal;
    }

    public function getMontantPaye(): float
    {
        return $this->montantPaye;
    }

    public function estCredit(): bool
    {
        return $this->estCredit;
    }

    public function getMontantRestant(): float
    {
        return $this->montantTotal - $this->montantPaye;
    }
  public function definirId(int $id): void
    {
        $this->id = $id;
    }

}