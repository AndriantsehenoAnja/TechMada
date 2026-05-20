<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard RH - TechMada RH</title>
    <link href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/bootstrap/bootstrap-icons/fonts/bootstrap-icons.css') ?>" rel="stylesheet">
    <style>
        :root {
            --forest: #2d5a3d;
            --success: #1e6b3f;
            --danger: #c0392b;
            --warn: #b8750a;
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
        .btn-approve {
            background: var(--success);
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.85rem;
            cursor: pointer;
        }
        .btn-refuse {
            background: var(--danger);
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.85rem;
            cursor: pointer;
        }
        .badge-pending {
            background: var(--warn);
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
        }
        .badge-approved {
            background: var(--success);
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
        }
        .badge-refused {
            background: var(--danger);
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
        }
        table {
            font-size: 0.9rem;
        }
        .header {
            margin-bottom: 2rem;
        }
        .logout-btn {
            margin-top: auto;
            margin-bottom: 1rem;
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
                <a href="<?= base_url('/rh') ?>" class="active">
                    <i class="bi bi-list-check"></i> Demandes en attente
                </a>
                <a href="<?= base_url('/rh/soldes') ?>">
                    <i class="bi bi-graph-up"></i> Soldes des employés
                </a>
            </nav>
            <a href="<?= base_url('/logout') ?>" class="btn btn-outline-light logout-btn w-100">Déconnexion</a>
        </div>

        <!-- Main Content -->
        <div class="col container-main">
            <div class="header">
                <h1><i class="bi bi-person-check"></i> Dashboard RH</h1>
                <p class="text-muted">Bienvenue <?= session('user_prenom') ?> <?= session('user_nom') ?></p>
            </div>

            <!-- Messages -->
            <?php if (session()->has('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-circle"></i> <?= session('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->has('success')): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle"></i> <?= session('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Filtres -->
            <div class="card mb-4 p-3">
                <h5><i class="bi bi-funnel"></i> Filtres</h5>
                <div class="row g-2">
                    <div class="col-md-6">
                        <label class="form-label">Par Statut</label>
                        <div class="btn-group w-100" role="group">
                            <a href="<?= base_url('/rh') ?>" class="btn btn-sm btn-outline-primary">Tous</a>
                            <a href="<?= base_url('/rh/filter-statut/en_attente') ?>" class="btn btn-sm btn-outline-warning">En attente</a>
                            <a href="<?= base_url('/rh/filter-statut/approuvee') ?>" class="btn btn-sm btn-outline-success">Approuvées</a>
                            <a href="<?= base_url('/rh/filter-statut/refusee') ?>" class="btn btn-sm btn-outline-danger">Refusées</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Liste des demandes -->
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-calendar-event"></i> Demandes de congé</h5>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($demandes)): ?>
                        <div class="p-4 text-center text-muted">
                            <p><i class="bi bi-inbox" style="font-size: 2rem; opacity: 0.3;"></i></p>
                            Aucune demande à afficher
                        </div>
                    <?php else: ?>
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Employé</th>
                                    <th>Type</th>
                                    <th>Période</th>
                                    <th>Durée</th>
                                    <th>Motif</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($demandes as $demande): ?>
                                    <tr>
                                        <td>
                                            <strong><?= htmlspecialchars($demande['prenom'] . ' ' . $demande['nom']) ?></strong>
                                        </td>
                                        <td><?= htmlspecialchars($demande['type_conge_libelle']) ?></td>
                                        <td>
                                            <small class="text-muted">
                                                <?= date('d/m/Y', strtotime($demande['date_debut'])) ?> au 
                                                <?= date('d/m/Y', strtotime($demande['date_fin'])) ?>
                                            </small>
                                        </td>
                                        <td><strong><?= $demande['nb_jours'] ?> j</strong></td>
                                        <td><?= htmlspecialchars($demande['motif'] ?? '-') ?></td>
                                        <td>
                                            <?php if ($demande['statut'] === 'en_attente'): ?>
                                                <span class="badge-pending">En attente</span>
                                            <?php elseif ($demande['statut'] === 'approuvee'): ?>
                                                <span class="badge-approved">Approuvée</span>
                                            <?php else: ?>
                                                <span class="badge-refused">Refusée</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($demande['statut'] === 'en_attente'): ?>
                                                <form method="POST" action="<?= base_url('/rh/approve/' . $demande['id']) ?>" style="display:inline;">
                                                    <?= csrf_field() ?>
                                                    <button type="submit" class="btn-approve" onclick="return confirm('Approuver cette demande ?')">
                                                        <i class="bi bi-check-circle"></i> Approuver
                                                    </button>
                                                </form>
                                                <button class="btn-refuse" data-bs-toggle="modal" data-bs-target="#refuseModal<?= $demande['id'] ?>">
                                                    <i class="bi bi-x-circle"></i> Refuser
                                                </button>
                                                <!-- Modal Refuser -->
                                                <div class="modal fade" id="refuseModal<?= $demande['id'] ?>" tabindex="-1">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form method="POST" action="<?= base_url('/rh/refuse/' . $demande['id']) ?>">
                                                                <?= csrf_field() ?>
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Refuser la demande</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Commentaire (optionnel)</label>
                                                                        <textarea name="commentaire" class="form-control" rows="3"></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                                    <button type="submit" class="btn btn-danger">Refuser</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <small class="text-muted">Traitée</small>
                                            <?php endif; ?>
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
