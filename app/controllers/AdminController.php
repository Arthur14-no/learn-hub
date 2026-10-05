<?php
// Contrôleur Admin - Gestion de la plateforme

class AdminController
{
    private $moduleModel;
    private $coursModel;
    private $utilisateurModel;
    private $statistiqueModel;

    public function __construct()
    {
        $this->moduleModel = new Module();
        $this->coursModel = new Cours();
        $this->utilisateurModel = new Utilisateur();
        $this->statistiqueModel = new Statistique();
    }

    // Vérifier que l'utilisateur est admin
    private function verifierAdmin()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== ROLE_ADMIN) {
            header('Location: ' . BASE_URL . 'index.php');
            exit;
        }
    }

    // Dashboard admin
    public function dashboard()
    {
        $this->verifierAdmin();
        $stats = $this->statistiqueModel->globales();
        require_once VIEWS_PATH . '/admin/dashboard.php';
    }

    // Liste des modules
    public function modules()
    {
        $this->verifierAdmin();
        $modules = $this->moduleModel->listerTous();
        require_once VIEWS_PATH . '/admin/modules.php';
    }

    // Créer un module
    public function creerModule()
    {
        $this->verifierAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre = trim($_POST['titre'] ?? '');
            $description = trim($_POST['description'] ?? '');

            if (empty($titre)) {
                $_SESSION['erreur'] = "Le titre est obligatoire.";
            } else {
                if ($this->moduleModel->creer($titre, $description)) {
                    $_SESSION['succes'] = "Module créé avec succès !";
                } else {
                    $_SESSION['erreur'] = "Erreur lors de la création.";
                }
            }
        }

        header('Location: ' . BASE_URL . 'index.php?action=admin_modules');
        exit;
    }

    // Supprimer un module
    public function supprimerModule()
    {
        $this->verifierAdmin();
        $id = (int)($_GET['id'] ?? 0);

        if ($id > 0 && $this->moduleModel->supprimer($id)) {
            $_SESSION['succes'] = "Module supprimé.";
        } else {
            $_SESSION['erreur'] = "Erreur lors de la suppression.";
        }

        header('Location: ' . BASE_URL . 'index.php?action=admin_modules');
        exit;
    }

    // Liste des utilisateurs
    public function utilisateurs()
    {
        $this->verifierAdmin();
        $utilisateurs = $this->utilisateurModel->listerTous();
        require_once VIEWS_PATH . '/admin/utilisateurs.php';
    }

    // Statistiques
    public function statistiques()
    {
        $this->verifierAdmin();
        $stats = $this->statistiqueModel->globales();
        $topCours = $this->statistiqueModel->topCours(5);
        require_once VIEWS_PATH . '/admin/statistiques.php';
    }
}