<?php

namespace src\Service;

use PDO;
use src\Core\Database;
use src\Model\Repository\ProduitRepository;

class VenteService
{
    public function __construct(
        private PDO $pdo,
        private ProduitRepository $produitRepository
    ) {
    }

    public function enregistrerVente(array $panier,int $clientId,int $modePaiementId,int $utilisateurId,float $montantPaye): int {

        // je verifie si le panier  contient au moins un produit
        if (empty($panier)) {
            throw new \InvalidArgumentException(
                'Le panier ne peut pas être vide.'
            );
        }

        // je  Vérifie aussi si  les quantités il sont pas null ou negatif
        foreach ($panier as $ligne) {
            if ($ligne['quantite'] <= 0) {
                throw new \InvalidArgumentException(
                    'La quantité doit être supérieure à zéro.'
                );
            }
        }

  //je commence maintenant la transaction
        $this->pdo->beginTransaction();

        try {

            $montantTotal = 0;
            $produitsVendus = [];

            //je verifie les produits,  vérifie le stock  et calculer le total.
             
            foreach ($panier as $ligne) {

                $produit = $this->produitRepository->trouverParIdPourVente( (int) $ligne['produit_id']);

                if ($produit === null) {
                    throw new \InvalidArgumentException(
                        'Le produit que vous avez demandé n’existe pas.'
                    );
                }

                $quantite = (int) $ligne['quantite'];

                if (!$produit->estDisponible($quantite)) {
                    throw new \InvalidArgumentException(
                        'Stock insuffisant pour le produit : '
                        
                    );
                }

                $prixUnitaire = $produit->getPrixVente();

                $sousTotal = $prixUnitaire * $quantite;

                $montantTotal += $sousTotal;

                // si tous se passe bien je garde dans une tableau $produitsVendus   les informations nécessaires du produit pour que je puis créer les lignes après la commande.
                 
                $produitsVendus[] = [
                    'produit' => $produit,
                    'quantite' => $quantite,
                    'prix_unitaire' => $prixUnitaire,
                    'sous_total' => $sousTotal
                ];
            }

            // je verifie maitenant le montant payé si il respecte les norme negatif ou si le montant paye n'est pas supeieur au montant total .
             
            if ( $montantPaye < 0 || $montantPaye > $montantTotal ) {
                throw new \InvalidArgumentException(
                    'Le montant payé est invalide.'
                );
            }

            //  je Calcule le montant restant pour cherche si le client cree une dette ou pas  il a une credit si le resultat du montantRestant est positif .
             
            $montantRestant = $montantTotal - $montantPaye;

            $estCredit = $montantRestant > 0;

            // je  Vérifie aussi si la limite de crédit est atteint si le peut encore de la ligne 96 à 120 crée une dette.
             
            if ($estCredit) {

                $sqlClient = " SELECT limite_credit FROM client WHERE id = :id";
                $client = Database::executeQuery( $sqlClient, ['id' => $clientId], true);

                if (empty($client)) {
                    throw new \InvalidArgumentException(
                        'Le client que vous demandé n’existe pas.'
                    );
                }
                 $limiteCredit = (float) $client['limite_credit'];

                $sqlDettes = "SELECT COALESCE(SUM(montant_restant),  0 ) AS total_dettes FROM dette WHERE client_id = :client_id ";

                $resultatDettes = Database::executeQuery( $sqlDettes,['client_id' => $clientId],true);

                 $dettesActuelles = (float) $resultatDettes['total_dettes'];

                if ( $dettesActuelles + $montantRestant > $limiteCredit
                ) {
                    throw new \InvalidArgumentException(
                        'La limite de crédit du client est dépassée.'
                    );
                }
            }

            //si tous ce passe bien je  Crée la commande pour ce client .
             
            $sqlCommande = 
              " INSERT INTO commande ( client_id,utilisateur_id,mode_paiement_id,date_commande,montant_total,montant_paye,est_credit)
                VALUES (:client_id,:utilisateur_id,:mode_paiement_id,CURRENT_TIMESTAMP,:montant_total,:montant_paye,:est_credit)";

            $commandeId = Database::executeUpdate( $sqlCommande,
                [
                    'client_id' => $clientId,
                    'utilisateur_id' => $utilisateurId,
                    'mode_paiement_id' => $modePaiementId,
                    'montant_total' => $montantTotal,
                    'montant_paye' => $montantPaye,
                    'est_credit' => $estCredit
                ]
            );

            //maitenant apres enrgister ce commande  je Crée  les lignes de commande et aussi mettre à jour le stock j'avais parcourus ligne de produit qui sont dans produitsVendus et l'insert  pour ce commande .
             
            foreach ($produitsVendus as $vente) {

                 $produit = $vente['produit'];

                $sqlLigne = "INSERT INTO ligne_commande (commande_id,produit_id,quantite,prix_unitaire,sous_total)
                    VALUES (:commande_id,:produit_id,:quantite,:prix_unitaire,:sous_total)";
                Database::executeUpdate( $sqlLigne,
                    [
                        'commande_id' => $commandeId,
                        'produit_id' => $produit->getId(),
                        'quantite' => $vente['quantite'],
                        'prix_unitaire' => $vente['prix_unitaire'],
                        'sous_total' => $vente['sous_total']
                    ]
                );

                $nouveauStock = $produit->getStock() - $vente['quantite'];

                $produit->setStock($nouveauStock);

                $this->produitRepository ->mettreAJourStock($produit);
            }

            // et enfin je  Crée la dette uniquement  si le paiement est incomplet par exemple estcredit n'est pas vide.
             
            if ($estCredit) {

                $sqlDette = "INSERT INTO dette (commande_id,client_id,montant_initial,montant_restant,statut)
                    VALUES ( :commande_id,:client_id,:montant_initial,:montant_restant,:statut)";
                Database::executeUpdate( $sqlDette,
                    [
                        'commande_id' => $commandeId,
                        'client_id' => $clientId,
                        'montant_initial' => $montantRestant,
                        'montant_restant' => $montantRestant,
                        'statut' => 'EN_COURS'
                    ]
                );
            }

            //si  Toutes ces  opérations ont réussi on commit.
             
            $this->pdo->commit();

            return $commandeId;

        } catch (\Throwable $exception) {

            // s'il a une erreur annule toute la vente.
             
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            throw $exception;
        }
    }
}