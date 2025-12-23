<?php require ROOT_PATH . '/src/Views/layouts/header.php'; ?>

<div style="padding: 2rem 0;">
    <div style="margin-bottom: 2rem;">
        <a href="<?php echo BASE_URL; ?>/teams" style="color: var(--text-muted); font-size: 0.9rem;">
            <i class="fa-solid fa-arrow-left"></i> Back to Teams
        </a>
    </div>

    <?php if(isset($_GET['updated'])): ?>
        <div class="alert" style="color: #4ade80; background: rgba(74, 222, 128, 0.2); border-color: rgba(74, 222, 128, 0.4); margin-bottom: 1.5rem;">
            Team updated successfully!
        </div>
    <?php endif; ?>

    <div class="glass-panel" style="padding: 2rem; margin-bottom: 1.5rem;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 60px; height: 60px; background: rgba(79, 70, 229, 0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i class="fa-solid fa-people-group" style="color: var(--primary); font-size: 1.5rem;"></i>
                </div>
                <div>
                    <h1 style="margin: 0; font-weight: 400;"><?php echo htmlspecialchars($team['name']); ?></h1>
                    <p style="color: var(--text-muted); margin: 0.25rem 0 0 0;">
                        <?php echo count($members); ?> member<?php echo count($members) != 1 ? 's' : ''; ?>
                    </p>
                </div>
            </div>
            
            <?php if($_SESSION['user_role'] === 'admin' || $_SESSION['user_role'] === 'mentor'): ?>
                <div style="display: flex; gap: 0.5rem;">
                    <a href="<?php echo BASE_URL; ?>/teams/edit/<?php echo $team['id']; ?>" class="nav-btn" style="background: var(--glass-bg); border: 1px solid var(--glass-border);">
                        <i class="fa-solid fa-edit"></i> Edit
                    </a>
                    <a href="<?php echo BASE_URL; ?>/teams/delete/<?php echo $team['id']; ?>" class="nav-btn" style="background: rgba(239, 68, 68, 0.2); border: 1px solid rgba(239, 68, 68, 0.4);"
                       onclick="return confirm('Delete this team? This cannot be undone.');">
                        <i class="fa-solid fa-trash"></i> Delete
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <?php if($team['description']): ?>
            <p style="color: var(--text-muted); margin-top: 1.5rem; line-height: 1.6;">
                <?php echo nl2br(htmlspecialchars($team['description'])); ?>
            </p>
        <?php endif; ?>
    </div>

    <!-- Team Members -->
    <div class="glass-panel" style="padding: 2rem;">
        <h2 style="margin-top: 0; font-weight: 400;">Team Members</h2>
        
        <?php if(empty($members)): ?>
            <p style="color: var(--text-muted); text-align: center; padding: 2rem;">
                No members in this team yet.
            </p>
        <?php else: ?>
            <div style="display: grid; gap: 0.75rem;">
                <?php foreach($members as $member): ?>
                    <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: rgba(0,0,0,0.2); border-radius: 8px;">
                        <div style="width: 40px; height: 40px; background: rgba(59, 130, 246, 0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fa-solid fa-user" style="color: #60a5fa;"></i>
                        </div>
                        <div>
                            <div style="font-weight: 500;"><?php echo htmlspecialchars($member['name']); ?></div>
                            <div style="font-size: 0.85rem; color: var(--text-muted);"><?php echo $member['email']; ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require ROOT_PATH . '/src/Views/layouts/footer.php'; ?>
