<?php

namespace src\Model\Repository;

use src\Model\Entity\Produit;
use src\Core\Database;


class ProduitRepository
{
   

    public function trouverTous(): array
    {
        $sql = "SELECT * FROM produit ORDER BY libelle";

        $lignes = Database::executeQuery($sql, [], false);

        $produits = [];

        foreach ($lignes as $ligne) {
            $produits[] = new Produit(
                id: (int) $ligne['id'],
                libelle: $ligne['libelle'],
                prixVente: (float) $ligne['prix_vente'],
                stock: (int) $ligne['stock'],
                seuilAlert: (int) $ligne['seuil_alert'],
                
            );
                // var_dump($ligne['seuil_alert']);
        }


        return $produits;
    }

    public function trouverParId(int $id): ?Produit
    {

        $sql = "SELECT * FROM produit WHERE id = :id";

        $ligne = Database::executeQuery($sql, ['id' => $id], true);
           if (empty($ligne)) {
            return null;
        }
        $produits = new Produit(
            id: (int) $ligne['id'],
            libelle: $ligne['libelle'],
            prixVente: (float) $ligne['prix_vente'],
            stock: (int) $ligne['stock'],
            seuilAlert: (int) $ligne['seuil_alert']
        );
        return $produits;
    }


    public function creer(Produit $produit): Produit
    {
        $sql= 'INSERT INTO produit (libelle, prix_vente, stock, seuil_alert) VALUES (:libelle, :prix_vente, :stock, :seuilAlert)';
        $id = Database::executeUpdate($sql,
            [
                'libelle' => $produit->getLibelle(),
                'prix_vente' => $produit->getPrixVente(),
                'stock' => $produit->getStock(),
                'seuil_alert' => $produit->getSeuilAlert(),
            ]
        );
        $produit->definirId($id);
        return $produit;
    }


    public function mettreAJourStock(Produit $produit): void
    {
        $sql='UPDATE produit SET stock = :stock WHERE id = :id';
        Database::executeUpdate(  $sql, ['stock' => $produit->getStock(), 'id' => $produit->getId()]
        );
    }
    public function trouverParIdPourVente(int $id): ?Produit
{
    $sql = " SELECT * FROM produit WHERE id = :id";

    $ligne = Database::executeQuery( $sql,['id' => $id], true );

    if (empty($ligne)) {
        return null;
    }

    return new Produit(
        id: (int) $ligne['id'],
        libelle: $ligne['libelle'],
        prixVente: (float) $ligne['prix_vente'],
        stock: (int) $ligne['stock'],
        seuilAlert: (int) $ligne['seuil_alert']
    );
}


}


// private int $id;
// private string $libelle;
// private float $prixVente;
// private int $stock;
// private int $seuilAlert;