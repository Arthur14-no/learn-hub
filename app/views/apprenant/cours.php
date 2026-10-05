<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== ROLE_APPRENANT) {
    header('Location: ' . BASE_URL . 'index.php'); exit;
}
$titre = "Cours disponibles";
require_once VIEWS_PATH . '/layouts/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>📚 Cours disponibles</h1>
    <a href="<?php echo BASE_URL; ?>index.php?action=dashboard_apprenant" class="btn btn-secondary">← Retour au dashboard</a>
</div>

<?php if (empty($cours)): ?>
    <div class="alert alert-info">Aucun cours disponible pour le moment.</div>
<?php else: ?>
    <div class="row">
        <?php foreach ($cours as $c): ?>
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h5><?php echo htmlspecialchars($c['titre']); ?></h5>
                        <p class="text-muted small">Module : <?php echo htmlspecialchars($c['module_titre']); ?></p>
                        <p class="small">Par : <?php echo htmlspecialchars($c['formateur_nom']); ?></p>
                        <p><?php echo htmlspecialchars(substr($c['description'] ?? '', 0, 100)); ?>...</p>
                    </div>
                    <div class="card-footer">
                        <a href="<?php echo BASE_URL; ?>index.php?action=apprenant_voir_cours&id=<?php echo $c['id']; ?>" class="btn btn-primary w-100">📖 Voir le cours</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require_once VIEWS_PATH . '/layouts/footer.php'; ?>