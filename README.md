# Student-Teacher Management System

![System Dashboard](https://via.placeholder.com/800x400?text=School+Management+Dashboard)

A comprehensive Arabic-language school management system built with PHP and MySQL that handles student/teacher administration, grade tracking, and class scheduling with RTL interface support.

## Features

### User Management
- 🛡️ Multi-role system (Admin, Teacher, Student, Parent)
- 🔐 Secure authentication with password hashing
- 🎨 Theme customization (Dark/Light modes)

![User Management](https://via.placeholder.com/800x400?text=User+Management+Interface)

### Academic Features
- 📊 Grade tracking and reporting
- 🕒 Interactive timetable management
- 📚 Subject and class organization

![Grade Management](https://via.placeholder.com/800x400?text=Grade+Management+View)

## Technology Stack

**Frontend**:
- HTML5, CSS3, JavaScript (RTL support)
- Font Awesome icons
- Tajawal Arabic font

**Backend**:
- PHP 7.4+
- PDO for database access
- MySQL relational database

**Security**:
- Prepared statements
- Input validation/sanitization
- Password hashing (bcrypt)

## Screenshots

| Dark Theme | Light Theme | Mobile View |
|------------|-------------|-------------|
| ![Dark Theme](https://via.placeholder.com/300x200?text=Dark+Theme) | ![Light Theme](https://via.placeholder.com/300x200?text=Light+Theme) | ![Mobile](https://via.placeholder.com/300x200?text=Mobile+View) |

## Database Schema

```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','teacher','student','parent') DEFAULT 'student',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```





## installation
```bash
git clone https://github.com/mohamedboukerche22/mohamedboudiaf2.git
cd mohamedboudiaf2
```
Admin: admin / admin123
