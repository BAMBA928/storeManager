<?php

namespace src\Repository;

use src\Core\Database;
use src\Model\Entity\Dette;

final class DetteRepository
{

    public function sommeDetteNonSoldeeParClient(int $clientId): float
    {
        $ligne = Database::executeQuery(
            "SELECT COALESCE(SUM(montant_restant), 0) AS total
             FROM dette
             WHERE client_id = :client_id AND statut = 'NON_SOLDEE'",
            ['client_id' => $clientId],
            true
        );

        return (float) ($ligne['total'] ?? 0);
    }

    public function creer(Dette $dette): Dette
    {
        $id = Database::executeUpdate(
            'INSERT INTO dette (commande_id, client_id, montant_initial, montant_restant, statut, date_echeance)
             VALUES (:commande_id, :client_id, :montant_initial, :montant_restant, :statut)',
            [
                'commande_id'     => $dette->getCommandeId(),
                'client_id'       => $dette->getClientId(),
                'montant_initial' => $dette->getMontantInitial(),
                'montant_restant' => $dette->getMontantRestant(),
                'statut'          => $dette->getStatut()
            ]
        );

        $dette->definirId($id);

        return $dette;
    }
}