<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== ROLE_APPRENANT) {
    header('Location: ' . BASE_URL . 'index.php'); exit;
}
$titre = $cours['titre'];
require_once VIEWS_PATH . '/layouts/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1><?php echo htmlspecialchars($cours['titre']); ?></h1>
        <p class="text-muted">Module : <?php echo htmlspecialchars($cours['module_titre']); ?> | 
            Par : <?php echo htmlspecialchars($cours['formateur_nom']); ?></p>
    </div>
    <a href="<?php echo BASE_URL; ?>index.php?action=apprenant_cours" class="btn btn-secondary">← Retour</a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <p><?php echo nl2br(htmlspecialchars($cours['description'] ?? '')); ?></p>
    </div>
</div>

<h3>📖 Leçons</h3>
<?php if (empty($lecons)): ?>
    <div class="alert alert-info">Aucune leçon pour ce cours.</div>
<?php else: ?>
    <div class="list-group">
        <?php foreach ($lecons as $l): ?>
            <div class="list-group-item">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong><?php echo $l['ordre'] . '. ' . htmlspecialchars($l['titre']); ?></strong>
                        <span class="badge bg-secondary"><?php echo $l['type_contenu']; ?></span>
                    </div>
                    <div>
                        <?php if ($l['type_contenu'] === 'pdf' || $l['type_contenu'] === 'video'): ?>
                            <a href="<?php echo htmlspecialchars($l['url_contenu']); ?>" target="_blank" class="btn btn-sm btn-primary">📂 Ouvrir</a>
                        <?php endif; ?>

                        <?php if (!empty($evaluationsParLecon[$l['id']])): ?>
                            <?php foreach ($evaluationsParLecon[$l['id']] as $ev): ?>
                                <a href="<?php echo BASE_URL; ?>index.php?action=apprenant_passer_evaluation&id=<?php echo $ev['id']; ?>" 
                                   class="btn btn-sm btn-success">
                                    📝 <?php echo htmlspecialchars($ev['titre']); ?>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if (!empty($l['contenu'])): ?>
                    <p class="mt-2 mb-0"><?php echo nl2br(htmlspecialchars($l['contenu'])); ?></p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require_once VIEWS_PATH . '/layouts/footer.php'; ?>