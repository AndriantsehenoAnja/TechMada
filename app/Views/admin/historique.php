<?php echo view('admin/_header', ['title' => 'Historique complet', 'active' => 'historique']); ?>
<?php $demandes = $demandes ?? []; ?>

<div class="page-head">
    <div>
        <h2>Historique complet des demandes</h2>
        <p>Liste de toutes les demandes de congé, quel que soit leur statut.</p>
    </div>
</div>

<div class="panel">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead><tr><th>Employé</th><th>Type</th><th>Période</th><th>Jours</th><th>Statut</th><th>Créée le</th></tr></thead>
            <tbody>
            <?php foreach ($demandes as $demande): ?>
                <tr>
                    <td><?= esc($demande['prenom_employe'] . ' ' . $demande['nom_employe']) ?></td>
                    <td><?= esc((string) ($demande['type_conge'] ?? '')) ?></td>
                    <td><?= esc((string) ($demande['date_debut'] ?? '')) ?> - <?= esc((string) ($demande['date_fin'] ?? '')) ?></td>
                    <td><?= esc((string) ($demande['nb_jours'] ?? '')) ?></td>
                    <td><span class="badge-soft status-<?= esc((string) ($demande['statut'] ?? '')) ?>"><?= esc((string) ($demande['statut'] ?? '')) ?></span></td>
                    <td><?= esc((string) ($demande['created_at'] ?? '')) ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($demandes)): ?>
                <tr><td colspan="6" class="text-muted">Aucune demande trouvée.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php echo view('admin/_footer'); ?>