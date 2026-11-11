# NOVA® — Creative Digital Agency
## Product Requirements Document (PRD)

**Tagline:** We Build Brands. We Create Experiences.

| Document information | Details |
|---|---|
| Product name | NOVA Creative Digital Agency |
| Document type | Product Requirements Document (PRD) |
| Product type | Company Profile Website |
| Platform | Responsive website (desktop, tablet, mobile) |
| Primary audience | Startup, UMKM, corporate, entrepreneurs, personal brands |
| Primary goal | Brand awareness, portfolio showcase, lead generation |
| Primary language | English (dapat dikembangkan menjadi bilingual) |
| Recommended stack | HTML, CSS, JavaScript, PHP, MySQL, Tailwind CSS |
| Document version | 1.0 |
| Date | 11 November 2026 |

---

## Executive Summary

NOVA adalah creative digital agency yang membantu bisnis membangun identitas brand, pengalaman digital, dan strategi kreatif melalui desain serta teknologi. Website NOVA berfungsi sebagai representasi digital perusahaan, pusat informasi layanan, showcase portfolio, dan kanal untuk menerima project inquiry dari calon klien.

Produk yang direncanakan adalah website company profile responsif dengan halaman publik dan admin dashboard. Pengunjung dapat mengenal perusahaan, menjelajahi layanan dan studi kasus, membaca insight, serta mengirim permintaan proyek. Admin dapat mengelola konten website dan memantau inquiry melalui dashboard.

## 1. Product Overview

### 1.1 Background

Bisnis membutuhkan identitas visual dan kehadiran digital yang kuat untuk membangun kepercayaan serta meningkatkan daya saing. Website company profile NOVA akan menyatukan informasi perusahaan, layanan kreatif, bukti hasil pekerjaan, dan jalur komunikasi dengan calon klien.

### 1.2 Product Objectives

1. Membangun citra perusahaan yang profesional, konsisten, dan terpercaya.
2. Menjelaskan layanan serta keahlian agency dengan mudah dipahami.
3. Menampilkan portfolio dan case study sebagai bukti kualitas pekerjaan.
4. Meningkatkan kepercayaan melalui profil tim, nilai perusahaan, dan testimonial.
5. Mempermudah calon klien menghubungi agency dan mengajukan project inquiry.
6. Menyediakan sistem pengelolaan konten agar informasi website dapat diperbarui oleh admin.

### 1.3 Success Metrics (Initial Targets)

| Metric | Initial target | Measurement |
|---|---:|---|
| Page load time | Under 3 seconds | Performance testing on representative pages |
| Responsive coverage | All supported breakpoints | Manual device and viewport testing |
| Inquiry conversion | At least 5% of visitors | Inquiries divided by unique visitors |
| Portfolio engagement | At least 40% of visitors | Visitors opening a project detail |
| Lighthouse performance | 90 or higher target | Lighthouse audit; optimize as needed |

> Catatan: seluruh angka merupakan target awal proyek, bukan hasil pengukuran aktual. Target perlu dievaluasi setelah website diluncurkan dan data analitik tersedia.

## 2. Target Audience & User Problems

### 2.1 Target Audience

| Audience | Primary needs |
|---|---|
| Startup founders | Brand identity, product design, and professional website |
| Small business owners | Digital presence, marketing materials, and business growth |
| Corporate companies | Creative solutions, campaign support, and partnerships |
| Entrepreneurs | Product identity, UI/UX, and website development |
| Personal brands | Visual identity, portfolio, and online presence |

### 2.2 User Pain Points

- Sulit menemukan agency dengan layanan kreatif dan digital yang saling terintegrasi.
- Calon klien tidak dapat menilai kualitas agency tanpa portfolio dan studi kasus yang jelas.
- Informasi mengenai proses kerja, deliverables, dan cakupan layanan sering tidak transparan.
- Jalur untuk menghubungi agency atau meminta estimasi proyek kurang praktis.

## 3. Product Scope & Sitemap

### 3.1 Public Website Pages

| Page | Purpose | Key content |
|---|---|---|
| Home | Introduce NOVA and guide visitors | Hero, about preview, services, selected work, testimonials, CTA |
| About | Build trust and explain the company | Company story, vision, mission, values, team, statistics |
| Services | Explain available services | Service descriptions, benefits, deliverables, related work, CTA |
| Work | Showcase completed projects | Project grid, categories, filters, project detail pages |
| Insights | Share knowledge and improve credibility | Featured article, article list, categories, search, article detail |
| Contact | Convert visitors into leads | Contact details, inquiry form, social links, location |
| Careers | Attract potential team members | Company culture, vacancies, job details, application form |

### 3.2 Navigation

Main navigation: **Home, About, Services, Work, Insights, Contact**.

