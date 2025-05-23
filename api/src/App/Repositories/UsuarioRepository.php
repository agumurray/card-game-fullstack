<?php

namespace App\Repositories;

use App\Database;
use PDO;

class UsuarioRepository
{

    public function __construct(private Database $database)
    {
    }

    public function validarRegistro($data): ?string
    {
        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuario WHERE usuario = :usuario");
        $stmt->execute([':usuario' => $data['usuario']]);
        $exists = $stmt->fetchColumn();

        $this->database->closeConnection();
        return $exists > 0 ? 'El usuario ya existe' : null;
    }
    public function crearUsuario($data): bool
    {
        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare("INSERT INTO usuario(nombre,usuario,password) VALUES (:nombre,:usuario,:password)");
        $result = $stmt->execute([
            ':nombre' => $data['nombre'],
            ':usuario' => $data['usuario'],
            ':password' => password_hash($data['clave'], PASSWORD_DEFAULT)
        ]);

        $this->database->closeConnection();
        return $result;
    }

    public function buscarPorUsuario(string $usuario): ?array
    {
        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare("SELECT * FROM usuario WHERE usuario = :usuario");
        $stmt->execute([':usuario' => $usuario]);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->database->closeConnection();
        return $resultado !== false ? $resultado : null;
    }

    public function buscarPorId(string $usuario): ?array
    {
        $pdo = $this->database->getConnection();
        $stmt = $pdo->query("SELECT id,nombre,usuario FROM usuario WHERE id = $usuario");
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->database->closeConnection();
        return $usuario ?: null;
    }

    public function buscarIDPorToken(string $token): int|false
    {
        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare("SELECT id FROM usuario WHERE token = :token");
        $stmt->execute([':token' => $token]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->database->closeConnection();
        return $resultado ? (int) $resultado['id'] : false;
    }


    public function guardarToken(int $id, string $token, int $exp): void
    {
        date_default_timezone_set('America/Argentina/Buenos_Aires');

        $expDatetime = date('Y-m-d H:i:s', $exp);

        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare("
        UPDATE usuario 
        SET token = :token, 
            vencimiento_token = :exp 
        WHERE id = :id
    ");
        $stmt->execute([
            ':token' => $token,
            ':exp' => $expDatetime,
            ':id' => $id
        ]);
        $pdo = $this->database->closeConnection();
    }


    public function tokenValido(int $id, string $token): bool
    {
        date_default_timezone_set('America/Argentina/Buenos_Aires');

        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare("SELECT token, vencimiento_token FROM usuario WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $pdo = $this->database->closeConnection();

        $result = $user &&
            $user['token'] === $token &&
            time() < strtotime($user['vencimiento_token']);

        $this->database->closeConnection();
        return $result;
    }

    public function actualizarUsuario(int $id, string $nombre, string $clave): bool
    {
        $pdo = $this->database->getConnection();
        $claveHash = password_hash($clave, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE usuario SET nombre = :nombre, password = :clave WHERE id = :id");
        $result = $stmt->execute([':nombre' => $nombre, ':clave' => $claveHash, ':id' => $id]);
        $pdo = $this->database->closeConnection();
        return $result;
    }
}
