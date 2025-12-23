<?php require ROOT_PATH . '/src/Views/layouts/header.php'; ?>

<div class="glass-panel auth-container">
    <h2 style="margin-bottom: 2rem;">Create Account</h2>
    
    <?php if(isset($error)): ?>
        <div class="alert"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="" method="POST">
        <div class="input-group">
            <label class="input-label">Full Name</label>
            <input type="text" name="name" class="form-control" placeholder="John Doe" required>
        </div>

        <div class="input-group">
            <label class="input-label">Email Address</label>
            <input type="email" name="email" class="form-control" placeholder="student@example.com" required>
        </div>
        
        <div class="input-group">
            <label class="input-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>

        <div class="input-group">
            <label class="input-label">I am a...</label>
            <select name="role" class="form-control" style="background: rgba(0, 0, 0, 0.2); color: white;">
                <option value="student">Student</option>
                <option value="mentor">Mentor</option>
                <!-- Admin registration is hidden/manual usually, but leaving it out here for safety -->
            </select>
        </div>

        <button type="submit" class="btn-primary">Create Account</button>
    </form>

    <p style="margin-top: 1.5rem; color: var(--text-muted); font-size: 0.9rem;">
        Already have an account? <a href="<?php echo BASE_URL; ?>/login" style="color: var(--primary);">Sign in</a>
    </p>
</div>

<?php require ROOT_PATH . '/src/Views/layouts/footer.php'; ?>