Primary call-to-action: **LET'S TALK**.

Careers dapat ditempatkan pada footer atau bagian About.

### 3.3 Sitemap Overview

- Home
- About
- Services
  - Service detail (optional)
- Work
  - Project case study
- Insights
  - Article detail
- Contact
  - Project inquiry form
- Careers
  - Job detail
  - Application form
- Admin
  - Login
  - Dashboard
  - Content management

## 4. Functional Requirements

### 4.1 Home Page

**Tujuan:** memperkenalkan NOVA secara singkat, membangun kesan pertama yang kuat, dan mengarahkan pengunjung menuju layanan, portfolio, atau contact.

| Section | Requirements | Priority |
|---|---|---|
| Hero | Headline, supporting text, primary CTA, secondary CTA, visual/animation | Must |
| About preview | Short company introduction and link to About | Must |
| Services | Summary cards for core services | Must |
| Selected works | Featured project cards linking to case studies | Must |
| Why choose us | Agency differentiators and working approach | Should |
| Testimonials | Client feedback and attribution | Should |
| CTA banner | Clear invitation to start a project | Must |
| Footer | Navigation, contact details, social links, copyright | Must |

**Suggested hero copy:**

- Headline: “We Create Digital Experiences That Matter.”
- Supporting text: “We combine creativity, strategy, and technology to help ambitious brands stand out in the digital world.”
- Primary CTA: “START A PROJECT”
- Secondary CTA: “EXPLORE OUR WORK”

### 4.2 About Page

- Company introduction and story.
- Vision and mission.
- Core values:
  - Creativity — Thinking beyond boundaries.
  - Innovation — Embracing new possibilities.
  - Collaboration — Creating better things together.
  - Excellence — Delivering quality in every detail.
- Team member profiles with role and short biography.
- Company statistics, such as projects completed or years of experience. Gunakan hanya angka yang sudah diverifikasi.
- Working philosophy and collaboration process.

### 4.3 Services Page

| Service | Description / deliverables |
|---|---|
| Brand Identity | Logo design, visual identity system, brand guidelines |
| UI/UX Design | User research, information architecture, wireframes, prototypes, interface design |
| Website Development | Landing pages, company profiles, and web applications |
| Graphic Design | Social media assets, campaigns, presentations, and marketing materials |
| Digital Marketing | Digital campaign planning, content strategy, and SEO support |

Setiap layanan harus memiliki judul, deskripsi, manfaat, daftar deliverables, project terkait (jika tersedia), dan CTA konsultasi.

### 4.4 Work / Portfolio Page

- Grid portfolio dengan thumbnail, judul proyek, klien, kategori, dan tahun.
- Filter kategori:
  - Branding
  - UI/UX Design
  - Web Development
  - Graphic Design
  - Digital Campaign
- Project detail / case study page.
- Case study berisi:
  1. Project overview
  2. Client
  3. Objectives
  4. Challenges
  5. Creative process
  6. Final output
  7. Outcomes
- Admin dapat menambah, mengubah, menghapus, dan mengatur status publikasi project.

### 4.5 Insights Page

- Featured article dan daftar artikel.
- Kategori: Branding, Design Trends, UI/UX, Technology, Digital Marketing.
- Search dan category filter.
- Article detail dengan judul, cover image, author, tanggal, isi, dan related articles.
- Admin dapat mengelola artikel dan status publikasinya.

### 4.6 Contact Page & Project Inquiry

Contact page menyediakan email, telepon/WhatsApp, alamat kantor (jika ada), social media, jam operasional, dan project inquiry form.

| Field | Input type | Required |
|---|---|---|
| Full name | Text | Yes |
| Email address | Email | Yes |
| Company name | Text | No |
| Service needed | Dropdown | Yes |
| Estimated budget | Dropdown | No |
| Project timeline | Dropdown | No |
| Project description | Textarea | Yes |

**Form behavior:**

- Validasi seluruh field wajib di frontend dan backend.
- Simpan inquiry ke database dengan timestamp dan status awal, misalnya `New`.
- Tampilkan pesan sukses setelah data tersimpan.
- Tampilkan pesan error yang jelas jika pengiriman gagal.
- Admin dapat melihat detail inquiry dan memperbarui status tindak lanjut.

### 4.7 Careers Page

- Company culture dan informasi bekerja di NOVA.
- Daftar posisi yang tersedia dan detail lowongan.
- Persyaratan, tanggung jawab, dan lokasi/jenis kerja.
- Application form dengan nama, email, posisi, dan CV.
- Validasi tipe dan ukuran file CV serta penyimpanan file yang aman.

## 5. UI/UX Design Requirements

### 5.1 Design Direction

Arah visual: **modern, minimalist, bold, dan editorial**. Website perlu memiliki hierarki visual yang kuat, whitespace yang cukup, layout grid konsisten, dan visual portfolio yang dominan.

