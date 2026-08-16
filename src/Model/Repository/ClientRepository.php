<?php
namespace src\Model\Repository;
  
use src\Core\Database;
// use PDO;
use src\Model\Entity\Client;
class clientRepository{

    // public function __construct(
    //     private PDO $pdo
    // ) {
    // }

    public function trouverTous(): array
    {
        $sql = "SELECT * FROM client ORDER BY nom, prenom";

        $lignes = Database::executeQuery($sql, [], false);

        $clients = [];

        foreach ($lignes as $ligne) {
            $clients[] = new client(
                id: (int) $ligne['id'],
                nom: $ligne['nom'],
                 prenom: $ligne['prenom'],
                email:  $ligne['email'],
                telephone:  $ligne['telephone'],
                limiteCredit:  $ligne['limiteCredit']  
            );

        }


        return $clients;
    }

    public function trouverParId(int $id): ?client
    {

        $sql = 'SELECT * FROM client WHERE id = :id';

        $ligne = Database::executeQuery($sql, ['id' => $id], true);
        if (empty($clients)) {
            return null;
        }
        $clients = new client(
            id: (int) $ligne['id'],
            nom: $ligne['nom'],
            prenom: $ligne['prenom'],
            email:  $ligne['email'],
            telephone:  $ligne['telephone']  ,   
            limiteCredit:  $ligne['limiteCredit']        );

        return $clients;
    }


    public function creer(client $client): client
    {
        $sql= 'INSERT INTO client (nom, prenom, email, telephone, limiteCredit)
             VALUES (:nom, :prenom, :email, :telephone, :limiteCredit)';
        $id = Database::executeUpdate($sql,
            [
                'nom' => $client->getNom(),
                'prenom' => $client->getPrenom(),
                'email' => $client->getEmail(),
                'telephone' => $client->getTel(),
                'limiteCredit' => $client->getLimiteCredit()
                
              
            ]
        );
        $client->definirId($id);
        return $client;
    }





}


//    id SERIAL PRIMARY KEY,
//     nom VARCHAR(100) NOT NULL,
//     prenom VARCHAR(100) NOT NULL,
//     email VARCHAR(150),
//     telephone VARCHAR(30) NOT NULL,
//     limite_credit NUMERIC(12,2) NOT NULL DEFAULT 0,
    // private int $id;
    // private string $nom;
    // private string $prenom;
    // private string $email;
    // private string $telephone;
    // private float $limiteCredit;