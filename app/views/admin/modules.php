<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== ROLE_ADMIN) {
    header('Location: ' . BASE_URL . 'index.php');
    exit;
}
$titre = "Gestion des modules";
require_once VIEWS_PATH . '/layouts/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>📚 Gestion des modules</h1>
    <a href="<?php echo BASE_URL; ?>index.php?action=dashboard_admin" class="btn btn-secondary">← Retour</a>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-primary text-white">➕ Nouveau module</div>
            <div class="card-body">
                <form method="POST" action="<?php echo BASE_URL; ?>index.php?action=admin_creer_module">
                    <div class="mb-3">
                        <label class="form-label">Titre</label>
                        <input type="text" name="titre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Créer</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-dark text-white">
                📋 Liste des modules (<?php echo count($modules); ?>)
            </div>
            <div class="card-body">
                <?php if (empty($modules)): ?>
                    <p class="text-muted">Aucun module pour le moment.</p>
                <?php else: ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Titre</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($modules as $m): ?>
                                <tr>
                                    <td><?php echo $m['id']; ?></td>
                                    <td><?php echo htmlspecialchars($m['titre']); ?></td>
                                    <td><?php echo htmlspecialchars(substr($m['description'] ?? '', 0, 50)); ?>...</td>
                                    <td>
                                        <a href="<?php echo BASE_URL; ?>index.php?action=admin_supprimer_module&id=<?php echo $m['id']; ?>"
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Supprimer ce module ?')">🗑️</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once VIEWS_PATH . '/layouts/footer.php'; ?>