<?php

namespace App\Repositories;

use App\Database;
use PDO;

class JugadaRepository
{
    public function __construct(private Database $database)
    {
    }

    public function contarJugadasEnPartida(int $id_partida): int
    {
        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM jugada WHERE partida_id = :partida_id");
        $stmt->execute([':partida_id' => $id_partida]);

        $pdo = $this->database->closeConnection();
        return $stmt->fetchColumn();
    }

    public function subirJugada(int $id_partida, int $id_carta_usuario, int $id_carta_servidor, string $resultado): void
    {
        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare("
            INSERT INTO jugada (partida_id, carta_id_a, carta_id_b, el_usuario)
            VALUES (:partida_id, :carta_usuario_id, :carta_servidor_id, :resultado)
        ");

        $stmt->execute([
            ':partida_id' => $id_partida,
            ':carta_usuario_id' => $id_carta_usuario,
            ':carta_servidor_id' => $id_carta_servidor,
            ':resultado' => $resultado
        ]);

        $pdo = $this->database->closeConnection();
    }

    function determinarGanador(int $id_partida): string
    {
        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare("SELECT el_usuario FROM jugada WHERE partida_id = :id_partida");
        $stmt->execute(['id_partida' => $id_partida]);
        $resultados = $stmt->fetchAll(PDO::FETCH_COLUMN);

        if (empty($resultados)) {
            return 'sin jugadas';
        }

        $ganadas = 0;
        $perdidas = 0;
        $empatadas = 0;

        foreach ($resultados as $resultado) {
            switch ($resultado) {
                case 'gano':
                    $ganadas++;
                    break;
                case 'perdio':
                    $perdidas++;
                    break;
                case 'empato':
                    $empatadas++;
                    break;
            }
        }

        if ($ganadas > $perdidas) {
            $resultadoFinal = 'gano';
        } elseif ($perdidas > $ganadas) {
            $resultadoFinal = 'perdio';
        } else {
            $resultadoFinal = 'empato';
        }

        $pdo = $this->database->closeConnection();
        return $resultadoFinal;
    }

    public function eliminarJugadasDePartida(int $id_partida): void
    {
        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare("DELETE FROM jugada WHERE partida_id = :id_partida");
        $stmt->execute([':id_partida' => $id_partida]);

        $this->database->closeConnection();
    }



}