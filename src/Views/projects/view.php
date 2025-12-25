<?php require ROOT_PATH . '/src/Views/layouts/header.php'; ?>

<div style="padding: 2rem 0;">
    <div style="margin-bottom: 2rem;">
        <a href="<?php echo BASE_URL; ?>/projects" style="color: var(--text-muted); font-size: 0.9rem;">
            <i class="fa-solid fa-arrow-left"></i> Back to Projects
        </a>
    </div>

    <?php if(isset($_GET['updated'])): ?>
        <div class="alert" style="color: #4ade80; background: rgba(74, 222, 128, 0.2); border-color: rgba(74, 222, 128, 0.4); margin-bottom: 1.5rem;">
            Project updated successfully!
        </div>
    <?php endif; ?>

    <?php if(isset($_GET['task_created'])): ?>
        <div class="alert" style="color: #4ade80; background: rgba(74, 222, 128, 0.2); border-color: rgba(74, 222, 128, 0.4); margin-bottom: 1.5rem;">
            Task added successfully!
        </div>
    <?php endif; ?>

    <!-- Project Header -->
    <div class="glass-panel" style="padding: 2rem; margin-bottom: 1.5rem;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem;">
            <div>
                <h1 style="margin: 0; font-weight: 400;"><?php echo htmlspecialchars($project['title']); ?></h1>
                <div style="margin-top: 0.5rem;">
                    <span class="status-badge status-<?php echo $project['status']; ?>">
                        <?php echo ucfirst(str_replace('_', ' ', $project['status'])); ?>
                    </span>
                </div>
            </div>
            
            <?php if($_SESSION['user_role'] === 'admin' || $_SESSION['user_role'] === 'mentor'): ?>
                <div style="display: flex; gap: 0.5rem;">
                    <a href="<?php echo BASE_URL; ?>/projects/edit/<?php echo $project['id']; ?>" class="nav-btn" style="background: var(--glass-bg); border: 1px solid var(--glass-border);">
                        <i class="fa-solid fa-edit"></i> Edit
                    </a>
                    <a href="<?php echo BASE_URL; ?>/projects/delete/<?php echo $project['id']; ?>" class="nav-btn" style="background: rgba(239, 68, 68, 0.2); border: 1px solid rgba(239, 68, 68, 0.4);"
                       onclick="return confirm('Are you sure you want to delete this project?');">
                        <i class="fa-solid fa-trash"></i> Delete
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <p style="color: var(--text-muted); margin-top: 1.5rem; line-height: 1.6;">
            <?php echo nl2br(htmlspecialchars($project['description'] ?? 'No description provided.')); ?>
        </p>

        <div style="display: flex; flex-wrap: wrap; gap: 2rem; margin-top: 1.5rem; font-size: 0.9rem; color: var(--text-muted);">
            <!-- Show project type -->
            <div>
                <i class="fa-solid fa-tag"></i> Type: 
                <strong><?php echo ucfirst(str_replace('_', ' ', $project['project_type'] ?? 'individual')); ?></strong>
            </div>
            
            <!-- Show students -->
            <?php if(!empty($projectStudents)): ?>
                <div>
                    <i class="fa-solid fa-users"></i> 
                    <?php if(count($projectStudents) == 1): ?>
                        Student: <strong><?php echo htmlspecialchars($projectStudents[0]['name']); ?></strong>
                    <?php else: ?>
                        Students: 
                        <strong>
                            <?php echo implode(', ', array_map(function($s) { 
                                return htmlspecialchars($s['name']); 
                            }, $projectStudents)); ?>
                        </strong>
                    <?php endif; ?>
                </div>
            <?php elseif($project['team_name']): ?>
                <div><i class="fa-solid fa-people-group"></i> Team: <strong><?php echo htmlspecialchars($project['team_name']); ?></strong></div>
            <?php endif; ?>
            
            <?php if($project['mentor_name']): ?>
                <div><i class="fa-solid fa-chalkboard-teacher"></i> Mentor: <strong><?php echo htmlspecialchars($project['mentor_name']); ?></strong></div>
            <?php endif; ?>
        </div>
    </div>

    <!-- AI Suggestion Card -->
    <div class="glass-panel" style="padding: 1.5rem; margin-bottom: 1.5rem; border: 1px solid rgba(79, 70, 229, 0.4);">
        <h3 style="margin-top: 0; display: flex; align-items: center; gap: 0.5rem;">
            <i class="fa-solid fa-robot" style="color: var(--secondary);"></i> AI Suggestion
        </h3>
        <p style="color: #e2e8f0; font-style: italic; margin-bottom: 0;">"<?php echo htmlspecialchars($ai_suggestion); ?>"</p>
    </div>

    <!-- Tasks Section -->
    <div class="glass-panel" style="padding: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h2 style="margin: 0; font-weight: 400;">Tasks</h2>
            <a href="<?php echo BASE_URL; ?>/tasks/create/<?php echo $project['id']; ?>" class="nav-btn" style="font-size: 0.9rem;">
                <i class="fa-solid fa-plus"></i> Add Task
            </a>
        </div>

        <?php if(empty($tasks)): ?>
            <p style="color: var(--text-muted); text-align: center; padding: 2rem;">
                No tasks yet. Add your first task to get started!
            </p>
        <?php else: ?>
            <div class="task-list">
                <?php foreach($tasks as $task): ?>
                    <div class="task-item" data-id="<?php echo $task['id']; ?>">
                        <div style="display: flex; align-items: flex-start; gap: 1rem; flex-grow: 1;">
                            <?php if($_SESSION['user_role'] === 'student'): ?>
                                <!-- Students can change status -->
                                <select class="task-status-select form-control" style="width: auto; padding: 0.4rem;" 
                                        onchange="updateTaskStatus(<?php echo $task['id']; ?>, this.value)"
                                        onclick="event.stopPropagation();">
                                    <option value="todo" <?php echo $task['status'] === 'todo' ? 'selected' : ''; ?>>To Do</option>
                                    <option value="in_progress" <?php echo $task['status'] === 'in_progress' ? 'selected' : ''; ?>>In Progress</option>
                                    <option value="done" <?php echo $task['status'] === 'done' ? 'selected' : ''; ?>>Done</option>
                                </select>
                            <?php else: ?>
                                <!-- Mentors/Admins see status as badge (read-only) -->
                                <?php
                                    $statusColors = [
                                        'todo' => 'background: rgba(239, 68, 68, 0.2); color: #f87171;',
                                        'in_progress' => 'background: rgba(251, 191, 36, 0.2); color: #fbbf24;',
                                        'done' => 'background: rgba(74, 222, 128, 0.2); color: #4ade80;'
                                    ];
                                    $statusLabels = ['todo' => 'To Do', 'in_progress' => 'In Progress', 'done' => 'Done'];
                                ?>
                                <span style="padding: 0.4rem 0.75rem; border-radius: 6px; font-size: 0.8rem; font-weight: 500; <?php echo $statusColors[$task['status']]; ?>">
                                    <?php echo $statusLabels[$task['status']]; ?>
                                </span>
                            <?php endif; ?>
                            <div style="flex-grow: 1; cursor: pointer;" onclick="toggleTaskDescription(<?php echo $task['id']; ?>)">
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <i class="fa-solid fa-chevron-right task-chevron" id="chevron-<?php echo $task['id']; ?>" style="font-size: 0.7rem; color: var(--text-muted); transition: transform 0.2s;"></i>
                                    <span style="font-weight: 500; <?php echo $task['status'] === 'done' ? 'text-decoration: line-through; opacity: 0.6;' : ''; ?>">
                                        <?php echo htmlspecialchars($task['title']); ?>
                                        <?php if($task['is_ai_suggested']): ?>
                                            <span style="color: var(--secondary); font-size: 0.75rem;"><i class="fa-solid fa-robot"></i></span>
                                        <?php endif; ?>
                                    </span>
                                </div>
                                <?php if(!empty($task['description'])): ?>
                                    <div class="task-description" id="desc-<?php echo $task['id']; ?>" style="display: none; font-size: 0.85rem; color: var(--text-muted); margin: 0.5rem 0 0.5rem 1.2rem; line-height: 1.5; padding: 0.75rem; background: rgba(0,0,0,0.2); border-radius: 6px;">
                                        <?php echo nl2br(htmlspecialchars($task['description'])); ?>
                                    </div>
                                <?php endif; ?>
                                <div style="display: flex; gap: 1rem; flex-wrap: wrap; margin-left: 1.2rem; margin-top: 0.25rem;">
                                    <?php if($task['due_date']): ?>
                                        <div style="font-size: 0.8rem; color: var(--text-muted);">
                                            <i class="fa-regular fa-calendar"></i> Due: <?php echo date('M d, Y', strtotime($task['due_date'])); ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if(!empty($task['assigned_student_name'])): ?>
                                        <div style="font-size: 0.8rem; color: #60a5fa;">
                                            <i class="fa-solid fa-user"></i> <?php echo htmlspecialchars($task['assigned_student_name']); ?>
                                        </div>
                                    <?php elseif(count($projectStudents) > 1): ?>
                                        <div style="font-size: 0.8rem; color: var(--text-muted);">
                                            <i class="fa-solid fa-users"></i> All Students
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <a href="<?php echo BASE_URL; ?>/tasks/delete/<?php echo $task['id']; ?>" class="task-delete" 
                           onclick="return confirm('Delete this task?');">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    .status-pending { background: rgba(234, 179, 8, 0.2); color: #eab308; }
    .status-in_progress { background: rgba(59, 130, 246, 0.2); color: #3b82f6; }
    .status-completed { background: rgba(34, 197, 94, 0.2); color: #22c55e; }
    .status-review { background: rgba(168, 85, 247, 0.2); color: #a855f7; }
    
    .task-list { display: flex; flex-direction: column; gap: 0.75rem; }
    .task-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background: rgba(0, 0, 0, 0.2);
        border-radius: 8px;
        border: 1px solid var(--glass-border);
    }
    .task-delete {
        color: #ef4444;
        opacity: 0.6;
        transition: opacity 0.2s;
    }
    .task-delete:hover { opacity: 1; }
</style>

<script>
function updateTaskStatus(taskId, status) {
    fetch('<?php echo BASE_URL; ?>/tasks/update-status', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'id=' + taskId + '&status=' + status
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

function toggleTaskDescription(taskId) {
    const desc = document.getElementById('desc-' + taskId);
    const chevron = document.getElementById('chevron-' + taskId);
    
    if (desc) {
        if (desc.style.display === 'none') {
            desc.style.display = 'block';
            chevron.style.transform = 'rotate(90deg)';
        } else {
            desc.style.display = 'none';
            chevron.style.transform = 'rotate(0deg)';
        }
    }
}
</script>

<?php require ROOT_PATH . '/src/Views/layouts/footer.php'; ?>
