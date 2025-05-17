<?php

namespace App\Repositories;

use App\Database;
use PDO;

class PartidaRepository
{
    public function __construct(private Database $database)
    {
    }

    public function crearPartida(int $id_usuario, int $id_mazo): int|false
    {
        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare("INSERT INTO partida(usuario_id,mazo_id,estado) VALUES (:id_usuario,:id_mazo,'en_curso')");
        if (
            $stmt->execute([
                ':id_usuario' => $id_usuario,
                ':id_mazo' => $id_mazo
            ])
        ) {
            $result = (int) $pdo->lastInsertId();
            $pdo = $this->database->closeConnection();
            return $result;
        }
        $pdo = $this->database->closeConnection();
        return false;
    }


    public function tienePartidaEnCurso(int $id_usuario): int|false
    {
        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare("SELECT id FROM partida WHERE usuario_id=$id_usuario AND estado = 'en_curso'");
        if ($stmt->execute()) {
            $pdo = $this->database->closeConnection();
            $result = $stmt->fetchColumn();
            return $result;
        } else {
            $pdo = $this->database->closeConnection();
            return false;
        }
    }

    public function verificarPartidaEnCursoDeUsuario(int $partida_id, int $usuario_id): bool
    {
        $pdo = $this->database->getConnection();

        $query = "SELECT COUNT(*) FROM partida 
        WHERE id = :partida_id 
          AND usuario_id = :usuario_id 
          AND estado = 'en_curso'";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':partida_id', $partida_id);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->execute();

        $count = $stmt->fetchColumn();

        $pdo = $this->database->closeConnection();
        return $count > 0;
    }

    public function mazoUtilizado(int $id_mazo): bool
    {
        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare("SELECT mazo_id FROM partida WHERE mazo_id = :id_mazo");
        $stmt->execute([':id_mazo' => $id_mazo]);
        $data = $stmt->fetchColumn();
        $pdo = $this->database->closeConnection();
        return $data;
    }

    public function obtenerIDMazo(int $id_partida): int
    {
        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare("SELECT mazo_id FROM partida WHERE id = :id_partida");
        $stmt->execute([':id_partida' => $id_partida]);
        $result = $stmt->fetchColumn();
        $pdo = $this->database->closeConnection();
        return $result;
    }

    public function finalizarPartida(int $id_partida, string $resultado): void
    {
        $pdo = $this->database->getConnection();

        $update = $pdo->prepare("UPDATE partida SET el_usuario = :resultado, estado = 'finalizada' WHERE id = :id_partida");
        $update->execute([
            'resultado' => $resultado,
            'id_partida' => $id_partida
        ]);
        $pdo = $this->database->closeConnection();
    }

    public function obtenerPartidas(): array
    {
        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare("SELECT usuario_id,el_usuario FROM partida WHERE estado='finalizada'");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $pdo = $this->database->closeConnection();
        return $result;
    }

}