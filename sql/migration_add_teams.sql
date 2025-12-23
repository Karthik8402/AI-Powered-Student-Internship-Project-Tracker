-- Migration script to add multi-student and team support
-- Run this on existing databases to add new tables and columns

-- Add new tables
CREATE TABLE IF NOT EXISTS `teams` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `teams_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `team_members` (
  `team_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `joined_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`team_id`, `user_id`),
  CONSTRAINT `team_members_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE,
  CONSTRAINT `team_members_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `project_students` (
  `project_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`project_id`, `user_id`),
  CONSTRAINT `project_students_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `project_students_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Modify projects table
ALTER TABLE `projects` 
  ADD COLUMN IF NOT EXISTS `project_type` enum('individual','team','multi_student') NOT NULL DEFAULT 'individual' AFTER `description`,
  ADD COLUMN IF NOT EXISTS `team_id` int(11) DEFAULT NULL AFTER `assigned_to`;

-- Modify tasks table
ALTER TABLE `tasks` 
  ADD COLUMN IF NOT EXISTS `assigned_to` int(11) DEFAULT NULL AFTER `project_id`;

-- Add indexes
ALTER TABLE `projects` ADD INDEX IF NOT EXISTS `idx_project_type` (`project_type`);
ALTER TABLE `projects` ADD INDEX IF NOT EXISTS `idx_team_id` (`team_id`);
ALTER TABLE `tasks` ADD INDEX IF NOT EXISTS `idx_assigned_to` (`assigned_to`);
