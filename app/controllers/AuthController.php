<?php
// Contrôleur d'authentification

class AuthController
{
    private $utilisateurModel;

    public function __construct()
    {
        $this->utilisateurModel = new Utilisateur();
    }

    // Afficher la page de connexion
    public function afficherConnexion()
    {
        require_once VIEWS_PATH . '/auth/connexion.php';
    }

    // Traiter la connexion
    public function connexion()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'index.php?action=connexion');
            exit;
        }

        $email = trim($_POST['email'] ?? '');
        $motDePasse = $_POST['mot_de_passe'] ?? '';

        if (empty($email) || empty($motDePasse)) {
            $_SESSION['erreur'] = "Veuillez remplir tous les champs.";
            header('Location: ' . BASE_URL . 'index.php?action=connexion');
            exit;
        }

        $user = $this->utilisateurModel->connecter($email, $motDePasse);

        if ($user) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['nom'] = $user['nom'];
            $_SESSION['prenom'] = $user['prenom'];

            // Redirection selon le rôle
            switch ($user['role']) {
                case ROLE_APPRENANT:
                    header('Location: ' . BASE_URL . 'index.php?action=dashboard_apprenant');
                    break;
                case ROLE_FORMATEUR:
                    header('Location: ' . BASE_URL . 'index.php?action=dashboard_formateur');
                    break;
                case ROLE_ADMIN:
                    header('Location: ' . BASE_URL . 'index.php?action=dashboard_admin');
                    break;
                default:
                    header('Location: ' . BASE_URL . 'index.php');
            }
            exit;
        } else {
            $_SESSION['erreur'] = "Email ou mot de passe incorrect.";
            header('Location: ' . BASE_URL . 'index.php?action=connexion');
            exit;
        }
    }

    // Afficher la page d'inscription
    public function afficherInscription()
    {
        require_once VIEWS_PATH . '/auth/inscription.php';
    }

    // Traiter l'inscription
    public function inscription()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'index.php?action=inscription');
            exit;
        }

        $nom = trim($_POST['nom'] ?? '');
        $prenom = trim($_POST['prenom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $motDePasse = $_POST['mot_de_passe'] ?? '';
        $role = $_POST['role'] ?? ROLE_APPRENANT;

        // Validation
        $erreurs = [];
        if (empty($nom)) $erreurs[] = "Le nom est obligatoire.";
        if (empty($prenom)) $erreurs[] = "Le prénom est obligatoire.";
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $erreurs[] = "Email invalide.";
        if (strlen($motDePasse) < 6) $erreurs[] = "Le mot de passe doit contenir au moins 6 caractères.";
        if (!in_array($role, [ROLE_APPRENANT, ROLE_FORMATEUR])) {
            $role = ROLE_APPRENANT; // Par défaut
        }

        if ($this->utilisateurModel->emailExiste($email)) {
            $erreurs[] = "Cet email est déjà utilisé.";
        }

        if (!empty($erreurs)) {
            $_SESSION['erreurs'] = $erreurs;
            $_SESSION['old'] = $_POST;
            header('Location: ' . BASE_URL . 'index.php?action=inscription');
            exit;
        }

        // Inscription
        $success = $this->utilisateurModel->inscrire($nom, $prenom, $email, $motDePasse, $role);

        if ($success) {
            $_SESSION['succes'] = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
            header('Location: ' . BASE_URL . 'index.php?action=connexion');
            exit;
        } else {
            $_SESSION['erreur'] = "Une erreur est survenue lors de l'inscription.";
            header('Location: ' . BASE_URL . 'index.php?action=inscription');
            exit;
        }
    }

    // Déconnexion
    public function deconnexion()
    {
        session_unset();
        session_destroy();
        setcookie(session_name(), '', time() - 3600, '/');
        header('Location: ' . BASE_URL . 'index.php');
        exit;
    }
}