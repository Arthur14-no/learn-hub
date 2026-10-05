<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== ROLE_FORMATEUR) {
    header('Location: ' . BASE_URL . 'index.php'); exit;
}
$titre = "Ajouter des questions";
require_once VIEWS_PATH . '/layouts/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1>📝 Questions de l'évaluation</h1>
        <p class="text-muted"><?php echo htmlspecialchars($evaluation['titre']); ?></p>
    </div>
    <a href="<?php echo BASE_URL; ?>index.php?action=formateur_terminer_evaluation&evaluation_id=<?php echo $evaluation['id']; ?>" class="btn btn-success">
        ✅ Terminer
    </a>
</div>

<div class="row">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header bg-primary text-white">➕ Nouvelle question</div>
            <div class="card-body">
                <form method="POST" action="<?php echo BASE_URL; ?>index.php?action=formateur_traiter_ajouter_question">
                    <input type="hidden" name="evaluation_id" value="<?php echo $evaluation['id']; ?>">
                    
                    <div class="mb-3">
                        <label class="form-label">Énoncé de la question</label>
                        <textarea name="enonce" class="form-control" rows="3" required></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Points</label>
                        <input type="number" name="points" class="form-control" value="1" min="0.5" step="0.5">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Réponses possibles (cochez la bonne)</label>
                        
                        <div class="input-group mb-2">
                            <span class="input-group-text">
                                <input type="radio" name="correcte" value="0" required>
                            </span>
                            <input type="text" name="reponses[]" class="form-control" placeholder="Réponse 1" required>
                        </div>
                        
                        <div class="input-group mb-2">
                            <span class="input-group-text">
                                <input type="radio" name="correcte" value="1">
                            </span>
                            <input type="text" name="reponses[]" class="form-control" placeholder="Réponse 2" required>
                        </div>
                        
                        <div class="input-group mb-2">
                            <span class="input-group-text">
                                <input type="radio" name="correcte" value="2">
                            </span>
                            <input type="text" name="reponses[]" class="form-control" placeholder="Réponse 3 (optionnel)">
                        </div>
                        
                        <div class="input-group mb-2">
                            <span class="input-group-text">
                                <input type="radio" name="correcte" value="3">
                            </span>
                            <input type="text" name="reponses[]" class="form-control" placeholder="Réponse 4 (optionnel)">
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">Ajouter la question</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card">
            <div class="card-header bg-dark text-white">
                📋 Questions existantes (<?php echo count($questions); ?>)
            </div>
            <div class="card-body">
                <?php if (empty($questions)): ?>
                    <p class="text-muted">Aucune question pour le moment.</p>
                <?php else: ?>
                    <?php foreach ($questions as $index => $q): ?>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h6>Q<?php echo $index + 1; ?> : <?php echo htmlspecialchars($q['enonce']); ?> (<?php echo $q['points']; ?> pt)</h6>
                                <ul class="list-group list-group-flush">
                                    <?php foreach ($reponsesParQuestion[$q['id']] as $r): ?>
                                        <li class="list-group-item <?php echo $r['est_correcte'] ? 'list-group-item-success' : ''; ?>">
                                            <?php echo $r['est_correcte'] ? '✅' : '⬜'; ?>
                                            <?php echo htmlspecialchars($r['texte']); ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once VIEWS_PATH . '/layouts/footer.php'; ?>