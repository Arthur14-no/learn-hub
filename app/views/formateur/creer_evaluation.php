<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== ROLE_FORMATEUR) {
    header('Location: ' . BASE_URL . 'index.php'); exit;
}
$titre = "Créer une évaluation";
require_once VIEWS_PATH . '/layouts/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>➕ Créer une évaluation</h1>
    <a href="<?php echo BASE_URL; ?>index.php?action=formateur_voir_cours&id=<?php echo $lecon['cours_id']; ?>" class="btn btn-secondary">← Retour</a>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <p class="text-muted">Leçon : <strong><?php echo htmlspecialchars($lecon['titre']); ?></strong></p>
                <form method="POST" action="<?php echo BASE_URL; ?>index.php?action=formateur_traiter_creer_evaluation">
                    <input type="hidden" name="lecon_id" value="<?php echo $lecon['id']; ?>">
                    
                    <div class="mb-3">
                        <label class="form-label">Titre de l'évaluation</label>
                        <input type="text" name="titre" class="form-control" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Durée (minutes, 0 = illimité)</label>
                            <input type="number" name="duree" class="form-control" value="0" min="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Note minimum (/20)</label>
                            <input type="number" name="note_min" class="form-control" value="10" min="0" max="20" step="0.5">
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">Créer et ajouter des questions</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once VIEWS_PATH . '/layouts/footer.php'; ?>