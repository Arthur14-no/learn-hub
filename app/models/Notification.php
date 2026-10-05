<?php
// Modèle Notification - Gestion des notifications

class Notification
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // Créer une notification
    public function creer($utilisateurId, $message, $type = 'info')
    {
        $sql = "INSERT INTO notifications (utilisateur_id, message, type) 
                VALUES (:utilisateur_id, :message, :type)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':utilisateur_id' => $utilisateurId,
            ':message' => $message,
            ':type' => $type
        ]);
    }

    // Créer une notification pour tous les utilisateurs d'un rôle
    public function creerPourRole($role, $message, $type = 'info')
    {
        $sql = "SELECT id FROM utilisateurs WHERE role = :role AND actif = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':role' => $role]);
        $utilisateurs = $stmt->fetchAll();

        foreach ($utilisateurs as $u) {
            $this->creer($u['id'], $message, $type);
        }
    }

    // Lister les notifications d'un utilisateur
    public function listerParUtilisateur($utilisateurId, $limite = 20)
    {
        $sql = "SELECT * FROM notifications 
                WHERE utilisateur_id = :utilisateur_id 
                ORDER BY date_envoi DESC 
                LIMIT :limite";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':utilisateur_id', $utilisateurId, PDO::PARAM_INT);
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Compter les notifications non lues
    public function compterNonLues($utilisateurId)
    {
        $sql = "SELECT COUNT(*) as total FROM notifications 
                WHERE utilisateur_id = :utilisateur_id AND lue = 0";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':utilisateur_id' => $utilisateurId]);
        return $stmt->fetch()['total'];
    }

    // Marquer une notification comme lue
    public function marquerLue($id, $utilisateurId)
    {
        $sql = "UPDATE notifications SET lue = 1 
                WHERE id = :id AND utilisateur_id = :utilisateur_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':utilisateur_id' => $utilisateurId
        ]);
    }

    // Marquer toutes les notifications comme lues
    public function marquerToutesLues($utilisateurId)
    {
        $sql = "UPDATE notifications SET lue = 1 
                WHERE utilisateur_id = :utilisateur_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':utilisateur_id' => $utilisateurId]);
    }

    // Supprimer une notification
    public function supprimer($id, $utilisateurId)
    {
        $sql = "DELETE FROM notifications 
                WHERE id = :id AND utilisateur_id = :utilisateur_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':utilisateur_id' => $utilisateurId
        ]);
    }
}