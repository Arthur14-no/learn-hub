<?php
// Modèle Leçon - Gestion des leçons

class Lecon
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // Créer une leçon
    public function creer($coursId, $titre, $typeContenu, $urlContenu, $contenu = null)
    {
        // Récupérer le prochain ordre
        $sql = "SELECT COALESCE(MAX(ordre), 0) + 1 AS prochain_ordre 
                FROM lecons WHERE cours_id = :cours_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':cours_id' => $coursId]);
        $ordre = $stmt->fetch()['prochain_ordre'];

        $sql = "INSERT INTO lecons (cours_id, titre, type_contenu, url_contenu, contenu, ordre) 
                VALUES (:cours_id, :titre, :type_contenu, :url_contenu, :contenu, :ordre)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':cours_id' => $coursId,
            ':titre' => $titre,
            ':type_contenu' => $typeContenu,
            ':url_contenu' => $urlContenu,
            ':contenu' => $contenu,
            ':ordre' => $ordre
        ]);
    }

    // Lister les leçons d'un cours
    public function listerParCours($coursId)
    {
        $sql = "SELECT * FROM lecons WHERE cours_id = :cours_id ORDER BY ordre ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':cours_id' => $coursId]);
        return $stmt->fetchAll();
    }

    // Trouver une leçon par ID
    public function trouverParId($id)
    {
        $sql = "SELECT * FROM lecons WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // Modifier une leçon
    public function modifier($id, $titre, $typeContenu, $urlContenu, $contenu)
    {
        $sql = "UPDATE lecons SET titre = :titre, type_contenu = :type_contenu, 
                url_contenu = :url_contenu, contenu = :contenu WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':titre' => $titre,
            ':type_contenu' => $typeContenu,
            ':url_contenu' => $urlContenu,
            ':contenu' => $contenu
        ]);
    }

    // Supprimer une leçon
    public function supprimer($id)
    {
        $sql = "DELETE FROM lecons WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // Compter les leçons
    public function compter()
    {
        return $this->db->query("SELECT COUNT(*) as total FROM lecons")->fetch()['total'];
    }
}