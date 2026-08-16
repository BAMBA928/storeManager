<?php

require_once dirname(__DIR__). '/src/Model/Entity/Role.php';
require_once  dirname(__DIR__). '/src/core/DataBase.php';
require_once  dirname(__DIR__).  '/src/Model/Entity/Produit.php';
require_once dirname(__DIR__).  '/src/Model/Repository/ProduitRepository.php';
require_once dirname(__DIR__).  '/src/Service/VentesService.php';

use src\Model\Entity\Role;

// $role = new Role();

// var_dump($role->afficherId());
use src\Service\VenteService;
use src\Model\Entity\Produit;
use src\Model\Repository\ProduitRepository;
use src\Core\Database;
$pdo = Database::getInstance();
// echo "Driver : " . $pdo->getAttribute(PDO::ATTR_DRIVER_NAME) . PHP_EOL;
// $repo = new ProduitRepository();
// $produits=$repo->trouverTous();
// var_dump($produits);
// trouverTous();

$service = new VenteService($pdo);
$panier = [
    [
        'produit_id' => 99999,
        'quantite' => 2
    ]
];

$service->enregistrerVente($panier);

// $service->enregistrerVente([]);
var_dump($service);

echo "Transaction terminée" . PHP_EOL;

// trouverParId(99999);
// // creer();
// mettreAJourStock();
// // $produit = new Produit(1,'lait',1000,10,2);

// echo $produit->getLibelle() ;
// echo $produit->getPrixVente();
// echo $produit->getStock() ;

// var_dump($produit->estDisponible(5));


