<?php

namespace App\Controllers;

use App\Models\Team;
use App\Models\User;

class TeamController {

    public function index() {
        $teamModel = new Team();
        $teams = $teamModel->getAll();
        
        require ROOT_PATH . '/src/Views/teams/index.php';
    }

    public function create() {
        $userModel = new User();
        $students = $userModel->getAllByRole('student');
        
        require ROOT_PATH . '/src/Views/teams/create.php';
    }

    public function handleCreate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $team = new Team();
            $team->name = $_POST['name'] ?? '';
            $team->description = $_POST['description'] ?? '';
            $team->created_by = $_SESSION['user_id'];

            if (empty($team->name)) {
                $error = "Team name is required.";
                $userModel = new User();
                $students = $userModel->getAllByRole('student');
                require ROOT_PATH . '/src/Views/teams/create.php';
                return;
            }

            if ($team->create()) {
                // Add selected members
                if (!empty($_POST['members'])) {
                    $team->addMembers($_POST['members']);
                }
                
                header("Location: " . BASE_URL . "/teams?created=true");
                exit;
            } else {
                $error = "Failed to create team.";
                require ROOT_PATH . '/src/Views/teams/create.php';
            }
        }
    }

    public function view($id) {
        $teamModel = new Team();
        $team = $teamModel->getById($id);

        if (!$team) {
            header("Location: " . BASE_URL . "/teams");
            exit;
        }

        $members = $teamModel->getMembers($id);
        
        require ROOT_PATH . '/src/Views/teams/view.php';
    }

    public function edit($id) {
        $teamModel = new Team();
        $team = $teamModel->getById($id);

        if (!$team) {
            header("Location: " . BASE_URL . "/teams");
            exit;
        }

        $members = $teamModel->getMembers($id);
        $userModel = new User();
        $students = $userModel->getAllByRole('student');
        
        require ROOT_PATH . '/src/Views/teams/edit.php';
    }

    public function handleEdit($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $teamModel = new Team();
            $teamModel->id = $id;
            $teamModel->name = $_POST['name'] ?? '';
            $teamModel->description = $_POST['description'] ?? '';

            if (empty($teamModel->name)) {
                $error = "Team name is required.";
                $team = ['id' => $id, 'name' => $teamModel->name, 'description' => $teamModel->description];
                $userModel = new User();
                $students = $userModel->getAllByRole('student');
                require ROOT_PATH . '/src/Views/teams/edit.php';
                return;
            }

            if ($teamModel->update()) {
                // Update members - clear and re-add
                $query = "DELETE FROM team_members WHERE team_id = :team_id";
                // Simple approach: just redirect, members can be edited separately
                
                header("Location: " . BASE_URL . "/teams/view/$id?updated=true");
                exit;
            } else {
                $error = "Failed to update team.";
                require ROOT_PATH . '/src/Views/teams/edit.php';
            }
        }
    }

    public function delete($id) {
        $role = $_SESSION['user_role'];
        if ($role !== 'admin' && $role !== 'mentor') {
            header("Location: " . BASE_URL . "/teams");
            exit;
        }

        $teamModel = new Team();
        if ($teamModel->delete($id)) {
            header("Location: " . BASE_URL . "/teams?deleted=true");
        } else {
            header("Location: " . BASE_URL . "/teams?error=delete");
        }
        exit;
    }
}
