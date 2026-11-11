# NOVA® — Creative Digital Agency

> **Tagline:** We Build Brands. We Create Experiences.  
> **PRD Implementation:** Complete Company Profile Website + Headless-ready CMS Admin Dashboard

---

## 🌟 Executive Overview

**NOVA®** is a modern, bold, editorial creative digital agency company profile website and custom Content Management System (CMS) built in accordance with the Product Requirements Document (`Nova.md`).

The platform provides a dual experience:
1. **Public Website:** Visually captivating, responsive, high-performance showcase presenting agency philosophy, service capabilities, interactive case studies, thought leadership essays, contact/project inquiries, and career vacancies.
2. **Admin Dashboard:** A secure, password-protected administrative back-office allowing full CRUD management over inquiries, portfolio projects, services, insights articles, testimonials, job positions, and site settings.

---

## 🎨 Visual Identity & Design System

Built strictly following Section 5 of the PRD:
- **Primary Black:** `#111111`
- **Dark Surface & Charcoal:** `#181818` / `#262626`
- **Accent Orange:** `#FF5A1F`
- **Muted Gray:** `#777777`
- **Off-White Surface & Text:** `#F5F3EF`
- **Typography:**
  - Headings: `Space Grotesk`
  - Body Copy: `Inter`
  - Metadata & Accents: `Space Mono`
- **Icons:** `Lucide Icons`
- **Responsive Breakpoints:** Fully fluid and tested across Mobile (320–767px), Tablet (768–1023px), Desktop (1024–1439px), and Large Desktop (1440px+).

---

## 🛠️ Technology Stack & Architecture

- **Frontend:** Semantic HTML5, CSS3, Tailwind CSS, Vanilla JavaScript (zero heavy framework bloat)
- **Backend:** PHP 8+ (Modular MVC-style organization, PDO abstraction layer)
- **Database:** Dual driver support (MySQL and zero-configuration SQLite auto-fallback)
  - `database.sql`: Production-ready MySQL schema for phpMyAdmin / MySQL CLI
  - `data/nova.sqlite`: Embedded SQLite database initialized out-of-the-box
- **Security:**
  - PDO Prepared Statements for 100% SQL injection prevention
  - CSRF Token verification across all forms
  - `htmlspecialchars()` output escaping
  - `password_hash()` (BCRYPT) and `password_verify()` authentication
  - Session regeneration on login to prevent fixation
  - Strict upload validation (MIME types, size limits, cryptographically safe filename generation)
  - Protected CV files accessible exclusively by authenticated administrators via `admin/download-cv.php`

---

## 🚀 Getting Started

### Option A: Quick Start with PHP Built-in Server (Recommended)

1. Open PowerShell or Terminal in the project root:
   ```powershell
   cd "f:\Nova Agency"
   ```

2. Start the built-in PHP development server:
   ```powershell
   php -S localhost:8000
   ```
   *(Or using XAMPP PHP: `& "C:\xampp\php\php.exe" -S localhost:8000`)*

