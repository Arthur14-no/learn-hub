<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== ROLE_FORMATEUR) {
    header('Location: ' . BASE_URL . 'index.php'); exit;
}
$titre = "Évaluations du cours";
require_once VIEWS_PATH . '/layouts/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1>📝 Évaluations</h1>
        <p class="text-muted">Cours : <?php echo htmlspecialchars($cours['titre']); ?></p>
    </div>
    <a href="<?php echo BASE_URL; ?>index.php?action=formateur_voir_cours&id=<?php echo $cours['id']; ?>" class="btn btn-secondary">← Retour</a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-dark text-white">
                📋 Liste des évaluations (<?php echo count($evaluations); ?>)
            </div>
            <div class="card-body">
                <?php if (empty($evaluations)): ?>
                    <p class="text-muted">Aucune évaluation pour ce cours.</p>
                <?php else: ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Titre</th>
                                <th>Leçon</th>
                                <th>Note min</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($evaluations as $e): ?>
                                <tr>
                                    <td><?php echo $e['id']; ?></td>
                                    <td><?php echo htmlspecialchars($e['titre']); ?></td>
                                    <td><?php echo htmlspecialchars($e['lecon_titre']); ?></td>
                                    <td><?php echo $e['note_min']; ?>/20</td>
                                    <td>
                                        <a href="<?php echo BASE_URL; ?>index.php?action=formateur_ajouter_question&evaluation_id=<?php echo $e['id']; ?>" class="btn btn-sm btn-primary">
                                            ➕ Questions
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-primary text-white">➕ Nouvelle évaluation</div>
            <div class="card-body">
                <p class="text-muted small">Pour créer une évaluation, accédez à une leçon du cours.</p>
                <a href="<?php echo BASE_URL; ?>index.php?action=formateur_voir_cours&id=<?php echo $cours['id']; ?>" class="btn btn-primary w-100">
                    Voir les leçons
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once VIEWS_PATH . '/layouts/footer.php'; ?>