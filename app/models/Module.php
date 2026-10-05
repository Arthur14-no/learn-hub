<?php
// Modèle Module - Gestion des modules de formation

class Module
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // Créer un module
    public function creer($titre, $description, $categorieId = null)
    {
        $sql = "INSERT INTO modules (titre, description, categorie_id) 
                VALUES (:titre, :description, :categorie_id)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':titre' => $titre,
            ':description' => $description,
            ':categorie_id' => $categorieId
        ]);
    }

    // Lister tous les modules
    public function listerTous()
    {
        $sql = "SELECT m.*, c.nom AS categorie_nom 
                FROM modules m 
                LEFT JOIN categories c ON m.categorie_id = c.id 
                ORDER BY m.date_creation DESC";
        return $this->db->query($sql)->fetchAll();
    }

    // Trouver un module par ID
    public function trouverParId($id)
    {
        $sql = "SELECT * FROM modules WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // Modifier un module
    public function modifier($id, $titre, $description, $categorieId = null)
    {
        $sql = "UPDATE modules SET titre = :titre, description = :description, 
                categorie_id = :categorie_id WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':titre' => $titre,
            ':description' => $description,
            ':categorie_id' => $categorieId
        ]);
    }

    // Supprimer un module
    public function supprimer($id)
    {
        $sql = "DELETE FROM modules WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // Compter les modules
    public function compter()
    {
        return $this->db->query("SELECT COUNT(*) as total FROM modules")->fetch()['total'];
    }
}