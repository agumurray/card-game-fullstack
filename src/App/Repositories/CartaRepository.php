<?php

namespace App\Repositories;

use App\Database;
use PDO;

class CartaRepository
{
    public function __construct(private Database $database)
    {
    }

    public function validarCartas(array $cartas): bool
    {
        $placeholders = implode(',', array_fill(0, count($cartas), '?'));

        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM carta WHERE id IN ($placeholders)");
        $stmt->execute($cartas);
        $cantidad = $stmt->fetchColumn();

        if ($cantidad != count($cartas)) {
            return false;
        }
        return true;
    }

    public function mostrarCartas(array $cartas):array
    {
        $pdo = $this->database->getConnection();
        $i=0;
        foreach ($cartas as $key=>$value){
            $id_cartas[$i]=(int)$value['carta_id'];
            $i = $i+1;
        }
            $stmt = $pdo->query("SELECT id,nombre,ataque_nombre,ataque FROM carta WHERE id IN (". implode(',',$id_cartas).")");
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
}