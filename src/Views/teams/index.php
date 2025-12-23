<?php require ROOT_PATH . '/src/Views/layouts/header.php'; ?>

<div style="padding: 2rem 0;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 style="margin: 0; font-weight: 300;">Teams</h1>
            <p style="color: var(--text-muted); margin-top: 0.5rem;">Manage student teams</p>
        </div>
        <?php if($_SESSION['user_role'] === 'admin' || $_SESSION['user_role'] === 'mentor'): ?>
            <a href="<?php echo BASE_URL; ?>/teams/create" class="nav-btn" style="display: inline-flex; align-items: center; gap: 0.5rem;">
                <i class="fa-solid fa-plus"></i> New Team
            </a>
        <?php endif; ?>
    </div>

    <?php if(isset($_GET['created'])): ?>
        <div class="alert" style="color: #4ade80; background: rgba(74, 222, 128, 0.2); border-color: rgba(74, 222, 128, 0.4); margin-bottom: 1.5rem;">
            Team created successfully!
        </div>
    <?php endif; ?>

    <?php if(isset($_GET['deleted'])): ?>
        <div class="alert" style="color: #4ade80; background: rgba(74, 222, 128, 0.2); border-color: rgba(74, 222, 128, 0.4); margin-bottom: 1.5rem;">
            Team deleted successfully!
        </div>
    <?php endif; ?>

    <?php if(empty($teams)): ?>
        <div class="glass-panel" style="padding: 3rem; text-align: center;">
            <i class="fa-solid fa-people-group" style="font-size: 3rem; color: var(--text-muted); margin-bottom: 1rem;"></i>
            <h3 style="font-weight: 300;">No teams yet</h3>
            <p style="color: var(--text-muted);">Create a team to group students for projects.</p>
            <?php if($_SESSION['user_role'] !== 'student'): ?>
                <a href="<?php echo BASE_URL; ?>/teams/create" class="nav-btn" style="margin-top: 1rem; display: inline-block;">
                    <i class="fa-solid fa-plus"></i> Create First Team
                </a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
            <?php foreach($teams as $team): ?>
                <div class="glass-panel" style="padding: 1.5rem;">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                        <div style="width: 50px; height: 50px; background: rgba(79, 70, 229, 0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fa-solid fa-people-group" style="color: var(--primary); font-size: 1.25rem;"></i>
                        </div>
                        <div>
                            <h3 style="margin: 0;"><?php echo htmlspecialchars($team['name']); ?></h3>
                            <span style="font-size: 0.85rem; color: var(--text-muted);">
                                <?php echo $team['member_count']; ?> member<?php echo $team['member_count'] != 1 ? 's' : ''; ?>
                            </span>
                        </div>
                    </div>
                    
                    <?php if($team['creator_name']): ?>
                        <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1rem;">
                            Created by <?php echo htmlspecialchars($team['creator_name']); ?>
                        </p>
                    <?php endif; ?>
                    
                    <a href="<?php echo BASE_URL; ?>/teams/view/<?php echo $team['id']; ?>" class="nav-btn" style="width: 100%; text-align: center;">
                        View Team
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require ROOT_PATH . '/src/Views/layouts/footer.php'; ?>
