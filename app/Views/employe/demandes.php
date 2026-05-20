<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes demandes - Employé</title>
    <link href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/bootstrap/bootstrap-icons/fonts/bootstrap-icons.css') ?>" rel="stylesheet">
    <style>
        :root { --forest: #2d5a3d; }
        body { font-family: 'DM Sans', sans-serif; background: #f8f6f1; }
        .sidebar { background: var(--forest); height: 100vh; position: sticky; top: 0; }
        .sidebar a { color: rgba(255,255,255,.7); text-decoration: none; display: block; padding: 12px 16px; border-radius: 8px; margin: 4px 8px; }
        .sidebar a:hover, .sidebar a.active { background: rgba(45,90,61,.3); color: white; }
        .container-main { padding: 2rem; }
        .card { border: 1px solid #dde8e1; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,.05); }
    </style>
</head>
<body>
    <div class="row g-0 min-vh-100">
        <div class="col-auto sidebar p-3">
            <h3 style="color: white; margin-bottom: 2rem;"><i class="bi bi-people"></i> Mon espace</h3>
            <nav class="d-flex flex-column">
                <a href="<?= base_url('/employe') ?>"><i class="bi bi-house"></i> Tableau de bord</a>
                <a href="<?= base_url('/employe/demandes') ?>" class="active"><i class="bi bi-calendar-event"></i> Mes demandes</a>
                <a href="<?= base_url('/employe/nouvelle-demande') ?>"><i class="bi bi-pencil-square"></i> Nouvelle demande</a>
                <a href="<?= base_url('/employe/solde') ?>"><i class="bi bi-graph-up"></i> Mon solde</a>
                <a href="<?= base_url('/employe/profil') ?>"><i class="bi bi-person"></i> Mon profil</a>
            </nav>
            <a href="<?= base_url('/logout') ?>" class="btn btn-outline-light w-100" style="margin-top: auto; margin-bottom: 1rem;">Déconnexion</a>
        </div>

        <div class="col container-main">
            <a href="<?= base_url('/employe') ?>" class="btn btn-sm btn-outline-secondary mb-3"><i class="bi bi-arrow-left"></i> Retour</a>

            <div class="header mb-3">
                <h1><i class="bi bi-calendar-event"></i> Mes demandes de congé</h1>
            </div>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body p-0">
                    <?php if (empty($demandes)): ?>
                        <div class="p-4 text-center text-muted">Aucune demande trouvée.</div>
                    <?php else: ?>
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Type</th>
                                    <th>Début</th>
                                    <th>Fin</th>
                                    <th>Jours</th>
                                    <th>Motif</th>
                                    <th>Statut</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($demandes as $demande): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($demande['type_conge'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars($demande['date_debut'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars($demande['date_fin'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><strong><?= htmlspecialchars((string) ($demande['nb_jours'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></strong></td>
                                        <td><?= htmlspecialchars($demande['motif'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                                        <td>
                                            <span class="badge bg-<?= ($demande['statut'] === 'en_attente') ? 'warning' : (($demande['statut'] === 'approuvee') ? 'success' : 'danger') ?>">
                                                <?= htmlspecialchars(ucfirst($demande['statut'] ?? '-')) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if (($demande['statut'] ?? '') === 'en_attente'): ?>
                                                <form method="post" action="<?= site_url('employe/demandes/cancel/' . ($demande['id'] ?? 0)) ?>">
                                                    <?= csrf_field() ?>
                                                    <button type="submit" class="btn btn-danger btn-sm">Annuler</button>
                                                </form>
                                            <?php else: ?>
                                                -
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