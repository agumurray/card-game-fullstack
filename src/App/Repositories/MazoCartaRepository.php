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
        $pdo->beginTransaction(); //inicia una transaccion en sql, lo que permite agrupar las inserciones como una operacion en conjunto
    
        try {
            $stmt = $pdo->prepare("INSERT INTO mazo_carta (carta_id, mazo_id, estado) VALUES (:carta_id, :mazo_id, :estado)");
    
            foreach ($cartas as $carta_id) {
                $stmt->execute([
                    ':carta_id' => $carta_id,
                    ':mazo_id' => $id_mazo,
                    ':estado'  => 'en_mazo',
                ]);
            }
    
            $pdo->commit(); //si no hubo erroes, se completa la transaccion y se suben todas las cartas
            return true;
        } catch (\PDOException $e) {
            $pdo->rollBack(); //si hubo al menos un error, no se sube ninguna carta
            return false;
        }
    }
    public function buscarMazo(int $partida,int $usuario):int
    {
        $pdo = $this->database->getConnection();
        if($usuario === 1){
            return 1;
        }
        $stmt = $pdo->prepare("SELECT mazo_id FROM partida WHERE id= :id AND usuario_id = :usuario_id AND estado = 'en_curso'");
        $stmt->execute(
            [':id' => $partida,
            ':usuario_id'=>$usuario]);
        $mazo_id= $stmt->fetchColumn();
        if ($mazo_id === false) {
            return 0;  // O puedes devolver -1 si lo prefieres
        }
        return (int) $mazo_id;
    }
    public function obtenerAtributo(array $cartas_id):array
    {
        $in = str_repeat('?,', count($cartas_id) - 1) . '?';
        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare("SELECT nombre FROM atributo WHERE id IN($in)");
        $stmt->execute($cartas_id);
        $atributo_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
        return $atributo_ids;
    }
    public function actualizarCartas(int $id_mazo, string $estado):bool
    {
        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare("UPDATE mazo_carta SET estado=:estado WHERE mazo_id=:id_mazo");
        return $stmt->execute([
            ':id_mazo' => $id_mazo,
            ':estado' => $estado
        ]);
    }

    public function buscarIdCartas(int $id_mazo):array
    {
        $pdo = $this->database->getConnection();
        $stmt = $pdo->query("SELECT carta_id FROM mazo_carta WHERE mazo_id=$id_mazo");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
    
    public function obtenerCartasEnMano(int $mazo_id): array
    {
        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare("SELECT carta_id FROM mazo_carta WHERE mazo_id = :mazo_id AND estado = 'en_mano'");
        $stmt->execute([':mazo_id' => $mazo_id]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    

    public function descartarCarta(int $carta_id, int $mazo_id):void
    {
        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare("UPDATE mazo_carta SET estado = 'descartado' WHERE mazo_id = :mazo_id AND carta_id = :carta_id");
        $stmt->execute([
            ':mazo_id' => $mazo_id,
            ':carta_id' => $carta_id
        ]);
    }
    
}