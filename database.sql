-- NOVA Creative Digital Agency
-- Database Schema & Initial Data for MySQL
-- Compatible with MySQL 5.7+ / 8.0+ & phpMyAdmin

CREATE DATABASE IF NOT EXISTS `nova_agency` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `nova_agency`;

-- 1. Table: admins
CREATE TABLE IF NOT EXISTS `admins` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `full_name` VARCHAR(100) NOT NULL,
  `role` VARCHAR(30) DEFAULT 'admin',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Table: categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) NOT NULL UNIQUE,
  `type` VARCHAR(30) NOT NULL DEFAULT 'project',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `categories` (`id`, `name`, `slug`, `type`) VALUES
(1, 'Branding', 'branding', 'project'),
(2, 'UI/UX Design', 'ui-ux-design', 'project'),
(3, 'Web Development', 'web-development', 'project'),
(4, 'Graphic Design', 'graphic-design', 'project'),
(5, 'Digital Campaign', 'digital-campaign', 'project'),
(6, 'Design Trends', 'design-trends', 'article'),
(7, 'Technology', 'technology', 'article'),
(8, 'Branding Strategy', 'branding-strategy', 'article'),
(9, 'Agency Culture', 'agency-culture', 'article')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

-- 3. Table: services
CREATE TABLE IF NOT EXISTS `services` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(150) NOT NULL,
  `slug` VARCHAR(150) NOT NULL UNIQUE,
  `icon` VARCHAR(50) DEFAULT 'sparkles',
  `short_description` TEXT,
  `description` TEXT,
  `benefits` TEXT,
  `deliverables` TEXT,
  `display_order` INT DEFAULT 0,
  `status` VARCHAR(20) DEFAULT 'active',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Table: projects
CREATE TABLE IF NOT EXISTS `projects` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(200) NOT NULL,
  `slug` VARCHAR(200) NOT NULL UNIQUE,
  `client` VARCHAR(150) NOT NULL,
  `category_id` INT DEFAULT NULL,
  `category_name` VARCHAR(100) DEFAULT NULL,
  `year` VARCHAR(10) NOT NULL,
  `summary` TEXT,
  `featured_image` VARCHAR(255),
  `objectives` TEXT,
  `challenges` TEXT,
  `creative_process` TEXT,
  `final_output` TEXT,
  `outcomes` TEXT,
  `is_featured` TINYINT DEFAULT 0,
  `display_order` INT DEFAULT 0,
  `status` VARCHAR(20) DEFAULT 'published',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `idx_projects_category` (`category_id`),
  KEY `idx_projects_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Table: project_images
CREATE TABLE IF NOT EXISTS `project_images` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `project_id` INT NOT NULL,
  `image_path` VARCHAR(255) NOT NULL,
  `caption` VARCHAR(255),
  `display_order` INT DEFAULT 0,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  KEY `idx_project_images_project` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Table: testimonials
CREATE TABLE IF NOT EXISTS `testimonials` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `client_name` VARCHAR(100) NOT NULL,
  `role_company` VARCHAR(150) NOT NULL,
  `avatar` VARCHAR(255),
  `quote` TEXT NOT NULL,
  `rating` INT DEFAULT 5,
  `display_order` INT DEFAULT 0,
  `status` VARCHAR(20) DEFAULT 'active',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Table: articles
CREATE TABLE IF NOT EXISTS `articles` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `category_id` INT DEFAULT NULL,
  `author` VARCHAR(100) DEFAULT 'NOVA Team',
  `excerpt` TEXT,
  `body` LONGTEXT,
  `cover_image` VARCHAR(255),
  `read_time` VARCHAR(20) DEFAULT '5 min',
  `publish_date` DATE,
  `is_featured` TINYINT DEFAULT 0,
  `status` VARCHAR(20) DEFAULT 'published',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `idx_articles_category` (`category_id`),
  KEY `idx_articles_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. Table: inquiries
CREATE TABLE IF NOT EXISTS `inquiries` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `full_name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(120) NOT NULL,
  `company_name` VARCHAR(120),
  `service_needed` VARCHAR(100) NOT NULL,
  `estimated_budget` VARCHAR(50),
  `project_timeline` VARCHAR(50),
  `description` TEXT NOT NULL,
  `status` VARCHAR(30) DEFAULT 'New',
  `admin_notes` TEXT,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `idx_inquiries_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. Table: job_positions
CREATE TABLE IF NOT EXISTS `job_positions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(150) NOT NULL,
  `slug` VARCHAR(150) NOT NULL UNIQUE,
  `department` VARCHAR(100) NOT NULL,
  `work_type` VARCHAR(50) DEFAULT 'Full-time',
  `location` VARCHAR(100) DEFAULT 'Remote / Hybrid',
  `short_description` TEXT,
  `description` TEXT,
  `responsibilities` TEXT,
  `requirements` TEXT,
  `status` VARCHAR(20) DEFAULT 'open',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. Table: applications
CREATE TABLE IF NOT EXISTS `applications` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `job_id` INT DEFAULT NULL,
  `full_name` VARCHAR(120) NOT NULL,
  `email` VARCHAR(120) NOT NULL,
  `phone` VARCHAR(50),
  `portfolio_url` VARCHAR(255),
  `cv_path` VARCHAR(255) NOT NULL,
  `cover_note` TEXT,
  `status` VARCHAR(30) DEFAULT 'Pending',
  `admin_notes` TEXT,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `idx_applications_job` (`job_id`),
  KEY `idx_applications_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 11. Table: site_settings
CREATE TABLE IF NOT EXISTS `site_settings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `setting_key` VARCHAR(100) NOT NULL UNIQUE,
  `setting_value` TEXT,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Default Admin Account (Username: admin | Password: admin123)
INSERT INTO `admins` (`username`, `email`, `password_hash`, `full_name`, `role`)
VALUES ('admin', 'admin@nova.agency', '$2y$10$qjXCEiWT.QK0lP.w99x0sesF.Ayg.g/vWGL/0W5p8GGfx760NLFhW', 'Alex Nova', 'superadmin')
ON DUPLICATE KEY UPDATE `password_hash`=VALUES(`password_hash`);

