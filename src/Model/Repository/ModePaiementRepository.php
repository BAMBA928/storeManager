<?php
namespace src\Model\Repository;
use src\Core\Database;

use src\Model\Entity\ModePaiement;
class ModePaiementRepository {
    public function trouverTous(): array
    {
        $sql = "SELECT * FROM ModePaiement ORDER BY libelle";

        $lignes = Database::executeQuery($sql, [], false);

        $ModePaiements = [];


        foreach ($lignes as $ligne) {
            $ModePaiements[] = new ModePaiement(
                id: (int) $ligne['id'],
                libelle: $ligne['libelle']
 
            );

        }

        return $ModePaiements;
    }
}