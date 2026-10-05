<?php
// Modèle Evaluation

class Evaluation
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // Créer une évaluation
    public function creer($leconId, $titre, $duree = 0, $noteMin = 10)
    {
        $sql = "INSERT INTO evaluations (lecon_id, titre, duree, note_min) 
                VALUES (:lecon_id, :titre, :duree, :note_min)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':lecon_id' => $leconId,
            ':titre' => $titre,
            ':duree' => $duree,
            ':note_min' => $noteMin
        ]);
        return $this->db->lastInsertId();
    }

    // Trouver une évaluation par ID
    public function trouverParId($id)
    {
        $sql = "SELECT e.*, l.titre AS lecon_titre, l.cours_id 
                FROM evaluations e 
                JOIN lecons l ON e.lecon_id = l.id 
                WHERE e.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // Lister les évaluations d'une leçon
    public function listerParLecon($leconId)
    {
        $sql = "SELECT * FROM evaluations WHERE lecon_id = :lecon_id ORDER BY id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':lecon_id' => $leconId]);
        return $stmt->fetchAll();
    }

    // Lister les évaluations d'un cours
    public function listerParCours($coursId)
    {
        $sql = "SELECT e.*, l.titre AS lecon_titre 
                FROM evaluations e 
                JOIN lecons l ON e.lecon_id = l.id 
                WHERE l.cours_id = :cours_id 
                ORDER BY l.ordre ASC, e.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':cours_id' => $coursId]);
        return $stmt->fetchAll();
    }

    // Supprimer une évaluation
    public function supprimer($id)
    {
        $sql = "DELETE FROM evaluations WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // Compter les évaluations
    public function compter()
    {
        return $this->db->query("SELECT COUNT(*) as total FROM evaluations")->fetch()['total'];
    }
}