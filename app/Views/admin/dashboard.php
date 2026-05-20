<?php echo view('admin/_header', ['title' => 'Dashboard Admin', 'active' => 'dashboard']); ?>
<?php
$userPrenom = (string) ($userPrenom ?? '');
$userNom = (string) ($userNom ?? '');
$employeesCount = (int) ($employeesCount ?? 0);
$departementsCount = (int) ($departementsCount ?? 0);
$typesCount = (int) ($typesCount ?? 0);
$pendingCount = (int) ($pendingCount ?? 0);
$currentMonthCongeCount = (int) ($currentMonthCongeCount ?? 0);
$currentMonthApprovedCount = (int) ($currentMonthApprovedCount ?? 0);
$currentMonthApprovedDays = (float) ($currentMonthApprovedDays ?? 0);
$currentMonthAbsences = $currentMonthAbsences ?? [];
$recentDemandes = $recentDemandes ?? [];
?>

<div class="page-head">
    <div>
        <h2>Dashboard Admin</h2>
        <p>Gérez les employés, les référentiels et le suivi des demandes de congé.</p>
    </div>
    <div class="text-end">
        <div class="badge text-bg-success-subtle border border-success-subtle text-success-emphasis">Bonjour <?= esc($userPrenom) ?> <?= esc($userNom) ?></div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="metric"><div class="label">Employés</div><div class="value"><?= $employeesCount ?></div></div></div>
    <div class="col-md-3"><div class="metric success"><div class="label">Départements</div><div class="value"><?= $departementsCount ?></div></div></div>
    <div class="col-md-3"><div class="metric dark"><div class="label">Types de congé</div><div class="value"><?= $typesCount ?></div></div></div>
    <div class="col-md-3"><div class="metric"><div class="label">Demandes en attente</div><div class="value"><?= $pendingCount ?></div></div></div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-7">
        <div class="panel h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Fonctionnalités Admin</h5>
                <span class="badge text-bg-success">Back-office</span>
            </div>
            <div class="list-group">
                <a href="<?= base_url('/admin/employes') ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">CRUD employés <i class="bi bi-arrow-right"></i></a>
                <a href="<?= base_url('/admin/departements') ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">CRUD départements <i class="bi bi-arrow-right"></i></a>
                <a href="<?= base_url('/admin/types-conges') ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">CRUD types de congé <i class="bi bi-arrow-right"></i></a>
                <a href="<?= base_url('/admin/absences') ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">Absences du mois en cours <i class="bi bi-arrow-right"></i></a>
                <a href="<?= base_url('/admin/soldes') ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">Initialiser / ajuster le solde annuel <i class="bi bi-arrow-right"></i></a>
                <a href="<?= base_url('/admin/historique') ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">Historique complet des demandes <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="panel h-100">
            <h5 class="mb-3">Mois en cours</h5>
            <div class="d-flex flex-column gap-3">
                <div class="d-flex justify-content-between"><span>Absences enregistrées</span><strong><?= $currentMonthCongeCount ?></strong></div>
                <div class="d-flex justify-content-between"><span>Absences approuvées</span><strong><?= $currentMonthApprovedCount ?></strong></div>
                <div class="d-flex justify-content-between"><span>Jours approuvés</span><strong><?= $currentMonthApprovedDays ?></strong></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="panel h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Absences du mois</h5>
                <a class="btn btn-sm btn-outline-primary" href="<?= base_url('/admin/absences') ?>">Voir tout</a>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                    <tr><th>Employé</th><th>Type</th><th>Période</th><th>Statut</th></tr>
                    </thead>
                    <tbody>
                    <?php foreach (array_slice($currentMonthAbsences, 0, 6) as $absence): ?>
                        <tr>
                            <td><?= esc((string) ($absence['prenom_employe'] ?? '') . ' ' . (string) ($absence['nom_employe'] ?? '')) ?></td>
                            <td><?= esc((string) ($absence['type_conge'] ?? '')) ?></td>
                            <td><?= esc((string) ($absence['date_debut'] ?? '')) ?> - <?= esc((string) ($absence['date_fin'] ?? '')) ?></td>
                            <td><span class="badge-soft status-<?= esc((string) ($absence['statut'] ?? '')) ?>"><?= esc((string) ($absence['statut'] ?? '')) ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($currentMonthAbsences)): ?>
                        <tr><td colspan="4" class="text-muted">Aucune absence ce mois-ci.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="panel h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Historique récent</h5>
                <a class="btn btn-sm btn-outline-primary" href="<?= base_url('/admin/historique') ?>">Historique complet</a>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                    <tr><th>Employé</th><th>Période</th><th>Statut</th></tr>
                    </thead>
                    <tbody>
                    <?php foreach (array_slice($recentDemandes, 0, 6) as $demande): ?>
                        <tr>
                            <td><?= esc((string) ($demande['prenom_employe'] ?? '') . ' ' . (string) ($demande['nom_employe'] ?? '')) ?></td>
                            <td><?= esc((string) ($demande['date_debut'] ?? '')) ?> - <?= esc((string) ($demande['date_fin'] ?? '')) ?></td>
                            <td><span class="badge-soft status-<?= esc((string) ($demande['statut'] ?? '')) ?>"><?= esc((string) ($demande['statut'] ?? '')) ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($recentDemandes)): ?>
                        <tr><td colspan="3" class="text-muted">Aucune demande trouvée.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php echo view('admin/_footer'); ?>
