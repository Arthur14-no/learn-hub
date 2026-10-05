<?php
// Modèle Certificat - Gestion des certificats

class Certificat
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // Créer un certificat
    public function creer($apprenantId, $moduleId, $url = null)
    {
        // Vérifier si le certificat existe déjà
        if ($this->existe($apprenantId, $moduleId)) {
            return false;
        }

        $sql = "INSERT INTO certificats (apprenant_id, module_id, date_obtention, url) 
                VALUES (:apprenant_id, :module_id, :date_obtention, :url)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':apprenant_id' => $apprenantId,
            ':module_id' => $moduleId,
            ':date_obtention' => date('Y-m-d'),
            ':url' => $url
        ]);
    }

    // Vérifier si un certificat existe déjà
    public function existe($apprenantId, $moduleId)
    {
        $sql = "SELECT COUNT(*) as total FROM certificats 
                WHERE apprenant_id = :apprenant_id AND module_id = :module_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':apprenant_id' => $apprenantId,
            ':module_id' => $moduleId
        ]);
        return $stmt->fetch()['total'] > 0;
    }

    // Trouver un certificat par ID
    public function trouverParId($id)
    {
        $sql = "SELECT c.*, m.titre AS module_titre, 
                CONCAT(u.prenom, ' ', u.nom) AS apprenant_nom,
                u.email AS apprenant_email
                FROM certificats c 
                JOIN modules m ON c.module_id = m.id 
                JOIN utilisateurs u ON c.apprenant_id = u.id 
                WHERE c.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // Lister les certificats d'un apprenant
    public function listerParApprenant($apprenantId)
    {
        $sql = "SELECT c.*, m.titre AS module_titre, m.description AS module_description
                FROM certificats c 
                JOIN modules m ON c.module_id = m.id 
                WHERE c.apprenant_id = :apprenant_id 
                ORDER BY c.date_obtention DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':apprenant_id' => $apprenantId]);
        return $stmt->fetchAll();
    }

    // Lister tous les certificats (admin)
    public function listerTous()
    {
        $sql = "SELECT c.*, m.titre AS module_titre, 
                CONCAT(u.prenom, ' ', u.nom) AS apprenant_nom
                FROM certificats c 
                JOIN modules m ON c.module_id = m.id 
                JOIN utilisateurs u ON c.apprenant_id = u.id 
                ORDER BY c.date_obtention DESC";
        return $this->db->query($sql)->fetchAll();
    }

    // Compter les certificats d'un apprenant
    public function compterParApprenant($apprenantId)
    {
        $sql = "SELECT COUNT(*) as total FROM certificats 
                WHERE apprenant_id = :apprenant_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':apprenant_id' => $apprenantId]);
        return $stmt->fetch()['total'];
    }

    // Compter tous les certificats
    public function compter()
    {
        return $this->db->query("SELECT COUNT(*) as total FROM certificats")->fetch()['total'];
    }

    // Vérifier si un apprenant a terminé un module (tous les cours du module à 100%)
    public function aTermineModule($apprenantId, $moduleId)
    {
        // Nombre total de cours dans le module
        $sql = "SELECT COUNT(*) as total FROM cours WHERE module_id = :module_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':module_id' => $moduleId]);
        $totalCours = $stmt->fetch()['total'];

        if ($totalCours == 0) {
            return false;
        }

        // Nombre de cours terminés (100%) par l'apprenant dans ce module
        $sql = "SELECT COUNT(*) as total FROM progression p 
                JOIN cours c ON p.cours_id = c.id 
                WHERE p.apprenant_id = :apprenant_id 
                AND c.module_id = :module_id 
                AND p.pourcentage >= 100";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':apprenant_id' => $apprenantId,
            ':module_id' => $moduleId
        ]);
        $coursTermines = $stmt->fetch()['total'];

        return $coursTermines >= $totalCours;
    }

    // Vérifier et générer les certificats automatiquement
    public function verifierEtGenerer($apprenantId)
    {
        // Récupérer tous les modules
        $sql = "SELECT id, titre FROM modules";
        $modules = $this->db->query($sql)->fetchAll();

        foreach ($modules as $module) {
            if ($this->aTermineModule($apprenantId, $module['id'])) {
                if (!$this->existe($apprenantId, $module['id'])) {
                    $this->creer($apprenantId, $module['id']);
                }
            }
        }
    }
}