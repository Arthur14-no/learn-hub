<?php
// Modèle Utilisateur - Gestion des utilisateurs

class Utilisateur
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // Inscription d'un nouvel utilisateur
    public function inscrire($nom, $prenom, $email, $motDePasse, $role)
    {
        $sql = "INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role) 
                VALUES (:nom, :prenom, :email, :mot_de_passe, :role)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':email' => $email,
            ':mot_de_passe' => password_hash($motDePasse, PASSWORD_DEFAULT),
            ':role' => $role
        ]);
    }

    // Connexion
    public function connecter($email, $motDePasse)
    {
        $sql = "SELECT * FROM utilisateurs WHERE email = :email AND actif = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($motDePasse, $user['mot_de_passe'])) {
            return $user;
        }
        return false;
    }

    // Vérifier si un email existe déjà
    public function emailExiste($email)
    {
        $sql = "SELECT COUNT(*) as total FROM utilisateurs WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch()['total'] > 0;
    }

    // Trouver un utilisateur par ID
    public function trouverParId($id)
    {
        $sql = "SELECT * FROM utilisateurs WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // Lister tous les utilisateurs
    public function listerTous()
    {
        $sql = "SELECT * FROM utilisateurs ORDER BY date_inscription DESC";
        return $this->db->query($sql)->fetchAll();
    }

    // Compter par rôle
    public function compterParRole($role)
    {
        $sql = "SELECT COUNT(*) as total FROM utilisateurs WHERE role = :role";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':role' => $role]);
        return $stmt->fetch()['total'];
    }

    // Modifier un utilisateur
    public function modifier($id, $nom, $prenom, $email)
    {
        $sql = "UPDATE utilisateurs SET nom = :nom, prenom = :prenom, email = :email 
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':email' => $email
        ]);
    }

    // Activer/Désactiver un utilisateur
    public function changerStatut($id, $actif)
    {
        $sql = "UPDATE utilisateurs SET actif = :actif WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id, ':actif' => $actif]);
    }
}