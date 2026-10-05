<?php
// Point d'entrée de l'application Learn Hub

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

// Autoload simple des classes
spl_autoload_register(function ($class) {
    $paths = [
        MODELS_PATH . "/$class.php",
        CONTROLLERS_PATH . "/$class.php",
    ];
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// Récupérer l'action
$action = $_GET['action'] ?? 'accueil';

// Router
switch ($action) {
    // ==================== ACCUEIL ====================
    case 'accueil':
        require_once VIEWS_PATH . '/accueil.php';
        break;

    // ==================== AUTHENTIFICATION ====================
    case 'connexion':
        (new AuthController())->afficherConnexion();
        break;
    case 'traiter_connexion':
        (new AuthController())->connexion();
        break;
    case 'inscription':
        (new AuthController())->afficherInscription();
        break;
    case 'traiter_inscription':
        (new AuthController())->inscription();
        break;
    case 'deconnexion':
        (new AuthController())->deconnexion();
        break;

    // ==================== APPRENANT ====================
    case 'dashboard_apprenant':
        (new ApprenantController())->dashboard();
        break;
    case 'apprenant_cours':
        (new ApprenantController())->cours();
        break;
    case 'apprenant_voir_cours':
        (new ApprenantController())->voirCours();
        break;
    case 'apprenant_progression':
        (new ApprenantController())->progression();
        break;
    case 'apprenant_certificats':
        (new ApprenantController())->certificats();
        break;

    // ==================== FORMATEUR ====================
    case 'dashboard_formateur':
        (new FormateurController())->dashboard();
        break;
    case 'formateur_mes_cours':
        (new FormateurController())->mesCours();
        break;
    case 'formateur_creer_cours':
        (new FormateurController())->creerCours();
        break;
    case 'formateur_traiter_creer_cours':
        (new FormateurController())->traiterCreerCours();
        break;
    case 'formateur_voir_cours':
        (new FormateurController())->voirCours();
        break;
    case 'formateur_publier_cours':
        (new FormateurController())->publierCours();
        break;
    case 'formateur_ajouter_lecon':
        (new FormateurController())->ajouterLecon();
        break;
    case 'formateur_traiter_ajouter_lecon':
        (new FormateurController())->traiterAjouterLecon();
        break;

    // ==================== ADMIN ====================
    case 'dashboard_admin':
        (new AdminController())->dashboard();
        break;
    case 'admin_modules':
        (new AdminController())->modules();
        break;
    case 'admin_creer_module':
        (new AdminController())->creerModule();
        break;
    case 'admin_supprimer_module':
        (new AdminController())->supprimerModule();
        break;
    case 'admin_utilisateurs':
        (new AdminController())->utilisateurs();
        break;
    case 'admin_statistiques':
        (new AdminController())->statistiques();
        break;

    // ==================== ÉVALUATIONS (FORMATEUR) ====================
    case 'formateur_mes_evaluations':
        (new EvaluationController())->mesEvaluations();
        break;
    case 'formateur_creer_evaluation':
        (new EvaluationController())->afficherCreerEvaluation();
        break;
    case 'formateur_traiter_creer_evaluation':
        (new EvaluationController())->traiterCreerEvaluation();
        break;
    case 'formateur_ajouter_question':
        (new EvaluationController())->afficherAjouterQuestion();
        break;
    case 'formateur_traiter_ajouter_question':
        (new EvaluationController())->traiterAjouterQuestion();
        break;
    case 'formateur_terminer_evaluation':
        (new EvaluationController())->terminerEvaluation();
        break;

    // ==================== ÉVALUATIONS (APPRENANT) ====================
    case 'apprenant_passer_evaluation':
        (new EvaluationController())->passerEvaluation();
        break;
    case 'apprenant_traiter_evaluation':
        (new EvaluationController())->traiterEvaluation();
        break;
    case 'apprenant_resultat_evaluation':
        (new EvaluationController())->afficherResultat();
        break;
    case 'apprenant_mes_notes':
        (new EvaluationController())->mesNotes();
        break;

    // ==================== NOTIFICATIONS ====================
    case 'notifications':
        (new NotificationController())->index();
        break;
    case 'notifications_marquer_lue':
        (new NotificationController())->marquerLue();
        break;
    case 'notifications_marquer_toutes_lues':
        (new NotificationController())->marquerToutesLues();
        break;
    case 'notifications_supprimer':
        (new NotificationController())->supprimer();
        break;

    // ==================== PAR DÉFAUT ====================
    default:
        require_once VIEWS_PATH . '/accueil.php';
        break;
}