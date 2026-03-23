# 🏋️ GymPro Fitness — Gym Management System

A modern, full-stack **Gym Management Website** built with PHP, MySQL, Bootstrap 5, and Chart.js. Features a premium dark-themed UI with smooth animations, user/admin dashboards, membership management, payment integration, and analytics.

![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.x-4479A1?logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?logo=bootstrap&logoColor=white)
![Chart.js](https://img.shields.io/badge/Chart.js-4.x-FF6384?logo=chartdotjs&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green)

---

## ✨ Features

### 🏠 Public Pages
- **Home** — Hero section, animated counters, services preview, pricing, BMI calculator
- **About** — Company story, mission/vision, stats
- **Services** — Dynamic service cards from database
- **Plans** — Membership pricing cards with feature comparison & FAQ
- **Trainers** — Trainer profiles with ratings & social links
- **Contact** — Contact form with validation + embedded Google Maps

### 👤 User Dashboard
- ✅ Secure Registration & Login (bcrypt)
- 📊 Dashboard with workout stats, BMI gauge, attendance tracking
- 💳 Membership subscription with payment (Razorpay test mode)
- 🏃 Workout logging & history
- 📅 Trainer booking system
- 🔔 Notification system
- 👤 Profile management with body measurements

### 🔐 Admin Panel
- 📈 Analytics dashboard with **Chart.js** (revenue, users, subscriptions)
- 👥 User management (activate/deactivate/delete)
- 💰 Plan CRUD (create, edit, delete membership plans)
- 💳 Payment transaction history
- 📬 Contact message management

### 🔒 Security
- Password hashing with **bcrypt**
- **PDO prepared statements** (SQL injection prevention)
- **XSS prevention** with output escaping
- Server-side form validation
- Session-based authentication
- `.htaccess` security headers

---

## 📁 Project Structure

```
GymPro/
├── index.php                  # Home page
├── about.php                  # About Us
├── services.php               # Services listing
├── plans.php                  # Membership plans & pricing
├── trainers.php               # Trainer profiles
├── contact.php                # Contact form + map
├── login.php                  # User login
├── register.php               # User registration
├── logout.php                 # Session destroy
├── .htaccess                  # Apache security config
│
├── admin/                     # Admin Panel
│   ├── dashboard.php          #   Analytics (Chart.js)
│   ├── users.php              #   Manage users
│   ├── plans.php              #   CRUD plans
│   ├── payments.php           #   View payments
│   └── messages.php           #   Contact messages
│
├── user/                      # User Dashboard
│   ├── dashboard.php          #   Main dashboard
│   ├── profile.php            #   Edit profile
│   ├── subscribe.php          #   Subscribe + pay
│   ├── workouts.php           #   Workout log
│   └── book-trainer.php       #   Book trainer
│
├── includes/                  # Core PHP
│   ├── config.php             #   Configuration
│   ├── db.php                 #   PDO connection
│   ├── functions.php          #   Helper functions
│   ├── header.php             #   Shared header
│   └── footer.php             #   Shared footer
│
├── assets/
│   ├── css/style.css          #   Complete design system
│   └── js/main.js             #   UI interactions
│
└── database/
    └── gym_db.sql             #   Database schema + seed data
```

---

## 🚀 Installation & Setup

### Prerequisites
- [XAMPP](https://www.apachefriends.org/) (includes Apache, MySQL, PHP)

### Step 1: Clone the Repository

```bash
git clone https://github.com/karanvishwakarma1209/GymPro.git
```

### Step 2: Move to XAMPP

Copy the cloned folder to your XAMPP htdocs directory:

```
C:\xampp\htdocs\GymPro\
```

> **Or** clone directly into htdocs:
> ```bash
> cd C:\xampp\htdocs
> git clone https://github.com/karanvishwakarma1209/GymPro.git
> ```

### Step 3: Start XAMPP

1. Open **XAMPP Control Panel**
2. Click **Start** next to **Apache** → should turn green ✅
3. Click **Start** next to **MySQL** → should turn green ✅

### Step 4: Create & Import Database

**Option A — Using phpMyAdmin (Recommended):**
1. Open your browser → go to `http://localhost/phpmyadmin`
2. Click **"New"** in the left sidebar
3. Enter database name: `gym_db` → click **"Create"**
4. Click the **"Import"** tab
5. Click **"Choose File"** → select `database/gym_db.sql` from the project
6. Click **"Go"** → you should see a success message ✅

**Option B — Using Command Line:**
```bash
cd C:\xampp\mysql\bin
mysql -u root -e "CREATE DATABASE gym_db;"
mysql -u root gym_db < C:\xampp\htdocs\GymPro\database\gym_db.sql
```

### Step 5: Configure the Project

Open `includes/config.php` and update if needed:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'gym_db');
define('DB_USER', 'root');        // default XAMPP user
define('DB_PASS', '');            // default XAMPP password (empty)
define('SITE_URL', 'http://localhost/GymPro-Fitness');  // ← match your folder name
```

> ⚠️ **Important:** The `SITE_URL` must match the exact folder name inside `htdocs`.

### Step 6: Open in Browser 🎉

```
http://localhost/GymPro/
```

---

## 🔑 Demo Login Credentials

| Role  | Email              | Password  |
|-------|--------------------|-----------|
| Admin | admin@gympro.com   | password  |
| User  | john@example.com   | User@123  |

---

## 🛠️ Tech Stack

| Technology | Purpose |
|-----------|---------|
| **PHP 8.x** | Backend logic, authentication, CRUD |
| **MySQL** | Database (PDO with prepared statements) |
| **Bootstrap 5** | Responsive UI framework |
| **Chart.js** | Admin analytics charts |
| **Font Awesome 6** | Icons |
| **AOS.js** | Scroll animations |
| **Google Fonts** | Typography (Inter + Outfit) |

---


## 💳 Payment Integration

The project uses **Razorpay Test Mode** for payments. Currently, it simulates a successful payment flow. To enable real Razorpay:

1. Create an account at [razorpay.com](https://razorpay.com)
2. Get your Test API Keys from Dashboard → Settings → API Keys
3. Update `includes/config.php`:
   ```php
   define('RAZORPAY_KEY_ID', 'rzp_test_YOUR_KEY');
   define('RAZORPAY_KEY_SECRET', 'YOUR_SECRET');
   ```

---

## 🗄️ Database Schema

| Table | Description |
|-------|-------------|
| `users` | All user accounts |
| `membership_plans` | Subscription plans |
| `user_memberships` | User-plan subscriptions |
| `payments` | Payment transactions |
| `attendance` | Check-in/check-out records |
| `trainers` | Trainer profiles |
| `trainer_bookings` | Session bookings |
| `workout_logs` | Workout history |
| `services` | Gym services |
| `contact_messages` | Contact form messages |
| `notifications` | User notifications |
| `site_settings` | Site configuration |

---

## ❓ Troubleshooting

| Problem | Solution |
|---------|----------|
| Blank white page | Check DB credentials in `includes/config.php` |
| CSS not loading | Ensure `SITE_URL` matches your folder name exactly |
| Database connection error | Make sure MySQL is running in XAMPP |
| Login not working | Re-import `gym_db.sql` to reset demo data |
| Apache won't start | Close Skype/IIS (port 80 conflict) or change Apache port |

---

## 📄 License

This project is open source and available under the [MIT License](LICENSE).

---

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

---

**⭐ If you found this project helpful, give it a star!**
