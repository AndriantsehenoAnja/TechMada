-- ============================================================
--  BASE DE DONNÉES - GESTION DES CONGÉS (5 TABLES)
-- ============================================================

PRAGMA foreign_keys = ON;

-- ------------------------------------------------------------
-- 1. DEPARTEMENTS
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS departements (
    id          INTEGER PRIMARY KEY AUTOINCREMENT,
    nom         TEXT    NOT NULL,
    description TEXT
);

-- ------------------------------------------------------------
-- 2. EMPLOYES
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS employes (
    id              INTEGER PRIMARY KEY AUTOINCREMENT,
    nom             TEXT    NOT NULL,
    prenom          TEXT    NOT NULL,
    email           TEXT    NOT NULL UNIQUE,
    password        TEXT    NOT NULL,
    role            TEXT    NOT NULL DEFAULT 'employe',
    departement_id  INTEGER REFERENCES departements(id) ON DELETE SET NULL,
    date_embauche   DATE,
    actif           INTEGER NOT NULL DEFAULT 1 CHECK (actif IN (0, 1))
);

-- ------------------------------------------------------------
-- 3. TYPES_CONGE
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS types_conge (
    id            INTEGER PRIMARY KEY AUTOINCREMENT,
    libelle       TEXT    NOT NULL,
    jours_annuels INTEGER NOT NULL DEFAULT 0,
    deductible    INTEGER NOT NULL DEFAULT 1 CHECK (deductible IN (0, 1))
);

-- ------------------------------------------------------------
-- 4. SOLDES
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS soldes (
    id              INTEGER PRIMARY KEY AUTOINCREMENT,
    employe_id      INTEGER NOT NULL REFERENCES employes(id)    ON DELETE CASCADE,
    type_conge_id   INTEGER NOT NULL REFERENCES types_conge(id) ON DELETE CASCADE,
    annee           INTEGER NOT NULL,
    jours_attribues REAL    NOT NULL DEFAULT 0,
    jours_pris      REAL    NOT NULL DEFAULT 0,
    -- restant = jours_attribues - jours_pris  (colonne calculée via vue)
    UNIQUE (employe_id, type_conge_id, annee)
);

-- Vue pratique : solde restant calculé automatiquement
CREATE VIEW IF NOT EXISTS v_soldes AS
SELECT
    s.*,
    (s.jours_attribues - s.jours_pris) AS restant,
    e.nom    || ' ' || e.prenom         AS employe,
    tc.libelle                           AS type_conge
FROM soldes s
JOIN employes    e  ON e.id  = s.employe_id
JOIN types_conge tc ON tc.id = s.type_conge_id;

-- ------------------------------------------------------------
-- 5. CONGES
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS conges (
    id              INTEGER PRIMARY KEY AUTOINCREMENT,
    employe_id      INTEGER NOT NULL REFERENCES employes(id)    ON DELETE CASCADE,
    type_conge_id   INTEGER NOT NULL REFERENCES types_conge(id) ON DELETE RESTRICT,
    date_debut      DATE    NOT NULL,
    date_fin        DATE    NOT NULL,
    nb_jours        REAL    NOT NULL,
    motif           TEXT,
    statut          TEXT    NOT NULL DEFAULT 'en_attente'
                            CHECK (statut IN ('en_attente','approuvee','refusee','annulee')),
    commentaire_rh  TEXT,
    created_at      DATETIME NOT NULL DEFAULT (datetime('now')),
    traite_par      INTEGER REFERENCES employes(id) ON DELETE SET NULL,
    CHECK (date_fin >= date_debut)
);

-- ============================================================
--  DONNÉES DE DÉMONSTRATION
-- ============================================================

-- Départements
INSERT INTO departements (nom, description) VALUES
    ('Ressources Humaines', 'Gestion du personnel et des congés'),
    ('Informatique',        'Direction des systèmes d''information'),
    ('Comptabilité',        'Gestion financière et paie'),
    ('Commercial',          'Ventes et relations clients');

-- Types de congé
INSERT INTO types_conge (libelle, jours_annuels, deductible) VALUES
    ('Congé payé',          25, 1),
    ('RTT',                 10, 1),
    ('Congé maladie',        0, 0),
    ('Congé sans solde',     0, 1),
    ('Congé maternité',    112, 0);

-- Employés (passwords hashés avec bcrypt)
INSERT INTO employes (nom, prenom, email, password, role, departement_id, date_embauche, actif) VALUES
    ('Martin',   'Sophie',  'sophie.martin@example.com',  '$2y$10$CW3m7mvmbHZtdYFt3p48ZOFTOk/54bapSZbsvDbWvOg2UVI6DDBq.', 'rh',      1, '2018-03-15', 1),
    ('Dupont',   'Jean',    'jean.dupont@example.com',    '$2y$10$qIukjylA1oJUk/xWOrM.7uu9pkfH0XkaacGbIQEvc9kFzkftY.kIa', 'employe', 2, '2020-06-01', 1),
    ('Leroy',    'Marie',   'marie.leroy@example.com',    '$2y$10$wb9vb7Sd0kFWO2OpZ7LveubzqehRnnaGtA7H2xZty6U3iV6bJiX.y', 'employe', 2, '2019-09-10', 1),
    ('Bernard',  'Pierre',  'pierre.bernard@example.com', '$2y$10$LlfE6gc5Vx6/vhwYWugJG.x.1Qb1x1Yn3p9BpYayEr7AobsCwdwLS', 'admin', 3, '2017-01-20', 1),
    ('Moreau',   'Claire',  'claire.moreau@example.com',  '$2y$10$ORteeT/TpOIrrYv.sS6T9efQGSoOf8JOCL//T7o/Oc..MG7HrHQCu', 'employe', 4, '2021-11-05', 1);

-- Soldes (année 2025)
INSERT INTO soldes (employe_id, type_conge_id, annee, jours_attribues, jours_pris) VALUES
    (2, 1, 2025, 25, 8),
    (2, 2, 2025, 10, 2),
    (3, 1, 2025, 25, 5),
    (3, 2, 2025, 10, 0),
    (4, 1, 2025, 25, 12),
    (5, 1, 2025, 25, 3);

-- Demandes de congé
INSERT INTO conges (employe_id, type_conge_id, date_debut, date_fin, nb_jours, motif, statut, traite_par) VALUES
    (2, 1, '2025-07-14', '2025-07-25', 8,  'Vacances d''été',        'approuvee', 1),
    (3, 1, '2025-08-04', '2025-08-08', 5,  'Vacances famille',       'approuvee', 1),
    (4, 1, '2025-06-02', '2025-06-20', 12, 'Voyage personnel',       'approuvee', 1),
    (5, 1, '2025-09-01', '2025-09-03', 3,  'Démarches administratives','en_attente', NULL),
    (2, 2, '2025-05-19', '2025-05-20', 2,  'Pont de l''Ascension',   'approuvee', 1);