<?php require ROOT_PATH . '/src/Views/layouts/header.php'; ?>

<div style="padding: 2rem 0;">
    <div style="margin-bottom: 2rem;">
        <a href="<?php echo BASE_URL; ?>/projects/view/<?php echo $project['id']; ?>" style="color: var(--text-muted); font-size: 0.9rem;">
            <i class="fa-solid fa-arrow-left"></i> Back to Project
        </a>
    </div>

    <div class="glass-panel" style="max-width: 600px; margin: 0 auto; padding: 2rem;">
        <h2 style="margin-top: 0; margin-bottom: 1.5rem;">Edit Project</h2>

        <?php if(isset($error)): ?>
            <div class="alert"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="<?php echo BASE_URL; ?>/projects/edit/<?php echo $project['id']; ?>" method="POST">
            <div class="input-group">
                <label class="input-label">Project Title *</label>
                <input type="text" name="title" class="form-control" placeholder="Enter project title" required
                       value="<?php echo htmlspecialchars($project['title']); ?>">
            </div>

            <div class="input-group">
                <label class="input-label">Description</label>
                <textarea name="description" class="form-control" rows="4" placeholder="Describe the project goals and requirements..."><?php echo htmlspecialchars($project['description'] ?? ''); ?></textarea>
            </div>

            <div class="input-group">
                <label class="input-label">Status</label>
                <select name="status" class="form-control">
                    <option value="pending" <?php echo $project['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="in_progress" <?php echo $project['status'] === 'in_progress' ? 'selected' : ''; ?>>In Progress</option>
                    <option value="review" <?php echo $project['status'] === 'review' ? 'selected' : ''; ?>>Under Review</option>
                    <option value="completed" <?php echo $project['status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                </select>
            </div>

            <div class="input-group">
                <label class="input-label">Assign to Student</label>
                <select name="assigned_to" class="form-control">
                    <option value="">-- Select Student --</option>
                    <?php foreach($students as $student): ?>
                        <option value="<?php echo $student['id']; ?>" <?php echo $project['assigned_to'] == $student['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($student['name']); ?> (<?php echo $student['email']; ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="input-group">
                <label class="input-label">Assign Mentor</label>
                <select name="mentor_id" class="form-control">
                    <option value="">-- Select Mentor --</option>
                    <?php foreach($mentors as $mentor): ?>
                        <option value="<?php echo $mentor['id']; ?>" <?php echo $project['mentor_id'] == $mentor['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($mentor['name']); ?> (<?php echo $mentor['email']; ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn-primary" style="margin-top: 1rem;">
                <i class="fa-solid fa-save"></i> Update Project
            </button>
        </form>
    </div>
</div>

<?php require ROOT_PATH . '/src/Views/layouts/footer.php'; ?>
