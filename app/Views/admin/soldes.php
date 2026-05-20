<?php echo view('admin/_header', ['title' => 'Soldes annuels', 'active' => 'soldes']); ?>
<?php
$year = (int) ($year ?? date('Y'));
$employes = $employes ?? [];
$soldes = $soldes ?? [];
?>

<div class="page-head">
    <div>
        <h2>Soldes annuels</h2>
        <p>Initialisez ou ajustez les soldes annuels d'un employé.</p>
    </div>
</div>

<div class="panel mb-4">
    <h5 class="mb-3">Initialiser / mettre à jour un solde annuel</h5>
    <form method="post" action="<?= base_url('/admin/soldes/initialize') ?>" class="row g-3">
        <?= csrf_field() ?>
        <div class="col-md-3"><label class="form-label">Année</label><input class="form-control" type="number" name="annee" value="<?= $year ?>" required></div>
        <div class="col-md-5"><label class="form-label">Employé</label><select class="form-select" name="employe_id"><option value="">Tous les employés actifs</option><?php foreach ($employes as $employe): ?><option value="<?= esc((string) $employe['id']) ?>"><?= esc((string) ($employe['prenom'] ?? '') . ' ' . (string) ($employe['nom'] ?? '')) ?></option><?php endforeach; ?></select></div>
        <div class="col-md-4 d-flex align-items-end"><button class="btn btn-primary w-100" type="submit">Initialiser</button></div>
        <div class="col-12 text-muted">Les types de congé déductibles sont initialisés à partir de leurs jours annuels.</div>
    </form>
</div>

<div class="panel">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead><tr><th>Employé</th><th>Type</th><th>Année</th><th>Attribués</th><th>Pris</th><th>Restant</th></tr></thead>
            <tbody>
            <?php foreach ($soldes as $solde): ?>
                <tr>
                    <td><?= esc((string) ($solde['prenom'] ?? '') . ' ' . (string) ($solde['nom'] ?? '')) ?></td>
                    <td><?= esc((string) ($solde['type_conge_libelle'] ?? '')) ?></td>
                    <td><?= esc((string) ($solde['annee'] ?? '')) ?></td>
                    <td><?= esc((string) ($solde['jours_attribues'] ?? '')) ?></td>
                    <td><?= esc((string) ($solde['jours_pris'] ?? '')) ?></td>
                    <td><?= esc((string) number_format((float) ($solde['jours_attribues'] ?? 0) - (float) ($solde['jours_pris'] ?? 0), 2, ',', ' ')) ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($soldes)): ?>
                <tr><td colspan="6" class="text-muted">Aucun solde pour cette année.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php echo view('admin/_footer'); ?>