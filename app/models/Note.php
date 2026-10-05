<?php
// Modèle Note

class Note
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // Enregistrer une note
    public function enregistrer($evaluationId, $apprenantId, $note, $progression = 0)
    {
        $sql = "INSERT INTO notes (evaluation_id, apprenant_id, note, progression) 
                VALUES (:evaluation_id, :apprenant_id, :note, :progression)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':evaluation_id' => $evaluationId,
            ':apprenant_id' => $apprenantId,
            ':note' => $note,
            ':progression' => $progression
        ]);
    }

    // Lister les notes d'un apprenant
    public function listerParApprenant($apprenantId)
    {
        $sql = "SELECT n.*, e.titre AS evaluation_titre, e.note_min,
                l.titre AS lecon_titre, c.titre AS cours_titre 
                FROM notes n 
                JOIN evaluations e ON n.evaluation_id = e.id 
                JOIN lecons l ON e.lecon_id = l.id 
                JOIN cours c ON l.cours_id = c.id 
                WHERE n.apprenant_id = :apprenant_id 
                ORDER BY n.date_passage DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':apprenant_id' => $apprenantId]);
        return $stmt->fetchAll();
    }

    // Lister les notes d'une évaluation
    public function listerParEvaluation($evaluationId)
    {
        $sql = "SELECT n.*, CONCAT(u.prenom, ' ', u.nom) AS apprenant_nom 
                FROM notes n 
                JOIN utilisateurs u ON n.apprenant_id = u.id 
                WHERE n.evaluation_id = :evaluation_id 
                ORDER BY n.date_passage DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':evaluation_id' => $evaluationId]);
        return $stmt->fetchAll();
    }

    // Vérifier si un apprenant a déjà passé une évaluation
    public function aDejaPasse($evaluationId, $apprenantId)
    {
        $sql = "SELECT COUNT(*) as total FROM notes 
                WHERE evaluation_id = :evaluation_id AND apprenant_id = :apprenant_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':evaluation_id' => $evaluationId,
            ':apprenant_id' => $apprenantId
        ]);
        return $stmt->fetch()['total'] > 0;
    }

    // Obtenir la meilleure note d'un apprenant pour une évaluation
    public function meilleureNote($evaluationId, $apprenantId)
    {
        $sql = "SELECT MAX(note) as meilleure FROM notes 
                WHERE evaluation_id = :evaluation_id AND apprenant_id = :apprenant_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':evaluation_id' => $evaluationId,
            ':apprenant_id' => $apprenantId
        ]);
        $result = $stmt->fetch();
        return $result['meilleure'] ?? null;
    }

    // Obtenir la moyenne d'un apprenant
    public function moyenneApprenant($apprenantId)
    {
        $sql = "SELECT AVG(note) as moyenne FROM notes WHERE apprenant_id = :apprenant_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':apprenant_id' => $apprenantId]);
        $result = $stmt->fetch();
        return $result['moyenne'] ? round($result['moyenne'], 2) : 0;
    }

    // Compter les évaluations réussies
    public function compterReussies($apprenantId)
    {
        $sql = "SELECT COUNT(*) as total FROM notes n 
                JOIN evaluations e ON n.evaluation_id = e.id 
                WHERE n.apprenant_id = :apprenant_id AND n.note >= e.note_min";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':apprenant_id' => $apprenantId]);
        return $stmt->fetch()['total'];
    }
}