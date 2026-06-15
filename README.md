# Benguet State University Gender and Development Activities Management System (BSU GAD-AMS)

BSU GAD-AMS is a web-based activity submission, monitoring, and accomplishment reporting system specifically tailored for the Gender and Development (GAD) Office of Benguet State University. It automates the review and approval processes for activity designs and accomplishment reports, enabling structured budget utilization tracking, mandate mapping, and annual reporting.

---

## 🏗️ System Architecture & Components

The system is split into three main components:

1. **Frontend (`/frontend`)**
   * Built with **Vue 3 + Vite** for a modern, responsive, and performant user interface.
   * Styled using **Tailwind CSS v4** for clean, modern layouts and utility-first designs.
   * Uses **Axios** for API requests to the backend server.
   * Utilizes **Vue Router** for frontend page routing and client-side access control.

2. **Backend (`/backend`)**
   * Powered by **CodeIgniter 4** (PHP REST API).
   * Exposes RESTful endpoints under the `/api` prefix for operations such as authentication, submission processing, and PDF serving.
   * Implements a centralized **FileStorage Library** (supporting local uploads under `writable/uploads/` during local development or Cloudflare R2 bucket storage when deployed).

3. **Database (`/gad_submission_system.sql`)**
   * A pre-populated MySQL database dump containing tables for users, roles, departments, activity designs, accomplishment reports, budget logs, and GAD mandates.
   * Includes custom MySQL triggers to automatically sync budget breakdowns and department assignments.

---

## 📋 System Requirements

To run the system locally, ensure you have the following installed on your machine:

* **PHP 8.2 or higher** with the following extensions enabled in `php.ini`:
  * `intl`
  * `mbstring`
  * `json` (enabled by default)
  * `mysqli` / `mysqlnd`
  * `curl` / `libcurl`
* **Composer** (PHP Package Manager)
* **Node.js (v18+)** and **npm**
* **MySQL** or **MariaDB** database server (e.g., via XAMPP, Laragon, or standalone installs)

---

## 🚀 Local Quickstart Guide (GitHub Codespaces / Host)

The recommended setup is running services directly on the host machine (such as GitHub Codespaces in Visual Studio Code or Antigravity) without Docker. This provides high performance, lower resource utilization, and faster load times.

### Option A: Codespace & Local Host Setup (Docker-Free)

Follow these steps to configure your local host or Codespace:

#### 1. System Requirements & Preparation
Ensure PHP 8.2+ and Node.js are available. If they are not installed, install them using your package manager.
Also ensure MySQL server and the PHP MySQL driver are installed on your host:
```bash
sudo apt-get update
sudo apt-get install -y mysql-server php8.2-mysql
```

#### 2. Start & Initialize the Database
1. Start the MySQL service:
   ```bash
   sudo service mysql start
   ```
2. Configure permissions for the root user and create the database:
   ```bash
   sudo mysql -e "CREATE DATABASE IF NOT EXISTS gad_submission_system;"
   sudo mysql -e "ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY '';"
   sudo mysql -e "CREATE USER IF NOT EXISTS 'root'@'127.0.0.1' IDENTIFIED WITH mysql_native_password BY '';"
   sudo mysql -e "GRANT ALL PRIVILEGES ON *.* TO 'root'@'127.0.0.1' WITH GRANT OPTION;"
   sudo mysql -e "FLUSH PRIVILEGES;"
   ```
3. Import the preloaded database dump:
   ```bash
   mysql -h 127.0.0.1 -u root gad_submission_system < gad_submission_system.sql
   ```

#### 3. Backend Setup (`/backend`)
1. Navigate to the backend folder and install PHP dependencies:
   ```bash
   cd backend
   composer install
   ```
2. Prepare the environment configuration:
   ```bash
   cp .env.example .env
   ```
3. In `backend/.env`, set the database hostname to `127.0.0.1`:
   ```env
   database.default.hostname = 127.0.0.1
   ```
4. Start the CodeIgniter development server:
   ```bash
   php spark serve
   ```
   The backend will listen on `http://localhost:8080`.

#### 4. Frontend Setup (`/frontend`)
1. Open a new terminal tab, navigate to the frontend folder, and install packages:
   ```bash
   cd frontend
   npm install
   ```
2. Prepare the environment configuration:
   ```bash
   cp .env.example .env
   ```
3. In `frontend/.env`, ensure the base URL points to your running backend:
   ```env
   VITE_API_BASE_URL=http://localhost:8080/api/
   ```
4. Start the Vite development server:
   ```bash
   npm run dev
   ```
   Open `http://localhost:5173` to access the application dashboard.