### 5.2 Brand Identity

| Token | Value / recommendation | Usage |
|---|---|---|
| Primary black | `#111111` | Background, text, high-contrast areas |
| Off-white | `#F5F3EF` | Main light surface and text contrast |
| Accent orange | `#FF5A1F` | CTA, highlights, active states |
| Gray | `#777777` | Secondary text |
| Charcoal | `#262626` | Cards and dark surfaces |
| Heading font | Space Grotesk | Headings and display typography |
| Body font | Inter | Paragraphs and interface text |
| Accent font | Space Mono | Labels, metadata, small technical details |

### 5.3 UI Components

- Responsive navigation bar and mobile hamburger menu.
- Primary and secondary buttons with hover/focus states.
- Service cards and portfolio cards.
- Testimonial slider (optional).
- Contact and application forms with validation states.
- Footer navigation and social links.
- Scroll progress indicator (optional).
- Custom cursor for desktop only (optional).
- Smooth scrolling and restrained page transitions.

### 5.4 Responsive Breakpoints

| Device | Viewport width |
|---|---:|
| Mobile | 320–767 px |
| Tablet | 768–1023 px |
| Desktop | 1024–1439 px |
| Large desktop | 1440 px and above |

Layout, typography, images, navigation, forms, dan interactive components harus tetap usable pada seluruh ukuran viewport yang didukung. Hindari layout yang hanya mengandalkan fixed width.

## 6. Technical Requirements

### 6.1 Recommended Technology Stack

| Layer | Technology | Notes |
|---|---|---|
| Frontend | HTML5, CSS3, JavaScript | Semantic markup and responsive interactions |
| CSS framework | Tailwind CSS (optional) | Utility-first styling |
| Backend | PHP | Form processing, authentication, CRUD |
| Database | MySQL | Store content and inquiries |
| Database management | phpMyAdmin | Development and administration |
| Animation | CSS Animation / GSAP | Use selectively; respect reduced-motion preferences |
| Icons | Lucide Icons | Consistent icon system |
| Hosting | Shared hosting or VPS | HTTPS-enabled production environment |

### 6.2 Simplified Architecture

**Visitor browser → Frontend (HTML/CSS/JavaScript) → PHP backend → MySQL database.**

Admin menggunakan aplikasi yang sama melalui route terautentikasi untuk mengelola konten. Uploaded assets harus disimpan di direktori terkontrol dengan validasi file yang aman.

### 6.3 Database Tables

| Table | Purpose / key data |
|---|---|
| `admins` | Admin accounts, password hash, role, timestamps |
| `services` | Service title, description, deliverables, display order, status |
| `projects` | Project title, client, category, summary, case study, year, status |
| `project_images` | Project image paths, alt text, display order |
| `testimonials` | Client name, role/company, quote, status |
| `articles` | Title, slug, category, author, body, cover, publish date, status |
| `categories` | Reusable project and article categories |
| `inquiries` | Contact details, requested service, budget, timeline, description, status |
| `job_positions` | Job title, description, requirements, status |
| `applications` | Applicant details, position, CV path, submission date, status |
| `site_settings` | Company contact, social links, SEO defaults, general settings |

Database implementation should include primary keys, appropriate foreign keys, indexes for frequently queried fields, `created_at` / `updated_at` timestamps, and clear status fields.

## 7. Admin Dashboard Requirements

- Secure admin login and logout.
- Dashboard overview showing inquiry counts and recent activity.
- CRUD management for services, projects, categories, testimonials, articles, and job positions.
- Inquiry list, detail view, and status management.
- Application list and CV access with authorization checks.
- Website settings management for contact details and social links.
- Confirmation before destructive actions and clear success/error feedback.

Admin dashboard adalah interface internal dan tidak boleh mengekspos data inquiry atau applicant kepada pengunjung publik.

## 8. User Flows

### 8.1 Visitor Flow

1. Visitor opens the website and lands on Home.
2. Visitor explores About, Services, or Work.
3. Visitor opens a project case study or reviews service details.
4. Visitor navigates to Contact or selects a project CTA.
5. Visitor completes and submits the inquiry form.
6. System validates and stores the inquiry.
7. Website displays a success or error message.

### 8.2 Admin Flow

1. Admin opens the dashboard login page.
2. Admin authenticates successfully.
3. Admin views dashboard overview.
4. Admin selects a content module or inquiry management.
5. Admin creates, edits, publishes, archives, or reviews records.
6. System validates and saves changes to the database.
7. Admin receives feedback and returns to the dashboard.

## 9. Non-Functional Requirements

### 9.1 Performance

