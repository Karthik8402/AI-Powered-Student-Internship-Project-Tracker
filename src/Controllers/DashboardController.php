<?php

namespace App\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Services\AIService;

class DashboardController {

    public function index() {
        $userId = $_SESSION['user_id'];
        $role = $_SESSION['user_role'];

        $projectModel = new Project();
        $taskModel = new Task();
        $userModel = new User();
        
        $data = [
            'role' => $role,
            'user_name' => $_SESSION['user_name'],
            'projects' => [],
            'recent_tasks' => [],
            'ai_insight' => "No active projects to analyze.",
            'stats' => []
        ];

        if ($role === 'student') {
            // Student Dashboard
            $data['projects'] = $projectModel->getByStudent($userId);
            $data['recent_tasks'] = $taskModel->getRecentByUser($userId);
            
            // Calculate stats - use task counts for task statuses
            $totalProjects = count($data['projects']);
            $completedProjects = count(array_filter($data['projects'], fn($p) => $p['status'] === 'completed'));
            
            $data['stats'] = [
                'total_projects' => $totalProjects,
                'todo_tasks' => $taskModel->countTodoByUser($userId),
                'in_progress_tasks' => $taskModel->countInProgressByUser($userId),
                'completed_tasks' => $taskModel->countCompletedByUser($userId)
            ];
            
            if (!empty($data['projects'])) {
                $data['ai_insight'] = AIService::suggestNextTask(
                    $data['projects'][0]['description'], 
                    $data['projects'][0]['status']
                );
            }
            
        } elseif ($role === 'mentor') {
            // Mentor Dashboard
            $data['projects'] = $projectModel->getByMentor($userId);
            $data['students'] = $userModel->getAllByRole('student');
            
            // Calculate stats
            $totalStudents = count(array_unique(array_column($data['projects'], 'assigned_to')));
            $reviewProjects = count(array_filter($data['projects'], fn($p) => $p['status'] === 'review'));
            
            $data['stats'] = [
                'total_projects' => count($data['projects']),
                'active_students' => $totalStudents,
                'pending_reviews' => $reviewProjects,
                'completed' => count(array_filter($data['projects'], fn($p) => $p['status'] === 'completed'))
            ];
            
            $data['ai_insight'] = "You have {$reviewProjects} project(s) awaiting review. Focus on providing timely feedback to keep students on track.";
            
        } else {
            // Admin Dashboard
            $data['projects'] = $projectModel->getAll();
            $data['students'] = $userModel->getAllByRole('student');
            $data['mentors'] = $userModel->getAllByRole('mentor');
            
            $data['stats'] = [
                'total_projects' => count($data['projects']),
                'total_students' => count($data['students']),
                'total_mentors' => count($data['mentors']),
                'completed_projects' => count(array_filter($data['projects'], fn($p) => $p['status'] === 'completed'))
            ];
            
            $data['ai_insight'] = "System status: All operational. " . count($data['projects']) . " projects tracked across " . count($data['students']) . " students.";
        }

        // Render role-specific dashboard view
        extract($data);
        require ROOT_PATH . '/src/Views/dashboard/index.php';
    }
}

