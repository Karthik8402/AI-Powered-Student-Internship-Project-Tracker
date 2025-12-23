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
            
            // Calculate stats
            $totalProjects = count($data['projects']);
            $completedProjects = count(array_filter($data['projects'], fn($p) => $p['status'] === 'completed'));
            $pendingTasks = count(array_filter($data['recent_tasks'], fn($t) => $t['status'] !== 'done'));
            
            $data['stats'] = [
                'total_projects' => $totalProjects,
                'completed_projects' => $completedProjects,
                'pending_tasks' => $pendingTasks,
                'in_progress' => count(array_filter($data['projects'], fn($p) => $p['status'] === 'in_progress'))
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

