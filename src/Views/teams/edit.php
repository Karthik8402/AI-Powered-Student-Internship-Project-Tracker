<?php require ROOT_PATH . '/src/Views/layouts/header.php'; ?>

<div style="padding: 2rem 0;">
    <div style="margin-bottom: 2rem;">
        <a href="<?php echo BASE_URL; ?>/teams" style="color: var(--text-muted); font-size: 0.9rem;">
            <i class="fa-solid fa-arrow-left"></i> Back to Teams
        </a>
    </div>

    <div class="glass-panel" style="max-width: 550px; margin: 0 auto; padding: 2rem;">
        <h2 style="margin-top: 0; margin-bottom: 1.5rem;">Edit Team: <?php echo htmlspecialchars($team['name']); ?></h2>

        <?php if(isset($error)): ?>
            <div class="alert"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="<?php echo BASE_URL; ?>/teams/edit/<?php echo $team['id']; ?>" method="POST">
            <div class="input-group">
                <label class="input-label">Team Name *</label>
                <input type="text" name="name" class="form-control" required
                       value="<?php echo htmlspecialchars($_POST['name'] ?? $team['name']); ?>">
            </div>

            <div class="input-group">
                <label class="input-label">Description</label>
                <textarea name="description" class="form-control" rows="2"><?php echo htmlspecialchars($_POST['description'] ?? $team['description']); ?></textarea>
            </div>

            <div class="input-group">
                <label class="input-label">Team Members</label>
                <div class="student-checkbox-list">
                    <?php if(empty($students)): ?>
                        <p style="padding: 1rem; color: var(--text-muted); text-align: center;">No students available</p>
                    <?php else: ?>
                        <?php 
                        // Get IDs of current members for checking boxes
                        $currentMemberIds = array_column($members, 'id');
                        ?>
                        <?php foreach($students as $student): ?>
                            <?php 
                            $isChecked = in_array($student['id'], $currentMemberIds);
                            // If form was submitted with errors, preserve selection from POST instead
                            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['members'])) {
                                $isChecked = in_array($student['id'], $_POST['members']);
                            }
                            ?>
                            <label class="student-checkbox">
                                <input type="checkbox" name="members[]" value="<?php echo $student['id']; ?>" <?php echo $isChecked ? 'checked' : ''; ?>>
                                <span><?php echo htmlspecialchars($student['name']); ?></span>
                                <small><?php echo $student['email']; ?></small>
                            </label>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <p style="margin-top: 0.5rem; font-size: 0.8rem; color: var(--text-muted);">
                    <i class="fa-solid fa-circle-info"></i> Uncheck users to remove them from the team.
                </p>
            </div>

            <button type="submit" class="btn-primary" style="margin-top: 1rem;">
                <i class="fa-solid fa-save"></i> Save Changes
            </button>
        </form>
    </div>
</div>

<style>
    .student-checkbox-list {
        max-height: 200px;
        overflow-y: auto;
        border: 1px solid var(--glass-border);
        border-radius: 8px;
        padding: 0.5rem;
    }
    .student-checkbox {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.5rem;
        border-radius: 6px;
        cursor: pointer;
        transition: background 0.2s;
    }
    .student-checkbox:hover { background: rgba(255,255,255,0.05); }
    .student-checkbox input { width: 18px; height: 18px; cursor: pointer; }
    .student-checkbox span { flex: 1; }
    .student-checkbox small { color: var(--text-muted); font-size: 0.8rem; }
</style>

<?php require ROOT_PATH . '/src/Views/layouts/footer.php'; ?>
