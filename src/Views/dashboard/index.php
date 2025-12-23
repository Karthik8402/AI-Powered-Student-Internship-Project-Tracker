<?php require ROOT_PATH . '/src/Views/layouts/header.php'; ?>

<div style="padding: 2rem 0;">
    <!-- Welcome Header -->
    <div style="margin-bottom: 2rem;">
        <h1 style="font-weight: 300; margin: 0;">
            <?php if($role === 'student'): ?>
                Welcome back, <?php echo htmlspecialchars($user_name); ?>! 👋
            <?php elseif($role === 'mentor'): ?>
                Mentor Dashboard
            <?php else: ?>
                Admin Control Panel
            <?php endif; ?>
        </h1>
        <p style="color: var(--text-muted); margin-top: 0.5rem;">
            <?php if($role === 'student'): ?>
                Track your internship projects and tasks
            <?php elseif($role === 'mentor'): ?>
                Manage and review student projects
            <?php else: ?>
                System overview and management
            <?php endif; ?>
        </p>
    </div>

    <!-- Stats Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
        <?php if($role === 'student'): ?>
            <div class="glass-panel stat-card">
                <div class="stat-icon" style="background: rgba(79, 70, 229, 0.2); color: #818cf8;">
                    <i class="fa-solid fa-folder"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo $stats['total_projects']; ?></div>
                    <div class="stat-label">Total Projects</div>
                </div>
            </div>
            <div class="glass-panel stat-card">
                <div class="stat-icon" style="background: rgba(59, 130, 246, 0.2); color: #60a5fa;">
                    <i class="fa-solid fa-spinner"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo $stats['in_progress']; ?></div>
                    <div class="stat-label">In Progress</div>
                </div>
            </div>
            <div class="glass-panel stat-card">
                <div class="stat-icon" style="background: rgba(234, 179, 8, 0.2); color: #fbbf24;">
                    <i class="fa-solid fa-list-check"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo $stats['pending_tasks']; ?></div>
                    <div class="stat-label">Pending Tasks</div>
                </div>
            </div>
            <div class="glass-panel stat-card">
                <div class="stat-icon" style="background: rgba(34, 197, 94, 0.2); color: #4ade80;">
                    <i class="fa-solid fa-check-circle"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo $stats['completed_projects']; ?></div>
                    <div class="stat-label">Completed</div>
                </div>
            </div>
        <?php elseif($role === 'mentor'): ?>
            <div class="glass-panel stat-card">
                <div class="stat-icon" style="background: rgba(79, 70, 229, 0.2); color: #818cf8;">
                    <i class="fa-solid fa-folder"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo $stats['total_projects']; ?></div>
                    <div class="stat-label">Total Projects</div>
                </div>
            </div>
            <div class="glass-panel stat-card">
                <div class="stat-icon" style="background: rgba(59, 130, 246, 0.2); color: #60a5fa;">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo $stats['active_students']; ?></div>
                    <div class="stat-label">Active Students</div>
                </div>
            </div>
            <div class="glass-panel stat-card">
                <div class="stat-icon" style="background: rgba(168, 85, 247, 0.2); color: #c084fc;">
                    <i class="fa-solid fa-clock"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo $stats['pending_reviews']; ?></div>
                    <div class="stat-label">Pending Reviews</div>
                </div>
            </div>
            <div class="glass-panel stat-card">
                <div class="stat-icon" style="background: rgba(34, 197, 94, 0.2); color: #4ade80;">
                    <i class="fa-solid fa-check-circle"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo $stats['completed']; ?></div>
                    <div class="stat-label">Completed</div>
                </div>
            </div>
        <?php else: ?>
            <div class="glass-panel stat-card">
                <div class="stat-icon" style="background: rgba(79, 70, 229, 0.2); color: #818cf8;">
                    <i class="fa-solid fa-folder"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo $stats['total_projects']; ?></div>
                    <div class="stat-label">Total Projects</div>
                </div>
            </div>
            <div class="glass-panel stat-card">
                <div class="stat-icon" style="background: rgba(59, 130, 246, 0.2); color: #60a5fa;">
                    <i class="fa-solid fa-user-graduate"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo $stats['total_students']; ?></div>
                    <div class="stat-label">Students</div>
                </div>
            </div>
            <div class="glass-panel stat-card">
                <div class="stat-icon" style="background: rgba(168, 85, 247, 0.2); color: #c084fc;">
                    <i class="fa-solid fa-chalkboard-teacher"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo $stats['total_mentors']; ?></div>
                    <div class="stat-label">Mentors</div>
                </div>
            </div>
            <div class="glass-panel stat-card">
                <div class="stat-icon" style="background: rgba(34, 197, 94, 0.2); color: #4ade80;">
                    <i class="fa-solid fa-check-circle"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo $stats['completed_projects']; ?></div>
                    <div class="stat-label">Completed</div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Main Content Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
        
        <!-- Projects Card -->
        <div class="glass-panel" style="padding: 1.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h3 style="margin: 0;">
                    <?php if($role === 'student'): ?>
                        My Projects
                    <?php elseif($role === 'mentor'): ?>
                        Student Projects
                    <?php else: ?>
                        All Projects
                    <?php endif; ?>
                </h3>
                <a href="<?php echo BASE_URL; ?>/projects" style="color: var(--primary); font-size: 0.9rem;">View All →</a>
            </div>
            
            <?php if(empty($projects)): ?>
                <p style="color: var(--text-muted);">No projects found.</p>
            <?php else: ?>
                <div class="project-list">
                    <?php foreach(array_slice($projects, 0, 4) as $p): ?>
                        <a href="<?php echo BASE_URL; ?>/projects/view/<?php echo $p['id']; ?>" class="project-item">
                            <div>
                                <div class="project-title"><?php echo htmlspecialchars($p['title']); ?></div>
                                <?php if(isset($p['student_name']) && $role !== 'student'): ?>
                                    <div class="project-meta"><?php echo htmlspecialchars($p['student_name']); ?></div>
                                <?php endif; ?>
                            </div>
                            <span class="status-badge status-<?php echo $p['status']; ?>">
                                <?php echo ucfirst(str_replace('_', ' ', $p['status'])); ?>
                            </span>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <?php if($role !== 'student'): ?>
                <a href="<?php echo BASE_URL; ?>/projects/create" class="nav-btn" style="margin-top: 1rem; font-size: 0.85rem; padding: 0.5rem 1rem; display: inline-block;">
                    <i class="fa-solid fa-plus"></i> New Project
                </a>
            <?php endif; ?>
        </div>

        <!-- Tasks Card (Student) / Quick Actions (Mentor/Admin) -->
        <?php if($role === 'student'): ?>
            <div class="glass-panel" style="padding: 1.5rem;">
                <h3 style="margin-top: 0;">Recent Tasks</h3>
                <?php if(empty($recent_tasks)): ?>
                    <p style="color: var(--text-muted);">No tasks pending.</p>
                <?php else: ?>
                    <div class="task-list-dashboard">
                        <?php foreach(array_slice($recent_tasks, 0, 5) as $t): ?>
                            <div class="task-item-dashboard <?php echo $t['status'] === 'done' ? 'task-done' : ''; ?>">
                                <span class="task-status-dot status-<?php echo $t['status']; ?>"></span>
                                <div>
                                    <div><?php echo htmlspecialchars($t['title']); ?></div>
                                    <div class="task-project"><?php echo htmlspecialchars($t['project_title'] ?? ''); ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="glass-panel" style="padding: 1.5rem;">
                <h3 style="margin-top: 0;">Quick Actions</h3>
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    <a href="<?php echo BASE_URL; ?>/projects/create" class="action-btn">
                        <i class="fa-solid fa-plus"></i> Create New Project
                    </a>
                    <a href="<?php echo BASE_URL; ?>/projects" class="action-btn">
                        <i class="fa-solid fa-list"></i> View All Projects
                    </a>
                    <?php if($role === 'admin'): ?>
                        <a href="<?php echo BASE_URL; ?>/change-password" class="action-btn">
                            <i class="fa-solid fa-shield"></i> Security Settings
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- AI Insight Card -->
        <div class="glass-panel" style="padding: 1.5rem; border: 1px solid rgba(79, 70, 229, 0.4);">
            <h3 style="margin-top: 0; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fa-solid fa-robot" style="color: var(--secondary);"></i> AI Assistant
            </h3>
            <p style="color: #e2e8f0; font-style: italic; line-height: 1.6;">"<?php echo htmlspecialchars($ai_insight); ?>"</p>
            
            <?php if($role === 'student' && !empty($projects)): ?>
                <a href="<?php echo BASE_URL; ?>/projects/view/<?php echo $projects[0]['id']; ?>" class="nav-btn" style="margin-top: 1rem; display: inline-block;">
                    View Project →
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .stat-card {
        padding: 1.25rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }
    .stat-value { font-size: 1.5rem; font-weight: 600; }
    .stat-label { font-size: 0.85rem; color: var(--text-muted); }
    
    .status-badge {
        padding: 0.25rem 0.6rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    .status-pending { background: rgba(234, 179, 8, 0.2); color: #eab308; }
    .status-in_progress { background: rgba(59, 130, 246, 0.2); color: #3b82f6; }
    .status-completed { background: rgba(34, 197, 94, 0.2); color: #22c55e; }
    .status-review { background: rgba(168, 85, 247, 0.2); color: #a855f7; }
    .status-todo { background: rgba(148, 163, 184, 0.2); color: #94a3b8; }
    .status-done { background: rgba(34, 197, 94, 0.2); color: #22c55e; }
    
    .project-list { display: flex; flex-direction: column; gap: 0.5rem; }
    .project-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem;
        background: rgba(0, 0, 0, 0.2);
        border-radius: 8px;
        transition: background 0.2s;
    }
    .project-item:hover { background: rgba(0, 0, 0, 0.3); }
    .project-title { font-weight: 500; }
    .project-meta { font-size: 0.8rem; color: var(--text-muted); margin-top: 0.25rem; }
    
    .task-list-dashboard { display: flex; flex-direction: column; gap: 0.5rem; }
    .task-item-dashboard {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 0.5rem 0;
        border-bottom: 1px solid var(--glass-border);
    }
    .task-item-dashboard:last-child { border-bottom: none; }
    .task-done { opacity: 0.5; text-decoration: line-through; }
    .task-status-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        margin-top: 5px;
        flex-shrink: 0;
    }
    .task-status-dot.status-todo { background: #94a3b8; }
    .task-status-dot.status-in_progress { background: #3b82f6; }
    .task-status-dot.status-done { background: #22c55e; }
    .task-project { font-size: 0.8rem; color: var(--text-muted); }
    
    .action-btn {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        background: rgba(0, 0, 0, 0.2);
        border-radius: 8px;
        transition: background 0.2s, transform 0.2s;
    }
    .action-btn:hover { background: rgba(79, 70, 229, 0.2); transform: translateX(5px); }
</style>

<?php require ROOT_PATH . '/src/Views/layouts/footer.php'; ?>
