<?php require ROOT_PATH . '/src/Views/layouts/header.php'; ?>

<div style="padding: 2rem 0;">
    <div style="margin-bottom: 2rem;">
        <a href="<?php echo BASE_URL; ?>/projects/view/<?php echo $project['id']; ?>" style="color: var(--text-muted); font-size: 0.9rem;">
            <i class="fa-solid fa-arrow-left"></i> Back to Project
        </a>
    </div>

    <div class="glass-panel" style="max-width: 500px; margin: 0 auto; padding: 2rem;">
        <h2 style="margin-top: 0; margin-bottom: 1.5rem;">Add New Task</h2>
        <p style="color: var(--text-muted); margin-bottom: 1.5rem;">
            Adding task to: <strong><?php echo htmlspecialchars($project['title']); ?></strong>
        </p>

        <form action="<?php echo BASE_URL; ?>/tasks/create" method="POST">
            <input type="hidden" name="project_id" value="<?php echo $project['id']; ?>">

            <div class="input-group">
                <label class="input-label">Task Title *</label>
                <input type="text" name="title" class="form-control" placeholder="What needs to be done?" required>
            </div>

            <div class="input-group">
                <label class="input-label">Description</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Add details about this task..."></textarea>
            </div>

            <!-- Student Assignment (only show if project has multiple students) -->
            <?php if(!empty($projectStudents) && count($projectStudents) > 1): ?>
                <div class="input-group">
                    <label class="input-label">Assign to Student</label>
                    <select name="assigned_to" class="form-control">
                        <option value="">-- All Students (Team Task) --</option>
                        <?php foreach($projectStudents as $student): ?>
                            <option value="<?php echo $student['id']; ?>"><?php echo htmlspecialchars($student['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.5rem;">
                        Leave empty to assign to all students
                    </p>
                </div>
            <?php elseif(!empty($projectStudents) && count($projectStudents) == 1): ?>
                <input type="hidden" name="assigned_to" value="<?php echo $projectStudents[0]['id']; ?>">
            <?php endif; ?>

            <div class="input-group">
                <label class="input-label">Due Date</label>
                <input type="date" name="due_date" class="form-control">
            </div>

            <button type="submit" class="btn-primary" style="margin-top: 1rem;">
                <i class="fa-solid fa-plus"></i> Add Task
            </button>
        </form>
    </div>
</div>

<?php require ROOT_PATH . '/src/Views/layouts/footer.php'; ?>
