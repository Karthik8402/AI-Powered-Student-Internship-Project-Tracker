<?php require ROOT_PATH . '/src/Views/layouts/header.php'; ?>

<div style="padding: 2rem 0;">
    <h1 style="font-weight: 300;">Dashboard</h1>
    <p style="color: var(--text-muted);">Welcome, <strong><?php echo $_SESSION['user_name']; ?></strong> (<?php echo $_SESSION['user_role']; ?>)</p>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-top: 2rem;">
        <!-- Card 1: Projects -->
        <div class="glass-panel" style="padding: 1.5rem;">
            <h3 style="margin-top: 0;">My Projects</h3>
            <?php if(empty($projects)): ?>
                <p style="color: var(--text-muted);">No projects found.</p>
            <?php else: ?>
                <ul style="padding-left: 1rem; color: var(--text-muted);">
                    <?php foreach($projects as $p): ?>
                        <li>
                            <strong><?php echo $p['title']; ?></strong> - 
                            <span><?php echo ucfirst($p['status']); ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <button class="nav-btn" style="margin-top: 1rem; width: 100%;">Create New Project</button>
        </div>

        <!-- Card 2: Tasks -->
        <div class="glass-panel" style="padding: 1.5rem;">
            <h3 style="margin-top: 0;">Recent Tasks</h3>
            <?php if(empty($recent_tasks)): ?>
                <p style="color: var(--text-muted);">No tasks pending.</p>
            <?php else: ?>
                <ul style="padding-left: 1rem; color: var(--text-muted);">
                    <?php foreach(array_slice($recent_tasks, 0, 5) as $t): ?>
                        <li><?php echo $t['title']; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <!-- Card 3: AI -->
        <div class="glass-panel" style="padding: 1.5rem; border: 1px solid rgba(79, 70, 229, 0.4);">
            <h3 style="margin-top: 0; display:flex; align-items:center; gap:0.5rem;">
                <i class="fa-solid fa-robot" style="color: var(--secondary);"></i> AI Assistant
            </h3>
            <p style="color: #e2e8f0; font-style: italic;">"<?php echo $ai_insight; ?>"</p>
        </div>
    </div>
</div>

<?php require ROOT_PATH . '/src/Views/layouts/footer.php'; ?>
