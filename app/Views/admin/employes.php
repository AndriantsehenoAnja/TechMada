<?php echo view('admin/_header', ['title' => 'Employés', 'active' => 'employes']); ?>
<?php
$editEmploye = $editEmploye ?? null;
$departements = $departements ?? [];
$employes = $employes ?? [];
?>

<div class="page-head">
    <div>
        <h2>CRUD employés</h2>
        <p>Créer, modifier et désactiver les comptes employés.</p>
    </div>
</div>

<div class="panel mb-4">
    <h5 class="mb-3"><?= $editEmploye ? 'Modifier un employé' : 'Créer un employé' ?></h5>
    <form method="post" action="<?= $editEmploye ? base_url('/admin/employes/update/' . $editEmploye['id']) : base_url('/admin/employes/store') ?>" class="row g-3">
        <?= csrf_field() ?>
        <div class="col-md-3"><label class="form-label">Nom</label><input class="form-control" name="nom" value="<?= esc((string) ($editEmploye['nom'] ?? '')) ?>" required></div>
        <div class="col-md-3"><label class="form-label">Prénom</label><input class="form-control" name="prenom" value="<?= esc((string) ($editEmploye['prenom'] ?? '')) ?>" required></div>
        <div class="col-md-3"><label class="form-label">Email</label><input class="form-control" type="email" name="email" value="<?= esc((string) ($editEmploye['email'] ?? '')) ?>" required></div>
        <div class="col-md-3"><label class="form-label">Mot de passe <?= $editEmploye ? '(optionnel)' : '' ?></label><input class="form-control" type="password" name="password" <?= $editEmploye ? '' : 'required' ?>></div>
        <div class="col-md-3"><label class="form-label">Rôle</label><select class="form-select" name="role"><option value="employe" <?= (($editEmploye['role'] ?? '') === 'employe') ? 'selected' : '' ?>>Employé</option><option value="rh" <?= (($editEmploye['role'] ?? '') === 'rh') ? 'selected' : '' ?>>RH</option><option value="admin" <?= (($editEmploye['role'] ?? '') === 'admin') ? 'selected' : '' ?>>Admin</option></select></div>
        <div class="col-md-3"><label class="form-label">Département</label><select class="form-select" name="departement_id"><option value="">--</option><?php foreach ($departements as $departement): ?><option value="<?= esc((string) $departement['id']) ?>" <?= ((string)($editEmploye['departement_id'] ?? '') === (string)$departement['id']) ? 'selected' : '' ?>><?= esc((string) $departement['nom']) ?></option><?php endforeach; ?></select></div>
        <div class="col-md-3"><label class="form-label">Date d'embauche</label><input class="form-control" type="date" name="date_embauche" value="<?= esc((string) ($editEmploye['date_embauche'] ?? '')) ?>"></div>
        <div class="col-md-3"><label class="form-label">Statut</label><select class="form-select" name="actif"><option value="1" <?= ((string)($editEmploye['actif'] ?? '1') === '1') ? 'selected' : '' ?>>Actif</option><option value="0" <?= ((string)($editEmploye['actif'] ?? '') === '0') ? 'selected' : '' ?>>Inactif</option></select></div>
        <div class="col-12 d-flex gap-2">
            <button class="btn btn-primary" type="submit"><?= $editEmploye ? 'Mettre à jour' : 'Créer' ?></button>
            <?php if ($editEmploye): ?><a class="btn btn-outline-secondary" href="<?= base_url('/admin/employes') ?>">Annuler</a><?php endif; ?>
        </div>
    </form>
</div>

<div class="panel">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead><tr><th>Employé</th><th>Email</th><th>Rôle</th><th>Département</th><th>Statut</th><th>Actions</th></tr></thead>
            <tbody>
            <?php foreach ($employes as $employe): ?>
                <tr>
                    <td><?= esc($employe['prenom'] . ' ' . $employe['nom']) ?></td>
                    <td><?= esc((string) $employe['email']) ?></td>
                    <td><span class="badge text-bg-light border"><?= esc((string) $employe['role']) ?></span></td>
                    <td><?= esc((string) ($employe['departement_nom'] ?? '-')) ?></td>
                    <td><?= $employe['actif'] ? '<span class="badge text-bg-success">Actif</span>' : '<span class="badge text-bg-secondary">Inactif</span>' ?></td>
                    <td class="d-flex gap-2">
                        <a href="<?= base_url('/admin/employes?edit=' . $employe['id']) ?>" class="btn btn-sm btn-outline-primary">Modifier</a>
                        <form method="post" action="<?= base_url('/admin/employes/toggle/' . $employe['id']) ?>">
                            <?= csrf_field() ?>
                            <button class="btn btn-sm btn-outline-warning" type="submit"><?= $employe['actif'] ? 'Désactiver' : 'Activer' ?></button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php echo view('admin/_footer'); ?>