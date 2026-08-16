# 📓 Journal de Développement (DEVLOG)
**Nom & Prénom** : [Sankhe & Bamba]  
**Projet** : StoreManager Pro (ERP PHP/POO)  

---

## 1. Suivi Chronologique des Phases
### 🌃 [Vendredi - Phase 1] : Conception & BDD Fallback
- **Heure de réalisation** : 19h00 – 20h45 (vendredi 14 août 2026)**;
- **Ce qui a été fait** : 
  **a. Diagramme de cas d'utilisation (Use Case)**
  -En premier j'ai cherche a Comprendre le besoin fonctionnel ,identifier les acteurs qui interagissent avec le système  (Admin, Vente, Stock, Inventaire) .
  -j'ai aussi identifier les fonctionnalités de chaque acteur.
  -Vérifier les droits de chaque acteur ce que chaque acteur faire dans le systeme et q'il ne peut pas faire par expemple l' Admin Boutique et inventaire tous les deux peuvent manipuler les meme donnée mais l'admin peut ajouter produit alors ce lui d'iventaire ne peut faire que consulter le stock d'une produit 
-gerer les relations entre les cas d'utilisation.
-Revoir les diagrammes afin de supprimer les fonctionnalités non justifiées comme utilisateur_id
-j'ai auusi fait les diagrammes en PlantUML`useCaseVente.puml`, `useCaseStock.puml`, `useCaseInventaire.puml`, `useCaseAdmin.puml`.Des diagrammes séparés permettent également d'étudier chaque acteur individuellement : Cette séparation facilite la lecture et prépare la prochaine étape de conception.
La règle suivie pendant cette étape est : Une fonctionnalité ne doit pas être ajoutée uniquement parce qu'elle semble utile. Elle doit être justifiée par le besoin métier.
 
 j'ai appris aussi  appris
  Différence `<<include>>` vs `<<extend>>`** : j'ai utilisé `<<include>>` pour "Vérifier le stock" et "Vérifier la limite de crédit" parce que ces vérifications ont lieu **à chaque fois** qu'on enregistre une vente, peu importe le mode de paiement. À l'inverse, "Créer une dette" est en `<<extend>>` de "Enregistrer une vente", car ça n'arrive que **dans certains cas** (paiement partiel ou crédit total) — une vente payée comptant n'a jamais de dette associée.

- **Difficultés / Obstacles** : 
La principale difficulté n'était pas la syntaxe PlantUML, mais la détermination du périmètre fonctionnel.

Il fallait notamment distinguer :

une fonctionnalité réellement nécessaire ;
une fonctionnalité simplement pratique ;
une fonctionnalité qui relève d'un autre acteur ;
une fonctionnalité qui pourrait être ajoutée plus tard.

Cette réflexion m'a permis de comprendre qu'un diagramme de cas d'utilisation ne sert pas uniquement à dessiner des acteurs et des ovales.

Il sert surtout à définir précisément ce que le système doit permettre de faire et à qui.;




**b Diagramme de Classe **;

Cette étape a pour objectif de transformer les besoins fonctionnels de StoreManager en modèle métier avant l'implémentation.

j'ai identification des entités métier ;
Classes retenues

Role ,ModePaiement ,StatutAppro ,Utilisateur ,Produit ,Client ,Fournisseur ;Commande ,LigneCommande ,Dette ,Paiement, Appro

LigneAppro
définition des attributs et cardinalités ;

  j'ai aussi identifier queleque règles métier identifiées

*Vente

Une vente doit vérifier la disponibilité du stock avant validation.

Enregistrer une vente <<include>> Vérifier le stock

Une vente peut être entièrement payée ou partiellement payée.

Exemple :

total = 100 000 FCFA

payé = 100 000 FCFA

aucune dette.

Si :

total = 100 000 FCFA

payé = 60 000 FCFA

dette = 40 000 FCFA.

La création de dette est donc conditionnelle :

Créer une dette <<extend>> Enregistrer une vente

Dette

Une commande peut ne générer aucune dette ou une seule dette :

Commande "1" --> "0..1" Dette

Une dette peut être réglée en plusieurs fois :

Dette "1" *-- "0..*" Paiement

Le montant restant évolue après chaque paiement.

Approvisionnement

Un approvisionnement appartient à un fournisseur et possède un statut.

Une ligne d'approvisionnement distingue la quantité prévue (qteAppro) de la quantité réellement reçue (qteRecu), ce qui permet de représenter une réception partielle.

4. Justification des principales classes

Utilisateur

Attributs : id, role_id, nomComplet, email, motPasse.

Un utilisateur possède un rôle. L'identifiant utilisateur est associé aux opérations importantes afin d'assurer une traçabilité persistante.

Produit

Attributs : id, libelle, prixVente, stock, seuilAlert.

