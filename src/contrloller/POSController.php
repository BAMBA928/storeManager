<?php

namespace src\Controller;

use src\Core\Database;
use src\Model\Repository\ProduitRepository;
use src\Model\Repository\clientRepository;
use src\Model\Repository\ModePaiementRepository;
use src\Service\VenteService;

class POSController
{
    private ProduitRepository $produitRepository;
    private VenteService $venteService;
    // private ModePaiementRepository $modePaiementRepository;
    // private ClientRepository $clientRepository;

    public function __construct()
    {
        $pdo = Database::getInstance();

        $this->produitRepository = new ProduitRepository();
        

        $this->venteService = new VenteService($pdo,  $this->produitRepository);
        // $this->modePaiementRepository = new ModePaiementRepository();

        //     $this->clientRepository = new ClientRepository();
    }

    public function index(): void
    {
        //   $clients = $this->clientRepository->trouverTous();
        $produits = $this->produitRepository->trouverTous();
        // $modesPaiement = $this->modePaiementRepository->trouverTous();
        // $ventesRecentes = $this->recupererVentesRecentes();
        // var_dump($modesPaiement);die;


        require dirname(__DIR__).'/../views/pos/index.php';
    }

    public function enregistrer(): void
    {
        try {

            $panier = $_SESSION['panier'] ?? [];

            $clientId = (int) $_POST['client_id'];

            $modePaiementId =
                (int) $_POST['mode_paiement_id'];

            $montantPaye =
                (float) $_POST['montant_paye'];

            $utilisateurId =
                (int) $_SESSION['user_id'];

            $commandeId =
                    $this->venteService->enregistrerVente(
                        panier: $panier,
                        clientId: $clientId,
                        modePaiementId: $modePaiementId,
                        utilisateurId: $utilisateurId,
                        montantPaye: $montantPaye
                );

            unset($_SESSION['panier']);

            // $_SESSION['success'] =
            //     "Vente enregistrée avec succès. "
            //     . "Commande #"
            //     . $commandeId;

            header('Location: /pos');
            exit;

        } catch (\Throwable $exception) {

            $_SESSION['error'] = $exception->getMessage();

            header('Location: /pos');
            exit;
        }
    }
}