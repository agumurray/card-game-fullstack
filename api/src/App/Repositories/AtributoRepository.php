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

    public function atributosID(array $atributosIds): array
    {
        if (empty($atributosIds)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($atributosIds), '?'));
        $query = "SELECT id, nombre FROM atributo WHERE id IN ($placeholders)";

        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare($query);
        $stmt->execute(array_values($atributosIds));

        $atributos = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

        $pdo = $this->database->closeConnection();

        return $atributos;
    }

    public function atributoID(int $id): string
    {
        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare("SELECT nombre FROM atributo WHERE id = ?");
        $stmt->execute([$id]);

        $nombre = $stmt->fetchColumn();

        $pdo = $this->database->closeConnection();

        return $nombre ?: 'Desconocido';
    }



}