Le produit contient le stock disponible et le prix de vente.

Client

Attributs : id, nom, prenom, email, tel, limiteCredit.

La limite de crédit permet d'envisager un contrôle du crédit accordé au client.

Fournisseur

Attributs : id, nomComplet, email, telephone, adresse.

Commande

Attributs : id, client_id, utilisateur_id, modePaiement_id, datCommande, montantTotal, montantPaye, estCredit.

La commande représente la vente et conserve son client, son auteur, son montant et son paiement.

LigneCommande

Attributs : id, commande_id, produit_id, quantite, prixUnitaire, soueTotal.

Elle permet de représenter plusieurs produits dans une commande et de conserver le prix appliqué au moment de la vente.

Dette

Attributs : id, commande_id, client_id, montantInitial, montantRestant, statut.

Elle représente le montant restant dû.

Paiement

Attributs : id, dette_id, modePaiement_id, utilisateur_id, datePaiement, montant.

Chaque paiement est rattaché à une dette et à un mode de paiement.

Appro

Attributs : id, fournisseur_id, statutAppro_id, utilisateur_id, refBl, dateAppro, montantTotal.

LigneAppro

Attributs : id, appro_id, produit_id, qteAppro, qteRecu, prixReel, sous_total.

Ce que cette étape permet d'apprendre:
Cette étape met en pratique :

analyse des besoins ;

règles métier ;

cas d'utilisation ;

<<include>> et <<extend>> ;

classes métier ;

associations ;

cardinalités ;

composition ;

modélisation des relations plusieurs-à-plusieurs ;

traçabilité ;

préparation d'une architecture avant développement.

  **c *creation  creation des scripts d'initialisation SQL avec `schema.sql` (PostgreSQL) et `schema_sqlite.sql` (Schéma BDD)**

  j'ai creer deux fichier `schema.sql` et `schema_sqlite.sql` pour intialiser les donné dans le base donnée 
  
    J'ai traduit le diagramme de classes en deux scripts SQL 

    cette schéma reprend les entités issues du diagramme de classes :
Role, ModePaiement, StatutAppro, Utilisateur, Produit, Client, Fournisseur, Commande, LigneCommande, Dette, Paiement, Appro et LigneAppro.
Des contraintes (CHECK) ont également été ajoutées afin d'empêcher certaines données incohérentes, comme par exemple avoir de - stock négatif :
-prix négatif ;
-quantité nulle ou négative ;
-quantité reçue supérieure à la quantité prévue ;
-montant payé supérieur au montant total ;
-montant restant supérieur au montant initial.

    la règle que j'ai appliquée pour choisir entre ON DELETE CASCADE et ON DELETE RESTRICT : les tables qui représentent une **composition** dans mon diagramme de classes (LigneCommande, LigneAppro, Paiement — qui n'ont aucun sens sans leur parent) sont en CASCADE. Les class qui sont durables (Client, Produit, Fournisseur, Utilisateur) sont en RESTRICT, pour ne jamais perdre l'historique de ventes ou de dettes si quelqu'un supprime un client ou un produit par erreur.

      Pour SQLite, j'ai dû adapter trois choses par rapport au script PostgreSQL : SERIAL devient INTEGER PRIMARY KEY AUTOINCREMENT, BOOLEAN n'existe pas donc j'utilise INTEGER avec une contrainte CHECK (... IN (0,1)), et il faut activer manuellement les clés étrangères avec PRAGMA foreign_keys = ON;(elles sont désactivées par défaut dans SQLite, contrairement à PostgreSQL).


        J'ai implémenté Database::getInstance() en pattern Singleton : le constructeur est privé, donc impossible de faire new Database() depuis l'extérieur — il faut obligatoirement passer par getInstance(), qui garantit qu'une seule connexion PDO existe pour toute la durée de la requête, même si plusieurs Repository en ont besoin.

         - Pour le Singleton, j'ai dû comprendre pourquoi une propriété `static` était nécessaire pour `$instance` (contrairement à une propriété normale, elle est partagée par toutes les instances de la classe et survit entre les appels à `getInstance()`).
j'ai aussi la connection qui marche super bien
j'ai fait une autre commit car j'avait creer le erp.db avec vide alors je devrait lier avec shema_sqlite.sql
j'avais aussi quelque dificulte sur Sqlite avec ces terme different avec postgressql comme SERIAL ...
j'ai aussi modifier ces ligne pour que la conexion marche sans problemme  avec mon   $pdo = new PDO(
                "pgsql:host=localhost;dbname=storemanager",
                "postgres",
                "1234"
            ); et             $sqlitePath = dirname(__DIR__) . '/../erp.php';



