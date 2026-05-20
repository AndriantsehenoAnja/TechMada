<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demandes de <?= $employee['prenom'] ?> - TechMada RH</title>
    <link href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/bootstrap/bootstrap-icons/fonts/bootstrap-icons.css') ?>" rel="stylesheet">
    <style>
        :root {
            --forest: #2d5a3d;
            --success: #1e6b3f;
            --danger: #c0392b;
        }
        body {
            font-family: 'DM Sans', sans-serif;
            background: #f8f6f1;
        }
        .sidebar {
            background: var(--forest);
            height: 100vh;
            position: sticky;
            top: 0;
        }
        .sidebar a {
            color: rgba(255,255,255,.7);
            text-decoration: none;
            display: block;
            padding: 12px 16px;
            border-radius: 8px;
            margin: 4px 8px;
            transition: all .2s;
        }
        .sidebar a:hover {
            background: rgba(45,90,61,.3);
            color: white;
        }
        .container-main {
            padding: 2rem;
        }
        .card {
            border: 1px solid #dde8e1;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0,0,0,.05);
        }
    </style>
</head>
<body>
    <div class="row g-0 min-vh-100">
        <!-- Sidebar -->
        <div class="col-auto sidebar p-3">
            <h3 style="color: white; margin-bottom: 2rem;">
                <i class="bi bi-briefcase"></i> TechMada RH
            </h3>
            <nav class="d-flex flex-column">
                <a href="<?= base_url('/rh') ?>">
                    <i class="bi bi-list-check"></i> Demandes en attente
                </a>
                <a href="<?= base_url('/rh/soldes') ?>">
                    <i class="bi bi-graph-up"></i> Soldes des employés
                </a>
            </nav>
            <a href="<?= base_url('/logout') ?>" class="btn btn-outline-light w-100" style="margin-top: auto; margin-bottom: 1rem;">Déconnexion</a>
        </div>

        <!-- Main Content -->
        <div class="col container-main">
            <a href="<?= base_url('/rh') ?>" class="btn btn-sm btn-outline-secondary mb-3">
                <i class="bi bi-arrow-left"></i> Retour
            </a>

            <div class="header mb-4">
                <h1><i class="bi bi-person"></i> <?= htmlspecialchars($employee['prenom'] . ' ' . $employee['nom']) ?></h1>
                <p class="text-muted">Email: <?= htmlspecialchars($employee['email']) ?></p>
            </div>

            <!-- Soldes de l'employé -->
            <?php if (!empty($soldes)): ?>
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Soldes (Année 2025)</h5>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Type de congé</th>
                                    <th>Attribués</th>
                                    <th>Pris</th>
                                    <th>Restants</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($soldes as $solde): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($solde['type_conge_libelle']) ?></td>
                                        <td><?= $solde['jours_attribues'] ?></td>
                                        <td><?= $solde['jours_pris'] ?></td>
                                        <td>
                                            <strong><?= max(0, $solde['jours_attribues'] - $solde['jours_pris']) ?></strong>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Demandes de l'employé -->
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-calendar-event"></i> Demandes de congé</h5>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($demandes)): ?>
                        <div class="p-4 text-center text-muted">
                            <p><i class="bi bi-inbox" style="font-size: 2rem; opacity: 0.3;"></i></p>
                            Aucune demande
                        </div>
                    <?php else: ?>
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Dates</th>
                                    <th>Durée</th>
                                    <th>Motif</th>
                                    <th>Statut</th>
                                    <th>Commentaire</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($demandes as $demande): ?>
                                    <tr>
                                        <td>
                                            <small>
                                                <?= date('d/m/Y', strtotime($demande['date_debut'])) ?> au 
                                                <?= date('d/m/Y', strtotime($demande['date_fin'])) ?>
                                            </small>
                                        </td>
                                        <td><?= $demande['nb_jours'] ?> jours</td>
                                        <td><?= htmlspecialchars($demande['motif'] ?? '-') ?></td>
                                        <td>
                                            <?php
                                                $badge_class = match($demande['statut']) {
                                                    'en_attente' => 'warning',
                                                    'approuvee' => 'success',
                                                    'refusee' => 'danger',
                                                    default => 'secondary'
                                                };
                                            ?>
                                            <span class="badge bg-<?= $badge_class ?>">
                                                <?= ucfirst($demande['statut']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <small><?= htmlspecialchars($demande['commentaire_rh'] ?? '-') ?></small>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
