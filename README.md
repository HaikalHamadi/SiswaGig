# 💼 SiswaGIG — Student Freelancer Platform

A full-stack web application that connects university students with freelance job opportunities. Students can register as **freelancers** or **clients**, post and apply for jobs, manage payments, and rate completed work.

## ✨ Features

### 👤 Authentication & Roles
- User registration and login system
- Role selection (Freelancer / Client)
- Session-based authentication
- Admin panel with separate access control

### 💼 Job Management
- Clients can post jobs with multi-step form (title → details → requirements & payment)
- Freelancers can browse and apply for available jobs
- Job status tracking: Open → In Progress → Completed
- Auto-deletion of expired open jobs

### 💰 Payment System
- Support for one-time and per-hour payment types
- Payment processing and tracking
- Payment history for clients and freelancers

### 🛡️ Admin Dashboard
- Dashboard with real-time statistics (users, jobs, revenue)
- Manage users (view, delete)
- Manage jobs (view, edit, delete)
- View all payment records

### 👨‍💻 Freelancer Features
- Skills management
- Hourly rate setting
- Job application and tracking
- Profile management

## 🗄️ Database Schema

| Table | Description |
|-------|-------------|
| `users` | User accounts with roles (freelancer/client/admin) |
| `jobs` | Job listings with status tracking |
| `applications` | Freelancer job applications |
| `payments` | Payment records between clients and freelancers |

## 🛠️ Tech Stack
- **Backend:** PHP 8.2
- **Database:** MySQL (MariaDB 10.4)
- **Frontend:** HTML5, CSS3, JavaScript
- **Server:** Apache (XAMPP)

## 🚀 How to Run
1. Install [XAMPP](https://www.apachefriends.org/)
2. Clone this repo into `htdocs/siswagig/`
3. Start Apache and MySQL from XAMPP Control Panel
4. Import `siswagig.sql` into phpMyAdmin
5. Open `http://localhost/siswagig/LoginGP.php`

## Screenshots
<!-- Take screenshots of your app and add them here! -->
