<?php

namespace src\Service;

use src\Core\Database;
use src\Model\Entity\Commande;
use src\Model\Entity\Dette;
use src\Model\Entity\LigneCommande;

use src\Repository\ClientRepository;
use src\Repository\CommandeRepository;
use src\Repository\DetteRepository;
use src\Repository\LigneCommandeRepository;
use src\Repository\ProduitRepository;

final class VenteService
{

    private ProduitRepository $produitRepository;
    private ClientRepository $clientRepository;
    private CommandeRepository $commandeRepository;
    private LigneCommandeRepository $ligneCommandeRepository;
    private DetteRepository $detteRepository;

    public function __construct()
    {
        $this->produitRepository = new ProduitRepository();
        $this->clientRepository = new ClientRepository();
        $this->commandeRepository = new CommandeRepository();
        $this->ligneCommandeRepository = new LigneCommandeRepository();
        $this->detteRepository = new DetteRepository();
    }

    public function validerVente(
        int $clientId,
        int $utilisateurId,
        int $modePaiementId,
        array $lignesPanier,
        float $avance
    ): Commande {
        if (empty($lignesPanier)) {
            throw new \RuntimeException('Le panier est vide.');
        }

        $pdo = Database::getInstance();
        $pdo->beginTransaction();

        try {
            $client = $this->clientRepository->trouverParId($clientId);
            if ($client === null) {
                throw new ('Client introuvable.');
            }


            $produitsPanier = [];
            $montantTotal = 0.0;

            foreach ($lignesPanier as $item) {
                $produit = $this->produitRepository->trouverParId($item['produit_id']);
                if ($produit === null) {
                    throw new ("Produit introuvable (id {$item['produit_id']}).");
                }

                $produit->decrementerStock($item['quantite']);

                $montantTotal += $produit->getPrixVente() * $item['quantite'];
                $produitsPanier[] = ['produit' => $produit, 'quantite' => $item['quantite']];
            }

            // ===== 3. Déterminer si c'est une vente à crédit =====
            $estCredit = $avance < $montantTotal;
            $soldeAFinancer = $montantTotal - $avance;

            // ===== 4. Si crédit, vérifier la limite AVANT d'écrire quoi que ce soit =====
            if ($estCredit) {
                $detteActuelle = $this->detteRepository->sommeDetteNonSoldeeParClient($clientId);
                $detteProjetee = $detteActuelle + $soldeAFinancer;

                if ($client->depasseLimiteCredit($detteProjetee)) {
                    throw new (
                        "Limite de crédit dépassée pour {$client->getPrenom()} {$client->getNom()} "
                        . "(dette projetée : {$detteProjetee}, limite : {$client->getLimiteCredit()})."
                    );
                }
            }

            // ===== 5. Créer la commande =====
            $commande = new Commande(
                id: null,
                clientId: $clientId,
                utilisateurId: $utilisateurId,
                modePaiementId: $modePaiementId,
                dateCommande: new \DateTime(),
                montantTotal: $montantTotal,
                montantPaye: $avance,
                estCredit: $estCredit
            );
            $commande = $this->commandeRepository->creer($commande);

            // Créer les lignes de commande + écrire le stock en base
            foreach ($produitsPanier as $item) {
                $produit = $item['produit'];

                $ligne = new LigneCommande(
                    id: null,
                    commandeId: $commande->getId(),
                    produitId: $produit->getId(),
                    quantite: $item['quantite'],
                    prixUnitaire: $produit->getPrixVente()
                );
                $this->ligneCommandeRepository->creer($ligne);

                $this->produitRepository->mettreAJourStock($produit);
            }

            // Si crédit, créer la dette associée
            if ($estCredit) {
                $dette = new Dette(
                    id: null,
                    commandeId: $commande->getId(),
                    clientId: $clientId,
                    montantInitial: $soldeAFinancer,
                    montantRestant: $soldeAFinancer,
                    statut: 'NON_SOLDEE',
                );
                $this->detteRepository->creer($dette);
            }

            //  Tout s'est bien passé : on valide définitivement 
            $pdo->commit();

            return $commande;
        } catch (\Throwable $e) {

            $pdo->rollBack();
            throw $e;
        }
    }
}