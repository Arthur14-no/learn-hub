<?php
// Modèle Cours - Gestion des cours

class Cours
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // Créer un cours
    public function creer($moduleId, $formateurId, $titre, $description)
    {
        $sql = "INSERT INTO cours (module_id, formateur_id, titre, description, statut) 
                VALUES (:module_id, :formateur_id, :titre, :description, 'brouillon')";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':module_id' => $moduleId,
            ':formateur_id' => $formateurId,
            ':titre' => $titre,
            ':description' => $description
        ]);
    }

    // Lister tous les cours (pour admin)
    public function listerTous()
    {
        $sql = "SELECT c.*, m.titre AS module_titre, 
                CONCAT(u.prenom, ' ', u.nom) AS formateur_nom 
                FROM cours c 
                JOIN modules m ON c.module_id = m.id 
                JOIN utilisateurs u ON c.formateur_id = u.id 
                ORDER BY c.date_creation DESC";
        return $this->db->query($sql)->fetchAll();
    }

    // Lister les cours d'un formateur
   public function listerParFormateur($formateurId)
{
    $sql = "SELECT c.*, m.titre AS module_titre 
            FROM cours c 
            JOIN modules m ON c.module_id = m.id 
            WHERE c.formateur_id = :formateur_id 
            ORDER BY c.date_creation DESC";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([':formateur_id' => $formateurId]);
    return $stmt->fetchAll();
}

    // Lister les cours publiés (pour apprenant)
    public function listerPublies()
    {
        $sql = "SELECT c.*, m.titre AS module_titre, 
                CONCAT(u.prenom, ' ', u.nom) AS formateur_nom 
                FROM cours c 
                JOIN modules m ON c.module_id = m.id 
                JOIN utilisateurs u ON c.formateur_id = u.id 
                WHERE c.statut = 'publie' 
                ORDER BY c.date_creation DESC";
        return $this->db->query($sql)->fetchAll();
    }

    // Trouver un cours par ID
    public function trouverParId($id)
    {
        $sql = "SELECT c.*, m.titre AS module_titre, 
                CONCAT(u.prenom, ' ', u.nom) AS formateur_nom 
                FROM cours c 
                JOIN modules m ON c.module_id = m.id 
                JOIN utilisateurs u ON c.formateur_id = u.id 
                WHERE c.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // Modifier un cours
    public function modifier($id, $titre, $description, $statut)
    {
        $sql = "UPDATE cours SET titre = :titre, description = :description, 
                statut = :statut WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':titre' => $titre,
            ':description' => $description,
            ':statut' => $statut
        ]);
    }

    // Publier un cours
    public function publier($id)
    {
        $sql = "UPDATE cours SET statut = 'publie' WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // Supprimer un cours
    public function supprimer($id)
    {
        $sql = "DELETE FROM cours WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // Compter les cours
    public function compter()
    {
        return $this->db->query("SELECT COUNT(*) as total FROM cours")->fetch()['total'];
    }

    // Vérifier que le cours appartient au formateur
    public function appartientAuFormateur($coursId, $formateurId)
    {
        $sql = "SELECT COUNT(*) as total FROM cours 
                WHERE id = :id AND formateur_id = :formateur_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $coursId, ':formateur_id' => $formateurId]);
        return $stmt->fetch()['total'] > 0;
    }
}