<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== ROLE_FORMATEUR) {
    header('Location: ' . BASE_URL . 'index.php'); exit;
}
$titre = "Dashboard Formateur";
require_once VIEWS_PATH . '/layouts/header.php';
?>

<h1>👨‍🏫 Bonjour, <?php echo htmlspecialchars($_SESSION['prenom']); ?> !</h1>
<p class="lead">Bienvenue sur votre espace formateur.</p>

<div class="row mt-4">
    <div class="col-md-4 mb-3">
        <div class="card text-center h-100">
            <div class="card-body">
                <h3>📚</h3>
                <h5>Mes cours</h5>
                <p class="text-muted small">Gérez vos cours et leçons</p>
                <a href="<?php echo BASE_URL; ?>index.php?action=formateur_mes_cours" class="btn btn-primary">Voir mes cours</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card text-center h-100">
            <div class="card-body">
                <h3>➕</h3>
                <h5>Créer un cours</h5>
                <p class="text-muted small">Ajoutez un nouveau cours</p>
                <a href="<?php echo BASE_URL; ?>index.php?action=formateur_creer_cours" class="btn btn-primary">Créer</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card text-center h-100">
            <div class="card-body">
                <h3>🔔</h3>
                <h5>Notifications</h5>
                <p class="text-muted small">Voir mes notifications</p>
                <a href="<?php echo BASE_URL; ?>index.php?action=notifications" class="btn btn-primary">Voir</a>
            </div>
        </div>
    </div>
</div>

<?php require_once VIEWS_PATH . '/layouts/footer.php'; ?>