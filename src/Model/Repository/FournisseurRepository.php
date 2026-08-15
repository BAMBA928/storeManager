<?php
namespace src\Model\Repository;
use src\Core\Database;
use PDO;
use src\Model\Entity\Fournisseur;
class FournisseurRepository{

    public function __construct(
        private PDO $pdo
    ) {
    }

    public function trouverTous(): array
    {
        $sql = "SELECT * FROM fournisseur ORDER BY nomComplet";

        $lignes = Database::executeQuery($sql, [], false);

        $Fournisseur = [];

        foreach ($lignes as $ligne) {
            $Fournisseur[] = new Fournisseur(
                id: (int) $ligne['id'],
                nomComplet: $ligne['nomComplet'],
                email: (float) $ligne['email'],
                telephone: (int) $ligne['telephone'],
            );

        }


        return $Fournisseur;
    }

    public function trouverParId(int $id): ?Fournisseur
    {

        $sql = "SELECT * FROM fournisseur WHERE id = :id";

        $ligne = Database::executeQuery($sql, ['id' => $id], true);
        $Fournisseurs = new Fournisseur(
            id: (int) $ligne['id'],
            nomComplet: $ligne['nomComplet'],
            email: (float) $ligne['email'],
            telephone: (int) $ligne['telephone']        );
        if (empty($Fournisseurs)) {
            return null;
        }


        return $Fournisseurs;
    }


    public function creer(Fournisseur $Fournisseur): Fournisseur
    {
        $sql= 'INSERT INTO fournisseur (nom_complet, email, telephone) VALUES (:nom_complet, :email, :telephone)';
        $id = Database::executeUpdate($sql,
            [
                'nomComplet' => $Fournisseur->getnomComplet(),
                'email' => $Fournisseur->getEmail(),
                'telephone' => $Fournisseur->getTel()
              
            ]
        );
        $Fournisseur->definirId($id);
        return $Fournisseur;
    }





}
// CREATE TABLE fournisseur (
//     id SERIAL PRIMARY KEY,
//     nom_complet VARCHAR(150) NOT NULL,
//     email VARCHAR(150),
//     telephone VARCHAR(30) NOT NULL,
//     adresse VARCHAR(255)
// );
// private int $id,private string $nomComplet,private string $email,private string $telephone)