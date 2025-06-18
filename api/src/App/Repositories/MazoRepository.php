<?php

namespace App\Repositories;

use App\Database;
use PDO;

class MazoRepository
{
    public function __construct(private Database $database)
    {
    }


    public function validarMazo(int $id_usuario, int $id_mazo): bool
    {
        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare("SELECT 1 FROM mazo WHERE id = :id_mazo AND usuario_id = :id_usuario");
        $stmt->execute([
            'id_mazo' => $id_mazo,
            'id_usuario' => $id_usuario
        ]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $pdo = $this->database->closeConnection();

        return (bool) $result;
    }
    

    public function crearMazo(int $id, string $nombre_mazo): int|false
    {
        $pdo = $this->database->getConnection();

        $check = $pdo->prepare("SELECT COUNT(*) FROM mazo WHERE usuario_id = :usuario_id");
        $check->execute([':usuario_id' => $id]);
        $cantidad = (int) $check->fetchColumn();

        if ($cantidad >= 3) {
            return false;
        }

        $stmt = $pdo->prepare("INSERT INTO mazo (usuario_id, nombre) VALUES (:usuario_id, :nombre)");

        if (
            $stmt->execute([
                ':usuario_id' => $id,
                ':nombre' => $nombre_mazo,
            ])
        ) {
            $result = (int) $pdo->lastInsertId();
            $pdo = $this->database->closeConnection();
            return $result;
        }

        $pdo = $this->database->closeConnection();
        return false;
    }


    public function eliminarMazo($id_mazo): bool
    {
        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare("DELETE FROM mazo WHERE id=:id_mazo");
        $success = $stmt->execute(['id_mazo' => $id_mazo]);
        $pdo = $this->database->closeConnection();
        return $success;
    }
    public function actualizarMazo($id, $nombre): bool
    {
        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare("UPDATE mazo SET nombre = :nombre WHERE id = :id");
        $success = $stmt->execute([':nombre' => $nombre, ':id' => $id]);
        $pdo = $this->database->closeConnection();
        return $success;
    }

    public function buscarMazosPorId($id_usuario): array
    {
        $pdo = $this->database->getConnection();
        $stmt = $pdo->query("SELECT id,nombre FROM mazo WHERE usuario_id= $id_usuario");
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $pdo = $this->database->closeConnection();
        return $result;
    }

}