PRAGMA foreign_keys = ON;



CREATE TABLE role (
    id INTEGER PRIMARY KEY ,
    nom TEXT NOT NULL UNIQUE
);


CREATE TABLE mode_paiement (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    libelle TEXT NOT NULL UNIQUE
);


CREATE TABLE statut_appro (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL UNIQUE
);


CREATE TABLE utilisateur (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    role_id INTEGER NOT NULL,
    nom_complet TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    mot_passe TEXT NOT NULL,

    FOREIGN KEY (role_id)
        REFERENCES role(id)
        ON DELETE RESTRICT
);


CREATE TABLE produit (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    libelle TEXT NOT NULL,
    prix_vente REAL NOT NULL,
    stock INTEGER NOT NULL DEFAULT 0,
    seuil_alert INTEGER NOT NULL DEFAULT 0,

    CHECK (prix_vente >= 0),
    CHECK (stock >= 0),
    CHECK (seuil_alert >= 0)
);


CREATE TABLE client (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL,
    prenom TEXT NOT NULL,
    email TEXT,
    telephone TEXT NOT NULL,
    limite_credit REAL NOT NULL DEFAULT 0,
    CHECK (limite_credit >= 0)
);
SELECT * FROM client;

DROP Table if EXISTS client;

CREATE TABLE fournisseur (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom_complet TEXT NOT NULL,
    email TEXT,
    telephone TEXT NOT NULL,
    adresse TEXT
);


CREATE TABLE commande (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    client_id INTEGER NOT NULL,
    utilisateur_id INTEGER NOT NULL,
    mode_paiement_id INTEGER NOT NULL,
    date_commande TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    montant_total REAL NOT NULL,
    montant_paye REAL NOT NULL DEFAULT 0,
    est_credit INTEGER NOT NULL DEFAULT 0,

    FOREIGN KEY (client_id)
        REFERENCES client(id)
        ON DELETE RESTRICT,

    FOREIGN KEY (utilisateur_id)
        REFERENCES utilisateur(id)
        ON DELETE RESTRICT,

    FOREIGN KEY (mode_paiement_id)
        REFERENCES mode_paiement(id)
        ON DELETE RESTRICT,

    CHECK (montant_total >= 0),
    CHECK (
        montant_paye >= 0
        AND montant_paye <= montant_total
    ),
    CHECK (est_credit IN (0, 1))
);


CREATE TABLE ligne_commande (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    commande_id INTEGER NOT NULL,
    produit_id INTEGER NOT NULL,
    quantite INTEGER NOT NULL,
    prix_unitaire REAL NOT NULL,
    sous_total REAL NOT NULL,

    FOREIGN KEY (commande_id)
        REFERENCES commande(id)
        ON DELETE CASCADE,

    FOREIGN KEY (produit_id)
        REFERENCES produit(id)
        ON DELETE RESTRICT,

    CHECK (quantite > 0),
    CHECK (prix_unitaire >= 0),
    CHECK (sous_total >= 0)
);


CREATE TABLE dette (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    commande_id INTEGER NOT NULL UNIQUE,
    client_id INTEGER NOT NULL,
    montant_initial REAL NOT NULL,
    montant_restant REAL NOT NULL,
    statut TEXT NOT NULL,

    FOREIGN KEY (commande_id)
        REFERENCES commande(id)
        ON DELETE RESTRICT,

    FOREIGN KEY (client_id)
        REFERENCES client(id)
        ON DELETE RESTRICT,

    CHECK (montant_initial > 0),
    CHECK (
        montant_restant >= 0
        AND montant_restant <= montant_initial
    )
);


CREATE TABLE paiement (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    dette_id INTEGER NOT NULL,
    mode_paiement_id INTEGER NOT NULL,
    utilisateur_id INTEGER NOT NULL,
    date_paiement TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    montant REAL NOT NULL,

    FOREIGN KEY (dette_id)
        REFERENCES dette(id)
        ON DELETE RESTRICT,

    FOREIGN KEY (mode_paiement_id)
        REFERENCES mode_paiement(id)
        ON DELETE RESTRICT,

    FOREIGN KEY (utilisateur_id)
        REFERENCES utilisateur(id)
        ON DELETE RESTRICT,

    CHECK (montant > 0)
);


CREATE TABLE appro (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    fournisseur_id INTEGER NOT NULL,
    statut_appro_id INTEGER NOT NULL,
    utilisateur_id INTEGER NOT NULL,
    ref_bl TEXT NOT NULL UNIQUE,
    date_appro TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    montant_total REAL NOT NULL,

    FOREIGN KEY (fournisseur_id)
        REFERENCES fournisseur(id)
        ON DELETE RESTRICT,

    FOREIGN KEY (statut_appro_id)
        REFERENCES statut_appro(id)
        ON DELETE RESTRICT,

    FOREIGN KEY (utilisateur_id)
        REFERENCES utilisateur(id)
        ON DELETE RESTRICT,

    CHECK (montant_total >= 0)
);


CREATE TABLE ligne_appro (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    appro_id INTEGER NOT NULL,
    produit_id INTEGER NOT NULL,
    qte_appro INTEGER NOT NULL,
    qte_recu INTEGER NOT NULL DEFAULT 0,
    prix_reel REAL NOT NULL,
    sous_total REAL NOT NULL,

    FOREIGN KEY (appro_id)
        REFERENCES appro(id)
        ON DELETE CASCADE,

    FOREIGN KEY (produit_id)
        REFERENCES produit(id)
        ON DELETE RESTRICT,

    CHECK (qte_appro > 0),
    CHECK (
        qte_recu >= 0
        AND qte_recu <= qte_appro
    ),
    CHECK (prix_reel >= 0),
    CHECK (sous_total >= 0)
);


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