<?php require ROOT_PATH . '/src/Views/layouts/header.php'; ?>

<div style="padding: 2rem 0;">
    <div style="margin-bottom: 2rem;">
        <a href="<?php echo BASE_URL; ?>/projects" style="color: var(--text-muted); font-size: 0.9rem;">
            <i class="fa-solid fa-arrow-left"></i> Back to Projects
        </a>
    </div>

    <div class="glass-panel" style="max-width: 650px; margin: 0 auto; padding: 2rem;">
        <h2 style="margin-top: 0; margin-bottom: 1.5rem;">Create New Project</h2>

        <?php if(isset($error)): ?>
            <div class="alert"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="<?php echo BASE_URL; ?>/projects/create" method="POST">
            <div class="input-group">
                <label class="input-label">Project Title *</label>
                <input type="text" name="title" class="form-control" placeholder="Enter project title" required
                       value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>">
            </div>

            <div class="input-group">
                <label class="input-label">Description</label>
                <textarea name="description" class="form-control" rows="4" placeholder="Describe the project goals and requirements..."><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
            </div>

            <!-- Project Type Selection -->
            <div class="input-group">
                <label class="input-label">Project Type</label>
                <div style="display: flex; gap: 1rem; margin-top: 0.5rem;">
                    <label class="project-type-option">
                        <input type="radio" name="project_type" value="individual" checked onchange="toggleProjectType()">
                        <span><i class="fa-solid fa-user"></i> Individual</span>
                    </label>
                    <label class="project-type-option">
                        <input type="radio" name="project_type" value="multi_student" onchange="toggleProjectType()">
                        <span><i class="fa-solid fa-users"></i> Multiple Students</span>
                    </label>
                    <label class="project-type-option">
                        <input type="radio" name="project_type" value="team" onchange="toggleProjectType()">
                        <span><i class="fa-solid fa-people-group"></i> Team</span>
                    </label>
                </div>
            </div>

            <!-- Individual Student Selection -->
            <div id="individual-section" class="input-group">
                <label class="input-label">Assign to Student</label>
                <select name="assigned_to" class="form-control">
                    <option value="">-- Select Student --</option>
                    <?php foreach($students as $student): ?>
                        <option value="<?php echo $student['id']; ?>"><?php echo htmlspecialchars($student['name']); ?> (<?php echo $student['email']; ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Multi-Student Selection -->
            <div id="multi-student-section" class="input-group" style="display: none;">
                <label class="input-label">Select Students (multiple)</label>
                <div class="student-checkbox-list">
                    <?php foreach($students as $student): ?>
                        <label class="student-checkbox">
                            <input type="checkbox" name="students[]" value="<?php echo $student['id']; ?>">
                            <span><?php echo htmlspecialchars($student['name']); ?></span>
                            <small><?php echo $student['email']; ?></small>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Team Selection -->
            <div id="team-section" class="input-group" style="display: none;">
                <label class="input-label">Select Team</label>
                <select name="team_id" class="form-control">
                    <option value="">-- Select Team --</option>
                    <?php if(!empty($teams)): ?>
                        <?php foreach($teams as $team): ?>
                            <option value="<?php echo $team['id']; ?>"><?php echo htmlspecialchars($team['name']); ?> (<?php echo $team['member_count']; ?> members)</option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <p style="font-size: 0.85rem; color: var(--text-muted); margin-top: 0.5rem;">
                    No teams? <a href="<?php echo BASE_URL; ?>/teams/create" style="color: var(--primary);">+ Create a new team</a>
                </p>
            </div>

            <div class="input-group">
                <label class="input-label">Assign Mentor</label>
                <select name="mentor_id" class="form-control">
                    <option value="">-- Select Mentor --</option>
                    <?php foreach($mentors as $mentor): ?>
                        <option value="<?php echo $mentor['id']; ?>"><?php echo htmlspecialchars($mentor['name']); ?> (<?php echo $mentor['email']; ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn-primary" style="margin-top: 1rem;">
                <i class="fa-solid fa-plus"></i> Create Project
            </button>
        </form>
    </div>
</div>

<style>
    .project-type-option {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1rem;
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .project-type-option:hover { background: rgba(79, 70, 229, 0.1); }
    .project-type-option:has(input:checked) {
        background: rgba(79, 70, 229, 0.2);
        border-color: var(--primary);
    }
    .project-type-option input { display: none; }
    .project-type-option span { display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem; }
    
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

<script>
function toggleProjectType() {
    const type = document.querySelector('input[name="project_type"]:checked').value;
    
    document.getElementById('individual-section').style.display = type === 'individual' ? 'block' : 'none';
    document.getElementById('multi-student-section').style.display = type === 'multi_student' ? 'block' : 'none';
    document.getElementById('team-section').style.display = type === 'team' ? 'block' : 'none';
}
</script>

<?php require ROOT_PATH . '/src/Views/layouts/footer.php'; ?>
