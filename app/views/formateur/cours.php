<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== ROLE_FORMATEUR) {
    header('Location: ' . BASE_URL . 'index.php'); exit;
}
$titre = "Mes cours";
require_once VIEWS_PATH . '/layouts/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>📚 Mes cours</h1>
    <div>
        <a href="<?php echo BASE_URL; ?>index.php?action=formateur_creer_cours" class="btn btn-primary">➕ Nouveau cours</a>
        <a href="<?php echo BASE_URL; ?>index.php?action=dashboard_formateur" class="btn btn-secondary">← Retour au dashboard</a>
    </div>
</div>

<?php if (empty($cours)): ?>
    <div class="alert alert-info">
        Vous n'avez créé aucun cours pour le moment.
        <a href="<?php echo BASE_URL; ?>index.php?action=formateur_creer_cours" class="alert-link">Créer votre premier cours</a>
    </div>
<?php else: ?>
    <div class="row">
        <?php foreach ($cours as $c): ?>
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h5><?php echo htmlspecialchars($c['titre']); ?></h5>
                        <p class="text-muted small">Module : <?php echo htmlspecialchars($c['module_titre']); ?></p>
                        <span class="badge bg-<?php echo $c['statut'] === 'publie' ? 'success' : 'warning'; ?>">
                            <?php echo $c['statut']; ?>
                        </span>
                    </div>
                    <div class="card-footer">
                        <a href="<?php echo BASE_URL; ?>index.php?action=formateur_voir_cours&id=<?php echo $c['id']; ?>" class="btn btn-sm btn-primary">📖 Voir</a>
                        <?php if ($c['statut'] !== 'publie'): ?>
                            <a href="<?php echo BASE_URL; ?>index.php?action=formateur_publier_cours&id=<?php echo $c['id']; ?>" class="btn btn-sm btn-success">📢 Publier</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require_once VIEWS_PATH . '/layouts/footer.php'; ?>