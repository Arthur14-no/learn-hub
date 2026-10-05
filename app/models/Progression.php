<?php
// Modèle Progression - Suivi de l'avancement des apprenants

class Progression
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // Créer ou mettre à jour la progression
    public function enregistrer($apprenantId, $coursId, $pourcentage)
    {
        // Vérifier si la progression existe déjà
        $sql = "SELECT id FROM progression 
                WHERE apprenant_id = :apprenant_id AND cours_id = :cours_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':apprenant_id' => $apprenantId,
            ':cours_id' => $coursId
        ]);
        $existante = $stmt->fetch();

        if ($existante) {
            // Mettre à jour
            $sql = "UPDATE progression 
                    SET pourcentage = :pourcentage 
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':pourcentage' => $pourcentage,
                ':id' => $existante['id']
            ]);
        } else {
            // Créer
            $sql = "INSERT INTO progression (apprenant_id, cours_id, pourcentage) 
                    VALUES (:apprenant_id, :cours_id, :pourcentage)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':apprenant_id' => $apprenantId,
                ':cours_id' => $coursId,
                ':pourcentage' => $pourcentage
            ]);
        }
    }

    // Obtenir la progression d'un apprenant pour un cours
    public function obtenir($apprenantId, $coursId)
    {
        $sql = "SELECT * FROM progression 
                WHERE apprenant_id = :apprenant_id AND cours_id = :cours_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':apprenant_id' => $apprenantId,
            ':cours_id' => $coursId
        ]);
        return $stmt->fetch();
    }

    // Lister toute la progression d'un apprenant
    public function listerParApprenant($apprenantId)
    {
        $sql = "SELECT p.*, c.titre AS cours_titre, c.description AS cours_description,
                m.titre AS module_titre,
                (SELECT COUNT(*) FROM lecons WHERE cours_id = c.id) AS total_lecons
                FROM progression p 
                JOIN cours c ON p.cours_id = c.id 
                JOIN modules m ON c.module_id = m.id 
                WHERE p.apprenant_id = :apprenant_id 
                ORDER BY p.date_debut DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':apprenant_id' => $apprenantId]);
        return $stmt->fetchAll();
    }

    // Calculer la progression d'un apprenant dans un cours
    // Basée sur le nombre de leçons consultées et d'évaluations réussies
    public function calculer($apprenantId, $coursId)
    {
        // Nombre total de leçons du cours
        $sql = "SELECT COUNT(*) as total FROM lecons WHERE cours_id = :cours_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':cours_id' => $coursId]);
        $totalLecons = $stmt->fetch()['total'];

        if ($totalLecons == 0) {
            return 0;
        }

        // Nombre d'évaluations réussies dans ce cours
        $sql = "SELECT COUNT(DISTINCT e.id) as total 
                FROM notes n 
                JOIN evaluations e ON n.evaluation_id = e.id 
                JOIN lecons l ON e.lecon_id = l.id 
                WHERE n.apprenant_id = :apprenant_id 
                AND l.cours_id = :cours_id 
                AND n.note >= e.note_min";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':apprenant_id' => $apprenantId,
            ':cours_id' => $coursId
        ]);
        $evaluationsReussies = $stmt->fetch()['total'];

        // Progression = (évaluations réussies / total leçons) * 100
        $pourcentage = ($evaluationsReussies / $totalLecons) * 100;

        return min(100, round($pourcentage, 2));
    }

    // Mettre à jour automatiquement la progression
    public function mettreAJour($apprenantId, $coursId)
    {
        $pourcentage = $this->calculer($apprenantId, $coursId);
        return $this->enregistrer($apprenantId, $coursId, $pourcentage);
    }

    // Compter les cours suivis par un apprenant
    public function compterParApprenant($apprenantId)
    {
        $sql = "SELECT COUNT(*) as total FROM progression 
                WHERE apprenant_id = :apprenant_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':apprenant_id' => $apprenantId]);
        return $stmt->fetch()['total'];
    }

    // Compter les cours terminés (100%) par un apprenant
    public function compterTermines($apprenantId)
    {
        $sql = "SELECT COUNT(*) as total FROM progression 
                WHERE apprenant_id = :apprenant_id AND pourcentage >= 100";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':apprenant_id' => $apprenantId]);
        return $stmt->fetch()['total'];
    }
}