- Target page load time under 3 seconds under defined test conditions.
- Use optimized WebP/AVIF images where supported.
- Implement lazy loading for below-the-fold images.
- Minify production CSS and JavaScript.
- Avoid unnecessary third-party scripts and heavy animations.

### 9.2 Security

- Validate input on both client and server.
- Use `password_hash()` and `password_verify()` for admin passwords.
- Use PDO or MySQLi prepared statements for database queries.
- Implement CSRF tokens for state-changing forms.
- Escape output to mitigate cross-site scripting (XSS).
- Use secure session settings and regenerate session IDs after login.
- Validate uploaded file type, size, and extension; store uploads safely.
- Use HTTPS in production and restrict admin routes.

### 9.3 SEO & Accessibility

- SEO-friendly URLs, unique page titles, and meta descriptions.
- Semantic HTML and logical heading hierarchy.
- Open Graph metadata and XML sitemap.
- `robots.txt` and descriptive image alt text.
- Keyboard-accessible navigation and visible focus states.
- Sufficient color contrast and labels for form controls.
- Respect `prefers-reduced-motion` for animated experiences.

## 10. Scope & Prioritization

### 10.1 Feature Priorities

| Feature | Priority | Phase |
|---|---|---|
| Home, About, Services, Work, Contact | Must have | 1 — MVP |
| Responsive design and navigation | Must have | 1 — MVP |
| Project inquiry form and database storage | Must have | 1 — MVP |
| Admin authentication | Must have | 2 — CMS |
| Portfolio CRUD and inquiry management | Must have | 2 — CMS |
| Services CRUD and testimonials | Should have | 2 — CMS |
| Insights / Blog | Should have | 3 — Expansion |
| Careers and job applications | Could have | 3 — Expansion |
| Advanced animations and custom cursor | Could have | 3 — Polish |
| Multi-language support | Could have | 3 — Expansion |

### 10.2 Out of Scope for Initial MVP

- Client portal and project progress tracking.
- Online payment and invoicing.
- Full CRM functionality.
- Automated appointment scheduling.
- Advanced marketing automation.

## 11. Acceptance Criteria

- [ ] All MVP pages are accessible through the navigation.
- [ ] Navigation, CTA buttons, and internal links work correctly.
- [ ] Website is usable on mobile, tablet, and desktop.
- [ ] Portfolio category filters work and project details can be opened.
- [ ] Contact form validates input and stores successful submissions.
- [ ] Admin can log in and log out securely.
- [ ] Admin can create, read, update, and delete portfolio records.
- [ ] Admin can manage services and view incoming inquiries.
- [ ] Private inquiry and applicant data are protected from public access.
- [ ] No critical broken links, console errors, or server errors remain.
- [ ] Basic SEO metadata and accessibility checks are completed.

## 12. Development Roadmap

| Timeline | Phase | Activities |
|---|---|---|
| Week 1 | Research & Planning | Competitor research, sitemap, user flow, wireframes, brand direction |
| Week 2 | UI/UX Design | High-fidelity screens, design system, responsive layouts, prototype |
| Week 3 | Frontend Development | Page implementation, responsive behavior, navigation, animation |
| Week 4 | Backend Development | PHP, MySQL, authentication, CRUD, inquiry form |
| Week 5 | Testing & Deployment | Functional and responsive testing, SEO, optimization, deployment |

Estimasi lima minggu mengasumsikan satu developer dengan scope MVP yang terkontrol. Perubahan scope, konten, integrasi pihak ketiga, dan revisi desain dapat memengaruhi durasi.

## 13. Future Improvements

1. Live chat integration.
2. WhatsApp contact integration.
3. Client portal untuk melihat progres project.
4. Online appointment scheduling.
5. CRM integration.
6. Advanced analytics dashboard.
7. Multi-language support.
8. Newsletter subscription.

## 14. Final Product Vision

NOVA bukan sekadar website company profile. Website harus menjadi representasi digital creative agency dengan identitas visual yang kuat, pengalaman pengguna yang intuitif, konten yang meyakinkan, dan jalur konversi yang jelas.

| Experience principle | Expected outcome |
|---|---|
| Visually impressive | Memorable visual identity and strong art direction |
| User-friendly | Clear navigation and intuitive interactions |
| Trustworthy | Credible company information and relevant case studies |
| Conversion-focused | Clear CTAs and low-friction project inquiry |
| Scalable | Content can be managed and expanded over time |

### Final Deliverables

- Company profile website.
- Responsive UI/UX design.
- Frontend implementation.
- PHP backend and MySQL database.
- Admin dashboard and content management.
- Contact / project inquiry system.
- SEO and performance optimization.

**Recommended implementation order:** build the MVP pages (Home, About, Services, Work, Contact), establish responsive behavior, connect the inquiry form to the database, then implement the admin dashboard. Add Insights and Careers after the core experience is stable.
