<?php require ROOT_PATH . '/src/Views/layouts/header.php'; ?>

<div class="glass-panel auth-container">
    <h2 style="margin-bottom: 2rem;">Welcome Back</h2>
    
    <?php if(isset($error)): ?>
        <div class="alert"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if(isset($_GET['registered'])): ?>
        <div class="alert" style="color: #4ade80; background: rgba(74, 222, 128, 0.2); border-color: rgba(74, 222, 128, 0.4);">
            Registration successful! Please login.
        </div>
    <?php endif; ?>

    <form action="/login" method="POST">
        <div class="input-group">
            <label class="input-label">Email Address</label>
            <input type="email" name="email" class="form-control" placeholder="student@example.com" required>
        </div>
        
        <div class="input-group">
            <label class="input-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>

        <button type="submit" class="btn-primary">Sign In</button>
    </form>

    <p style="margin-top: 1.5rem; color: var(--text-muted); font-size: 0.9rem;">
        Don't have an account? <a href="/register" style="color: var(--primary);">Sign up</a>
    </p>
</div>

<?php require ROOT_PATH . '/src/Views/layouts/footer.php'; ?>
