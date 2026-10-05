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

<div class="row align-items-center my-5">
    <div class="col-lg-6 text-center text-lg-start mb-4 mb-lg-0">
        <h1 class="display-3 fw-bold mb-3">
            Apprenez <span style="color: var(--primary);">sans limites</span> avec Learn Hub
        </h1>
        <p class="lead mb-4">
            La plateforme d'apprentissage en ligne qui facilite la formation à distance. 
            Accédez à des cours, suivez votre progression et obtenez des certificats.
        </p>
        <div class="d-flex gap-3 flex-wrap">
            <a href="<?php echo BASE_URL; ?>index.php?action=inscription" class="btn btn-primary btn-lg">
                🚀 Commencer
            </a>
            <a href="<?php echo BASE_URL; ?>index.php?action=connexion" class="btn btn-outline-primary btn-lg">
                Se connecter
            </a>
        </div>
    </div>
    <div class="col-lg-6 text-center">
        <div style="font-size: 12rem; line-height: 1;">🎓</div>
    </div>
</div>

<div class="row mt-5">
    <div class="col-md-4 mb-4">
        <div class="card text-center h-100">
            <div class="card-body">
                <div style="font-size: 3rem;">📖</div>
                <h5 class="card-title mt-3">Cours en ligne</h5>
                <p class="card-text text-muted">
                    Accédez à des cours créés par des formateurs qualifiés, à votre rythme.
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card text-center h-100">
            <div class="card-body">
                <div style="font-size: 3rem;">📊</div>
                <h5 class="card-title mt-3">Suivi de progression</h5>
                <p class="card-text text-muted">
                    Suivez votre avancement en temps réel et consultez vos résultats.
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card text-center h-100">
            <div class="card-body">
                <div style="font-size: 3rem;">🎓</div>
                <h5 class="card-title mt-3">Certificats</h5>
                <p class="card-text text-muted">
                    Obtenez des certificats après validation de vos modules.
                </p>
            </div>
        </div>
    </div>
</div>

<div class="row mt-5 mb-4">
    <div class="col-12 text-center">
        <h3 class="mb-3">Prêt à commencer votre parcours ?</h3>
        <p class="text-muted mb-4">Rejoignez des milliers d'apprenants et développez vos compétences.</p>
        <a href="<?php echo BASE_URL; ?>index.php?action=inscription" class="btn btn-primary btn-lg">
            Créer un compte gratuit
        </a>
    </div>
</div>

<?php require_once VIEWS_PATH . '/layouts/footer.php'; ?>