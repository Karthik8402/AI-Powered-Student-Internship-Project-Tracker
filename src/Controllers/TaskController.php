<?php

namespace App\Controllers;

use App\Models\Task;
use App\Models\Project;

class TaskController {

    public function create($projectId) {
        $projectModel = new Project();
        $project = $projectModel->getById($projectId);

        if (!$project) {
            header("Location: " . BASE_URL . "/projects");
            exit;
        }

        // Get project students for task assignment dropdown
        $projectStudents = $projectModel->getAllProjectStudents($projectId);

        require ROOT_PATH . '/src/Views/tasks/create.php';
    }

    public function handleCreate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $task = new Task();
            $task->project_id = $_POST['project_id'] ?? 0;
            $task->assigned_to = !empty($_POST['assigned_to']) ? $_POST['assigned_to'] : null;
            $task->title = $_POST['title'] ?? '';
            $task->description = $_POST['description'] ?? '';
            $task->status = 'todo';
            $task->due_date = !empty($_POST['due_date']) ? $_POST['due_date'] : null;
            $task->is_ai_suggested = 0;

            if (empty($task->title) || empty($task->project_id)) {
                header("Location: " . BASE_URL . "/projects");
                exit;
            }

            if ($task->create()) {
                header("Location: " . BASE_URL . "/projects/view/{$task->project_id}?task_created=true");
            } else {
                header("Location: " . BASE_URL . "/projects/view/{$task->project_id}?error=task");
            }
            exit;
        }
    }

    public function updateStatus() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? 0;
            $status = $_POST['status'] ?? '';

            $taskModel = new Task();
            $success = $taskModel->updateStatus($id, $status);

            header('Content-Type: application/json');
            echo json_encode(['success' => $success]);
            exit;
        }
    }

    public function delete($id) {
        $taskModel = new Task();
        $task = $taskModel->getById($id);
        
        if (!$task) {
            header("Location: " . BASE_URL . "/projects");
            exit;
        }

        $projectId = $task['project_id'];
        
        if ($taskModel->delete($id)) {
            header("Location: " . BASE_URL . "/projects/view/$projectId?task_deleted=true");
        } else {
            header("Location: " . BASE_URL . "/projects/view/$projectId?error=delete");
        }
        exit;
    }
}
