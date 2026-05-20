<?php helper('url'); ?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Congés</title>
    <link href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
</head>
<body>
    <div class="container py-4">
<h1 class="mb-3">Liste des congés</h1>

<p><a class="btn btn-secondary btn-sm" href="<?= site_url('conges') ?>">Actualiser</a></p>

<table class="table table-bordered table-striped">
    <tr>
        <th>ID</th>
        <th>Employé</th>
        <th>Type</th>
        <th>Début</th>
        <th>Fin</th>
        <th>Jours</th>
        <th>Statut</th>
        <th>Action</th>
    </tr>
    <?php if (! empty($conges)): ?>
        <?php foreach ($conges as $conge): ?>
            <tr>
                <td><?= $conge['id'] ?? '-' ?></td>
                <td><?= trim(($conge['prenom_employe'] ?? '') . ' ' . ($conge['nom_employe'] ?? '')) ?: '-' ?></td>
                <td><?= $conge['type_conge'] ?? '-' ?></td>
                <td><?= $conge['date_debut'] ?? '-' ?></td>
                <td><?= $conge['date_fin'] ?? '-' ?></td>
                <td><?= $conge['nb_jours'] ?? '-' ?></td>
                <td><?= $conge['statut'] ?? 'En attente' ?></td>
                <td><a href="<?= site_url('conges/show/' . ($conge['id'] ?? 0)) ?>">Voir</a></td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="8">Aucun congé trouvé.</td>
        </tr>
    <?php endif; ?>
</table>

</body>
</html>