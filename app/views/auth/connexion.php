<?php $titre = "Connexion"; require_once VIEWS_PATH . '/layouts/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow">
            <div class="card-body p-4">
                <h2 class="text-center mb-4">🔐 Connexion</h2>

                <form method="POST" action="<?php echo BASE_URL; ?>index.php?action=traiter_connexion">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <div class="mb-3">
                        <label for="mot_de_passe" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Se connecter</button>
                </form>

                <hr class="my-4">
                <p class="text-center mb-0">
                    Pas encore de compte ?
                    <a href="<?php echo BASE_URL; ?>index.php?action=inscription">S'inscrire</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once VIEWS_PATH . '/layouts/footer.php'; ?>