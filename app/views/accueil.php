<?php 
// Rediriger vers le dashboard si l'utilisateur est connecté
if (isset($_SESSION['user_id'])) {
    $dashboardAction = 'accueil';
    if ($_SESSION['role'] === ROLE_APPRENANT) {
        $dashboardAction = 'dashboard_apprenant';
    } elseif ($_SESSION['role'] === ROLE_FORMATEUR) {
        $dashboardAction = 'dashboard_formateur';
    } elseif ($_SESSION['role'] === ROLE_ADMIN) {
        $dashboardAction = 'dashboard_admin';
    }
    header('Location: ' . BASE_URL . 'index.php?action=' . $dashboardAction);
    exit;
}

$titre = "Accueil"; 
require_once VIEWS_PATH . '/layouts/header.php'; 
?>

<div class="row">
    <div class="col-lg-8 mx-auto text-center">
        <h1 class="display-4">Bienvenue sur <?php echo APP_NAME; ?></h1>
        <p class="lead">La plateforme d'apprentissage en ligne qui facilite la formation à distance.</p>
        <hr class="my-4">
        <p>Accédez à des cours, suivez votre progression et obtenez des certificats.</p>
        <?php if (!isset($_SESSION['user_id'])): ?>
            <div class="mt-4">
                <a href="<?php echo BASE_URL; ?>index.php?action=inscription" class="btn btn-primary btn-lg me-2">
                    S'inscrire
                </a>
                <a href="<?php echo BASE_URL; ?>index.php?action=connexion" class="btn btn-outline-primary btn-lg">
                    Se connecter
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="row mt-5">
    <div class="col-md-4">
        <div class="card text-center h-100">
            <div class="card-body">
                <h3>📖</h3>
                <h5 class="card-title">Cours en ligne</h5>
                <p class="card-text">Accédez à des cours créés par des formateurs qualifiés.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center h-100">
            <div class="card-body">
                <h3>📊</h3>
                <h5 class="card-title">Suivi de progression</h5>
                <p class="card-text">Suivez votre avancement et consultez vos résultats.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center h-100">
            <div class="card-body">
                <h3>🎓</h3>
                <h5 class="card-title">Certificats</h5>
                <p class="card-text">Obtenez des certificats après validation de vos modules.</p>
            </div>
        </div>
    </div>
</div>

<?php require_once VIEWS_PATH . '/layouts/footer.php'; ?>
