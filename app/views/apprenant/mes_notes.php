<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== ROLE_APPRENANT) {
    header('Location: ' . BASE_URL . 'index.php'); exit;
}
$titre = "Mes notes";
require_once VIEWS_PATH . '/layouts/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>📊 Mes notes</h1>
    <a href="<?php echo BASE_URL; ?>index.php?action=dashboard_apprenant" class="btn btn-secondary">← Retour au dashboard</a>
</div>

<!-- Statistiques -->
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card text-center bg-primary text-white">
            <div class="card-body">
                <h2><?php echo count($notes); ?></h2>
                <p class="mb-0">📝 Évaluations passées</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card text-center bg-success text-white">
            <div class="card-body">
                <h2><?php echo $moyenne; ?>/20</h2>
                <p class="mb-0">📈 Moyenne générale</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card text-center bg-warning text-dark">
            <div class="card-body">
                <h2><?php echo $reussies; ?></h2>
                <p class="mb-0">✅ Évaluations réussies</p>
            </div>
        </div>
    </div>
</div>

<!-- Tableau des notes -->
<div class="card">
    <div class="card-header bg-dark text-white">
        📋 Détail de mes notes
    </div>
    <div class="card-body">
        <?php if (empty($notes)): ?>
            <div class="alert alert-info">
                Aucune note pour le moment. Passez des évaluations pour voir vos résultats ici.
            </div>
        <?php else: ?>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Cours</th>
                        <th>Leçon</th>
                        <th>Évaluation</th>
                        <th>Note</th>
                        <th>Statut</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($notes as $n): ?>
                        <?php $reussi = $n['note'] >= $n['note_min']; ?>
                        <tr>
                            <td><?php echo htmlspecialchars($n['cours_titre']); ?></td>
                            <td><?php echo htmlspecialchars($n['lecon_titre']); ?></td>
                            <td><?php echo htmlspecialchars($n['evaluation_titre']); ?></td>
                            <td>
                                <strong class="<?php echo $reussi ? 'text-success' : 'text-danger'; ?>">
                                    <?php echo round($n['note'], 2); ?>/20
                                </strong>
                            </td>
                            <td>
                                <span class="badge bg-<?php echo $reussi ? 'success' : 'danger'; ?>">
                                    <?php echo $reussi ? '✅ Réussi' : '❌ Échoué'; ?>
                                </span>
                            </td>
                            <td><?php echo date('d/m/Y H:i', strtotime($n['date_passage'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php require_once VIEWS_PATH . '/layouts/footer.php'; ?>