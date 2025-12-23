<?php

namespace App\Models;

use App\Config\Database;
use PDO;

class Team {
    private $conn;
    
    public $id;
    public $name;
    public $description;
    public $created_by;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Create a new team
    public function create() {
        $query = "INSERT INTO teams (name, description, created_by) VALUES (:name, :description, :created_by)";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':created_by', $this->created_by);
        
        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    // Add member to team
    public function addMember($userId) {
        $query = "INSERT IGNORE INTO team_members (team_id, user_id) VALUES (:team_id, :user_id)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':team_id', $this->id);
        $stmt->bindParam(':user_id', $userId);
        return $stmt->execute();
    }

    // Add multiple members
    public function addMembers($userIds) {
        foreach ($userIds as $userId) {
            $this->addMember($userId);
        }
        return true;
    }

    // Remove member from team
    public function removeMember($userId) {
        $query = "DELETE FROM team_members WHERE team_id = :team_id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':team_id', $this->id);
        $stmt->bindParam(':user_id', $userId);
        return $stmt->execute();
    }

    // Get all teams
    public function getAll() {
        $query = "SELECT t.*, u.name as creator_name, 
                  (SELECT COUNT(*) FROM team_members WHERE team_id = t.id) as member_count 
                  FROM teams t 
                  LEFT JOIN users u ON t.created_by = u.id 
                  ORDER BY t.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get team by ID with members
    public function getById($id) {
        $query = "SELECT t.*, u.name as creator_name FROM teams t 
                  LEFT JOIN users u ON t.created_by = u.id 
                  WHERE t.id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get team members
    public function getMembers($teamId) {
        $query = "SELECT u.id, u.name, u.email, tm.joined_at 
                  FROM users u 
                  INNER JOIN team_members tm ON u.id = tm.user_id 
                  WHERE tm.team_id = :team_id 
                  ORDER BY u.name";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':team_id', $teamId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update team
    public function update() {
        $query = "UPDATE teams SET name = :name, description = :description WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }

    // Delete team
    public function delete($id) {
        $query = "DELETE FROM teams WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
