<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== ROLE_FORMATEUR) {
    header('Location: ' . BASE_URL . 'index.php'); exit;
}
$titre = $cours['titre'];
require_once VIEWS_PATH . '/layouts/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1><?php echo htmlspecialchars($cours['titre']); ?></h1>
        <p class="text-muted">Module : <?php echo htmlspecialchars($cours['module_titre']); ?> | 
            <span class="badge bg-<?php echo $cours['statut'] === 'publie' ? 'success' : 'warning'; ?>">
                <?php echo $cours['statut']; ?>
            </span>
        </p>
    </div>
    <div>
        <a href="<?php echo BASE_URL; ?>index.php?action=formateur_ajouter_lecon&cours_id=<?php echo $cours['id']; ?>" class="btn btn-primary">➕ Ajouter une leçon</a>
        <a href="<?php echo BASE_URL; ?>index.php?action=formateur_mes_cours" class="btn btn-secondary">← Retour</a>
    </div>
</div>

<div class="card">
    <div class="card-header">📖 Leçons (<?php echo count($lecons); ?>)</div>
    <div class="card-body">
        <?php if (empty($lecons)): ?>
            <p class="text-muted">Aucune leçon pour ce cours.</p>
        <?php else: ?>
            <div class="list-group">
                <?php foreach ($lecons as $l): ?>
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <div>
                                <strong><?php echo $l['ordre'] . '. ' . htmlspecialchars($l['titre']); ?></strong>
                                <span class="badge bg-secondary"><?php echo $l['type_contenu']; ?></span>
                            </div>
                            <div>
                                <a href="<?php echo BASE_URL; ?>index.php?action=formateur_creer_evaluation&lecon_id=<?php echo $l['id']; ?>" 
                                   class="btn btn-sm btn-outline-primary">
                                    ➕ Évaluation
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once VIEWS_PATH . '/layouts/footer.php'; ?>