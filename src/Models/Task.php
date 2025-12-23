<?php

namespace App\Models;

use App\Config\Database;
use PDO;

class Task {
    private $conn;
    private $table = 'tasks';

    public $id;
    public $project_id;
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
        $query = "INSERT INTO " . $this->table . " SET project_id=:project_id, title=:title, description=:description, status=:status, due_date=:due_date, is_ai_suggested=:is_ai_suggested";
        $stmt = $this->conn->prepare($query);

        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));
        
        $stmt->bindParam(":project_id", $this->project_id);
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":due_date", $this->due_date);
        $stmt->bindParam(":is_ai_suggested", $this->is_ai_suggested);

        return $stmt->execute();
    }

    public function getByProject($project_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE project_id = :project_id ORDER BY status ASC, due_date ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":project_id", $project_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus($id, $status) {
        $query = "UPDATE " . $this->table . " SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }
}
