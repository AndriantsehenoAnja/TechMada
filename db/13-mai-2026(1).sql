-- =========================
-- Base de données SQLite
-- Gestion des congés
-- =========================

PRAGMA foreign_keys = ON;

-- =========================
-- TABLE : departements
-- =========================
CREATE TABLE departements (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL,
    description TEXT
);

-- =========================
-- TABLE : types_conge
-- =========================
CREATE TABLE types_conge (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    libelle TEXT NOT NULL,
    jours_annuels INTEGER NOT NULL DEFAULT 0,
    deductible INTEGER NOT NULL DEFAULT 1
    -- 0 = non déductible
    -- 1 = déductible
);

-- =========================
-- TABLE : employes
-- =========================
CREATE TABLE employes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL,
    prenom TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    role TEXT NOT NULL DEFAULT 'employe',
    departement_id INTEGER,
    date_embauche DATE,
    actif INTEGER NOT NULL DEFAULT 1,
    
    FOREIGN KEY (departement_id)
        REFERENCES departements(id)
        ON DELETE SET NULL
);

-- =========================
-- TABLE : soldes
-- =========================
CREATE TABLE soldes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    employe_id INTEGER NOT NULL,
    type_conge_id INTEGER NOT NULL,
    annee INTEGER NOT NULL,
    jours_attribues REAL NOT NULL DEFAULT 0,
    jours_pris REAL NOT NULL DEFAULT 0,

    FOREIGN KEY (employe_id)
        REFERENCES employes(id)
        ON DELETE CASCADE,

    FOREIGN KEY (type_conge_id)
        REFERENCES types_conge(id)
        ON DELETE CASCADE,

    UNIQUE (employe_id, type_conge_id, annee)
);

-- =========================
-- TABLE : conges
-- =========================
CREATE TABLE conges (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    employe_id INTEGER NOT NULL,
    type_conge_id INTEGER NOT NULL,
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    nb_jours REAL NOT NULL,
    motif TEXT,
    
    status TEXT NOT NULL DEFAULT 'en_attente'
        CHECK(status IN (
            'en_attente',
            'approuve',
            'refuse',
            'annule'
        )),

    commentaire_rh TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,

    traite_par INTEGER,

    FOREIGN KEY (employe_id)
        REFERENCES employes(id)
        ON DELETE CASCADE,

    FOREIGN KEY (type_conge_id)
        REFERENCES types_conge(id)
        ON DELETE CASCADE,

    FOREIGN KEY (traite_par)
        REFERENCES employes(id)
        ON DELETE SET NULL
);

-- =========================
-- INDEX (optionnel mais conseillé)
-- =========================
CREATE INDEX idx_employes_departement
ON employes(departement_id);

CREATE INDEX idx_soldes_employe
ON soldes(employe_id);

CREATE INDEX idx_conges_employe
ON conges(employe_id);

CREATE INDEX idx_conges_status
ON conges(status);