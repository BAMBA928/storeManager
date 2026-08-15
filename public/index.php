<?php

require_once dirname(__DIR__). '/src/Model/Entity/Role.php';
require_once  dirname(__DIR__). '/src/core/DataBase.php';
require_once  dirname(__DIR__).  '/src/Model/Entity/Produit.php';
require_once dirname(__DIR__).  '/src/Model/Repository/ProduitRepository.php';

use src\Model\Entity\Role;

// $role = new Role();

// var_dump($role->afficherId());

use src\Model\Entity\Produit;
use src\Model\Repository\ProduitRepository;
use src\Core\Database;

$pdo = Database::getInstance();
// echo "Driver : " . $pdo->getAttribute(PDO::ATTR_DRIVER_NAME) . PHP_EOL;
$repo = new ProduitRepository($pdo);
$produits=$repo->trouverTous();
var_dump($produits);
// $produit = new Produit(1,'lait',1000,10,2);

// echo $produit->getLibelle() ;
// echo $produit->getPrixVente();
// echo $produit->getStock() ;

// var_dump($produit->estDisponible(5));


