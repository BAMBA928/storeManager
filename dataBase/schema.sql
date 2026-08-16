
CREATE TABLE role (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE mode_paiement (
    id SERIAL PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE statut_appro (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE utilisateur (
    id SERIAL PRIMARY KEY,
    role_id INTEGER NOT NULL,
    nom_complet VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    mot_passe VARCHAR(255) NOT NULL,

    CONSTRAINT fk_utilisateur_role
        FOREIGN KEY (role_id)
        REFERENCES role(id)
        ON DELETE RESTRICT
);

CREATE TABLE produit (
    id SERIAL PRIMARY KEY,
    libelle VARCHAR(150) NOT NULL,
    prix_vente NUMERIC(12,2) NOT NULL,
    stock INTEGER NOT NULL DEFAULT 0,
    seuil_alert INTEGER NOT NULL DEFAULT 0,

    CONSTRAINT ck_produit_prix
        CHECK (prix_vente >= 0),

    CONSTRAINT ck_produit_stock
        CHECK (stock >= 0),

    CONSTRAINT ck_produit_seuil
        CHECK (seuil_alert >= 0)
);

CREATE TABLE client (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(150),
    telephone VARCHAR(30) NOT NULL,
    limite_credit NUMERIC(12,2) NOT NULL DEFAULT 0,

    CONSTRAINT ck_client_limite_credit
        CHECK (limite_credit >= 0)
);
INSERT INTO client VALUES(1,'Sankhe','Bamba','Bamba@gmail.com','763640650',10);
INSERT INTO client VALUES(2,'FALL','Bamba','Fall@gmail.com','7636400000',10);

                    SELECT * FROM client;

 SELECT limite_credit FROM client WHERE id = 1;
CREATE TABLE fournisseur (
    id SERIAL PRIMARY KEY,
    nom_complet VARCHAR(150) NOT NULL,
    email VARCHAR(150),
    telephone VARCHAR(30) NOT NULL,
    adresse VARCHAR(255)
);


CREATE TABLE commande (
    id SERIAL PRIMARY KEY,
    client_id INTEGER NOT NULL,
    utilisateur_id INTEGER NOT NULL,
    mode_paiement_id INTEGER NOT NULL,
    date_commande TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    montant_total NUMERIC(12,2) NOT NULL,
    montant_paye NUMERIC(12,2) NOT NULL DEFAULT 0,
    est_credit BOOLEAN NOT NULL DEFAULT FALSE,

    CONSTRAINT fk_commande_client
        FOREIGN KEY (client_id)
        REFERENCES client(id)
        ON DELETE RESTRICT,

    CONSTRAINT fk_commande_utilisateur
        FOREIGN KEY (utilisateur_id)
        REFERENCES utilisateur(id)
        ON DELETE RESTRICT,

    CONSTRAINT fk_commande_mode_paiement
        FOREIGN KEY (mode_paiement_id)
        REFERENCES mode_paiement(id)
        ON DELETE RESTRICT,

    CONSTRAINT ck_commande_montant_total
        CHECK (montant_total >= 0),

    CONSTRAINT ck_commande_montant_paye
        CHECK (
            montant_paye >= 0
            AND montant_paye <= montant_total
        )
);
INSERT INTO commande(id,client_id,utilisateur_id,mode_paiement_id,montant_total,montant_paye,) VALUES(1,1,1,1,15000,10000,TRUE);

INSERT INTO commande ( client_id,utilisateur_id,mode_paiement_id,date_commande,montant_total,montant_paye)
        
                VALUES (2,1,2,CURRENT_TIMESTAMP,500000,30000);
                    SELECT * FROM ligne_commande;

CREATE TABLE ligne_commande (
    id SERIAL PRIMARY KEY,
    commande_id INTEGER NOT NULL,
    produit_id INTEGER NOT NULL,
    quantite INTEGER NOT NULL,
    prix_unitaire NUMERIC(12,2) NOT NULL,
    sous_total NUMERIC(12,2) NOT NULL,

    CONSTRAINT fk_ligne_commande_commande
        FOREIGN KEY (commande_id)
        REFERENCES commande(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_ligne_commande_produit
        FOREIGN KEY (produit_id)
        REFERENCES produit(id)
        ON DELETE RESTRICT,

    CONSTRAINT ck_ligne_commande_quantite
        CHECK (quantite > 0),

    CONSTRAINT ck_ligne_commande_prix
        CHECK (prix_unitaire >= 0),

    CONSTRAINT ck_ligne_commande_sous_total
        CHECK (sous_total >= 0)
);
INSERT INTO ligne_commande (commande_id,produit_id,quantite,prix_unitaire,sous_total)
                    VALUES (2,2,2,6000,);

                    SELECT * FROM ligne_commande;

CREATE TABLE dette (
    id SERIAL PRIMARY KEY,
    commande_id INTEGER NOT NULL UNIQUE,
    client_id INTEGER NOT NULL,
    montant_initial NUMERIC(12,2) NOT NULL,
    montant_restant NUMERIC(12,2) NOT NULL,
    statut VARCHAR(50) NOT NULL,

    CONSTRAINT fk_dette_commande
        FOREIGN KEY (commande_id)
        REFERENCES commande(id)
        ON DELETE RESTRICT,

    CONSTRAINT fk_dette_client
        FOREIGN KEY (client_id)
        REFERENCES client(id)
        ON DELETE RESTRICT,

    CONSTRAINT ck_dette_montant_initial
        CHECK (montant_initial > 0),

    CONSTRAINT ck_dette_montant_restant
        CHECK (
            montant_restant >= 0
            AND montant_restant <= montant_initial
        )
);
INSERT INTO dette VALUES(1,1,1,10000,5000,'EN COURS');


SELECT COALESCE(SUM(montant_restant),  0 ) AS total_dettes FROM dette WHERE client_id = 1 ;

CREATE TABLE paiement (
    id SERIAL PRIMARY KEY,
    dette_id INTEGER NOT NULL,
    mode_paiement_id INTEGER NOT NULL,
    utilisateur_id INTEGER NOT NULL,
    date_paiement TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    montant NUMERIC(12,2) NOT NULL,

    CONSTRAINT fk_paiement_dette
        FOREIGN KEY (dette_id)
        REFERENCES dette(id)
        ON DELETE RESTRICT,

    CONSTRAINT fk_paiement_mode
        FOREIGN KEY (mode_paiement_id)
        REFERENCES mode_paiement(id)
        ON DELETE RESTRICT,

    CONSTRAINT fk_paiement_utilisateur
        FOREIGN KEY (utilisateur_id)
        REFERENCES utilisateur(id)
        ON DELETE RESTRICT,

    CONSTRAINT ck_paiement_montant
        CHECK (montant > 0)
);

CREATE TABLE appros (
    id SERIAL PRIMARY KEY,
    fournisseur_id INTEGER NOT NULL,
    statut_appro_id INTEGER NOT NULL,
    utilisateur_id INTEGER NOT NULL,
    ref_bl VARCHAR(100) NOT NULL UNIQUE,
    date_appro TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    montant_total NUMERIC(12,2) NOT NULL,

    CONSTRAINT fk_appro_fournisseur
        FOREIGN KEY (fournisseur_id)
        REFERENCES fournisseur(id)
        ON DELETE RESTRICT,

    CONSTRAINT fk_appro_statut
        FOREIGN KEY (statut_appro_id)
        REFERENCES statut_appro(id)
        ON DELETE RESTRICT,

    CONSTRAINT fk_appro_utilisateur
        FOREIGN KEY (utilisateur_id)
        REFERENCES utilisateur(id)
        ON DELETE RESTRICT,

    CONSTRAINT ck_appro_montant
        CHECK (montant_total >= 0)
);


CREATE TABLE ligne_Appros (
    id SERIAL PRIMARY KEY,
    appro_id INTEGER NOT NULL,
    produit_id INTEGER NOT NULL,
    qte_appro INTEGER NOT NULL,
    qte_recu INTEGER NOT NULL DEFAULT 0,
    prix_reel NUMERIC(12,2) NOT NULL,
    sous_total NUMERIC(12,2) NOT NULL,

    CONSTRAINT fk_ligne_appro_appro
        FOREIGN KEY (appro_id)
        REFERENCES appro(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_ligne_appro_produit
        FOREIGN KEY (produit_id)
        REFERENCES produit(id)
        ON DELETE RESTRICT,

    CONSTRAINT ck_ligne_appro_qte
        CHECK (qte_appro > 0),

    CONSTRAINT ck_ligne_appro_qte_recu
        CHECK (
            qte_recu >= 0
            AND qte_recu <= qte_appro
        ),

    CONSTRAINT ck_ligne_appro_prix
        CHECK (prix_reel >= 0),

    CONSTRAINT ck_ligne_appro_sous_total
        CHECK (sous_total >= 0)
);

INSERT INTO produit (libelle, prix_vente, stock) VALUES
('riz',15000,10),
('sucre',16000,15),
('Pomme de terre',9000,9);

SELECT id, libelle, prix_vente, stock, seuil_alert
                FROM produit;
INSERT INTO role (nom) VALUES
('ADMIN'),
('VENTE'),
('STOCK'),
('INVENTAIRE');

SELECT nom from role;

INSERT INTO mode_paiement (libelle) VALUES
('ESPECES'),
('WAVE'),
('ORANGE MONEY'),
('CARTE');

SELECT libelle  from mode_paiement;

INSERT INTO statut_appro (nom) VALUES
('EN_ATTENTE'),
('RECEPTIONNE'),
('ANNULE');

SELECT nom  from statut_appro;


