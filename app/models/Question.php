<?php
// Modèle Question

class Question
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // Créer une question
    public function creer($evaluationId, $enonce, $type = 'qcm', $points = 1)
    {
        $sql = "INSERT INTO questions (evaluation_id, enonce, type, points) 
                VALUES (:evaluation_id, :enonce, :type, :points)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':evaluation_id' => $evaluationId,
            ':enonce' => $enonce,
            ':type' => $type,
            ':points' => $points
        ]);
        return $this->db->lastInsertId();
    }

    // Lister les questions d'une évaluation
    public function listerParEvaluation($evaluationId)
    {
        $sql = "SELECT * FROM questions WHERE evaluation_id = :evaluation_id ORDER BY id ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':evaluation_id' => $evaluationId]);
        return $stmt->fetchAll();
    }

    // Trouver une question par ID
    public function trouverParId($id)
    {
        $sql = "SELECT * FROM questions WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // Supprimer une question
    public function supprimer($id)
    {
        $sql = "DELETE FROM questions WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}