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
    
    public function obtenerFuerza(int $id_carta): int
    {
        $pdo = $this->database->getConnection();
    
        $stmt = $pdo->prepare("SELECT ataque FROM carta WHERE id = :id_carta");
        $stmt->execute([':id_carta' => $id_carta]);
    
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $resultado['ataque'] ?? 0;
    }

    public function obtenerAtributo(int $id_carta): int
    {
        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare("SELECT atributo_id FROM carta WHERE id = :id_carta");
        $stmt->execute([':id_carta' => $id_carta]);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return $resultado['atributo_id'] ?? 0;
    }

    public function buscarCartasPorAtributoYNombre(int $atributo, string $nombre): array
    {
        $pdo = $this->database->getConnection();
    
        $select = "SELECT id, nombre, ataque, ataque_nombre, atributo_id FROM carta";
    
        if (empty($nombre)) {
            $stmt = $pdo->prepare("$select WHERE atributo_id = :atributo");
            $stmt->bindValue(':atributo', $atributo);
        } else {
            $stmt = $pdo->prepare("$select WHERE atributo_id = :atributo AND LOWER(nombre) LIKE LOWER(:nombre)");
            $stmt->bindValue(':atributo', $atributo);
            $stmt->bindValue(':nombre', "%$nombre%");
        }
    
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
}