<?php
// Contrôleur Apprenant - Consultation des cours

class ApprenantController
{
    private $coursModel;
    private $leconModel;
    private $evaluationModel;
    private $progressionModel;
    private $certificatModel;

    public function __construct()
    {
        $this->coursModel = new Cours();
        $this->leconModel = new Lecon();
        $this->evaluationModel = new Evaluation();
        $this->progressionModel = new Progression();
        $this->certificatModel = new Certificat();
    }

    // Vérifier que l'utilisateur est apprenant
    private function verifierApprenant()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== ROLE_APPRENANT) {
            header('Location: ' . BASE_URL . 'index.php');
            exit;
        }
    }

    // Dashboard apprenant
    public function dashboard()
    {
        $this->verifierApprenant();
        
        // Vérifier et générer les certificats automatiquement
        $this->certificatModel->verifierEtGenerer($_SESSION['user_id']);
        
        $cours = $this->coursModel->listerPublies();
        $progression = $this->progressionModel->listerParApprenant($_SESSION['user_id']);
        $nbCoursSuivis = $this->progressionModel->compterParApprenant($_SESSION['user_id']);
        $nbCoursTermines = $this->progressionModel->compterTermines($_SESSION['user_id']);
        $nbCertificats = $this->certificatModel->compterParApprenant($_SESSION['user_id']);
        require_once VIEWS_PATH . '/apprenant/dashboard.php';
    }

    // Liste des cours disponibles
    public function cours()
    {
        $this->verifierApprenant();
        $cours = $this->coursModel->listerPublies();
        require_once VIEWS_PATH . '/apprenant/cours.php';
    }

    // Voir un cours (avec ses leçons)
    public function voirCours()
    {
        $this->verifierApprenant();
        $id = (int)($_GET['id'] ?? 0);
        $cours = $this->coursModel->trouverParId($id);

        if (!$cours || $cours['statut'] !== 'publie') {
            $_SESSION['erreur'] = "Cours introuvable.";
            header('Location: ' . BASE_URL . 'index.php?action=apprenant_cours');
            exit;
        }

        $lecons = $this->leconModel->listerParCours($id);

        // Charger les évaluations pour chaque leçon
        $evaluationsParLecon = [];
        foreach ($lecons as $l) {
            $evaluationsParLecon[$l['id']] = $this->evaluationModel->listerParLecon($l['id']);
        }

        // Mettre à jour la progression
        $this->progressionModel->mettreAJour($_SESSION['user_id'], $id);
        $progression = $this->progressionModel->obtenir($_SESSION['user_id'], $id);

        // Vérifier les certificats
        $this->certificatModel->verifierEtGenerer($_SESSION['user_id']);

        require_once VIEWS_PATH . '/apprenant/voir_cours.php';
    }

    // Voir sa progression
    public function progression()
    {
        $this->verifierApprenant();

        // Mettre à jour toutes les progressions
        $coursSuivis = $this->progressionModel->listerParApprenant($_SESSION['user_id']);
        foreach ($coursSuivis as $c) {
            $this->progressionModel->mettreAJour($_SESSION['user_id'], $c['cours_id']);
        }

        // Vérifier les certificats
        $this->certificatModel->verifierEtGenerer($_SESSION['user_id']);

        // Recharger après mise à jour
        $progression = $this->progressionModel->listerParApprenant($_SESSION['user_id']);
        $nbCoursSuivis = $this->progressionModel->compterParApprenant($_SESSION['user_id']);
        $nbCoursTermines = $this->progressionModel->compterTermines($_SESSION['user_id']);

        require_once VIEWS_PATH . '/apprenant/progression.php';
    }

    // Voir ses certificats
    public function certificats()
    {
        $this->verifierApprenant();

        // Vérifier et générer les certificats automatiquement
        $this->certificatModel->verifierEtGenerer($_SESSION['user_id']);

        $certificats = $this->certificatModel->listerParApprenant($_SESSION['user_id']);
        $nbCertificats = count($certificats);

        require_once VIEWS_PATH . '/apprenant/certificats.php';
    }
}