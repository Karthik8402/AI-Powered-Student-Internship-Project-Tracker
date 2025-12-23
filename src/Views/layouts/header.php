<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Tracker | eSTAR</title>
    <!-- Use Google Fonts for premium feel -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #4F46E5;
            --secondary: #ec4899;
            --bg-gradient: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            --glass-bg: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.1);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: var(--bg-gradient);
            color: var(--text-main);
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        a { text-decoration: none; color: inherit; }

        .glass-panel {
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        }

        /* Navbar */
        .navbar {
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-brand { font-size: 1.5rem; font-weight: 700; background: linear-gradient(to right, var(--primary), var(--secondary)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        
        .nav-links { display: flex; gap: 1.5rem; }
        .nav-item { color: var(--text-muted); transition: color 0.3s; font-size: 0.95rem; }
        .nav-item:hover { color: var(--text-main); }
        .nav-btn {
            background: var(--primary);
            color: white;
            padding: 0.5rem 1.2rem;
            border-radius: 8px;
            transition: transform 0.2s;
        }
        .nav-btn:hover { transform: translateY(-2px); }

        /* Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
            width: 100%;
        }

        /* Form Styles */
        .auth-container {
            max-width: 400px;
            margin: 4rem auto;
            padding: 2rem;
            text-align: center;
        }
        
        .input-group { margin-bottom: 1.5rem; text-align: left; }
        .input-label { display: block; margin-bottom: 0.5rem; color: var(--text-muted); font-size: 0.9rem; }
        .form-control {
            width: 100%;
            padding: 0.8rem;
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid var(--glass-border);
            border-radius: 8px;
            color: white;
            font-family: inherit;
            box-sizing: border-box; /* Fix padding issue */
        }
        .form-control:focus { outline: none; border-color: var(--primary); }

        .btn-primary {
            width: 100%;
            padding: 0.8rem;
            background: linear-gradient(to right, var(--primary), var(--secondary));
            border: none;
            border-radius: 8px;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: opacity 0.3s;
        }
        .btn-primary:hover { opacity: 0.9; }

        .alert {
            padding: 0.8rem;
            background: rgba(239, 68, 68, 0.2);
            border: 1px solid rgba(239, 68, 68, 0.4);
            color: #fca5a5;
            border-radius: 8px;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

<nav class="navbar glass-panel" style="border-radius: 0; border-left: 0; border-right: 0; border-top: 0;">
    <a href="/" class="nav-brand">eSTAR Tracker</a>
    <div class="nav-links">
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="/dashboard" class="nav-item">Dashboard</a>
            <a href="/logout" class="nav-item">Logout</a>
        <?php else: ?>
            <a href="/login" class="nav-item">Login</a>
            <a href="/register" class="nav-btn">Sign Up</a>
        <?php endif; ?>
    </div>
</nav>

<div class="container">
