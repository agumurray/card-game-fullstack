<?php

namespace App\Repositories;

use App\Database;
use PDO;

class MazoRepository
{
    public function __construct(private Database $database)
    {
    }


    public function validarMazo(int $id_usuario, int $id_mazo):?string
    {
        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare("SELECT usuario_id FROM mazo WHERE id=:id_mazo");
        $stmt->execute(['id_mazo' => $id_mazo]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user && $id_usuario==$user['usuario_id'];
    }

    public function crearMazo(int $id, string $nombre_mazo): int|false
    {
        $pdo = $this->database->getConnection();
    
        //Verificar si el usuario ya tiene 3 mazos
        $check = $pdo->prepare("SELECT COUNT(*) FROM mazo WHERE usuario_id = :usuario_id");
        $check->execute([':usuario_id' => $id]);
        $cantidad = (int) $check->fetchColumn();
    
        if ($cantidad >= 3) {
            return false; 
        }

        //Verificar que no exista un mazo con el mismo nombre
        $check = $pdo->prepare("SELECT COUNT(*) FROM mazo WHERE nombre = :nombre");
        $check->execute([':nombre' => $nombre_mazo]);
        $cantidad = (int) $check->fetchColumn();
    
        if ($cantidad >= 1) {
            return false; 
        }
    
        //Insertar el mazo y devolver el id del mismo
        $stmt = $pdo->prepare("INSERT INTO mazo (usuario_id, nombre) VALUES (:usuario_id, :nombre)");
    
        if ($stmt->execute([
            ':usuario_id' => $id,
            ':nombre' => $nombre_mazo,
        ])) {
            return (int) $pdo->lastInsertId();
        }
    
        return false;
    }
    
    public function eliminarMazo($id_mazo):bool
    {
        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare("DELETE FROM mazo WHERE id=:id_mazo");
        return $stmt->execute(['id_mazo' => $id_mazo]);
    }
    
    public function buscarMazosPorId($id_usuario):array
    {
        $pdo = $this->database->getConnection();
        $stmt = $pdo->query("SELECT id,nombre FROM mazo WHERE usuario_id= $id_usuario");
        //$stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}