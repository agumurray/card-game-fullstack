<?php

namespace App\Repositories;

use App\Database;
use PDO;

class MazoCartaRepository
{
    public function __construct(private Database $database)
    {
    }

    public function crearMazo(array $cartas, int $id_mazo): bool
    {
        $pdo = $this->database->getConnection();
        $pdo->beginTransaction();

        try {
            $stmt = $pdo->prepare("INSERT INTO mazo_carta (carta_id, mazo_id, estado) VALUES (:carta_id, :mazo_id, :estado)");

            foreach ($cartas as $carta_id) {
                $stmt->execute([
                    ':carta_id' => $carta_id,
                    ':mazo_id' => $id_mazo,
                    ':estado' => 'en_mazo',
                ]);
            }

            $pdo->commit();
            $pdo = $this->database->closeConnection();
            return true;
        } catch (\PDOException $e) {
            $pdo->rollBack();
            $pdo = $this->database->closeConnection();
            return false;
        }
    }
    public function actualizarCartas(int $id_mazo, string $estado): bool
    {
        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare("UPDATE mazo_carta SET estado=:estado WHERE mazo_id=:id_mazo");
        $success = $stmt->execute([
            ':id_mazo' => $id_mazo,
            ':estado' => $estado
        ]);

        $pdo = $this->database->closeConnection();

        return $success;
    }

    public function buscarIdCartas(int $id_mazo): array
    {
        $pdo = $this->database->getConnection();
        $stmt = $pdo->query("SELECT carta_id FROM mazo_carta WHERE mazo_id=$id_mazo");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $pdo = $this->database->closeConnection();
        return $data;
    }

    public function obtenerCartasEnMano(int $mazo_id): array
    {
        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare("SELECT carta_id FROM mazo_carta WHERE mazo_id = :mazo_id AND estado = 'en_mano'");
        $stmt->execute([':mazo_id' => $mazo_id]);
        $result = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $pdo = $this->database->closeConnection();
        return $result;
    }


    public function descartarCarta(int $carta_id, int $mazo_id): void
    {
        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare("UPDATE mazo_carta SET estado = 'descartado' WHERE mazo_id = :mazo_id AND carta_id = :carta_id");
        $stmt->execute([
            ':mazo_id' => $mazo_id,
            ':carta_id' => $carta_id
        ]);
        $pdo = $this->database->closeConnection();
    }

}