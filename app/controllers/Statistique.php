<?php
// Modèle Statistique - Indicateurs

class Statistique
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function globales()
    {
        return [
            'apprenants' => $this->compter('utilisateurs', "role = 'apprenant'"),
            'formateurs' => $this->compter('utilisateurs', "role = 'formateur'"),
            'admins' => $this->compter('utilisateurs', "role = 'admin'"),
            'modules' => $this->compter('modules'),
            'cours' => $this->compter('cours'),
            'lecons' => $this->compter('lecons'),
            'evaluations' => $this->compter('evaluations'),
            'certificats' => $this->compter('certificats'),
        ];
    }

    private function compter($table, $where = '1')
    {
        $sql = "SELECT COUNT(*) as total FROM $table WHERE $where";
        return $this->db->query($sql)->fetch()['total'];
    }

    public function topCours($limite = 5)
    {
        $sql = "SELECT c.titre, COUNT(p.id) as total_inscriptions 
                FROM cours c 
                LEFT JOIN progression p ON c.id = p.cours_id 
                GROUP BY c.id 
                ORDER BY total_inscriptions DESC 
                LIMIT :limite";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}