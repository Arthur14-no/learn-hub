<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titre ?? APP_NAME; ?> - <?php echo APP_NAME; ?></title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>📚</text></svg>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/style.css">
</head>
<body>

<?php
// Compter les notifications non lues si connecté
$nbNotificationsNonLues = 0;
if (isset($_SESSION['user_id'])) {
    require_once MODELS_PATH . '/Notification.php';
    $notifModel = new Notification();
    $nbNotificationsNonLues = $notifModel->compterNonLues($_SESSION['user_id']);
}

// Déterminer le dashboard selon le rôle
$dashboardAction = 'accueil';
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === ROLE_APPRENANT) {
        $dashboardAction = 'dashboard_apprenant';
    } elseif ($_SESSION['role'] === ROLE_FORMATEUR) {
        $dashboardAction = 'dashboard_formateur';
    } elseif ($_SESSION['role'] === ROLE_ADMIN) {
        $dashboardAction = 'dashboard_admin';
    }
}
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="<?php echo BASE_URL; ?>index.php<?php echo isset($_SESSION['user_id']) ? '?action=' . $dashboardAction : ''; ?>">
            📚 <?php echo APP_NAME; ?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item me-2">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>index.php?action=<?php echo $dashboardAction; ?>">
                            <i class="bi bi-house-door"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item me-2">
                        <a class="nav-link position-relative" href="<?php echo BASE_URL; ?>index.php?action=notifications">
                            <i class="bi bi-bell fs-5"></i>
                            <?php if ($nbNotificationsNonLues > 0): ?>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    <?php echo $nbNotificationsNonLues; ?>
                                </span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <li class="nav-item me-2">
                        <span class="nav-link d-flex align-items-center">
                            <span class="avatar-circle me-2">
                                <?php echo strtoupper(substr($_SESSION['prenom'], 0, 1) . substr($_SESSION['nom'], 0, 1)); ?>
                            </span>
                            <?php echo htmlspecialchars($_SESSION['prenom'] . ' ' . $_SESSION['nom']); ?>
                            <span class="badge bg-light text-primary ms-2"><?php echo $_SESSION['role']; ?></span>
                        </span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>index.php?action=deconnexion">
                            <i class="bi bi-box-arrow-right"></i> Déconnexion
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<main class="container my-4">
    <?php if (isset($_SESSION['erreur'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?php echo $_SESSION['erreur']; unset($_SESSION['erreur']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['succes'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo $_SESSION['succes']; unset($_SESSION['succes']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
