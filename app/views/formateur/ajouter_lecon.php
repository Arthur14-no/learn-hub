<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== ROLE_FORMATEUR) {
    header('Location: ' . BASE_URL . 'index.php'); exit;
}
$titre = "Ajouter une leçon";
require_once VIEWS_PATH . '/layouts/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>➕ Ajouter une leçon</h1>
    <a href="<?php echo BASE_URL; ?>index.php?action=formateur_voir_cours&id=<?php echo $cours['id']; ?>" class="btn btn-secondary">← Retour</a>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="<?php echo BASE_URL; ?>index.php?action=formateur_traiter_ajouter_lecon">
                    <input type="hidden" name="cours_id" value="<?php echo $cours['id']; ?>">
                    
                    <div class="mb-3">
                        <label class="form-label">Titre de la leçon</label>
                        <input type="text" name="titre" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Type de contenu</label>
                        <select name="type_contenu" class="form-select" required>
                            <option value="texte">Texte</option>
                            <option value="pdf">PDF</option>
                            <option value="video">Vidéo</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">URL du contenu (si PDF/Vidéo)</label>
                        <input type="text" name="url_contenu" class="form-control">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Contenu texte (optionnel)</label>
                        <textarea name="contenu" class="form-control" rows="5"></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">Ajouter la leçon</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once VIEWS_PATH . '/layouts/footer.php'; ?>