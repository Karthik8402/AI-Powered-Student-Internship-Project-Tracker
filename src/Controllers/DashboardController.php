<?php

namespace App\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Services\AIService;

class DashboardController {

    public function index() {
        $userId = $_SESSION['user_id'];
        $role = $_SESSION['user_role'];

        $projectModel = new Project();
        $taskModel = new Task();
        
        $data = [
            'projects' => [],
            'recent_tasks' => [],
            'ai_insight' => "No active projects to analyze."
        ];

        if ($role === 'student') {
            $data['projects'] = $projectModel->getByStudent($userId);
            if (!empty($data['projects'])) {
                // Get tasks for the first project (simplification for dashboard)
                $firstProjectId = $data['projects'][0]['id'];
                $data['recent_tasks'] = $taskModel->getByProject($firstProjectId);
                $data['ai_insight'] = AIService::suggestNextTask($data['projects'][0]['description']);
            }
        } elseif ($role === 'mentor') {
            $data['projects'] = $projectModel->getByMentor($userId);
            $data['ai_insight'] = "You have " . count($data['projects']) . " active student projects.";
        } else {
            // Admin
            $data['ai_insight'] = "System status: Operational. All services running.";
        }

        // Render dashboard view with data
        extract($data); // Expose variables to view
        require ROOT_PATH . '/src/Views/dashboard/index.php';
    }
}
