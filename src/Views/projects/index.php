<?php require ROOT_PATH . '/src/Views/layouts/header.php'; ?>

<div style="padding: 2rem 0;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 style="font-weight: 300; margin: 0;">Projects</h1>
            <p style="color: var(--text-muted); margin-top: 0.5rem;">Manage your internship projects</p>
        </div>
        <?php if($_SESSION['user_role'] === 'admin' || $_SESSION['user_role'] === 'mentor'): ?>
            <a href="<?php echo BASE_URL; ?>/projects/create" class="nav-btn" style="display: inline-flex; align-items: center; gap: 0.5rem;">
                <i class="fa-solid fa-plus"></i> New Project
            </a>
        <?php endif; ?>
    </div>

    <?php if(isset($_GET['created'])): ?>
        <div class="alert" style="color: #4ade80; background: rgba(74, 222, 128, 0.2); border-color: rgba(74, 222, 128, 0.4); margin-bottom: 1.5rem;">
            Project created successfully!
        </div>
    <?php endif; ?>

    <?php if(isset($_GET['deleted'])): ?>
        <div class="alert" style="color: #4ade80; background: rgba(74, 222, 128, 0.2); border-color: rgba(74, 222, 128, 0.4); margin-bottom: 1.5rem;">
            Project deleted successfully!
        </div>
    <?php endif; ?>

    <?php if(empty($projects)): ?>
        <div class="glass-panel" style="padding: 3rem; text-align: center;">
            <i class="fa-solid fa-folder-open" style="font-size: 3rem; color: var(--text-muted); margin-bottom: 1rem;"></i>
            <h3 style="color: var(--text-muted); font-weight: 400;">No projects found</h3>
            <p style="color: var(--text-muted);">
                <?php if($_SESSION['user_role'] === 'student'): ?>
                    You haven't been assigned any projects yet.
                <?php else: ?>
                    Create a new project to get started.
                <?php endif; ?>
            </p>
        </div>
    <?php else: ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.5rem;">
            <?php foreach($projects as $project): ?>
                <div class="glass-panel" style="padding: 1.5rem; display: flex; flex-direction: column;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                        <h3 style="margin: 0; font-size: 1.1rem;"><?php echo htmlspecialchars($project['title']); ?></h3>
                        <span class="status-badge status-<?php echo $project['status']; ?>">
                            <?php echo ucfirst(str_replace('_', ' ', $project['status'])); ?>
                        </span>
                    </div>
                    
                    <p style="color: var(--text-muted); font-size: 0.9rem; flex-grow: 1; margin-bottom: 1rem;">
                        <?php echo htmlspecialchars(substr($project['description'] ?? 'No description', 0, 100)); ?>
                        <?php echo strlen($project['description'] ?? '') > 100 ? '...' : ''; ?>
                    </p>
                    
                    <div style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1rem;">
                        <?php if(isset($project['student_name'])): ?>
                            <div><i class="fa-solid fa-user"></i> <?php echo htmlspecialchars($project['student_name']); ?></div>
                        <?php endif; ?>
                        <?php if(isset($project['mentor_name'])): ?>
                            <div><i class="fa-solid fa-chalkboard-teacher"></i> <?php echo htmlspecialchars($project['mentor_name']); ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <a href="<?php echo BASE_URL; ?>/projects/view/<?php echo $project['id']; ?>" class="nav-btn" style="text-align: center; margin-top: auto;">
                        View Details
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
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
</style>

<?php require ROOT_PATH . '/src/Views/layouts/footer.php'; ?>
