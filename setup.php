<?php
/**
 * NOVA Creative Digital Agency
 * Database Installer & Data Seeder
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

class NovaSetup {
    public static function run(PDO $pdo, string $driver): bool {
        // Table definitions compatible with both SQLite and MySQL
        $isSqlite = ($driver === 'sqlite');

        $pkDef = $isSqlite ? "INTEGER PRIMARY KEY AUTOINCREMENT" : "INT AUTO_INCREMENT PRIMARY KEY";
        $dtDef = "DATETIME DEFAULT CURRENT_TIMESTAMP";

        // Create admins table
        $pdo->exec("CREATE TABLE IF NOT EXISTS admins (
            id {$pkDef},
            username VARCHAR(50) NOT NULL UNIQUE,
            email VARCHAR(100) NOT NULL UNIQUE,
            password_hash VARCHAR(255) NOT NULL,
            full_name VARCHAR(100) NOT NULL,
            role VARCHAR(30) DEFAULT 'admin',
            created_at {$dtDef},
            updated_at {$dtDef}
        );");

        // Create categories table
        $pdo->exec("CREATE TABLE IF NOT EXISTS categories (
            id {$pkDef},
            name VARCHAR(100) NOT NULL,
            slug VARCHAR(100) NOT NULL UNIQUE,
            type VARCHAR(30) NOT NULL DEFAULT 'project',
            created_at {$dtDef}
        );");

        // Create services table
        $pdo->exec("CREATE TABLE IF NOT EXISTS services (
            id {$pkDef},
            title VARCHAR(150) NOT NULL,
            slug VARCHAR(150) NOT NULL UNIQUE,
            icon VARCHAR(50) DEFAULT 'sparkles',
            short_description TEXT,
            description TEXT,
            benefits TEXT,
            deliverables TEXT,
            display_order INT DEFAULT 0,
            status VARCHAR(20) DEFAULT 'active',
            created_at {$dtDef},
            updated_at {$dtDef}
        );");

        // Create projects table
        $pdo->exec("CREATE TABLE IF NOT EXISTS projects (
            id {$pkDef},
            title VARCHAR(200) NOT NULL,
            slug VARCHAR(200) NOT NULL UNIQUE,
            client VARCHAR(150) NOT NULL,
            category_id INT DEFAULT NULL,
            category_name VARCHAR(100) DEFAULT NULL,
            year VARCHAR(10) NOT NULL,
            summary TEXT,
            featured_image VARCHAR(255),
            objectives TEXT,
            challenges TEXT,
            creative_process TEXT,
            final_output TEXT,
            outcomes TEXT,
            is_featured TINYINT DEFAULT 0,
            display_order INT DEFAULT 0,
            status VARCHAR(20) DEFAULT 'published',
            created_at {$dtDef},
            updated_at {$dtDef}
        );");

        // Create project_images table
        $pdo->exec("CREATE TABLE IF NOT EXISTS project_images (
            id {$pkDef},
            project_id INT NOT NULL,
            image_path VARCHAR(255) NOT NULL,
            caption VARCHAR(255),
            display_order INT DEFAULT 0,
            created_at {$dtDef}
        );");

        // Create testimonials table
        $pdo->exec("CREATE TABLE IF NOT EXISTS testimonials (
            id {$pkDef},
            client_name VARCHAR(100) NOT NULL,
            role_company VARCHAR(150) NOT NULL,
            avatar VARCHAR(255),
            quote TEXT NOT NULL,
            rating INT DEFAULT 5,
            display_order INT DEFAULT 0,
            status VARCHAR(20) DEFAULT 'active',
            created_at {$dtDef}
        );");

        // Create articles table
        $pdo->exec("CREATE TABLE IF NOT EXISTS articles (
            id {$pkDef},
            title VARCHAR(255) NOT NULL,
            slug VARCHAR(255) NOT NULL UNIQUE,
            category_id INT DEFAULT NULL,
            author VARCHAR(100) DEFAULT 'NOVA Team',
            excerpt TEXT,
            body TEXT,
            cover_image VARCHAR(255),
            read_time VARCHAR(20) DEFAULT '5 min',
            publish_date DATE,
            is_featured TINYINT DEFAULT 0,
            status VARCHAR(20) DEFAULT 'published',
            created_at {$dtDef},
            updated_at {$dtDef}
        );");

        // Create inquiries table
        $pdo->exec("CREATE TABLE IF NOT EXISTS inquiries (
            id {$pkDef},
            full_name VARCHAR(100) NOT NULL,
            email VARCHAR(120) NOT NULL,
            company_name VARCHAR(120),
            service_needed VARCHAR(100) NOT NULL,
            estimated_budget VARCHAR(50),
            project_timeline VARCHAR(50),
            description TEXT NOT NULL,
            status VARCHAR(30) DEFAULT 'New',
            admin_notes TEXT,
            created_at {$dtDef},
            updated_at {$dtDef}
        );");

        // Create job_positions table
        $pdo->exec("CREATE TABLE IF NOT EXISTS job_positions (
            id {$pkDef},
            title VARCHAR(150) NOT NULL,
            slug VARCHAR(150) NOT NULL UNIQUE,
            department VARCHAR(100) NOT NULL,
            work_type VARCHAR(50) DEFAULT 'Full-time',
            location VARCHAR(100) DEFAULT 'Remote / Hybrid',
            short_description TEXT,
            description TEXT,
            responsibilities TEXT,
            requirements TEXT,
            status VARCHAR(20) DEFAULT 'open',
            created_at {$dtDef},
            updated_at {$dtDef}
        );");

        // Create applications table
        $pdo->exec("CREATE TABLE IF NOT EXISTS applications (
            id {$pkDef},
            job_id INT DEFAULT NULL,
            full_name VARCHAR(120) NOT NULL,
            email VARCHAR(120) NOT NULL,
            phone VARCHAR(50),
            portfolio_url VARCHAR(255),
            cv_path VARCHAR(255) NOT NULL,
            cover_note TEXT,
            status VARCHAR(30) DEFAULT 'Pending',
            admin_notes TEXT,
            created_at {$dtDef},
            updated_at {$dtDef}
        );");

        // Create site_settings table
        $pdo->exec("CREATE TABLE IF NOT EXISTS site_settings (
            id {$pkDef},
            setting_key VARCHAR(100) NOT NULL UNIQUE,
            setting_value TEXT,
            updated_at {$dtDef}
        );");

        // Check if categories exist; if not, seed data
        $check = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
        if ($check == 0) {
            self::seedData($pdo);
        }

        return true;
    }

    private static function seedData(PDO $pdo): void {
        // 1. Admin (only insert if not already present)
        $adminCheck = $pdo->query("SELECT COUNT(*) FROM admins")->fetchColumn();
        if ($adminCheck == 0) {
            $passHash = password_hash('admin123', PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("INSERT INTO admins (username, email, password_hash, full_name, role) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute(['admin', 'admin@nova.agency', $passHash, 'Alex Nova', 'superadmin']);
        }

        // 2. Categories
        $categories = [
            ['Branding', 'branding', 'project'],
            ['UI/UX Design', 'ui-ux-design', 'project'],
            ['Web Development', 'web-development', 'project'],
            ['Graphic Design', 'graphic-design', 'project'],
            ['Digital Campaign', 'digital-campaign', 'project'],
            ['Design Trends', 'design-trends', 'article'],
            ['Technology', 'technology', 'article'],
            ['Branding Strategy', 'branding-strategy', 'article'],
            ['Agency Culture', 'agency-culture', 'article']
        ];
        $catStmt = $pdo->prepare("INSERT INTO categories (name, slug, type) VALUES (?, ?, ?)");
        foreach ($categories as $cat) {
            $catStmt->execute($cat);
        }

        // 3. Services
        $services = [
            [
                'Brand Identity',
                'brand-identity',
                'fingerprint',
                'Distinctive visual identities, logo systems, and strategic brand guidelines that make your company unforgettable.',
                'A great brand is more than just a logo; it is the soul of your business. We craft enduring visual identities grounded in thorough market research, category positioning, and timeless aesthetic craft. We ensure your brand communicates confidence across every touchpoint.',
                "Establish commanding market authority\nBuild immediate customer trust & recall\nUnified visual consistency across all channels\nFuture-proof asset ecosystem",
                "Brand Strategy & Positioning\nLogo Suite & Monogram Design\nTypography & Bespoke Color System\nBrand Style Guide & Master Guidelines (PDF)\nStationery & Social Media Starter Kit",
                1,
                'active'
            ],
            [
                'UI/UX Design',
                'ui-ux-design',
                'layout',
                'Intuitive, data-backed interfaces and immersive digital product experiences designed to delight users and convert.',
                'We design digital products that feel effortless and look breathtaking. Through deep user research, information architecture, wireframing, and interactive prototyping, we turn complex workflows into friction-free, habit-forming digital experiences.',
                "Drastically reduce user onboarding drop-off\nMaximize retention & conversion rates\nCohesive design system for rapid developer handoff\nWCAG 2.1 accessibility compliance",
                "User Research & Journey Mapping\nWireframing & Information Architecture\nHigh-Fidelity Interactive Prototypes (Figma)\nDesign Token & Component System\nUsability Testing & Developer Handoff",
                2,
                'active'
            ],
            [
                'Website Development',
                'website-development',
                'code-2',
                'High-performance, bespoke websites and web applications built with modern stacks, lightning speed, and flawless responsiveness.',
                'We bring bold creative concepts to life with clean, scalable, and secure code. Whether you need an editorial brand showcase, an interactive WebGL experience, or a robust web application, we engineer websites that load in milliseconds and rank at the top.',
                "Sub-second load times & 95+ Google Lighthouse scores\nMobile-first responsive architecture on all viewports\nSEO-optimized semantic code structure\nEnterprise-grade security & clean maintainable code",
                "Custom Responsive Frontend & Backend\nContent Management System (CMS)\nSEO & Open Graph Meta Architecture\nInteractive Micro-animations\nHosting Setup, SSL & Analytics Integration",
                3,
                'active'
            ],
            [
                'Graphic Design',
                'graphic-design',
                'palette',
                'Editorial publications, presentation pitch decks, packaging, and high-impact digital collateral.',
                'Every asset you present to the world shapes how your value is perceived. Our graphic design services produce compelling marketing collateral, pitch decks that close venture rounds, bespoke packaging, and editorial publications that demand attention.',
                "Turn complex data into captivating visual stories\nElevate investor presentations to close high-ticket rounds\nTangible unboxing experiences that drive viral reach\nHigh-resolution print-ready and digital deliverables",
                "Investor Pitch Decks & Keynote Presentations\nProduct Packaging & Label Architecture\nMarketing & Advertising Visual Assets\nEditorial Publications & Annual Reports\nTrade Show & Environmental Graphics",
                4,
                'active'
            ],
            [
                'Digital Marketing',
                'digital-marketing',
                'trending-up',
                'Integrated creative campaigns, search optimization, and performance marketing to scale your brand reach.',
                'Creative without reach is invisible. We connect your brand with high-intent audiences through strategic multi-channel campaigns, search engine optimization, content frameworks, and high-converting performance creatives that drive measurable ROI.',
                "Predictable, scalable customer acquisition\nOrganic search visibility and domain authority\nConsistent high-engagement social presence\nTransparent data analytics and real-time reporting",
                "Go-To-Market Digital Strategy\nSearch Engine Optimization (SEO) Roadmap\nPaid Media Creative Assets (Social & Search)\nContent Calendar & Copywriting\nConversion Rate Optimization (CRO)",
                5,
                'active'
            ]
        ];

        $svcStmt = $pdo->prepare("INSERT INTO services (title, slug, icon, short_description, description, benefits, deliverables, display_order, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($services as $svc) {
            $svcStmt->execute($svc);
        }

        // 4. Projects (Case studies)
        $projects = [
            [
                'Aether Fintech — Next-Gen Financial OS',
                'aether-fintech-os',
                'Aether Capital Corp',
                2, // UI/UX Design
                'UI/UX Design',
                '2026',
                'End-to-end design system, mobile app UI/UX, and web dashboard for a global wealth management platform.',
                'project-aether.svg',
                'Redesign the complex trading & wealth management interface to lower bounce rates, accelerate investor onboarding, and establish Aether as the modern benchmark for digital wealth management.',
                'Financial software is notoriously dense. The core challenge was distilling institutional-grade algorithmic charts, multi-currency wallets, and portfolio rebalancing into a clean, intuitive, and reassuring interface that novice investors and hedge fund veterans both adore.',
                'We conducted 40+ hours of user interviews across London and Singapore. We engineered a modular typography hierarchy, high-contrast dark mode surface architecture, and tactile micro-interactions that provide instant clarity on every trade execution.',
                'Delivered a 160-screen responsive design system in Figma, complete micro-interaction animations, developer handoff tokens, and a native iOS / Android application suite.',
                '+142% surge in daily active users within 60 days of launch; 38% reduction in onboarding drop-offs; winner of Best Financial App Design at the 2026 Digital Design Awards.',
                1,
                1,
                'published'
            ],
            [
                'Kromatik — Sustainable Luxury Fashion Identity',
                'kromatik-luxury-fashion',
                'Kromatik Studio Milan',
                1, // Branding
                'Branding',
                '2026',
                'Avant-garde visual identity, tactile packaging suite, and editorial eCommerce experience for an eco-couture atelier.',
                'project-kromatik.svg',
                'Position Kromatik at the intersection of haute couture and circular ecology, proving that radical sustainability can command the highest tier of luxury prestige.',
                'Avoiding the clichéd "earthy green" aesthetic common in sustainable products. Kromatik required an unapologetic, high-contrast editorial look that could stand alongside Balenciaga and Celine in Milan and Paris.',
                'We designed a bespoke high-contrast serif monogram with delicate chisel curves, paired with an earthy obsidian and raw ecru palette. We designed physical unboxing elements using 100% seaweed-derived compostable cardstock with blind debossing.',
                'Complete brand master guidelines (120 pages), monogram and typography licensing, custom packaging suite, showroom signage, and a headless eCommerce storefront concept.',
                'Tripled direct-to-consumer online sales in Q3; secured flagship distribution across 14 luxury retailers in Paris, Tokyo, and New York.',
                1,
                2,
                'published'
            ],
            [
                'Veloce Mobility — Electric Superbike Platform',
                'veloce-mobility-platform',
                'Veloce Motors',
                3, // Web Development
                'Web Development',
                '2025',
                'Immersive 3D interactive web platform and real-time customizer for futuristic performance electric motorcycles.',
                'project-veloce.svg',
                'Build a heart-racing interactive website to showcase the Veloce Model-X electric superbike and secure global pre-orders ahead of production.',
                'Rendering real-time 3D motorcycle models with raytraced reflections directly in the browser while maintaining lightning-fast 60fps performance on standard smartphone browsers.',
                'We engineered custom WebGL shaders, progressive asset streaming, and a minimalist cockpit-inspired UI. Visitors can customize body paint, carbon fiber trims, and battery configurations with real-time price calculation.',
                'Full responsive web platform, 3D interactive configurator, localized reservation deposit checkout, and an executive administration dashboard.',
                '2,400+ pre-orders secured in the first 48 hours; over $18M in total reservation commitments; featured as Site of the Day on multiple design publications.',
                1,
                3,
                'published'
            ],
            [
                'Kinetic Energy — Clean Energy Brand & Campaign',
                'kinetic-energy-campaign',
                'Kinetic Renewables',
                5, // Digital Campaign
                'Digital Campaign',
                '2025',
                'Multi-channel global sustainability campaign, interactive impact calculator, and social media storytelling.',
                'project-kinetic.svg',
                'Demystify commercial solar & microgrid transitions for Fortune 500 decision-makers and drive qualified enterprise pipeline.',
                'B2B sustainability marketing often defaults to dry corporate whitepapers that fail to emotionally engage executive stakeholders.',
                'We created an interactive "Carbon-to-Capital" simulator, cinematic 3D motion explainers, and precision-targeted LinkedIn campaign creatives that highlighted the sheer economic upside of clean energy.',
                'Campaign microsite, dynamic ROI calculator tool, 6 animated video ad spots, social media graphics suite, and comprehensive marketing analytics tracking.',
                '+320% increase in qualified enterprise inbound leads; 4.2M video views across LinkedIn and YouTube; closed 12 enterprise contracts in 90 days.',
                1,
                4,
                'published'
            ],
            [
                'Solis Coffee Roasters — Artisan Packaging & Visual Identity',
                'solis-coffee-packaging',
                'Solis Specialty Coffee',
                4, // Graphic Design
                'Graphic Design',
                '2025',
                'Minimalist typography-led packaging system and retail identity for artisanal single-origin coffee roasters.',
                'project-solis.svg',
                'Create an unmistakable packaging system for premium single-origin coffee beans that commands attention on specialty grocery shelves.',
                'Balancing extensive origin, varietal, and elevation metadata with strict minimalist aesthetic restraint.',
                'We developed an architectural grid system utilizing Space Mono for flavor profiles and altitudes, complemented by color-coded foiled seals indicating roast profiles. All bags are printed on carbon-neutral kraft paper.',
                '12 packaging SKU variations, retail menu boards, merchandise apparel, and unboxing collateral for monthly subscription boxes.',
                'Secured distribution in 85 premium retailers nationwide; 80% growth in monthly coffee subscription members within 6 months.',
                0,
                5,
                'published'
            ]
        ];

        $projStmt = $pdo->prepare("INSERT INTO projects (
            title, slug, client, category_id, category_name, year, summary, featured_image,
            objectives, challenges, creative_process, final_output, outcomes, is_featured, display_order, status
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        foreach ($projects as $proj) {
            $projStmt->execute($proj);
        }

        // 5. Testimonials
        $testimonials = [
            [
                'Sophia Lorenzen',
                'Chief Product Officer at Aether Fintech',
                'avatar-1.svg',
                'NOVA transformed our digital platform from a standard financial tool into an award-winning experience. Their design system saved our engineering team hundreds of development hours, and our user retention has never been higher.',
                5,
                1,
                'active'
            ],
            [
                'Marco Rossi',
                'Founder & Creative Director at Kromatik Studio Milan',
                'avatar-2.svg',
                'The aesthetic sensibility that NOVA brings to the table is rare. They understood our luxury heritage and elevated it into a digital presence that feels uncompromising, bold, and magnetic.',
                5,
                2,
                'active'
            ],
            [
                'David Chen',
                'VP of Global Marketing at Kinetic Renewables',
                'avatar-3.svg',
                'NOVA doesn’t just design pretty screens; they understand business economics and conversion psychology. The campaign they created produced the highest ROI of any marketing initiative in our company’s history.',
                5,
                3,
                'active'
            ]
        ];

        $testStmt = $pdo->prepare("INSERT INTO testimonials (client_name, role_company, avatar, quote, rating, display_order, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        foreach ($testimonials as $test) {
            $testStmt->execute($test);
        }

        // 6. Articles (Insights)
        $articles = [
            [
                'The Death of Generic Minimalist Design: Why Radical Character Wins in 2027',
                'death-of-generic-minimalist-design',
                6, // Design Trends
                'Alex Nova',
                'For a decade, digital design converged on sterile sans-serif interfaces and homogenous grids. Today, high-growth brands are winning through unapologetic typography, editorial layouts, and visceral character.',
                "<p class='lead text-lg text-offwhite/90 mb-6'>Walk through the digital landscape of the past seven years and you will find an ocean of sameness. Clean white cards, round blue buttons, and Inter font set at 16px with 150% line height. While functional, this hyper-standardization stripped brands of their emotional soul.</p>
                <h3 class='text-2xl font-heading text-white font-bold mt-8 mb-4'>The Homogeneity Trap</h3>
                <p class='mb-6'>When every digital agency uses the same component libraries and the same usability playbooks, differentiation collapses. Consumers cannot distinguish between five different fintech apps or three different SaaS platforms without checking the top-left logo.</p>
                <p class='mb-6'>At NOVA, we believe that true usability and radical visual identity are not mutually exclusive. The most memorable digital experiences combine rock-solid information architecture with editorial surprise: expressive typography, asymmetrical balance, and atmospheric sound and motion.</p>
                <h3 class='text-2xl font-heading text-white font-bold mt-8 mb-4'>Three Principles for the New Editorial Era</h3>
                <ul class='list-disc pl-6 space-y-3 mb-6'>
                    <li><strong class='text-white'>Typography as Architecture:</strong> Allow type to command the page rather than hiding behind generic card containers.</li>
                    <li><strong class='text-white'>Atmospheric Contrast:</strong> Use deep obsidian backgrounds with purposeful incandescent highlights to create spatial depth.</li>
                    <li><strong class='text-white'>Tactile Micro-interactions:</strong> Digital buttons should feel weighted and responsive, providing physical satisfaction on click.</li>
                </ul>
                <p class='mb-6'>As artificial intelligence automates the generation of generic design, human taste, deliberate imperfection, and bold artistic point-of-view will become the ultimate competitive moat.</p>",
                'article-editorial-design.svg',
                '6 min read',
                '2026-10-15',
                1,
                'published'
            ],
            [
                'Designing Design Systems That Scale Without Killing Team Creativity',
                'design-systems-that-scale-without-killing-creativity',
                7, // UI/UX
                'Elena Rostova',
                'A design system should be an electric springboard, not a bureaucratic cage. How modern product teams maintain agility while governing hundreds of cross-platform tokens.',
                "<p class='lead text-lg text-offwhite/90 mb-6'>Most design systems start with noble intentions: eliminate redundancy, accelerate feature delivery, and unify brand consistency. Yet two years later, product designers complain they feel like assembly line workers stamping pre-approved rectangles.</p>
                <h3 class='text-2xl font-heading text-white font-bold mt-8 mb-4'>The Core vs. Periphery Framework</h3>
                <p class='mb-6'>To avoid design stagnation, divide your system into strict Core tokens (color tokens, spacing base units, accessibility standards) and flexible Periphery patterns. The core cannot be broken without team consensus; the periphery invites ongoing experimentation.</p>
                <p class='mb-6'>When your design system embraces modular flexibility, engineers ship with supreme velocity while designers retain the freedom to innovate on high-impact customer touchpoints.</p>",
                'article-design-systems.svg',
                '8 min read',
                '2026-09-28',
                0,
                'published'
            ],
            [
                'How High-Performance Digital Experiences Drive 3x Conversion Rates',
                'high-performance-digital-experiences-conversion',
                8, // Technology
                'Marcus Vance',
                'Every 100 milliseconds of latency chips away at your revenue. A deep dive into modern asset orchestration, server-side caching, and semantic web development.',
                "<p class='lead text-lg text-offwhite/90 mb-6'>Consumer patience in 2026 is measured in milliseconds. If your company website takes longer than 2.5 seconds to become interactive, more than 40% of prospective clients will bounce before reading your first headline.</p>
                <h3 class='text-2xl font-heading text-white font-bold mt-8 mb-4'>The High Cost of Code Bloat</h3>
                <p class='mb-6'>Modern web development often suffers from dependency obesity: hundred-megabyte node_modules, dozens of unnecessary analytics trackers, and heavy unoptimized client-side frameworks for pages that are essentially static content.</p>
                <p class='mb-6'>At NOVA, we engineer lean, bespoke websites using lightweight semantic architectures, optimized WebP imagery, and aggressive asset caching. The outcome is effortless 95+ Google Lighthouse scores and conversion funnels that never leak leads.</p>",
                'article-web-performance.svg',
                '5 min read',
                '2026-09-10',
                0,
                'published'
            ]
        ];

        $artStmt = $pdo->prepare("INSERT INTO articles (title, slug, category_id, author, excerpt, body, cover_image, read_time, publish_date, is_featured, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($articles as $art) {
            $artStmt->execute($art);
        }

        // 7. Job Positions
        $jobs = [
            [
                'Senior UI/UX Designer',
                'senior-ui-ux-designer',
                'Design',
                'Full-time',
                'Remote / Hybrid (Jakarta)',
                'Lead interface architecture and digital product design for world-class fintech, mobility, and SaaS clients.',
                'We are seeking a visionary Senior UI/UX Designer to craft world-class interfaces, scalable design systems, and delightful digital experiences. You will collaborate directly with our Creative Director and engineering teams from initial concept discovery to final production handoff.',
                "- Lead product discovery workshops and translate client goals into intuitive wireframes and prototypes.\n- Build and maintain multi-brand design systems in Figma.\n- Conduct usability testing and synthesize quantitative analytics into interface iterations.\n- Mentor junior designers and uphold visual excellence across agency outputs.",
                "- 5+ years of demonstrable product / UI/UX design experience with a standout portfolio.\n- Mastery of Figma, auto-layout, variables, component libraries, and interactive prototyping.\n- Deep understanding of web responsive behavior, mobile OS design guidelines, and WCAG accessibility.\n- Strong English communication skills and confidence presenting designs to executive stakeholders.",
                'open'
            ],
            [
                'Creative Frontend Engineer',
                'creative-frontend-engineer',
                'Engineering',
                'Full-time',
                'Remote',
                'Transform high-fidelity editorial designs into silky-smooth, accessible, and ultra-fast web experiences.',
                'We are looking for a Creative Frontend Engineer who obsesses over micro-animations, typography layout, and sub-second load times. You bridge the gap between world-class design aesthetics and robust technical execution.',
                "- Build pixel-perfect, responsive web interfaces from Figma designs using semantic HTML5, modern CSS/Tailwind, and vanilla JavaScript.\n- Implement high-performance scroll triggers, page transitions, and interactive micro-animations.\n- Optimize web vitals to consistently achieve 90+ Lighthouse scores.\n- Collaborate with backend engineers to integrate dynamic PHP templates and REST APIs.",
                "- 3+ years of professional frontend development experience.\n- Proficient in modern CSS, responsive layouts, Tailwind CSS, and DOM manipulation.\n- Experience with GSAP, CSS animations, or WebGL is a huge bonus.\n- Passion for typography, visual detail, and clean code architecture.",
                'open'
            ],
            [
                'Brand Strategist & Copywriter',
                'brand-strategist-copywriter',
                'Strategy',
                'Full-time',
                'Remote / Jakarta',
                'Define brand voices, strategic market positioning, and magnetic messaging for ambitious global companies.',
                'Words shape perception. We are searching for an articulate Brand Strategist & Copywriter who can uncover the core truth of a business and translate it into arresting taglines, manifesto statements, and comprehensive brand narratives.',
                "- Conduct competitor analysis and market positioning research for incoming client projects.\n- Craft brand manifestos, positioning pillars, tone of voice guidelines, and core messaging frameworks.\n- Write high-converting website copy, pitch deck narratives, and campaign copy.\n- Collaborate closely with visual designers to ensure design and copy elevate one another.",
                "- 3+ years of brand strategy or agency copywriting experience.\n- Impeccable English written communication with a commanding, sharp editorial tone.\n- Ability to distill complex technology or B2B concepts into punchy, persuasive prose.\n- Portfolio demonstrating brand strategy decks, website copy, and campaign headlines.",
                'open'
            ]
        ];

        $jobStmt = $pdo->prepare("INSERT INTO job_positions (title, slug, department, work_type, location, short_description, description, responsibilities, requirements, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($jobs as $job) {
            $jobStmt->execute($job);
        }

        // 8. Site Settings
        $settings = [
            ['agency_name', 'NOVA® Creative Digital Agency'],
            ['agency_tagline', 'We Build Brands. We Create Experiences.'],
            ['contact_email', 'hello@nova.agency'],
            ['contact_phone', '+62 21 555-8900'],
            ['contact_whatsapp', '+62 812-8900-6682'],
            ['office_address', 'SCBD District 8, Treasury Tower Level 24, South Jakarta, Indonesia'],
            ['working_hours', 'Monday – Friday, 09:00 – 18:00 WIB'],
            ['social_instagram', 'https://instagram.com/novaagency'],
            ['social_linkedin', 'https://linkedin.com/company/nova-creative-agency'],
            ['social_dribbble', 'https://dribbble.com/novaagency'],
            ['social_behance', 'https://behance.net/novaagency'],
            ['social_x', 'https://x.com/novaagency'],
            ['meta_title', 'NOVA® — Creative Digital Agency | We Build Brands, We Create Experiences'],
            ['meta_description', 'NOVA is an award-winning creative digital agency specializing in Brand Identity, UI/UX Design, Website Development, and Digital Strategy for ambitious brands worldwide.'],
            ['analytics_code', '']
        ];

        $setStmt = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (?, ?)");
        foreach ($settings as $setting) {
            $setStmt->execute($setting);
        }

        // 9. Sample initial Inquiry
        $inqStmt = $pdo->prepare("INSERT INTO inquiries (full_name, email, company_name, service_needed, estimated_budget, project_timeline, description, status, admin_notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $inqStmt->execute([
            'Jonathan Sterling',
            'jonathan@sterlingwealth.co',
            'Sterling Wealth Global',
            'UI/UX Design',
            '$10,000 - $25,000',
            '1-3 months',
            'We are preparing to launch our new family office asset management portal and need an exceptional digital agency to design the web application and brand identity.',
            'New',
            'High priority lead. Intro discovery call scheduled for Friday.'
        ]);
    }
}

// If invoked directly via CLI or browser
if (php_sapi_name() === 'cli' || basename($_SERVER['PHP_SELF'] ?? '') === 'setup.php') {
    echo "Initializing NOVA Agency Database...\n";
    try {
        $db = Database::getConnection();
        $driver = Database::getDriverUsed();
        NovaSetup::run($db, $driver);
        echo "Database initialization and seeding completed successfully! Driver used: {$driver}\n";
        echo "Default admin credentials: Username: admin | Password: admin123\n";
    } catch (Exception $e) {
        echo "Setup failed: " . $e->getMessage() . "\n";
    }
}
