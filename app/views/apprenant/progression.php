<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== ROLE_APPRENANT) {
    header('Location: ' . BASE_URL . 'index.php'); exit;
}
$titre = "Ma progression";
require_once VIEWS_PATH . '/layouts/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>📈 Ma progression</h1>
    <a href="<?php echo BASE_URL; ?>index.php?action=dashboard_apprenant" class="btn btn-secondary">← Retour au dashboard</a>
</div>

<!-- Statistiques -->
<div class="row mb-4">
    <div class="col-md-6 mb-3">
        <div class="card text-center bg-primary text-white">
            <div class="card-body">
                <h2><?php echo $nbCoursSuivis; ?></h2>
                <p class="mb-0">📚 Cours suivis</p>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card text-center bg-success text-white">
            <div class="card-body">
                <h2><?php echo $nbCoursTermines; ?></h2>
                <p class="mb-0">✅ Cours terminés</p>
            </div>
        </div>
    </div>
</div>

<!-- Liste des progressions -->
<div class="card">
    <div class="card-header bg-dark text-white">
        📊 Détail de ma progression
    </div>
    <div class="card-body">
        <?php if (empty($progression)): ?>
            <div class="alert alert-info">
                Vous ne suivez aucun cours pour le moment.
                <a href="<?php echo BASE_URL; ?>index.php?action=apprenant_cours" class="alert-link">Voir les cours disponibles</a>
            </div>
        <?php else: ?>
            <?php foreach ($progression as $p): ?>
                <?php 
                $pourcentage = round($p['pourcentage'], 0);
                $couleur = $pourcentage >= 100 ? 'success' : ($pourcentage >= 50 ? 'info' : 'warning');
                ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <h5 class="mb-0"><?php echo htmlspecialchars($p['cours_titre']); ?></h5>
                                <small class="text-muted">Module : <?php echo htmlspecialchars($p['module_titre']); ?></small>
                            </div>
                            <div>
                                <span class="badge bg-<?php echo $couleur; ?> fs-6">
                                    <?php echo $pourcentage; ?>%
                                </span>
                            </div>
                        </div>
                        
                        <div class="progress mb-2" style="height: 25px;">
                            <div class="progress-bar bg-<?php echo $couleur; ?>" 
                                 role="progressbar" 
                                 style="width: <?php echo $pourcentage; ?>%"
                                 aria-valuenow="<?php echo $pourcentage; ?>" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                                <?php echo $pourcentage; ?>%
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                📖 <?php echo $p['total_lecons']; ?> leçon(s) |
                                📅 Début : <?php echo date('d/m/Y', strtotime($p['date_debut'])); ?>
                            </small>
                            <a href="<?php echo BASE_URL; ?>index.php?action=apprenant_voir_cours&id=<?php echo $p['cours_id']; ?>" 
                               class="btn btn-sm btn-primary">
                                Continuer →
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php require_once VIEWS_PATH . '/layouts/footer.php'; ?>