<?php
// Contrôleur Evaluation

class EvaluationController
{
    private $evaluationModel;
    private $questionModel;
    private $reponseModel;
    private $noteModel;
    private $leconModel;
    private $coursModel;

    public function __construct()
    {
        $this->evaluationModel = new Evaluation();
        $this->questionModel = new Question();
        $this->reponseModel = new Reponse();
        $this->noteModel = new Note();
        $this->leconModel = new Lecon();
        $this->coursModel = new Cours();
    }

    // Vérifier que l'utilisateur est formateur
    private function verifierFormateur()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== ROLE_FORMATEUR) {
            header('Location: ' . BASE_URL . 'index.php');
            exit;
        }
    }

    // Vérifier que l'utilisateur est apprenant
    private function verifierApprenant()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== ROLE_APPRENANT) {
            header('Location: ' . BASE_URL . 'index.php');
            exit;
        }
    }

    // ==================== FORMATEUR ====================

    public function mesEvaluations()
    {
        $this->verifierFormateur();
        $coursId = (int)($_GET['cours_id'] ?? 0);
        $cours = $this->coursModel->trouverParId($coursId);

        if (!$cours || $cours['formateur_id'] != $_SESSION['user_id']) {
            $_SESSION['erreur'] = "Cours introuvable.";
            header('Location: ' . BASE_URL . 'index.php?action=formateur_mes_cours');
            exit;
        }

        $evaluations = $this->evaluationModel->listerParCours($coursId);
        require_once VIEWS_PATH . '/formateur/evaluations.php';
    }

    public function afficherCreerEvaluation()
    {
        $this->verifierFormateur();
        $leconId = (int)($_GET['lecon_id'] ?? 0);
        $lecon = $this->leconModel->trouverParId($leconId);

        if (!$lecon) {
            $_SESSION['erreur'] = "Leçon introuvable.";
            header('Location: ' . BASE_URL . 'index.php?action=formateur_mes_cours');
            exit;
        }

        require_once VIEWS_PATH . '/formateur/creer_evaluation.php';
    }

    public function traiterCreerEvaluation()
    {
        $this->verifierFormateur();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'index.php?action=formateur_mes_cours');
            exit;
        }

        $leconId = (int)($_POST['lecon_id'] ?? 0);
        $titre = trim($_POST['titre'] ?? '');
        $duree = (int)($_POST['duree'] ?? 0);
        $noteMin = (float)($_POST['note_min'] ?? 10);

        if (empty($titre) || $leconId <= 0) {
            $_SESSION['erreur'] = "Titre obligatoire.";
            header('Location: ' . BASE_URL . 'index.php?action=formateur_creer_evaluation&lecon_id=' . $leconId);
            exit;
        }

        $evaluationId = $this->evaluationModel->creer($leconId, $titre, $duree, $noteMin);

        if ($evaluationId) {
            $_SESSION['succes'] = "Évaluation créée ! Ajoutez maintenant des questions.";
            header('Location: ' . BASE_URL . 'index.php?action=formateur_ajouter_question&evaluation_id=' . $evaluationId);
        } else {
            $_SESSION['erreur'] = "Erreur lors de la création.";
            header('Location: ' . BASE_URL . 'index.php?action=formateur_creer_evaluation&lecon_id=' . $leconId);
        }
        exit;
    }

    public function afficherAjouterQuestion()
    {
        $this->verifierFormateur();
        $evaluationId = (int)($_GET['evaluation_id'] ?? 0);
        $evaluation = $this->evaluationModel->trouverParId($evaluationId);

        if (!$evaluation) {
            $_SESSION['erreur'] = "Évaluation introuvable.";
            header('Location: ' . BASE_URL . 'index.php?action=formateur_mes_cours');
            exit;
        }

        $questions = $this->questionModel->listerParEvaluation($evaluationId);

        $reponsesParQuestion = [];
        foreach ($questions as $q) {
            $reponsesParQuestion[$q['id']] = $this->reponseModel->listerParQuestion($q['id']);
        }

        require_once VIEWS_PATH . '/formateur/ajouter_question.php';
    }

    public function traiterAjouterQuestion()
    {
        $this->verifierFormateur();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'index.php?action=formateur_mes_cours');
            exit;
        }

        $evaluationId = (int)($_POST['evaluation_id'] ?? 0);
        $enonce = trim($_POST['enonce'] ?? '');
        $points = (float)($_POST['points'] ?? 1);
        $reponses = $_POST['reponses'] ?? [];
        $correcte = (int)($_POST['correcte'] ?? -1);

        if (empty($enonce) || count($reponses) < 2 || $correcte < 0) {
            $_SESSION['erreur'] = "Énoncé et au moins 2 réponses obligatoires, avec une réponse correcte.";
            header('Location: ' . BASE_URL . 'index.php?action=formateur_ajouter_question&evaluation_id=' . $evaluationId);
            exit;
        }

        $questionId = $this->questionModel->creer($evaluationId, $enonce, 'qcm', $points);

        if ($questionId) {
            foreach ($reponses as $index => $texte) {
                if (!empty(trim($texte))) {
                    $estCorrecte = ($index == $correcte) ? 1 : 0;
                    $this->reponseModel->creer($questionId, trim($texte), $estCorrecte);
                }
            }
            $_SESSION['succes'] = "Question ajoutée !";
        } else {
            $_SESSION['erreur'] = "Erreur lors de l'ajout.";
        }

        header('Location: ' . BASE_URL . 'index.php?action=formateur_ajouter_question&evaluation_id=' . $evaluationId);
        exit;
    }

    public function terminerEvaluation()
    {
        $this->verifierFormateur();
        $evaluationId = (int)($_GET['evaluation_id'] ?? 0);
        $evaluation = $this->evaluationModel->trouverParId($evaluationId);

        if ($evaluation) {
            $_SESSION['succes'] = "Évaluation terminée !";
            header('Location: ' . BASE_URL . 'index.php?action=formateur_mes_evaluations&cours_id=' . $evaluation['cours_id']);
        } else {
            header('Location: ' . BASE_URL . 'index.php?action=formateur_mes_cours');
        }
        exit;
    }

    // ==================== APPRENANT ====================

    public function passerEvaluation()
    {
        $this->verifierApprenant();
        $evaluationId = (int)($_GET['id'] ?? 0);
        $evaluation = $this->evaluationModel->trouverParId($evaluationId);

        if (!$evaluation) {
            $_SESSION['erreur'] = "Évaluation introuvable.";
            header('Location: ' . BASE_URL . 'index.php?action=apprenant_cours');
            exit;
        }

        $questions = $this->questionModel->listerParEvaluation($evaluationId);

        if (empty($questions)) {
            $_SESSION['erreur'] = "Cette évaluation ne contient aucune question.";
            header('Location: ' . BASE_URL . 'index.php?action=apprenant_voir_cours&id=' . $evaluation['cours_id']);
            exit;
        }

        $reponsesParQuestion = [];
        foreach ($questions as $q) {
            $reponsesParQuestion[$q['id']] = $this->reponseModel->listerParQuestion($q['id']);
        }

        require_once VIEWS_PATH . '/apprenant/passer_evaluation.php';
    }

    public function traiterEvaluation()
    {
        $this->verifierApprenant();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'index.php?action=apprenant_cours');
            exit;
        }

        $evaluationId = (int)($_POST['evaluation_id'] ?? 0);
        $reponsesDonnees = $_POST['reponses'] ?? [];

        $evaluation = $this->evaluationModel->trouverParId($evaluationId);
        if (!$evaluation) {
            $_SESSION['erreur'] = "Évaluation introuvable.";
            header('Location: ' . BASE_URL . 'index.php?action=apprenant_cours');
            exit;
        }

        $questions = $this->questionModel->listerParEvaluation($evaluationId);
        $totalPoints = 0;
        $pointsObtenus = 0;
        $details = [];

        foreach ($questions as $q) {
            $totalPoints += $q['points'];
            $reponseId = $reponsesDonnees[$q['id']] ?? null;
            $correcte = false;

            if ($reponseId) {
                $correcte = $this->reponseModel->estCorrecte((int)$reponseId);
            }

            if ($correcte) {
                $pointsObtenus += $q['points'];
            }

            $details[] = [
                'question' => $q['enonce'],
                'correcte' => $correcte,
                'points' => $q['points']
            ];
        }

        $note = $totalPoints > 0 ? ($pointsObtenus / $totalPoints) * 20 : 0;
        $progression = $totalPoints > 0 ? ($pointsObtenus / $totalPoints) * 100 : 0;

        $this->noteModel->enregistrer($evaluationId, $_SESSION['user_id'], $note, $progression);

        $_SESSION['resultat_evaluation'] = [
            'note' => round($note, 2),
            'progression' => round($progression, 2),
            'details' => $details,
            'evaluation_titre' => $evaluation['titre'],
            'cours_id' => $evaluation['cours_id']
        ];

        header('Location: ' . BASE_URL . 'index.php?action=apprenant_resultat_evaluation');
        exit;
    }

    public function afficherResultat()
    {
        $this->verifierApprenant();

        if (!isset($_SESSION['resultat_evaluation'])) {
            header('Location: ' . BASE_URL . 'index.php?action=apprenant_cours');
            exit;
        }

        $resultat = $_SESSION['resultat_evaluation'];
        unset($_SESSION['resultat_evaluation']);

        require_once VIEWS_PATH . '/apprenant/resultat_evaluation.php';
    }

    // ==================== MES NOTES ====================

    public function mesNotes()
    {
        $this->verifierApprenant();
        
        $notes = $this->noteModel->listerParApprenant($_SESSION['user_id']);
        $moyenne = $this->noteModel->moyenneApprenant($_SESSION['user_id']);
        $reussies = $this->noteModel->compterReussies($_SESSION['user_id']);
        
        require_once VIEWS_PATH . '/apprenant/mes_notes.php';
    }
}