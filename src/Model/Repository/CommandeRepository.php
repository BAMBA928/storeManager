<?php

namespace src\Repository;

use src\Core\Database;
use src\Model\Entity\Commande;

final class CommandeRepository
{
    public function creer(Commande $commande): Commande
    {
        $sql=  'INSERT INTO commande (client_id, utilisateur_id, mode_paiement_id, dat_commande, montant_total, avance, est_credit)
             VALUES (:client_id, :utilisateur_id, :mode_paiement_id, :dat_commande, :montant_total, :montant_paye, :est_credit)';
        $id = Database::executeUpdate(
          $sql,
            [
                'client_id'        => $commande->getClientId(),
                'utilisateur_id'   => $commande->getUtilisateurId(),
                'mode_paiement_id' => $commande->getModePaiementId(),
                'dat_commande'     => $commande->getDateCommande()->format('Y-m-d H:i:s'),
                'montant_total'    => $commande->getMontantTotal(),
                'montant_paye'     => $commande->getMontantPaye(),
                'est_credit'       => $commande->estCredit() ? 1 : 0,
            ]
        );

        $commande->definirId($id);

        return $commande;
    }
}