---

### Option B: Run with Docker Compose

If you still prefer running inside containers, you can use the provided Docker Compose configuration:

1. **Prepare Environment Files**:
   * Copy the frontend environment file:
     ```bash
     cp frontend/.env.example frontend/.env
     ```
   * The backend `.env` is configured automatically inside `docker-compose.yml`.
2. **Start the Infrastructure**:
   From the root of the project, run:
   ```bash
   docker compose up --build -d
   ```
3. **Start Frontend Dev Server**:
   Navigate to the frontend folder, install packages, and launch Vite:
   ```bash
   cd frontend
   npm install
   npm run dev
   ```

---

## 🗄️ Managing MySQL inside GitHub Codespaces

Here is how you can manage the local MySQL server running in your Codespace using Visual Studio Code or Antigravity.

### 1. Connecting via Terminal (CLI)
You can interact with your database directly from any Codespace terminal tab.

* **Open the MySQL Client**:
  ```bash
  mysql -h 127.0.0.1 -u root
  ```
  *(or `sudo mysql` if you want root socket privileges)*

* **Useful CLI Commands**:
  ```sql
  -- Show all databases
  SHOW DATABASES;

  -- Select the GAD-AMS database
  USE gad_submission_system;

  -- Show all tables
  SHOW TABLES;

  -- Query department or user records
  SELECT * FROM users LIMIT 5;
  ```

### 2. Connecting via GUI (Visual Database Managers)
Rather than using the CLI, you can manage the database visually within VS Code/Antigravity using extensions:

1. **Install Database Extension**:
   Search for and install the **Database Client** (by Weijan Chen) or **MySQL** extension in the Extension marketplace.
2. **Add a Connection**:
   Click the database icon in the sidebar and choose "Add Connection".
3. **Enter Connection Details**:
   * **Host**: `127.0.0.1`
   * **Port**: `3306`
   * **User**: `root`
   * **Password**: *(Leave empty)*
   * **Database**: `gad_submission_system` (Optional, to auto-focus)
4. Now you can click through tables, run SQL queries, and edit records in a convenient UI.

---

## 👥 User Roles & Access Control

Once the system is running, you can log in or register accounts for different workflows:

1. **Admin (GAD Office)**
   * **Role value in DB:** `admin`
   * **Key Tasks:** View the complete admin dashboard, review submitted activity designs and accomplishment reports, approve or request revisions, manage GAD plan/budgets, map mandates, and generate annual Excel reports.
   * **Seeded Account:** `gad.office@bsu.edu.ph` / Username: `Gender And Development`

2. **GAD Staff**
   * **Role value in DB:** `gad_staff`
   * **Key Tasks:** Monitor submitted documents, view mandates progress, monitor budget balances, and generate annual reports. (Read-only view for uploaded files).
   * **Seeded Account:** `gad.staff@bsu.edu.ph` / Username: `gad.staff`

3. **Colleges / Departments**
   * **Role value in DB:** `college`
   * **Key Tasks:** Create and submit new Activity Designs, upload drafts, track submission status, and submit Accomplishment Reports once activity designs are approved.
   * **Seeded Account:** `ca@bsu.edu.ph` / Username: `College of Agriculture`

> 💡 **Tip:** If you need to access seeded accounts or register a new custom role, you can use the **Register** link on the login screen. Alternatively, you can reset database passwords locally using PHP's `password_hash()` or standard MySQL queries if needed.

---

## 📂 Project Directory Structure

```text
GAD_AMS/
├── backend/                  # CodeIgniter 4 REST API
│   ├── app/
│   │   ├── Config/           # Application Configuration (Routes, Database, CORS, etc.)
│   │   ├── Controllers/      # API Endpoint Controllers (Auth, Activity Designs, Reports)
│   │   ├── Libraries/        # Custom helpers (FileStorage)
│   │   └── Models/           # Database Models
│   ├── public/               # Public entry point (index.php)
│   ├── writable/             # Temporary files, logs, and local file uploads
│   ├── Dockerfile            # Container deployment configuration
│   └── spark                 # CodeIgniter CLI tool
│
├── frontend/                 # Vue 3 + Vite Application
│   ├── src/
│   │   ├── components/       # Reusable UI components
│   │   ├── views/            # Screen views (Dashboards, Submissions, User Manuals)
│   │   ├── router/           # Client-side router configuration
│   │   ├── api.js            # Central Axios API instance config
│   │   └── App.vue           # Root component
│   ├── tailwind.config.js    # Tailwind configuration
│   └── package.json          # Node dependencies and scripts
│
└── gad_submission_system.sql # Database schema and seed data
```
