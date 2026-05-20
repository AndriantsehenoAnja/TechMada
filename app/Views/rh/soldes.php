<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soldes - TechMada RH</title>
    <link href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/bootstrap/bootstrap-icons/fonts/bootstrap-icons.css') ?>" rel="stylesheet">
    <style>
        :root {
            --forest: #2d5a3d;
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
        .sidebar a:hover, .sidebar a.active {
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
        .progress-bar {
            background: #1e6b3f;
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
                <a href="<?= base_url('/rh/soldes') ?>" class="active">
                    <i class="bi bi-graph-up"></i> Soldes des employés
                </a>
            </nav>
            <a href="<?= base_url('/logout') ?>" class="btn btn-outline-light w-100" style="margin-top: auto; margin-bottom: 1rem;">Déconnexion</a>
        </div>

        <!-- Main Content -->
        <div class="col container-main">
            <div class="header mb-4">
                <h1><i class="bi bi-graph-up"></i> Soldes des employés</h1>
                <p class="text-muted">Année 2025</p>
            </div>

            <!-- Liste des soldes par employé -->
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-list-task"></i> Soldes par employé et type de congé</h5>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($soldes)): ?>
                        <div class="p-4 text-center text-muted">
                            <p><i class="bi bi-inbox" style="font-size: 2rem; opacity: 0.3;"></i></p>
                            Aucune donnée de solde disponible
                        </div>
                    <?php else: ?>
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Employé</th>
                                    <th>Type de congé</th>
                                    <th>Jours attribués</th>
                                    <th>Jours pris</th>
                                    <th>Jours restants</th>
                                    <th>Utilisation</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($soldes as $solde): ?>
                                    <tr>
                                        <td>
                                            <strong><?= htmlspecialchars($solde['prenom'] . ' ' . $solde['nom']) ?></strong>
                                        </td>
                                        <td><?= htmlspecialchars($solde['type_conge_libelle']) ?></td>
                                        <td><?= $solde['jours_attribues'] ?></td>
                                        <td><?= $solde['jours_pris'] ?></td>
                                        <td>
                                            <?php $restants = $solde['jours_attribues'] - $solde['jours_pris']; ?>
                                            <strong><?= $restants >= 0 ? $restants : 0 ?></strong>
                                        </td>
                                        <td>
                                            <?php 
                                                $pourcentage = ($solde['jours_pris'] / $solde['jours_attribues']) * 100;
                                                $pourcentage = min(100, $pourcentage);
                                            ?>
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar" style="width: <?= $pourcentage ?>%">
                                                    <?= round($pourcentage) ?>%
                                                </div>
                                            </div>
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
