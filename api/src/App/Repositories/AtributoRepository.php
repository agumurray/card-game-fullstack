<?php

namespace App\Repositories;

use App\Database;
use PDO;

class AtributoRepository
{
    public function __construct(private Database $database)
    {
    }

    public function obtenerAtributosPorIds(array $atributoIds): array
    {
        if (empty($atributoIds)) {
            return [];
        }

        // Armar placeholders para la consulta
        $placeholders = implode(',', array_fill(0, count($atributoIds), '?'));
        $query = "SELECT id, nombre FROM atributo WHERE id IN ($placeholders)";

        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare($query);
        $stmt->execute($atributoIds);

        // Mapear resultados por ID
        $atributos = $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // id => nombre

        // Reconstruir arreglo respetando los IDs originales y duplicados
        $resultado = [];
        foreach ($atributoIds as $id) {
            if (isset($atributos[$id])) {
                $resultado[] = $atributos[$id];
            }
        }

        $pdo = $this->database->closeConnection();

        return $resultado;
    }




}















