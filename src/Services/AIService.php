<?php

namespace App\Services;

class AIService {
    
    // Simulate AI suggestion
    public static function suggestNextTask($projectDescription) {
        // In a real app, this would call OpenAI API
        $suggestions = [
            "Research and document requirement specifications.",
            "Design the database schema based on the requirements.",
            "Create low-fidelity wireframes for the user interface.",
            "Set up the initial development environment and git repository."
        ];
        
        // Return a random suggestion for simulation
        return $suggestions[array_rand($suggestions)];
    }
}