### ☀️ [Samedi - Phase 2] : POO, Repositories & Ventes POS
- **Heure de réalisation: 9h00 – 20h (samedi 15 août 2026)** : 
- **Ce qui a été fait** : 
j' J'ai créé les 13 classes d'entités dans `src/Model/Entity/` en utilisant le constructeur 
 les propriétés sont déclarées directement dans les paramètres du constructeur, avec visibilité private, ce qui évite de dupliquer chaque propriété et son affectation dans le corps du constructeur ou de  modifier directement l'attribut à l'exterieur dificile de faire une controle en cas une mal affectaion
$produit->stock = -10; .

j'ai utilser aussi les namesapaces pour eviter les confilts sur les noms des fonction ont les meme noms mais de classe diferrentes


Des getters sont utilisés pour accéder aux données et les setters sont utilisés uniquement si une il y'a une modification contrôlée est nécessaire.

j'ai aussi ajouter quelque  méthodes métier .

Exemples : daans la classe Produit la methode estDisponible() L'objectif est d'éviter de disperser les règles métier dans les contrôleurs.

 ** Step 2.2 **
 j'ai creer tois fichchier dans le src/Model/Repository ProduitRepository.php, ClientRepository.php, FournisseurRepository.php. 
 j'ai fait la conexion avec en uilsant PDO car doit  Repository reçoit une connexion PDO pour eviter que que chaque Repository creer son propre connexion avec la base j'ai fait private PDO $pdo;

public function __construct(PDO $pdo)
{
    $this->pdo = $pdo;
} aulieu de class ProduitRepository
{
    public function __construct()
    {
        $this->pdo = new PDO();
    }
} en lui passant $pdo  la dépendance dont il a seulment besoin.
j'ai aussi creer getAll une methode pour chacun avec les memes mais les namespace je n'aurait pas de problemme car il va préciser chaque method d'ou il vient
le method retoun une array car je recupere beaucoup de ligne 
pour la requette j'ai $sql = "SELECT * FROM nom de la table"; * car  j'aurait besoin tous ces clones
pour eviter que postgres nous envoie les donné sous cette façons libelle = "Ordinateur"
prix_vente = 450000 on fait une transformation avec PDO::FETCH_ASSOC en tableau associatif
Mais notre application ne veut pas un tableau nous travail avec produit ,fourniseur ou client donc 
nous devrait encore faire la transformation de BDD ->tableau associatif ->Produit ..

j'ai aussi creer une fonction creer qui nous lors d'un enregistrement (fournisseurs,produit ou client) et j'ai aussi ajouter une nouvelle fonction 
definirId qui me lors que la fonction creeer retoune l'id d'insertion de le recuperer 
et une fonction qui permet de mettre à jour le stock lors qu'enregistre une vente ou une enregistrement une nouvelle produit il retourne void car il fait seulement une mise a jour
tous ces fonctions je l'ai met public car il devrait utiliser par les controllers et services 

 ** Step 2.3 **
j'ai creer un fichier serviceVente.php dans src/service 
ce que je voulait faire Charge le client 
Pour chaque article du panier : vérifie le produit, décrémente son stock  decrementerStock(), qui en meme temps verifie si le  stock est insuffisant, calcule le total Détermine si la vente est à crédit (montantPaye < montantTotal)
Si crédit : j'additionne la dette déjà existante du client avec cette methode sommeDetteNonSoldeeParClient() à la nouvelle dette creer, et vérifie via a travers du fonction qui sera dans la classe client depasseLimiteCredit()  avant d'écrire quoi que ce soit en base
Seulement après toutes ces vérifications : crée la Commande, les LigneCommande, écrit le nouveau stock en base, et crée la Dette s'ilen a

c'est cela que j'ai penser d'utilser la transactionnels car je dois INSERT commande INSERT lignes UPDATE stock INSERT dette si l'une de de ces requettes echoue tous le reste ne devrait pas s'executer et les opérations précédentes seront annulées on fait une roalback si tous passe on commit

Sur cette j'ai appris aussi la repository recupere les donné et Le VenteService sert à prendre la décision métier.
j'ai appris une nouvelle notion FOR UPDATE Le FOR UPDATE demande à PostgreSQL de verrouiller la ligne jusqu'au COMMIT ou ROLLBACK.
SELECT *
FROM produit
WHERE id = :id
FOR UPDATE

- **Difficultés / Obstacles** : 
le premier erreur que j'ai eu est lors que je cree mon premier class role j'avait une erreur comme ce type:
Typed property src\Model\Entity\Role::$id must not be accessed before initialization la traduction Tu essaies de lire $id, mais tu ne lui as encore donné aucune valeur.ce que j'avait fait $role->afficherId();alors que dans la classe role class Role
{
    private int $id;
    private string $nom;

    public function afficherId(): void
    {
        var_dump($this->id);
    }
}
j'ai resoudre ce problemme en creant une contructeur  qui fait que lors que je fait $role = new Role(1, "Administrateur"); il initialise  les donnée
