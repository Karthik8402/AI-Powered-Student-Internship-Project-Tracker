<?php

namespace App\Models;

use App\Config\Database;
use PDO;

class User {
    private $conn;
    private $table = 'users';

    public $id;
    public $name;
    public $email;
    public $password;
    public $role;
    public $status;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Create new user
    public function create() {
        $query = "INSERT INTO " . $this->table . " SET name=:name, email=:email, password=:password, role=:role, status='active'";
        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->role = htmlspecialchars(strip_tags($this->role));
        
        // Hash password
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);

        // Bind data
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":role", $this->role);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Find user by email
    public function findByEmail($email) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->email = $row['email'];
            $this->password = $row['password'];
            $this->role = $row['role'];
            $this->status = $row['status'];
            return true;
        }
        return false;
    }

    // Find user by ID
    public function findById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id, \PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if($row) {
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->email = $row['email'];
            $this->password = $row['password'];
            $this->role = $row['role'];
            $this->status = $row['status'];
            return true;
        }
        return false;
    }

    // Update password
    public function updatePassword($id, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        
        $query = "UPDATE " . $this->table . " SET password = :password WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":password", $hashedPassword);
        $stmt->bindParam(":id", $id, \PDO::PARAM_INT);

        return $stmt->execute();
    }

    // Get all users by role (for dropdowns)
    public function getAllByRole($role) {
        $query = "SELECT id, name, email FROM " . $this->table . " WHERE role = :role AND status = 'active' ORDER BY name ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":role", $role);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
