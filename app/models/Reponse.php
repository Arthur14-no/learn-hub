<?php
// Modèle Reponse

class Reponse
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // Créer une réponse
    public function creer($questionId, $texte, $estCorrecte = 0)
    {
        $sql = "INSERT INTO reponses (question_id, texte, est_correcte) 
                VALUES (:question_id, :texte, :est_correcte)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':question_id' => $questionId,
            ':texte' => $texte,
            ':est_correcte' => $estCorrecte ? 1 : 0
        ]);
    }

    // Lister les réponses d'une question
    public function listerParQuestion($questionId)
    {
        $sql = "SELECT * FROM reponses WHERE question_id = :question_id ORDER BY id ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':question_id' => $questionId]);
        return $stmt->fetchAll();
    }

    // Trouver la réponse correcte d'une question
    public function trouverCorrecte($questionId)
    {
        $sql = "SELECT * FROM reponses WHERE question_id = :question_id AND est_correcte = 1 LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':question_id' => $questionId]);
        return $stmt->fetch();
    }

    // Vérifier si une réponse est correcte
    public function estCorrecte($reponseId)
    {
        $sql = "SELECT est_correcte FROM reponses WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $reponseId]);
        $result = $stmt->fetch();
        return $result && $result['est_correcte'] == 1;
    }
}