<?php

namespace src\Model\Entity;

class Produit
{
    private int $id;
    private string $libelle;
    private float $prixVente;
    private int $stock;
    private int $seuilAlert;

    public function __construct(int $id,string $libelle,float $prixVente,int $stock = 0,int $seuilAlert = 0) {
        $this->id = $id;
        $this->libelle = $libelle;
        $this->prixVente = $prixVente;
        $this->stock = $stock;
        $this->seuilAlert = $seuilAlert;
    }

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

    public function getPrixVente(): float
    {
        return $this->prixVente;
    }

    public function setPrixVente(float $prixVente): void
    {

        $this->prixVente = $prixVente;
    }

    public function getStock(): int
    {
        return $this->stock;
    }

    public function setStock(int $stock): void
    {


        $this->stock = $stock;
    }

    public function getSeuilAlert(): int
    {
        return $this->seuilAlert;
    }

    public function setSeuilAlert(int $seuilAlert): void
    {

        $this->seuilAlert = $seuilAlert;
    }

    public function estDisponible(int $quantite): bool
    {
        return $quantite  <= $this->stock;
    }






}