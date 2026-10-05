<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== ROLE_APPRENANT) {
    header('Location: ' . BASE_URL . 'index.php'); exit;
}
$titre = "Résultat de l'évaluation";
require_once VIEWS_PATH . '/layouts/header.php';

$reussi = $resultat['note'] >= 10;
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body text-center">
                <h1><?php echo $reussi ? '🎉' : '😔'; ?></h1>
                <h2><?php echo $reussi ? 'Félicitations !' : 'Dommage...'; ?></h2>
                <p class="lead"><?php echo htmlspecialchars($resultat['evaluation_titre']); ?></p>
                
                <div class="display-3 my-4 <?php echo $reussi ? 'text-success' : 'text-danger'; ?>">
                    <?php echo $resultat['note']; ?>/20
                </div>
                
                <div class="progress mb-4" style="height: 30px;">
                    <div class="progress-bar <?php echo $reussi ? 'bg-success' : 'bg-danger'; ?>" 
                         style="width: <?php echo $resultat['progression']; ?>%">
                        <?php echo $resultat['progression']; ?>%
                    </div>
                </div>
                
                <h4>Détail des réponses</h4>
                <ul class="list-group mb-4">
                    <?php foreach ($resultat['details'] as $index => $d): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>
                                <?php echo $d['correcte'] ? '✅' : '❌'; ?>
                                Question <?php echo $index + 1; ?> : <?php echo htmlspecialchars($d['question']); ?>
                            </span>
                            <span class="badge bg-<?php echo $d['correcte'] ? 'success' : 'danger'; ?>">
                                <?php echo $d['correcte'] ? $d['points'] : 0; ?>/<?php echo $d['points']; ?>
                            </span>
                        </li>
                    <?php endforeach; ?>
                </ul>
                
                <a href="<?php echo BASE_URL; ?>index.php?action=apprenant_voir_cours&id=<?php echo $resultat['cours_id']; ?>" 
                   class="btn btn-primary">
                    ← Retour au cours
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once VIEWS_PATH . '/layouts/footer.php'; ?>