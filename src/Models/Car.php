<?php
namespace App\Models;

use PDO;

//Modèle Car, représente une voiture en BDO
class Car extends BaseModel{

    protected string $table = 'car';

    /**
     * Récupère toutes les voitures
     * @return array tableau de voiture
     */

    public function all():array{
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY created_at DESC");
        //FETCH_ASSOC est déjà defini par défaut dans notre class DATABASE
        return $stmt->fetchAll(PDO::FETCH_ASSOC);        
    }

    public function find(int $car_id): ?array{
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE car_id = :id");
        $stmt->execute([':id'=> $car_id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ?: null;
    }
}
