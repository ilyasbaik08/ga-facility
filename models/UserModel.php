<?php

require_once __DIR__ . '/../config/connection.php';

class UserModel
{
    private $conn;

    public function __construct()
    {
        $this->conn = (new Connection())->openConnection();
    }

    public function login($email)
    {
        $sql = "SELECT id, name, email, division, level, password FROM users WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    public function getUserById($id)
    {
        $sql = "SELECT * FROM users WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            return $user;
        } else {
            return null;
        }
    }

    public function register($name, $email, $division, $level, $password)
    {
        $stmt = $this->conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            return false;
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $this->conn->prepare("INSERT INTO users (name, email, division, level, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $email, $division, $level, $hashedPassword);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }

    public function updatePassword($id, $password)
    {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $sql = "UPDATE users SET password=? WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $hashed_password, $id);
        $stmt->execute();
    }
}
