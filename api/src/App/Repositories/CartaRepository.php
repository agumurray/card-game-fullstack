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

        $pdo = $this->database->closeConnection();
        return true;
    }


    public function mostrarCartas(array|int $cartas): array
    {
        $pdo = $this->database->getConnection();

        if (is_int($cartas)) {
            $id_cartas = [$cartas];
        } else {
            $id_cartas = [];
            foreach ($cartas as $value) {
                $id_cartas[] = (int) $value['carta_id'];
            }
        }

        $in = implode(',', array_fill(0, count($id_cartas), '?'));
        $stmt = $pdo->prepare("SELECT id,nombre,ataque_nombre,ataque,atributo_id FROM carta WHERE id IN ($in)");
        $stmt->execute($id_cartas);

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $pdo = $this->database->closeConnection();

        return $data;
    }

    public function obtenerFuerza(int $id_carta): int
    {
        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare("SELECT ataque FROM carta WHERE id = :id_carta");
        $stmt->execute([':id_carta' => $id_carta]);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        $pdo = $this->database->closeConnection();
        return $resultado['ataque'] ?? 0;
    }

    public function obtenerAtributo(int $id_carta): int
    {
        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare("SELECT atributo_id FROM carta WHERE id = :id_carta");
        $stmt->execute([':id_carta' => $id_carta]);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        $pdo = $this->database->closeConnection();
        return $resultado['atributo_id'] ?? 0;
    }

    public function buscarCartasPorAtributoYNombre(?int $atributo, string $nombre): array
    {
        $pdo = $this->database->getConnection();

        $sql = "SELECT id, nombre, ataque, ataque_nombre, atributo_id FROM carta";
        $condiciones = [];
        $params = [];

        if (!empty($atributo)) {
            $condiciones[] = "atributo_id = :atributo";
            $params[':atributo'] = $atributo;
        }

        if (!empty($nombre)) {
            $condiciones[] = "LOWER(nombre) LIKE LOWER(:nombre)";
            $params[':nombre'] = "%$nombre%";
        }

        if (!empty($condiciones)) {
            $sql .= " WHERE " . implode(" AND ", $condiciones);
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        $pdo = $this->database->closeConnection();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerAtributosDeCartas(array $cartaIds): array
    {
        if (empty($cartaIds)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($cartaIds), '?'));
        $query = "SELECT atributo_id FROM carta WHERE id IN ($placeholders)";

        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare($query);
        $stmt->execute($cartaIds);

        $pdo = $this->database->closeConnection();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
