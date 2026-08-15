<?php

require_once dirname(__DIR__). '/src/Model/Entity/Role.php';
require_once dirname(__DIR__). '/src/Model/Entity/Produit.php';

use src\Model\Entity\Role;

// $role = new Role();

// var_dump($role->afficherId());

use src\Model\Entity\Produit;

$produit = new Produit(1,'lait',1000,10,2);

echo $produit->getLibelle() ;
echo $produit->getPrixVente();
echo $produit->getStock() ;

var_dump($produit->estDisponible(5));




