<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== ROLE_APPRENANT) {
    header('Location: ' . BASE_URL . 'index.php'); exit;
}
$titre = "Évaluation";
require_once VIEWS_PATH . '/layouts/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>📝 <?php echo htmlspecialchars($evaluation['titre']); ?></h1>
    <a href="<?php echo BASE_URL; ?>index.php?action=apprenant_voir_cours&id=<?php echo $evaluation['cours_id']; ?>" class="btn btn-secondary">← Retour</a>
</div>

<div class="alert alert-info">
    <strong>Instructions :</strong> Répondez à toutes les questions, puis validez. Note minimale : <?php echo $evaluation['note_min']; ?>/20.
</div>

<form method="POST" action="<?php echo BASE_URL; ?>index.php?action=apprenant_traiter_evaluation">
    <input type="hidden" name="evaluation_id" value="<?php echo $evaluation['id']; ?>">
    
    <?php foreach ($questions as $index => $q): ?>
        <div class="card mb-3">
            <div class="card-body">
                <h5>Question <?php echo $index + 1; ?> : <?php echo htmlspecialchars($q['enonce']); ?></h5>
                <p class="text-muted small"><?php echo $q['points']; ?> point(s)</p>
                
                <?php foreach ($reponsesParQuestion[$q['id']] as $r): ?>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" 
                               name="reponses[<?php echo $q['id']; ?>]" 
                               id="rep_<?php echo $r['id']; ?>" 
                               value="<?php echo $r['id']; ?>" required>
                        <label class="form-check-label" for="rep_<?php echo $r['id']; ?>">
                            <?php echo htmlspecialchars($r['texte']); ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
    
    <div class="text-center">
        <button type="submit" class="btn btn-primary btn-lg">
            ✅ Valider mes réponses
        </button>
    </div>
</form>

<?php require_once VIEWS_PATH . '/layouts/footer.php'; ?>