<?php echo view('admin/_header', ['title' => 'Départements', 'active' => 'departements']); ?>
<?php
$departementEdit = $departementEdit ?? null;
$departements = $departements ?? [];
?>

<div class="page-head">
    <div>
        <h2>CRUD départements</h2>
        <p>Créer, modifier ou supprimer les départements.</p>
    </div>
</div>

<div class="panel mb-4">
    <h5 class="mb-3"><?= $departementEdit ? 'Modifier un département' : 'Créer un département' ?></h5>
    <form method="post" action="<?= $departementEdit ? base_url('/admin/departements/update/' . $departementEdit['id']) : base_url('/admin/departements/store') ?>" class="row g-3">
        <?= csrf_field() ?>
        <div class="col-md-4"><label class="form-label">Nom</label><input class="form-control" name="nom" value="<?= esc((string) ($departementEdit['nom'] ?? '')) ?>" required></div>
        <div class="col-md-8"><label class="form-label">Description</label><input class="form-control" name="description" value="<?= esc((string) ($departementEdit['description'] ?? '')) ?>"></div>
        <div class="col-12 d-flex gap-2">
            <button class="btn btn-primary" type="submit"><?= $departementEdit ? 'Mettre à jour' : 'Créer' ?></button>
            <?php if ($departementEdit): ?><a class="btn btn-outline-secondary" href="<?= base_url('/admin/departements') ?>">Annuler</a><?php endif; ?>
        </div>
    </form>
</div>

<div class="panel">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead><tr><th>Nom</th><th>Description</th><th>Actions</th></tr></thead>
            <tbody>
            <?php foreach ($departements as $departement): ?>
                <tr>
                    <td><?= esc((string) $departement['nom']) ?></td>
                    <td><?= esc((string) ($departement['description'] ?? '-')) ?></td>
                    <td class="d-flex gap-2">
                        <a href="<?= base_url('/admin/departements?edit=' . $departement['id']) ?>" class="btn btn-sm btn-outline-primary">Modifier</a>
                        <form method="post" action="<?= base_url('/admin/departements/delete/' . $departement['id']) ?>" onsubmit="return confirm('Supprimer ce département ?');">
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