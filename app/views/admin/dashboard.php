<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== ROLE_ADMIN) {
    header('Location: ' . BASE_URL . 'index.php'); exit;
}
$titre = "Dashboard Admin";
require_once VIEWS_PATH . '/layouts/header.php';
?>

<h1>👨‍💼 Bonjour, <?php echo htmlspecialchars($_SESSION['prenom']); ?> !</h1>
<p class="lead">Bienvenue sur votre espace administrateur.</p>

<div class="row mt-4">
    <div class="col-md-3 mb-3">
        <div class="card text-center h-100">
            <div class="card-body">
                <h3>👥</h3>
                <h5>Utilisateurs</h5>
                <p class="text-muted small">Gérer les comptes</p>
                <a href="<?php echo BASE_URL; ?>index.php?action=admin_utilisateurs" class="btn btn-primary btn-sm">Gérer</a>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-center h-100">
            <div class="card-body">
                <h3>📚</h3>
                <h5>Modules</h5>
                <p class="text-muted small">Gérer les modules</p>
                <a href="<?php echo BASE_URL; ?>index.php?action=admin_modules" class="btn btn-primary btn-sm">Gérer</a>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-center h-100">
            <div class="card-body">
                <h3>📊</h3>
                <h5>Statistiques</h5>
                <p class="text-muted small">Voir les statistiques</p>
                <a href="<?php echo BASE_URL; ?>index.php?action=admin_statistiques" class="btn btn-primary btn-sm">Voir</a>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-center h-100">
            <div class="card-body">
                <h3>🔔</h3>
                <h5>Notifications</h5>
                <p class="text-muted small">Voir mes notifications</p>
                <a href="<?php echo BASE_URL; ?>index.php?action=notifications" class="btn btn-primary btn-sm">Voir</a>
            </div>
        </div>
    </div>
</div>

<?php require_once VIEWS_PATH . '/layouts/footer.php'; ?>