<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== ROLE_APPRENANT) {
    header('Location: ' . BASE_URL . 'index.php'); exit;
}
$titre = "Mes certificats";
require_once VIEWS_PATH . '/layouts/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>🎓 Mes certificats</h1>
    <a href="<?php echo BASE_URL; ?>index.php?action=dashboard_apprenant" class="btn btn-secondary">← Retour au dashboard</a>
</div>

<div class="alert alert-info">
    💡 Les certificats sont générés automatiquement lorsque vous terminez <strong>tous les cours d'un module</strong> (progression à 100%).
</div>

<?php if (empty($certificats)): ?>
    <div class="card">
        <div class="card-body text-center">
            <h3>😔</h3>
            <p class="lead">Vous n'avez encore obtenu aucun certificat.</p>
            <p class="text-muted">Terminez tous les cours d'un module pour obtenir votre premier certificat !</p>
            <a href="<?php echo BASE_URL; ?>index.php?action=apprenant_cours" class="btn btn-primary">
                📖 Voir les cours disponibles
            </a>
        </div>
    </div>
<?php else: ?>
    <div class="row">
        <?php foreach ($certificats as $c): ?>
            <div class="col-md-6 mb-3">
                <div class="card border-success h-100">
                    <div class="card-header bg-success text-white">
                        🎓 Certificat de réussite
                    </div>
                    <div class="card-body">
                        <h4><?php echo htmlspecialchars($c['module_titre']); ?></h4>
                        <p class="text-muted"><?php echo htmlspecialchars($c['module_description'] ?? ''); ?></p>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">Obtenu le</small>
                                <p class="mb-0"><strong><?php echo date('d/m/Y', strtotime($c['date_obtention'])); ?></strong></p>
                            </div>
                            <div class="text-end">
                                <small class="text-muted">Délivré à</small>
                                <p class="mb-0"><strong><?php echo htmlspecialchars($_SESSION['prenom'] . ' ' . $_SESSION['nom']); ?></strong></p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="<?php echo BASE_URL; ?>certificats/certificat_modele.pdf" 
                           target="_blank" 
                           class="btn btn-success w-100">
                            📥 Télécharger le certificat
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require_once VIEWS_PATH . '/layouts/footer.php'; ?>
