<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== ROLE_FORMATEUR) {
    header('Location: ' . BASE_URL . 'index.php'); exit;
}
$titre = "Créer un cours";
require_once VIEWS_PATH . '/layouts/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>➕ Créer un cours</h1>
    <a href="<?php echo BASE_URL; ?>index.php?action=formateur_mes_cours" class="btn btn-secondary">← Retour</a>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="<?php echo BASE_URL; ?>index.php?action=formateur_traiter_creer_cours">
                    <div class="mb-3">
                        <label class="form-label">Module</label>
                        <select name="module_id" class="form-select" required>
                            <option value="">-- Choisir un module --</option>
                            <?php foreach ($modules as $m): ?>
                                <option value="<?php echo $m['id']; ?>"><?php echo htmlspecialchars($m['titre']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Titre du cours</label>
                        <input type="text" name="titre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Créer le cours</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once VIEWS_PATH . '/layouts/footer.php'; ?>