<?php echo view('admin/_header', ['title' => 'Absences du mois', 'active' => 'absences']); ?>
<?php
$mois = (string) ($mois ?? '');
$absences = $absences ?? [];
$approvedCount = (int) ($approvedCount ?? 0);
$pendingCount = (int) ($pendingCount ?? 0);
?>

<div class="page-head">
    <div>
        <h2>Absences du mois en cours</h2>
        <p>Vue des congés qui chevauchent le mois <?= esc($mois) ?>.</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6"><div class="metric"><div class="label">Absences approuvées</div><div class="value"><?= $approvedCount ?></div></div></div>
    <div class="col-md-6"><div class="metric success"><div class="label">Absences en attente sur la période</div><div class="value"><?= $pendingCount ?></div></div></div>
</div>

<div class="panel">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead><tr><th>Employé</th><th>Type</th><th>Période</th><th>Jours</th><th>Statut</th></tr></thead>
            <tbody>
            <?php foreach ($absences as $absence): ?>
                <tr>
                    <td><?= esc($absence['prenom_employe'] . ' ' . $absence['nom_employe']) ?></td>
                    <td><?= esc((string) ($absence['type_conge'] ?? '')) ?></td>
                    <td><?= esc((string) ($absence['date_debut'] ?? '')) ?> - <?= esc((string) ($absence['date_fin'] ?? '')) ?></td>
                    <td><?= esc((string) ($absence['nb_jours'] ?? '')) ?></td>
                    <td><span class="badge-soft status-<?= esc((string) ($absence['statut'] ?? '')) ?>"><?= esc((string) ($absence['statut'] ?? '')) ?></span></td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($absences)): ?>
                <tr><td colspan="5" class="text-muted">Aucune absence enregistrée ce mois-ci.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php echo view('admin/_footer'); ?>