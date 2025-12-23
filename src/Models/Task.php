<?php

namespace App\Models;

use App\Config\Database;
use PDO;

class Task {
    private $conn;
    private $table = 'tasks';

    public $id;
    public $project_id;
    public $assigned_to;  // Student ID for specific task assignment
    public $title;
    public $description;
    public $status;
    public $due_date;
    public $is_ai_suggested;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                  SET project_id=:project_id, assigned_to=:assigned_to, title=:title, 
                      description=:description, status=:status, due_date=:due_date, is_ai_suggested=:is_ai_suggested";
        $stmt = $this->conn->prepare($query);

        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));
        
        $stmt->bindParam(":project_id", $this->project_id);
        $stmt->bindParam(":assigned_to", $this->assigned_to);
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":due_date", $this->due_date);
        $stmt->bindParam(":is_ai_suggested", $this->is_ai_suggested);

        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    public function getByProject($project_id) {
        $query = "SELECT t.*, u.name as assigned_student_name 
                  FROM " . $this->table . " t 
                  LEFT JOIN users u ON t.assigned_to = u.id 
                  WHERE t.project_id = :project_id 
                  ORDER BY t.status ASC, t.due_date ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":project_id", $project_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus($id, $status) {
        $query = "UPDATE " . $this->table . " SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getById($id) {
        $query = "SELECT t.*, u.name as assigned_student_name 
                  FROM " . $this->table . " t 
                  LEFT JOIN users u ON t.assigned_to = u.id 
                  WHERE t.id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $this->id = $row['id'];
            $this->project_id = $row['project_id'];
            $this->assigned_to = $row['assigned_to'] ?? null;
            $this->title = $row['title'];
            $this->description = $row['description'];
            $this->status = $row['status'];
            $this->due_date = $row['due_date'];
            $this->is_ai_suggested = $row['is_ai_suggested'];
            return $row;
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table . " 
                  SET title=:title, description=:description, assigned_to=:assigned_to, 
                      status=:status, due_date=:due_date 
                  WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));

        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":assigned_to", $this->assigned_to);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":due_date", $this->due_date);
        $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getRecentByUser($userId) {
        // Get tasks for student: only tasks assigned to them OR unassigned (team tasks) 
        // from projects they are part of
        $query = "SELECT DISTINCT t.*, p.title as project_title 
                  FROM " . $this->table . " t 
                  JOIN projects p ON t.project_id = p.id 
                  LEFT JOIN project_students ps ON p.id = ps.project_id
                  WHERE (t.assigned_to = :user_id OR t.assigned_to IS NULL)
                    AND (p.assigned_to = :user_id2 OR ps.user_id = :user_id3)
                  ORDER BY t.created_at DESC LIMIT 10";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userId, PDO::PARAM_INT);
        $stmt->bindParam(":user_id2", $userId, PDO::PARAM_INT);
        $stmt->bindParam(":user_id3", $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get tasks assigned to specific student
    public function getByStudent($userId) {
        $query = "SELECT t.*, p.title as project_title 
                  FROM " . $this->table . " t 
                  JOIN projects p ON t.project_id = p.id 
                  WHERE t.assigned_to = :user_id 
                  ORDER BY t.due_date ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

