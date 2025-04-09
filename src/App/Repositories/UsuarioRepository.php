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

        if (empty($data['nombre']) || empty($data['usuario']) || empty($data['clave'])) {
            return 'Todos los campos (nombre, usuario, password) son obligatorios';
        }

        if (!preg_match('/^[a-zA-Z0-9]{6,20}$/', $data['usuario'])) {
            return 'El nombre de usuario debe tener entre 6 y 20 caracteres y solo contener letras y números';
        }

        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $data['clave'])) {
            return 'La contraseña debe tener al menos 8 caracteres, mayúscula, minúscula, al menos un numero y un carácter especial';
        }

        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuario WHERE usuario = :usuario");
        $stmt->execute([':usuario' => $data['usuario']]);
        $exists = $stmt->fetchColumn();

        return $exists > 0 ? 'El usuario ya existe' : null;
    }

    public function crearUsuario($data): bool
    {
        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare("INSERT INTO usuario(nombre,usuario,password) VALUES (:nombre,:usuario,:password)");
        return $stmt->execute([
            ':nombre' => $data['nombre'],
            ':usuario' => $data['usuario'],
            ':password' => password_hash($data['clave'], PASSWORD_DEFAULT)
        ]);
    }

    public function buscarPorUsuario(string $usuario): ?array
    {
        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare("SELECT * FROM usuario WHERE usuario = :usuario");
        $stmt->execute([':usuario' => $usuario]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function guardarToken(int $id, string $token, int $exp): void
    {
        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare("UPDATE usuario SET token = :token, vencimiento_token = FROM_UNIXTIME(:exp) WHERE id = :id");
        $stmt->execute([':token' => $token, ':exp' => $exp, ':id' => $id]);
    }

    public function tokenValido(int $id, string $token): bool
    {   
        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare("SELECT token, vencimiento_token FROM usuario WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user && $user['token'] === $token && time() < strtotime($user['vencimiento_token']);
    }

    public function actualizarUsuario(int $id, string $nombre, string $clave): bool
    {
        $pdo = $this->database->getConnection();
        $claveHash = password_hash($clave, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE usuario SET nombre = :nombre, password = :clave WHERE id = :id");
        return $stmt->execute([':nombre' => $nombre, ':clave' => $claveHash, ':id' => $id]);
    }
}
