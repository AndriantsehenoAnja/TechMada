<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle demande - Employé</title>
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
                <a href="<?= base_url('/employe/demandes') ?>"><i class="bi bi-calendar-event"></i> Mes demandes</a>
                <a href="<?= base_url('/employe/nouvelle-demande') ?>" class="active"><i class="bi bi-pencil-square"></i> Nouvelle demande</a>
                <a href="<?= base_url('/employe/solde') ?>"><i class="bi bi-graph-up"></i> Mon solde</a>
                <a href="<?= base_url('/employe/profil') ?>"><i class="bi bi-person"></i> Mon profil</a>
            </nav>
            <a href="<?= base_url('/logout') ?>" class="btn btn-outline-light w-100" style="margin-top: auto; margin-bottom: 1rem;">Déconnexion</a>
        </div>

        <div class="col container-main">
            <a href="<?= base_url('/employe/demandes') ?>" class="btn btn-sm btn-outline-secondary mb-3"><i class="bi bi-arrow-left"></i> Retour aux demandes</a>

            <div class="header mb-3">
                <h1><i class="bi bi-pencil-square"></i> Nouvelle demande de congé</h1>
            </div>

            <?php $typesConge = $typesConge ?? []; $soldes = $soldes ?? []; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>

            <div class="card mb-4 p-3">
                <form method="post" action="<?= site_url('employe/demandes/store') ?>">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Type de congé</label>
                        <select name="type_conge_id" class="form-select" required>
                            <option value="">Choisir</option>
                            <?php foreach ($typesConge as $type): ?>
                                <option value="<?= (int) $type['id'] ?>"><?= htmlspecialchars($type['libelle'], ENT_QUOTES, 'UTF-8') ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date de début</label>
                            <input type="date" name="date_debut" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date de fin</label>
                            <input type="date" name="date_fin" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Motif</label>
                        <textarea name="motif" rows="4" class="form-control" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Envoyer</button>
                </form>
            </div>

            <div class="card">
                <div class="card-header bg-light"><h5 class="mb-0">Mon solde</h5></div>
                <div class="card-body">
                    <table class="table table-bordered table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Attribués</th>
                                <th>Pris</th>
                                <th>Restant</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (! empty($soldes)): ?>
                                <?php foreach ($soldes as $solde): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($solde['type_conge'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars((string) ($solde['jours_attribues'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars((string) ($solde['jours_pris'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars((string) ($solde['restant'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="4">Aucun solde trouvé.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>