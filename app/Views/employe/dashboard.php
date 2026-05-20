<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon espace - Employé</title>
    <link href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/bootstrap/bootstrap-icons/fonts/bootstrap-icons.css') ?>" rel="stylesheet">
    <style>
        :root { --forest: #2d5a3d; --success: #1e6b3f; --danger: #c0392b; }
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
                <a href="<?= base_url('/employe') ?>" class="active"><i class="bi bi-house"></i> Tableau de bord</a>
                <a href="<?= base_url('/employe/demandes') ?>"><i class="bi bi-calendar-event"></i> Mes demandes</a>
                <a href="<?= base_url('/employe/nouvelle-demande') ?>"><i class="bi bi-pencil-square"></i> Nouvelle demande</a>
                <a href="<?= base_url('/employe/solde') ?>"><i class="bi bi-graph-up"></i> Mon solde</a>
                <a href="<?= base_url('/employe/profil') ?>"><i class="bi bi-person"></i> Mon profil</a>
            </nav>
            <a href="<?= base_url('/logout') ?>" class="btn btn-outline-light w-100" style="margin-top: auto; margin-bottom: 1rem;">Déconnexion</a>
        </div>

        <div class="col container-main">
            <div class="header mb-3">
                <h1><i class="bi bi-house"></i> Tableau de bord</h1>
                <p class="text-muted">Bienvenue <?= htmlspecialchars(session('user_prenom') . ' ' . session('user_nom')) ?></p>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="card p-3">
                        <h5>Actions rapides</h5>
                        <div class="list-group mt-2">
                            <a class="list-group-item list-group-item-action" href="<?= site_url('employe/nouvelle-demande') ?>">Soumettre une demande</a>
                            <a class="list-group-item list-group-item-action" href="<?= site_url('employe/demandes') ?>">Voir mes demandes</a>
                            <a class="list-group-item list-group-item-action" href="<?= site_url('employe/solde') ?>">Voir mon solde</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card p-3">
                        <h5>Infos</h5>
                        <p class="mb-0"><strong>Rôle :</strong> <?= htmlspecialchars(session('user_role') ?? '') ?></p>
                        <p class="mb-0"><strong>Email :</strong> <?= htmlspecialchars(session('user_email') ?? '') ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
