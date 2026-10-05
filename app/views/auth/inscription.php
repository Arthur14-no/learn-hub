<?php $titre = "Inscription"; require_once VIEWS_PATH . '/layouts/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow">
            <div class="card-body p-4">
                <h2 class="text-center mb-4">📝 Inscription</h2>

                <?php if (isset($_SESSION['erreurs'])): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($_SESSION['erreurs'] as $err): ?>
                                <li><?php echo $err; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php unset($_SESSION['erreurs']); ?>
                <?php endif; ?>

                <form method="POST" action="<?php echo BASE_URL; ?>index.php?action=traiter_inscription">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nom" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="nom" name="nom" required
                                   value="<?php echo htmlspecialchars($_SESSION['old']['nom'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="prenom" class="form-label">Prénom</label>
                            <input type="text" class="form-control" id="prenom" name="prenom" required
                                   value="<?php echo htmlspecialchars($_SESSION['old']['prenom'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required
                               value="<?php echo htmlspecialchars($_SESSION['old']['email'] ?? ''); ?>">
                    </div>

                    <div class="mb-3">
                        <label for="mot_de_passe" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" required minlength="6">
                        <small class="text-muted">Au moins 6 caractères</small>
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">Je m'inscris en tant que</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="apprenant">Apprenant</option>
                            <option value="formateur">Formateur</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">S'inscrire</button>
                </form>

                <hr class="my-4">
                <p class="text-center mb-0">
                    Déjà un compte ?
                    <a href="<?php echo BASE_URL; ?>index.php?action=connexion">Se connecter</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php unset($_SESSION['old']); ?>
<?php require_once VIEWS_PATH . '/layouts/footer.php'; ?>