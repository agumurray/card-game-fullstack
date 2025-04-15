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
    
    public function actualizarCartas(int $id_mazo):bool
    {
        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare("UPDATE mazo_carta SET estado='en_mano' WHERE mazo_id=:id_mazo");
        return $stmt->execute(['id_mazo' => $id_mazo]);
    }

    public function buscarIdCartas(int $id_mazo):array
    {
        $pdo = $this->database->getConnection();
        $stmt = $pdo->query("SELECT carta_id FROM mazo_carta WHERE mazo_id=$id_mazo");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
    
}