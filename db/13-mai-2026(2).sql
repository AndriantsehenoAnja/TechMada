-- =========================
-- DONNÉES DE TEST
-- =========================

-- =========================
-- Départements
-- =========================
INSERT INTO departements (nom, description) VALUES
('Informatique', 'Département des développeurs et administrateurs'),
('Ressources Humaines', 'Gestion du personnel'),
('Comptabilité', 'Gestion financière'),
('Marketing', 'Communication et publicité');

-- =========================
-- Types de congé
-- =========================
INSERT INTO types_conge (libelle, jours_annuels, deductible) VALUES
('Congé payé', 30, 1),
('Congé maladie', 15, 0),
('Congé maternité', 90, 0),
('Permission spéciale', 5, 1);

-- =========================
-- Employés
-- =========================
INSERT INTO employes (
    nom,
    prenom,
    email,
    password,
    role,
    departement_id,
    date_embauche,
    actif
) VALUES

('Dupont', 'Jean', 'jean.dupont@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC', 'admin', 1, '2022-01-10', 1),

('Martin', 'Claire', 'claire.martin@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC', 'rh', 2, '2021-03-15', 1),

('Diallo', 'Amadou', 'amadou.diallo@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC', 'employe', 1, '2023-06-01', 1),

('Nguyen', 'Lina', 'lina.nguyen@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC', 'employe', 3, '2024-02-20', 1),

('Traore', 'Mariam', 'mariam.traore@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC', 'employe', 4, '2022-09-05', 1);
