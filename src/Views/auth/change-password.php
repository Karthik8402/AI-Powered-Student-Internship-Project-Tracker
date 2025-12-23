<?php require ROOT_PATH . '/src/Views/layouts/header.php'; ?>

<div class="glass-panel auth-container">
    <h2 style="margin-bottom: 2rem;">Change Password</h2>
    
    <?php if(isset($error)): ?>
        <div class="alert"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if(isset($success)): ?>
        <div class="alert" style="color: #4ade80; background: rgba(74, 222, 128, 0.2); border-color: rgba(74, 222, 128, 0.4);">
            <?php echo $success; ?>
        </div>
    <?php endif; ?>

    <form action="" method="POST">
        <div class="input-group">
            <label class="input-label">Current Password</label>
            <input type="password" name="current_password" class="form-control" placeholder="••••••••" required>
        </div>
        
        <div class="input-group">
            <label class="input-label">New Password</label>
            <input type="password" name="new_password" class="form-control" placeholder="••••••••" required minlength="6">
        </div>

        <div class="input-group">
            <label class="input-label">Confirm New Password</label>
            <input type="password" name="confirm_password" class="form-control" placeholder="••••••••" required minlength="6">
        </div>

        <button type="submit" class="btn-primary">Update Password</button>
    </form>

    <p style="margin-top: 1.5rem; color: var(--text-muted); font-size: 0.9rem;">
        <a href="<?php echo BASE_URL; ?>/dashboard" style="color: var(--primary);">← Back to Dashboard</a>
    </p>
</div>

<?php require ROOT_PATH . '/src/Views/layouts/footer.php'; ?>
