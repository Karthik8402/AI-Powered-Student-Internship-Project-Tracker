<?php

namespace App\Services;

class AIService {
    
    // Simulate AI suggestion based on project context
    public static function suggestNextTask($projectDescription, $status = 'pending') {
        $description = strtolower($projectDescription ?? '');
        
        // Status-based suggestions
        $statusSuggestions = [
            'pending' => [
                "Start by breaking down the project into smaller milestones.",
                "Create a project roadmap with key deliverables and deadlines.",
                "Schedule a kickoff meeting to align on project goals."
            ],
            'in_progress' => [
                "Review your current progress and update task statuses.",
                "Document any blockers and seek mentor guidance if needed.",
                "Consider a mid-project checkpoint to validate direction."
            ],
            'review' => [
                "Prepare documentation for the review session.",
                "Create a demo or presentation of completed features.",
                "Compile a list of learnings and challenges faced."
            ],
            'completed' => [
                "Write a final project report summarizing outcomes.",
                "Archive project materials for future reference.",
                "Reflect on what went well and areas for improvement."
            ]
        ];

        // Keyword-based suggestions
        $keywordSuggestions = [
            'web' => "Set up the frontend framework and create responsive layouts.",
            'mobile' => "Design the app wireframes and define navigation flow.",
            'api' => "Document the API endpoints and create a Postman collection.",
            'database' => "Design the database schema and create ER diagrams.",
            'machine learning' => "Gather and preprocess the training dataset.",
            'react' => "Set up component structure and state management.",
            'python' => "Create virtual environment and install dependencies."
        ];

        // Check for keyword matches
        foreach ($keywordSuggestions as $keyword => $suggestion) {
            if (strpos($description, $keyword) !== false) {
                return $suggestion;
            }
        }

        // Fall back to status-based suggestion
        $suggestions = $statusSuggestions[$status] ?? $statusSuggestions['pending'];
        return $suggestions[array_rand($suggestions)];
    }

    // Generate task suggestions for a project
    public static function suggestTasks($projectDescription) {
        $description = strtolower($projectDescription ?? '');
        
        $suggestions = [
            ["Research and document requirement specifications.", "Design the database schema.", "Create wireframes for the UI."],
            ["Set up development environment.", "Create project repository.", "Define coding standards."],
            ["Implement core features.", "Write unit tests.", "Conduct code review."]
        ];

        return $suggestions[array_rand($suggestions)];
    }
}

