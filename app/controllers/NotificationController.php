<?php
// Contrôleur Notification

class NotificationController
{
    private $notificationModel;

    public function __construct()
    {
        $this->notificationModel = new Notification();
    }

    // Vérifier que l'utilisateur est connecté
    private function verifierConnecte()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'index.php');
            exit;
        }
    }

    // Liste des notifications
    public function index()
    {
        $this->verifierConnecte();
        $notifications = $this->notificationModel->listerParUtilisateur($_SESSION['user_id'], 50);
        $nbNonLues = $this->notificationModel->compterNonLues($_SESSION['user_id']);
        require_once VIEWS_PATH . '/notifications/index.php';
    }

    // Marquer une notification comme lue
    public function marquerLue()
    {
        $this->verifierConnecte();
        $id = (int)($_GET['id'] ?? 0);

        if ($id > 0) {
            $this->notificationModel->marquerLue($id, $_SESSION['user_id']);
        }

        header('Location: ' . BASE_URL . 'index.php?action=notifications');
        exit;
    }

    // Marquer toutes comme lues
    public function marquerToutesLues()
    {
        $this->verifierConnecte();
        $this->notificationModel->marquerToutesLues($_SESSION['user_id']);
        header('Location: ' . BASE_URL . 'index.php?action=notifications');
        exit;
    }

    // Supprimer une notification
    public function supprimer()
    {
        $this->verifierConnecte();
        $id = (int)($_GET['id'] ?? 0);

        if ($id > 0) {
            $this->notificationModel->supprimer($id, $_SESSION['user_id']);
        }

        header('Location: ' . BASE_URL . 'index.php?action=notifications');
        exit;
    }
}