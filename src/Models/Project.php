<?php

namespace App\Models;

use App\Config\Database;
use PDO;

class Project {
    private $conn;
    private $table = 'projects';

    public $id;
    public $title;
    public $description;
    public $assigned_to;
    public $mentor_id;
    public $status;
    public $created_at;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " SET title=:title, description=:description, assigned_to=:assigned_to, mentor_id=:mentor_id, status='pending'";
        $stmt = $this->conn->prepare($query);

        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));

        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":assigned_to", $this->assigned_to);
        $stmt->bindParam(":mentor_id", $this->mentor_id);

        return $stmt->execute();
    }

    public function getByStudent($student_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE assigned_to = :student_id ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":student_id", $student_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getByMentor($mentor_id) {
        $query = "SELECT p.*, u.name as student_name FROM " . $this->table . " p 
                  LEFT JOIN users u ON p.assigned_to = u.id 
                  WHERE p.mentor_id = :mentor_id ORDER BY p.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":mentor_id", $mentor_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
