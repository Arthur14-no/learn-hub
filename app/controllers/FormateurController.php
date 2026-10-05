<?php
// Contrôleur Formateur - Gestion des cours

class FormateurController
{
    private $coursModel;
    private $leconModel;
    private $moduleModel;

    public function __construct()
    {
        $this->coursModel = new Cours();
        $this->leconModel = new Lecon();
        $this->moduleModel = new Module();
    }

    // Vérifier que l'utilisateur est formateur
    private function verifierFormateur()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== ROLE_FORMATEUR) {
            header('Location: ' . BASE_URL . 'index.php');
            exit;
        }
    }

    // Dashboard formateur
    public function dashboard()
    {
        $this->verifierFormateur();
        $cours = $this->coursModel->listerParFormateur($_SESSION['user_id']);
        require_once VIEWS_PATH . '/formateur/dashboard.php';
    }

    // Liste des cours du formateur
    public function mesCours()
    {
        $this->verifierFormateur();
        $cours = $this->coursModel->listerParFormateur($_SESSION['user_id']);
        require_once VIEWS_PATH . '/formateur/cours.php';
    }

    // Formulaire de création de cours
    public function creerCours()
    {
        $this->verifierFormateur();
        $modules = $this->moduleModel->listerTous();
        require_once VIEWS_PATH . '/formateur/creer_cours.php';
    }

    // Traitement de création de cours
    public function traiterCreerCours()
    {
        $this->verifierFormateur();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'index.php?action=formateur_creer_cours');
            exit;
        }

        $moduleId = (int)($_POST['module_id'] ?? 0);
        $titre = trim($_POST['titre'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if (empty($titre) || $moduleId <= 0) {
            $_SESSION['erreur'] = "Titre et module obligatoires.";
            header('Location: ' . BASE_URL . 'index.php?action=formateur_creer_cours');
            exit;
        }

        if ($this->coursModel->creer($moduleId, $_SESSION['user_id'], $titre, $description)) {
            $_SESSION['succes'] = "Cours créé avec succès !";
            header('Location: ' . BASE_URL . 'index.php?action=formateur_mes_cours');
        } else {
            $_SESSION['erreur'] = "Erreur lors de la création.";
            header('Location: ' . BASE_URL . 'index.php?action=formateur_creer_cours');
        }
        exit;
    }

    // Voir un cours (avec ses leçons)
    public function voirCours()
    {
        $this->verifierFormateur();
        $id = (int)($_GET['id'] ?? 0);
        $cours = $this->coursModel->trouverParId($id);

        if (!$cours || $cours['formateur_id'] != $_SESSION['user_id']) {
            $_SESSION['erreur'] = "Cours introuvable.";
            header('Location: ' . BASE_URL . 'index.php?action=formateur_mes_cours');
            exit;
        }

        $lecons = $this->leconModel->listerParCours($id);
        require_once VIEWS_PATH . '/formateur/voir_cours.php';
    }

    // Publier un cours
    public function publierCours()
    {
        $this->verifierFormateur();
        $id = (int)($_GET['id'] ?? 0);

        if ($this->coursModel->appartientAuFormateur($id, $_SESSION['user_id'])) {
            $this->coursModel->publier($id);
            $_SESSION['succes'] = "Cours publié !";
        } else {
            $_SESSION['erreur'] = "Action non autorisée.";
        }

        header('Location: ' . BASE_URL . 'index.php?action=formateur_mes_cours');
        exit;
    }

    // Ajouter une leçon
    public function ajouterLecon()
    {
        $this->verifierFormateur();
        $coursId = (int)($_GET['cours_id'] ?? 0);
        $cours = $this->coursModel->trouverParId($coursId);

        if (!$cours || $cours['formateur_id'] != $_SESSION['user_id']) {
            $_SESSION['erreur'] = "Cours introuvable.";
            header('Location: ' . BASE_URL . 'index.php?action=formateur_mes_cours');
            exit;
        }

        require_once VIEWS_PATH . '/formateur/ajouter_lecon.php';
    }

    // Traitement ajout leçon
    public function traiterAjouterLecon()
    {
        $this->verifierFormateur();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'index.php?action=formateur_mes_cours');
            exit;
        }

        $coursId = (int)($_POST['cours_id'] ?? 0);
        $titre = trim($_POST['titre'] ?? '');
        $typeContenu = $_POST['type_contenu'] ?? 'texte';
        $urlContenu = trim($_POST['url_contenu'] ?? '');
        $contenu = trim($_POST['contenu'] ?? '');

        if (empty($titre) || $coursId <= 0) {
            $_SESSION['erreur'] = "Titre obligatoire.";
            header('Location: ' . BASE_URL . 'index.php?action=formateur_ajouter_lecon&cours_id=' . $coursId);
            exit;
        }

        if ($this->leconModel->creer($coursId, $titre, $typeContenu, $urlContenu, $contenu)) {
            $_SESSION['succes'] = "Leçon ajoutée !";
            header('Location: ' . BASE_URL . 'index.php?action=formateur_voir_cours&id=' . $coursId);
        } else {
            $_SESSION['erreur'] = "Erreur lors de l'ajout.";
            header('Location: ' . BASE_URL . 'index.php?action=formateur_ajouter_lecon&cours_id=' . $coursId);
        }
        exit;
    }
}