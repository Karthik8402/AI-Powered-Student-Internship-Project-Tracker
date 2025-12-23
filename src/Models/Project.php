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
    public $project_type;  // 'individual', 'team', 'multi_student'
    public $assigned_to;   // Primary student (for individual) or null
    public $team_id;       // Team ID (for team projects)
    public $mentor_id;
    public $status;
    public $created_at;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                  SET title=:title, description=:description, project_type=:project_type,
                      assigned_to=:assigned_to, team_id=:team_id, mentor_id=:mentor_id, status='pending'";
        $stmt = $this->conn->prepare($query);

        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->project_type = $this->project_type ?: 'individual';

        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":project_type", $this->project_type);
        $stmt->bindParam(":assigned_to", $this->assigned_to);
        $stmt->bindParam(":team_id", $this->team_id);
        $stmt->bindParam(":mentor_id", $this->mentor_id);

        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    // Add students to a multi_student project
    public function addStudents($studentIds) {
        if (empty($studentIds) || !$this->id) return false;
        
        foreach ($studentIds as $studentId) {
            $query = "INSERT IGNORE INTO project_students (project_id, user_id) VALUES (:project_id, :user_id)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':project_id', $this->id);
            $stmt->bindParam(':user_id', $studentId);
            $stmt->execute();
        }
        return true;
    }

    // Get all students assigned to a project (for multi_student type)
    public function getStudents($projectId) {
        $query = "SELECT u.id, u.name, u.email 
                  FROM users u 
                  INNER JOIN project_students ps ON u.id = ps.user_id 
                  WHERE ps.project_id = :project_id 
                  ORDER BY u.name";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':project_id', $projectId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Clear students from project
    public function clearStudents($projectId) {
        $query = "DELETE FROM project_students WHERE project_id = :project_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':project_id', $projectId);
        return $stmt->execute();
    }

    public function getByStudent($student_id) {
        // Get individual projects + multi_student projects where student is assigned
        $query = "SELECT DISTINCT p.*, m.name as mentor_name FROM " . $this->table . " p 
                  LEFT JOIN users m ON p.mentor_id = m.id 
                  LEFT JOIN project_students ps ON p.id = ps.project_id 
                  WHERE p.assigned_to = :student_id 
                     OR ps.user_id = :student_id2
                     OR (p.team_id IS NOT NULL AND p.team_id IN 
                         (SELECT team_id FROM team_members WHERE user_id = :student_id3))
                  ORDER BY p.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":student_id", $student_id);
        $stmt->bindParam(":student_id2", $student_id);
        $stmt->bindParam(":student_id3", $student_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getByMentor($mentor_id) {
        $query = "SELECT p.*, u.name as student_name, t.name as team_name 
                  FROM " . $this->table . " p 
                  LEFT JOIN users u ON p.assigned_to = u.id 
                  LEFT JOIN teams t ON p.team_id = t.id
                  WHERE p.mentor_id = :mentor_id 
                  ORDER BY p.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":mentor_id", $mentor_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAll() {
        $query = "SELECT p.*, u.name as student_name, m.name as mentor_name, t.name as team_name 
                  FROM " . $this->table . " p 
                  LEFT JOIN users u ON p.assigned_to = u.id 
                  LEFT JOIN users m ON p.mentor_id = m.id 
                  LEFT JOIN teams t ON p.team_id = t.id
                  ORDER BY p.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $query = "SELECT p.*, u.name as student_name, m.name as mentor_name, t.name as team_name 
                  FROM " . $this->table . " p 
                  LEFT JOIN users u ON p.assigned_to = u.id 
                  LEFT JOIN users m ON p.mentor_id = m.id 
                  LEFT JOIN teams t ON p.team_id = t.id
                  WHERE p.id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $this->id = $row['id'];
            $this->title = $row['title'];
            $this->description = $row['description'];
            $this->project_type = $row['project_type'] ?? 'individual';
            $this->assigned_to = $row['assigned_to'];
            $this->team_id = $row['team_id'];
            $this->mentor_id = $row['mentor_id'];
            $this->status = $row['status'];
            $this->created_at = $row['created_at'];
            return $row;
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table . " 
                  SET title=:title, description=:description, project_type=:project_type,
                      assigned_to=:assigned_to, team_id=:team_id, mentor_id=:mentor_id, status=:status 
                  WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));

        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":project_type", $this->project_type);
        $stmt->bindParam(":assigned_to", $this->assigned_to);
        $stmt->bindParam(":team_id", $this->team_id);
        $stmt->bindParam(":mentor_id", $this->mentor_id);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function updateStatus($id, $status) {
        $query = "UPDATE " . $this->table . " SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Get all students for a project (handles all project types)
    public function getAllProjectStudents($projectId) {
        $project = $this->getById($projectId);
        if (!$project) return [];

        $students = [];
        
        if ($project['project_type'] === 'individual' && $project['assigned_to']) {
            // Single student
            $query = "SELECT id, name, email FROM users WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $project['assigned_to']);
            $stmt->execute();
            $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } elseif ($project['project_type'] === 'team' && $project['team_id']) {
            // Team members
            $query = "SELECT u.id, u.name, u.email 
                      FROM users u 
                      INNER JOIN team_members tm ON u.id = tm.user_id 
                      WHERE tm.team_id = :team_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':team_id', $project['team_id']);
            $stmt->execute();
            $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            // Multi-student from project_students
            $students = $this->getStudents($projectId);
        }

        return $students;
    }
}

