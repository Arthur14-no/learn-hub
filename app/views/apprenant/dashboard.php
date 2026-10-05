<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== ROLE_APPRENANT) {
    header('Location: ' . BASE_URL . 'index.php'); exit;
}
$titre = "Dashboard Apprenant";
require_once VIEWS_PATH . '/layouts/header.php';
?>

<h1>👨‍🎓 Bonjour, <?php echo htmlspecialchars($_SESSION['prenom']); ?> !</h1>
<p class="lead">Bienvenue sur votre espace apprenant.</p>

<!-- Statistiques rapides -->
<div class="row mt-4">
    <div class="col-md-3 mb-3">
        <div class="card text-center h-100">
            <div class="card-body">
                <h3>📖</h3>
                <h5>Mes cours</h5>
                <p class="text-muted small">Consultez les cours disponibles</p>
                <a href="<?php echo BASE_URL; ?>index.php?action=apprenant_cours" class="btn btn-primary btn-sm">Voir les cours</a>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-center h-100">
            <div class="card-body">
                <h3>📈</h3>
                <h5>Ma progression</h5>
                <p class="text-muted small">
                    <?php echo $nbCoursTermines ?? 0; ?> cours terminé(s) sur <?php echo $nbCoursSuivis ?? 0; ?>
                </p>
                <a href="<?php echo BASE_URL; ?>index.php?action=apprenant_progression" class="btn btn-primary btn-sm">Voir ma progression</a>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-center h-100">
            <div class="card-body">
                <h3>📊</h3>
                <h5>Mes notes</h5>
                <p class="text-muted small">Consultez vos résultats</p>
                <a href="<?php echo BASE_URL; ?>index.php?action=apprenant_mes_notes" class="btn btn-primary btn-sm">Voir mes notes</a>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-center h-100">
            <div class="card-body">
                <h3>🎓</h3>
                <h5>Mes certificats</h5>
                <p class="text-muted small"><?php echo $nbCertificats ?? 0; ?> certificat(s)</p>
                <a href="<?php echo BASE_URL; ?>index.php?action=apprenant_certificats" class="btn btn-primary btn-sm">Voir mes certificats</a>
            </div>
        </div>
    </div>
</div>

<!-- Derniers cours suivis -->
<?php if (!empty($progression)): ?>
    <div class="card mt-4">
        <div class="card-header bg-dark text-white">
            📚 Mes derniers cours
        </div>
        <div class="card-body">
            <div class="row">
                <?php foreach (array_slice($progression, 0, 3) as $p): ?>
                    <?php 
                    $pourcentage = round($p['pourcentage'], 0);
                    $couleur = $pourcentage >= 100 ? 'success' : ($pourcentage >= 50 ? 'info' : 'warning');
                    ?>
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <h6><?php echo htmlspecialchars($p['cours_titre']); ?></h6>
                                <small class="text-muted"><?php echo htmlspecialchars($p['module_titre']); ?></small>
                                <div class="progress mt-2" style="height: 20px;">
                                    <div class="progress-bar bg-<?php echo $couleur; ?>" 
                                         style="width: <?php echo $pourcentage; ?>%">
                                        <?php echo $pourcentage; ?>%
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="<?php echo BASE_URL; ?>index.php?action=apprenant_voir_cours&id=<?php echo $p['cours_id']; ?>" 
                                   class="btn btn-sm btn-primary w-100">
                                    Continuer
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php require_once VIEWS_PATH . '/layouts/footer.php'; ?>