3. Open your browser and navigate to:
   - **Public Website:** [http://localhost:8000/](http://localhost:8000/)
   - **Admin Console:** [http://localhost:8000/admin/login.php](http://localhost:8000/admin/login.php)

---

### Option B: Running with XAMPP Apache & MySQL

1. Copy or symlink the `Nova Agency` directory into your `C:\xampp\htdocs\` folder:
   ```powershell
   Copy-Item -Recurse "f:\Nova Agency" "C:\xampp\htdocs\nova"
   ```
2. Start **Apache** and **MySQL** in the XAMPP Control Panel.
3. Open **phpMyAdmin** (`http://localhost/phpmyadmin/`), create a database named `nova_agency`, and import `database.sql`.
4. In `config/config.php`, ensure `DB_DRIVER` is set to `'auto'` or `'mysql'`:
   ```php
   define('DB_DRIVER', 'mysql');
   define('DB_HOST', '127.0.0.1');
   define('DB_PORT', '3306');
   define('DB_NAME', 'nova_agency');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   ```
5. Navigate to: `http://localhost/nova/`

---

## 🔐 Default Admin Credentials

| Parameter | Value |
|---|---|
| **Login URL** | `/admin/login.php` |
| **Username** | `admin` |
| **Password** | `admin123` |
| **Role** | Superadmin |

*(Password can be updated anytime inside the Admin Dashboard under **Settings**)*

---

## 📁 Project Structure

```
f:\Nova Agency/
├── assets/
│   ├── css/
│   │   └── style.css            # Custom editorial theme styles & design tokens
│   ├── js/
│   │   └── main.js              # Navbar toggle, category filter, animations
│   └── images/                  # Vector logos, avatars, and case study visuals
├── config/
│   ├── config.php               # Environment, upload limits, path constants
│   ├── database.php             # PDO connection manager (MySQL + SQLite)
│   └── helpers.php              # Security, CSRF, flash messaging, formatting
├── data/
│   ├── nova.sqlite              # Embedded SQLite database
│   └── sessions/                # Secure session files
├── includes/
│   ├── header.php               # Semantic HTML5 header & navigation
│   ├── footer.php               # Global footer, links, and scripts
│   ├── cta-banner.php           # Reusable call-to-action banner
│   └── notifications.php        # Dismissible flash alerts
├── uploads/
│   ├── projects/                # Uploaded case study images
│   ├── articles/                # Uploaded article cover photos
│   └── cv/                      # Restricted applicant resumes (.htaccess protected)
├── admin/
│   ├── includes/
│   │   ├── auth.php             # Session guard
│   │   ├── header.php           # Sidebar navigation & admin topbar
│   │   └── footer.php           # Admin footer & modal scripts
│   ├── index.php                # Dashboard KPIs and recent inquiries
│   ├── login.php                # Admin authentication
│   ├── logout.php               # Secure session termination
│   ├── inquiries.php            # Prospect lead management & filter
│   ├── inquiry-detail.php       # Lead review, status updates, admin notes
│   ├── projects.php             # Portfolio listing & delete
│   ├── project-edit.php         # Portfolio case study creation/edit
│   ├── services.php             # Services listing
│   ├── service-edit.php         # Service editor & deliverables
│   ├── articles.php             # Insights / blog listing
│   ├── article-edit.php         # Article editor with HTML body support
│   ├── testimonials.php         # Client endorsements management
│   ├── jobs.php                 # Career openings listing
│   ├── job-edit.php             # Job vacancy post & requirements editor
│   ├── applications.php         # Candidate review pipeline
│   ├── download-cv.php          # Authorized secure CV downloader
│   └── settings.php             # Agency info, socials, SEO & password update
├── index.php                    # Public Homepage
├── about.php                    # Public About Us Page
├── services.php                 # Public Services Page
├── work.php                     # Public Portfolio with category filters
├── project-detail.php           # Public Case Study deep-dive page
├── insights.php                 # Public Articles page with search
├── article-detail.php           # Public Editorial Article page
├── contact.php                  # Public Contact & Project Inquiry Form
├── careers.php                  # Public Careers & Job Application Form
├── database.sql                 # MySQL schema & seed script
├── setup.php                    # Database initialization runner
├── test_runner.php              # Automated test suite
├── Nova.md                      # Original Product Requirements Document
└── README.md                    # Documentation
```

---

## 📋 Features Checklist (PRD Alignment)

- [x] **Home Page:** Hero headline, availability badge, agency metrics strip, about preview, core services cards, selected works showcase, why choose us pillars, client testimonials, and CTA banner.
- [x] **About Page:** Agency story, vision, mission, core values (Creativity, Innovation, Collaboration, Excellence), verified metrics, team profiles, and 4-stage collaboration process.
- [x] **Services Page:** Comprehensive breakdown of Brand Identity, UI/UX Design, Website Development, Graphic Design, and Digital Marketing with benefits and deliverables checklists.
- [x] **Work / Portfolio:** Dynamic project grid with real-time category filtering (Branding, UI/UX Design, Web Development, Graphic Design, Digital Campaign).
- [x] **Project Detail / Case Study:** Structured 7-part case study (Overview, Client, Objectives, Challenges, Process, Final Deliverables, Measurable Outcomes, and Related Projects).
- [x] **Insights / Blog:** Featured essay, topic filtering, keyword search, read-time calculation, editorial typography, and social share links.
- [x] **Contact & Inquiry System:** Interactive form with full validation, CSRF verification, budget and timeline selectors, saving inquiries with 'New' status.
- [x] **Careers & Hiring:** Culture benefits, open position specs, and candidate application form with CV file upload.
- [x] **Admin Dashboard:** Complete CRUD suite for Inquiries, Projects, Services, Articles, Testimonials, Jobs, Applications, and Website Settings.
- [x] **Security & Permissions:** Authenticated admin routes, CSRF tokens, output escaping, and restricted CV download authorization.

