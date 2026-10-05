<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== ROLE_ADMIN) {
    header('Location: ' . BASE_URL . 'index.php');
    exit;
}
$titre = "Gestion des utilisateurs";
require_once VIEWS_PATH . '/layouts/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>👥 Utilisateurs</h1>
    <a href="<?php echo BASE_URL; ?>index.php?action=dashboard_admin" class="btn btn-secondary">← Retour</a>
</div>

<div class="card">
    <div class="card-header bg-dark text-white">
        📋 Liste des utilisateurs (<?php echo count($utilisateurs); ?>)
    </div>
    <div class="card-body">
        <?php if (empty($utilisateurs)): ?>
            <p class="text-muted">Aucun utilisateur.</p>
        <?php else: ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Inscription</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($utilisateurs as $u): ?>
                        <tr>
                            <td><?php echo $u['id']; ?></td>
                            <td><?php echo htmlspecialchars($u['nom']); ?></td>
                            <td><?php echo htmlspecialchars($u['prenom']); ?></td>
                            <td><?php echo htmlspecialchars($u['email']); ?></td>
                            <td>
                                <span class="badge bg-<?php 
                                    echo $u['role'] === 'admin' ? 'danger' : 
                                        ($u['role'] === 'formateur' ? 'success' : 'primary'); 
                                ?>">
                                    <?php echo $u['role']; ?>
                                </span>
                            </td>
                            <td><?php echo $u['date_inscription']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php require_once VIEWS_PATH . '/layouts/footer.php'; ?>