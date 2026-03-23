# 🏢 Events Manager

A company internal event management system built with **Laravel 12** and **Filament 3**.
Manage events, registrations, QR code check-ins and attendance tracking — all in one place.

---

## ✨ Features

### Admin Panel (`/admin`)
- Create, edit and delete company events
- View all registrations per event
- QR code viewer for each registration
- Camera-based QR code scanner for check-in (webcam + phone)
- Dashboard with stats: total events, registrations, attendances
- Upcoming events and past events with attendance overview
- User management (create admins and employees)

### Employee Panel (`/employee`)
- Browse upcoming events
- View full event details
- Register and unregister for events
- Personal QR code for check-in
- Dashboard with personal stats: registrations, attended, upcoming

### General
- Single login page at `/login` with role-based redirect
- Admins → `/admin`, Employees → `/employee`
- Logout always redirects to `/login`
- Event status badges: Upcoming, Ongoing, Past
- Capacity tracking with Full/Available badges

---

## 🛠 Tech Stack

| Layer | Technology |
|-------|-----------|
| Framework | Laravel 12 |
| Admin UI | Filament 3 |
| Database | MySQL |
| QR Generation | simplesoftwareio/simple-qrcode |
| QR Scanning | html5-qrcode |
| PHP | 8.2 |

---

## 🗄 Database Structure

| Table | Description |
|-------|-------------|
| `users` | Admins and employees (role: admin/employee) |
| `events` | Company events with capacity and dates |
| `registrations` | Employee registrations with unique QR code |
| `attendances` | Check-in records with timestamp |

---

## 🚀 Installation

### Requirements
- PHP 8.2+
- Composer
- MySQL
- Node.js & npm

### Steps

**1 — Clone the repository**
```bash
git clone https://github.com/vladimirhristovski/EventsApp.git
cd EventsApp
```

**2 — Install PHP dependencies**
```bash
composer install
```

**3 — Set up environment**
```bash
cp .env.example .env
php artisan key:generate
```

**4 — Configure database**

Open `.env` and update:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=events_app
DB_USERNAME=root
DB_PASSWORD=
```

**5 — Run migrations**
```bash
php artisan migrate
```

**6 — Compile assets**
```bash
npm install
npm run build

# Compile admin theme
npx tailwindcss@3 --input ./resources/css/filament/admin/theme.css --output ./public/css/filament/admin/theme.css --config ./resources/css/filament/admin/tailwind.config.js --minify

# Compile employee theme
npx tailwindcss@3 --input ./resources/css/filament/employee/theme.css --output ./public/css/filament/employee/theme.css --config ./resources/css/filament/employee/tailwind.config.js --minify
```

**7 — Create admin user**
```bash
php artisan make:filament-user
```
When prompted, make sure to set the role to `admin` via tinker afterwards:
```bash
php artisan tinker
App\Models\User::where('email', 'your@email.com')->update(['role' => 'admin']);
```

**8 — Start the server**
```bash
php artisan serve
```

Visit `http://127.0.0.1:8000` 🎉

---

## 👤 User Roles

| Role | Access | Panel |
|------|--------|-------|
| `admin` | Full access — manage events, users, check-ins | `/admin` |
| `employee` | Browse events, register, view QR code | `/employee` |

---

## 📱 QR Code Check-in Flow

1. Admin creates an event
2. Employee registers for the event → unique QR code is generated
3. Employee opens **My Registrations** → clicks **View QR**
4. Admin opens **Check-in** page → scans QR with camera or types manually
5. Attendance is recorded with timestamp

---

## 🔗 Routes

| URL | Description |
|-----|-------------|
| `/` | Redirects to `/login` |
| `/login` | Single login page for all users |
| `/logout` | Logs out and redirects to `/login` |
| `/admin` | Admin panel dashboard |
| `/employee` | Employee panel dashboard |

---

## 📁 Project Structure

```
app/
├── Filament/
│   ├── Resources/          # Admin resources (Event, User, Registration)
│   ├── Widgets/            # Admin dashboard widgets
│   ├── Pages/              # Admin pages (CheckIn, Dashboard)
│   └── Employee/
│       ├── Resources/      # Employee resources (Event, Registration)
│       ├── Widgets/        # Employee dashboard widgets
│       └── Pages/          # Employee pages
├── Http/
│   ├── Controllers/Auth/   # Custom login controller
│   └── Responses/          # Custom logout response
├── Models/                 # User, Event, Registration, Attendance
└── Providers/Filament/     # AdminPanelProvider, EmployeePanelProvider
```

---

## 🧑‍💻 Development

To recompile Filament themes after CSS changes:

```bash
# Admin theme
npx tailwindcss@3 --input ./resources/css/filament/admin/theme.css --output ./public/css/filament/admin/theme.css --config ./resources/css/filament/admin/tailwind.config.js --minify

# Employee theme
npx tailwindcss@3 --input ./resources/css/filament/employee/theme.css --output ./public/css/filament/employee/theme.css --config ./resources/css/filament/employee/tailwind.config.js --minify
```

---

## 📄 License

This project is for educational purposes.
