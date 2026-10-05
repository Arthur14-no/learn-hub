<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== ROLE_ADMIN) {
    header('Location: ' . BASE_URL . 'index.php');
    exit;
}
$titre = "Statistiques";
require_once VIEWS_PATH . '/layouts/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>📊 Statistiques</h1>
    <a href="<?php echo BASE_URL; ?>index.php?action=dashboard_admin" class="btn btn-secondary">← Retour</a>
</div>

<div class="row">
    <div class="col-md-3 mb-3">
        <div class="card text-center bg-primary text-white">
            <div class="card-body">
                <h2><?php echo $stats['apprenants']; ?></h2>
                <p>👨‍🎓 Apprenants</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-center bg-success text-white">
            <div class="card-body">
                <h2><?php echo $stats['formateurs']; ?></h2>
                <p>👨‍🏫 Formateurs</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-center bg-info text-white">
            <div class="card-body">
                <h2><?php echo $stats['cours']; ?></h2>
                <p>📚 Cours</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-center bg-warning text-dark">
            <div class="card-body">
                <h2><?php echo $stats['certificats']; ?></h2>
                <p>🎓 Certificats</p>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-dark text-white">📚 Modules & Leçons</div>
            <div class="card-body">
                <p>Modules : <strong><?php echo $stats['modules']; ?></strong></p>
                <p>Leçons : <strong><?php echo $stats['lecons']; ?></strong></p>
                <p>Évaluations : <strong><?php echo $stats['evaluations']; ?></strong></p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-dark text-white">🏆 Top 5 des cours</div>
            <div class="card-body">
                <?php if (empty($topCours)): ?>
                    <p class="text-muted">Aucune donnée.</p>
                <?php else: ?>
                    <ol class="mb-0">
                        <?php foreach ($topCours as $tc): ?>
                            <li><?php echo htmlspecialchars($tc['titre']); ?> (<?php echo $tc['total_inscriptions']; ?> inscrits)</li>
                        <?php endforeach; ?>
                    </ol>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once VIEWS_PATH . '/layouts/footer.php'; ?>