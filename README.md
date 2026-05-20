# Conge — Instructions d'installation et comptes de test

Ce dépôt contient une application PHP (CodeIgniter). Ce fichier fournit des instructions rapides pour lancer l'application en local et inclut des comptes de test (administrateur et employé).

1. Démarrez le serveur de développement (CodeIgniter 4) :

   php spark serve --host=localhost --port=8080

L'application sera disponible sur : http://localhost:8080

## Importer la base de données

Le projet contient des fichiers SQL et des fichiers SQLite :

- `writable/database.sql` — script SQL fourni
- `database/conges.db` et `writable/conges.db` — fichiers SQLite inclus

Options d'import :

- Utiliser SQLite (plus simple, aucun mot de passe requis):

  - Si vous voulez utiliser le fichier fourni, copiez `writable/conges.db` vers `database/conges.db` ou vers l'emplacement attendu par votre configuration.

  - Pour créer/importer à partir du SQL :

    sqlite3 database/conges.db < writable/database.sql

- Utiliser MySQL / MariaDB :

  - Créez une base de données (par ex. `conge`) puis importez `database/all.sql` ou `writable/database.sql` :

    mysql -u root -p conge < database/all.sql

  - Mettez à jour la configuration de base de données dans `app/Config/Database.php` ou dans votre `.env` selon la manière dont l'application est configurée.

## Comptes de test
- Compte administrateur (admin)
  - Email : pierre.bernard@example.com 
  - Mot de passe : hash_pwd_4

- Compte employé (user / employer)
  - Email : jean.dupont@example.com
  - Mot de passe : hash_pwd_2

## URL de connexion
  http://localhost:8080


##  Authentification et Rôles

### Trois rôles disponibles

| Rôle | Accès | Fonctionnalités |
|------|-------|-----------------|
| **Admin** | `/admin` | Gestion complète : employés, départements, types de congés, soldes |
| **RH** | `/rh` | Validation des demandes, suivi des soldes, commentaires |
| **Employé** | `/employe` | Création de demandes, suivi du solde personnel |

### Comptes de démonstration

```
Rôle       | Email                        | Mot de passe
-----------|------------------------------|------------------
RH         | sophie.martin@example.com    | hash_pwd_1
Admin      | pierre.bernard@example.com   | hash_pwd_4
Employé    | jean.dupont@example.com      | hash_pwd_2
Employé    | marie.leroy@example.com      | hash_pwd_3
Employé    | claire.moreau@example.com    | hash_pwd_5
```

---

##  Fonctionnalités RH Implémentées

### 1️⃣ Voir toutes les demandes en attente

**Route:** `GET /rh`

-  Affiche un tableau complet des demandes de congé non traitées
-  Colonnes : Employé, Type, Période, Durée, Motif, Statut, Actions
-  Actualisation des données en temps réel

### 2️⃣ Approuver ou Refuser une demande

**Routes:**
- `POST /rh/approve/:id` - Approuve et met à jour le solde
- `POST /rh/refuse/:id` - Refuse avec commentaire optionnel

**Features :**
-  Boutons d'action pour chaque demande en attente
-  Modal de refus avec champ de commentaire
-  Confirmation avant approbation

### 3️⃣ Mise à jour automatique du solde

Lorsqu'une demande est **approuvée** :
-  Les jours pris sont automatiquement ajoutés aux soldes
-  Calcul : `jours_pris += nb_jours_congé`
-  Année de référence : 2025 (configurable)





### Installation


L'application sera accessible à : **http://localhost:8080**

### Configuration

**Base URL** - Modifier si nécessaire dans `app/Config/App.php` :
```php
public string $baseURL = 'http://localhost:8080/';
```

##  Interface utilisateur
### Composants

- Tables avec tri et filtrage
- Modales pour les actions confirmées
- Alerts/Toasts pour les messages
- Sidebar de navigation pour RH
- Barres de progression pour les soldes
