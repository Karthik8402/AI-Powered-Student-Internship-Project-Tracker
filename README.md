# 🎓 Student Internship & Project Tracker (eSTAR)

An AI-powered web application for tracking student internships and projects with role-based access for Students, Mentors, and Admins.

![PHP](https://img.shields.io/badge/PHP-8.0+-777BB4?logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-5.7+-4479A1?logo=mysql&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green)

## ✨ Features

### 🔐 Role-Based System
| Role | Capabilities |
|------|--------------|
| **Student** | View assigned projects, update task status, see personal tasks only |
| **Mentor** | Manage projects, assign tasks to students, view all student progress |
| **Admin** | Full access, manage teams, users, and all projects |

### 📋 Project Management
- **Multi-Student Projects** - Assign multiple students to one project
- **Team Projects** - Create teams and assign projects to entire teams
- **Individual Projects** - Traditional single-student assignment

### ✅ Task Management
- Create tasks with due dates
- Assign tasks to specific students or all team members
- Students update their own task status (To Do → In Progress → Done)
- Mentors/Admins view task progress (read-only)

### 🤖 AI Assistant
- Smart task suggestions based on project description and status
- Contextual recommendations for web, mobile, API, and database projects

### 🔒 Security
- Bcrypt password hashing
- Session-based authentication
- Role-based access control
- Prepared SQL statements (SQL injection prevention)

---

## 🚀 Quick Start (XAMPP)

### Prerequisites
- [XAMPP](https://www.apachefriends.org/) with PHP 8.0+ and MySQL
- Git

### Installation

1. **Clone the repository**
   ```bash
   cd C:\xampp\htdocs
   git clone https://github.com/YOUR_USERNAME/student-tracker.git
   ```

2. **Setup Database**
   - Start Apache & MySQL in XAMPP Control Panel
   - Open `http://localhost/phpmyadmin`
   - Create database: `student_tracker`
   - Import: `sql/schema.sql`

3. **Configure Database** (if needed)
   Edit `src/Config/Database.php` with your credentials

4. **Access the app**
   ```
   http://localhost/student-tracker/public
   ```

### Default Login
| Role | Email | Password |
|------|-------|----------|
| Admin | `admin@estar.com` | `password` |

---

## 📁 Project Structure

```
student-tracker/
├── public/                 # Web root (entry point)
│   └── index.php           # Router
├── src/
│   ├── Config/             # Database configuration
│   ├── Controllers/        # Request handlers
│   ├── Models/             # Database models
│   ├── Services/           # AI Service
│   └── Views/              # PHP templates
│       ├── auth/           # Login, Register, Change Password
│       ├── dashboard/      # Role-specific dashboards
│       ├── projects/       # Project CRUD views
│       ├── tasks/          # Task views
│       ├── teams/          # Team management
│       └── layouts/        # Header, Footer
└── sql/
    ├── schema.sql          # Full database schema
    └── migration_add_teams.sql  # Migration for existing DBs
```

---

## 🌐 Deployment (Shared Hosting)

### For iPage, Hostinger, GoDaddy, etc.

1. **Create MySQL Database** via cPanel
2. **Import** `sql/schema.sql` via phpMyAdmin
3. **Upload files** to `public_html/student-tracker/`
4. **Update** `src/Config/Database.php` with server credentials
5. **Access**: `https://yourdomain.com/student-tracker/public/`

---

## 🛠️ Tech Stack

- **Backend**: PHP 8.0+ (MVC Architecture)
- **Database**: MySQL 5.7+
- **Frontend**: HTML, CSS (Glassmorphism UI), JavaScript
- **Icons**: Font Awesome 6

---

## 📸 Screenshots

### Dashboard (Role-Specific)
- Student: Personal projects and tasks
- Mentor: Student progress overview
- Admin: System statistics

### Project View
- Project details with assigned students
- Task list with status updates
- AI-powered suggestions

---

## 🤝 Contributing

1. Fork the repository
2. Create feature branch (`git checkout -b feature/amazing-feature`)
3. Commit changes (`git commit -m 'Add amazing feature'`)
4. Push to branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

---

## 📄 License

This project is licensed under the MIT License.

---

## 👨‍💻 Author

**Karthik Kumar**

---

*Built with ❤️ for managing student internships and projects efficiently.*
