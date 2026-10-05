<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'index.php'); exit;
}
$titre = "Mes notifications";
require_once VIEWS_PATH . '/layouts/header.php';

// Déterminer le dashboard selon le rôle
$dashboardAction = 'accueil';
if ($_SESSION['role'] === ROLE_APPRENANT) {
    $dashboardAction = 'dashboard_apprenant';
} elseif ($_SESSION['role'] === ROLE_FORMATEUR) {
    $dashboardAction = 'dashboard_formateur';
} elseif ($_SESSION['role'] === ROLE_ADMIN) {
    $dashboardAction = 'dashboard_admin';
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>🔔 Mes notifications</h1>
    <div>
        <?php if ($nbNonLues > 0): ?>
            <a href="<?php echo BASE_URL; ?>index.php?action=notifications_marquer_toutes_lues" class="btn btn-success">
                ✅ Tout marquer comme lu (<?php echo $nbNonLues; ?>)
            </a>
        <?php endif; ?>
        <a href="<?php echo BASE_URL; ?>index.php?action=<?php echo $dashboardAction; ?>" class="btn btn-secondary">
            ← Retour au dashboard
        </a>
    </div>
</div>

<?php if (empty($notifications)): ?>
    <div class="alert alert-info">
        Aucune notification pour le moment.
    </div>
<?php else: ?>
    <div class="list-group">
        <?php foreach ($notifications as $n): ?>
            <?php
            $couleurs = [
                'info' => 'info',
                'succes' => 'success',
                'avertissement' => 'warning',
                'erreur' => 'danger'
            ];
            $couleur = $couleurs[$n['type']] ?? 'info';
            ?>
            <div class="list-group-item <?php echo $n['lue'] ? '' : 'list-group-item-' . $couleur; ?>">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <p class="mb-1"><?php echo htmlspecialchars($n['message']); ?></p>
                        <small class="text-muted">
                            <?php echo date('d/m/Y H:i', strtotime($n['date_envoi'])); ?>
                            <?php if (!$n['lue']): ?>
                                <span class="badge bg-<?php echo $couleur; ?>">Nouveau</span>
                            <?php endif; ?>
                        </small>
                    </div>
                    <div>
                        <?php if (!$n['lue']): ?>
                            <a href="<?php echo BASE_URL; ?>index.php?action=notifications_marquer_lue&id=<?php echo $n['id']; ?>" 
                               class="btn btn-sm btn-outline-success">
                                ✅
                            </a>
                        <?php endif; ?>
                        <a href="<?php echo BASE_URL; ?>index.php?action=notifications_supprimer&id=<?php echo $n['id']; ?>" 
                           class="btn btn-sm btn-outline-danger"
                           onclick="return confirm('Supprimer cette notification ?')">
                            🗑️
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require_once VIEWS_PATH . '/layouts/footer.php'; ?>
