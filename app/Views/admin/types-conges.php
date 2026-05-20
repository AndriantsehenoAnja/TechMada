<?php echo view('admin/_header', ['title' => 'Types de congé', 'active' => 'types']); ?>
<?php
$typeCongeEdit = $typeCongeEdit ?? null;
$typesConges = $typesConges ?? [];
?>

<div class="page-head">
    <div>
        <h2>CRUD types de congé</h2>
        <p>Gérez les référentiels de congés et leurs jours annuels.</p>
    </div>
</div>

<div class="panel mb-4">
    <h5 class="mb-3"><?= $typeCongeEdit ? 'Modifier un type de congé' : 'Créer un type de congé' ?></h5>
    <form method="post" action="<?= $typeCongeEdit ? base_url('/admin/types-conges/update/' . $typeCongeEdit['id']) : base_url('/admin/types-conges/store') ?>" class="row g-3">
        <?= csrf_field() ?>
        <div class="col-md-5"><label class="form-label">Libellé</label><input class="form-control" name="libelle" value="<?= esc((string) ($typeCongeEdit['libelle'] ?? '')) ?>" required></div>
        <div class="col-md-3"><label class="form-label">Jours annuels</label><input class="form-control" type="number" min="0" name="jours_annuels" value="<?= esc((string) ($typeCongeEdit['jours_annuels'] ?? 0)) ?>"></div>
        <div class="col-md-2"><label class="form-label">Déductible</label><select class="form-select" name="deductible"><option value="1" <?= ((string)($typeCongeEdit['deductible'] ?? '1') === '1') ? 'selected' : '' ?>>Oui</option><option value="0" <?= ((string)($typeCongeEdit['deductible'] ?? '') === '0') ? 'selected' : '' ?>>Non</option></select></div>
        <div class="col-12 d-flex gap-2">
            <button class="btn btn-primary" type="submit"><?= $typeCongeEdit ? 'Mettre à jour' : 'Créer' ?></button>
            <?php if ($typeCongeEdit): ?><a class="btn btn-outline-secondary" href="<?= base_url('/admin/types-conges') ?>">Annuler</a><?php endif; ?>
        </div>
    </form>
</div>

<div class="panel">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead><tr><th>Libellé</th><th>Jours annuels</th><th>Déductible</th><th>Actions</th></tr></thead>
            <tbody>
            <?php foreach ($typesConges as $typeConge): ?>
                <tr>
                    <td><?= esc((string) $typeConge['libelle']) ?></td>
                    <td><?= esc((string) $typeConge['jours_annuels']) ?></td>
                    <td><?= $typeConge['deductible'] ? 'Oui' : 'Non' ?></td>
                    <td class="d-flex gap-2">
                        <a href="<?= base_url('/admin/types-conges?edit=' . $typeConge['id']) ?>" class="btn btn-sm btn-outline-primary">Modifier</a>
                        <form method="post" action="<?= base_url('/admin/types-conges/delete/' . $typeConge['id']) ?>" onsubmit="return confirm('Supprimer ce type ?');">
                            <?= csrf_field() ?>
                            <button class="btn btn-sm btn-outline-danger" type="submit">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php echo view('admin/_footer'); ?>