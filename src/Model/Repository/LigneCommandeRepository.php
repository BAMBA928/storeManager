<?php

namespace src\Repository;

use src\Core\Database;
use src\Model\Entity\LigneCommande;

final class LigneCommandeRepository
{
    public function creer(LigneCommande $ligne): LigneCommande
    {
        $sql= 'INSERT INTO ligne_commande (commande_id, produit_id, quantite, prix_unitaire)
             VALUES (:commande_id, :produit_id, :quantite, :prix_unitaire)';
        $id = Database::executeUpdate(
           $sql,
            [
                'commande_id'   => $ligne->getCommandeId(),
                'produit_id'    => $ligne->getProduitId(),
                'quantite'      => $ligne->getQuantite(),
                'prix_unitaire' => $ligne->getPrixUnitaire(),
            ]
        );

        $ligne->definirId($id);

        return $ligne;
    }
}