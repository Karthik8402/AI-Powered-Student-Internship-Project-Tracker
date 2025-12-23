# Student Internship and Project Tracker

An AI-powered web application for tracking student internships and projects, designed for Students, Mentors, and Admins.

## Features
- **Role-Based Access**: Specialized dashboards for Students, Mentors, and Admins.
- **Project Tracking**: Assign projects, track status, and view details.
- **Task Management**: Create and update tasks with due dates.
- **AI Assistant**: (Mock) AI suggestions for next steps based on project descriptions.
- **Security**: Secure authentication, session management, and role validation.
- **Audit Logging**: Tracks user activities.

---

## 🚀 How to Run Locally (Windows)

Since you are on Windows, the easiest way to run PHP and MySQL is using **XAMPP**.

### 1. Prerequisites
- Download and install **[XAMPP](https://www.apachefriends.org/index.html)** (select PHP and MySQL).
- Ensure Git is installed.

### 2. Setup Database
1. Open XAMPP Control Panel and Start **Apache** and **MySQL**.
2. Go to `http://localhost/phpmyadmin` in your browser.
3. Click **New** and create a database named `student_tracker`.
4. Click on the new database, then go to the **Import** tab.
5. Choose the file `sql/schema.sql` from this project and click **Go**.

### 3. Configure Application
1. Open `src/Config/Database.php`.
2. Ensure the settings match your XAMPP MySQL defaults (usually `root` with no password):
   ```php
   private $host = 'localhost';
   private $db_name = 'student_tracker';
   private $username = 'root';
   private $password = ''; // Leave empty for default XAMPP
   ```

### 4. Run the Application

**Option A: Using XAMPP (Recommended)**
1. Copy the entire project folder to `C:\xampp\htdocs\student-tracker`.
2. Open your browser and go to:
   `http://localhost/student-tracker/public`

**Option B: Using Built-in PHP Server**
If you have added PHP to your system PATH:
1. Open a terminal in the project root.
2. Run:
   ```powershell
   php -S localhost:8000 -t public
   ```
3. Visit `http://localhost:8000` in your browser.

---

## 🌐 How to Deploy to iPage

1. **Upload Files**:
   - Use File Manager or FTP (FileZilla) to upload the contents of `public/` to your `public_html` folder.
   - Upload the `src/` folder to the same level (or securely inside `public_html`).
   - *Note*: If you upload `src` inside `public_html`, ensure you protect it with `.htaccess` or move it outside the web root if possible.

2. **Database Setup**:
   - Log in to iPage Control Panel -> MySQL Database.
   - Create a new database and user.
   - Use **phpMyAdmin** on iPage to import `sql/schema.sql`.

3. **Update Config**:
   - Edit `src/Config/Database.php` on the server with your iPage database credentials.

---

## Default Login Credentials
After importing `schema.sql`, an admin account is available:
- **Email**: `admin@estar.com`
- **Password**: `password123`

You can register new Student/Mentor accounts via the Sign-Up page.
