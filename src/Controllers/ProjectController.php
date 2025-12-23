<?php

namespace App\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Services\AIService;

class ProjectController {

    public function index() {
        $userId = $_SESSION['user_id'];
        $role = $_SESSION['user_role'];

        $projectModel = new Project();
        
        // Get projects based on role
        if ($role === 'admin') {
            $projects = $projectModel->getAll();
        } elseif ($role === 'mentor') {
            $projects = $projectModel->getByMentor($userId);
        } else {
            $projects = $projectModel->getByStudent($userId);
        }

        require ROOT_PATH . '/src/Views/projects/index.php';
    }

    public function create() {
        $userModel = new User();
        $students = $userModel->getAllByRole('student');
        $mentors = $userModel->getAllByRole('mentor');
        
        // Get teams for team projects
        $teamModel = new \App\Models\Team();
        $teams = $teamModel->getAll();
        
        require ROOT_PATH . '/src/Views/projects/create.php';
    }

    public function handleCreate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $project = new Project();
            $project->title = $_POST['title'] ?? '';
            $project->description = $_POST['description'] ?? '';
            $project->project_type = $_POST['project_type'] ?? 'individual';
            $project->mentor_id = !empty($_POST['mentor_id']) ? $_POST['mentor_id'] : null;

            // Handle different project types
            if ($project->project_type === 'individual') {
                $project->assigned_to = !empty($_POST['assigned_to']) ? $_POST['assigned_to'] : null;
                $project->team_id = null;
            } elseif ($project->project_type === 'team') {
                $project->team_id = !empty($_POST['team_id']) ? $_POST['team_id'] : null;
                $project->assigned_to = null;
            } else {
                // multi_student - will add students after creation
                $project->assigned_to = null;
                $project->team_id = null;
            }

            if (empty($project->title)) {
                $error = "Project title is required.";
                $userModel = new User();
                $students = $userModel->getAllByRole('student');
                $mentors = $userModel->getAllByRole('mentor');
                $teamModel = new \App\Models\Team();
                $teams = $teamModel->getAll();
                require ROOT_PATH . '/src/Views/projects/create.php';
                return;
            }

            if ($project->create()) {
                // If multi_student, add selected students
                if ($project->project_type === 'multi_student' && !empty($_POST['students'])) {
                    $project->addStudents($_POST['students']);
                }
                
                header("Location: " . BASE_URL . "/projects?created=true");
                exit;
            } else {
                $error = "Failed to create project.";
                require ROOT_PATH . '/src/Views/projects/create.php';
            }
        }
    }

    public function view($id) {
        $projectModel = new Project();
        $project = $projectModel->getById($id);

        if (!$project) {
            header("Location: " . BASE_URL . "/projects");
            exit;
        }

        // Check access permissions - allow if admin, mentor, or any assigned student
        $userId = $_SESSION['user_id'];
        $role = $_SESSION['user_role'];
        
        // Get all students for this project
        $projectStudents = $projectModel->getAllProjectStudents($id);
        $studentIds = array_column($projectStudents, 'id');
        
        if ($role !== 'admin' && $project['mentor_id'] != $userId && !in_array($userId, $studentIds)) {
            header("Location: " . BASE_URL . "/projects");
            exit;
        }

        $taskModel = new Task();
        $allTasks = $taskModel->getByProject($id);
        
        // Filter tasks for students - only show their tasks or unassigned (team) tasks
        if ($role === 'student') {
            $tasks = array_filter($allTasks, function($task) use ($userId) {
                // Show task if: assigned to this student OR not assigned to anyone (team task)
                return $task['assigned_to'] == $userId || $task['assigned_to'] === null;
            });
            $tasks = array_values($tasks); // Re-index array
        } else {
            // Mentors and admins see all tasks
            $tasks = $allTasks;
        }
        
        // Get AI suggestion
        $ai_suggestion = AIService::suggestNextTask($project['description']);

        require ROOT_PATH . '/src/Views/projects/view.php';
    }

    public function edit($id) {
        $projectModel = new Project();
        $project = $projectModel->getById($id);

        if (!$project) {
            header("Location: " . BASE_URL . "/projects");
            exit;
        }

        $userModel = new User();
        $students = $userModel->getAllByRole('student');
        $mentors = $userModel->getAllByRole('mentor');

        require ROOT_PATH . '/src/Views/projects/edit.php';
    }

    public function handleEdit($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $projectModel = new Project();
            $projectModel->id = $id;
            $projectModel->title = $_POST['title'] ?? '';
            $projectModel->description = $_POST['description'] ?? '';
            $projectModel->assigned_to = !empty($_POST['assigned_to']) ? $_POST['assigned_to'] : null;
            $projectModel->mentor_id = !empty($_POST['mentor_id']) ? $_POST['mentor_id'] : null;
            $projectModel->status = $_POST['status'] ?? 'pending';

            if (empty($projectModel->title)) {
                $error = "Project title is required.";
                $project = ['id' => $id, 'title' => $projectModel->title, 'description' => $projectModel->description];
                $userModel = new User();
                $students = $userModel->getAllByRole('student');
                $mentors = $userModel->getAllByRole('mentor');
                require ROOT_PATH . '/src/Views/projects/edit.php';
                return;
            }

            if ($projectModel->update()) {
                header("Location: " . BASE_URL . "/projects/view/$id?updated=true");
                exit;
            } else {
                $error = "Failed to update project.";
                require ROOT_PATH . '/src/Views/projects/edit.php';
            }
        }
    }

    public function delete($id) {
        $projectModel = new Project();
        
        // Only admin or mentor can delete
        $role = $_SESSION['user_role'];
        if ($role !== 'admin' && $role !== 'mentor') {
            header("Location: " . BASE_URL . "/projects");
            exit;
        }

        if ($projectModel->delete($id)) {
            header("Location: " . BASE_URL . "/projects?deleted=true");
        } else {
            header("Location: " . BASE_URL . "/projects?error=delete");
        }
        exit;
    }

    public function updateStatus() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? 0;
            $status = $_POST['status'] ?? '';

            $projectModel = new Project();
            $success = $projectModel->updateStatus($id, $status);

            header('Content-Type: application/json');
            echo json_encode(['success' => $success]);
            exit;
        }
    }
}
