<?php
/* =====================================================================
 |  BLOSSOM PUBLIC SCHOOL  —  DATABASE CONNECTION & SEEDER
 |  ---------------------------------------------------------------
 |  Database: blossom | User: root | Password: (empty)
 * ===================================================================== */

declare(strict_types=1);

const DB_HOST = '127.0.0.1';
const DB_NAME = 'blossom';
const DB_USER = 'root';
const DB_PASS = '';
const DB_CHAR = 'utf8mb4';

function get_db(): PDO
{
    static $pdo = null;
    if ($pdo !== null) {
        return $pdo;
    }

    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', DB_HOST, DB_NAME, DB_CHAR);
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (PDOException $e) {
        // If database blossom does not exist, try to create it
        try {
            $tmpDsn = sprintf('mysql:host=%s;charset=%s', DB_HOST, DB_CHAR);
            $tmpPdo = new PDO($tmpDsn, DB_USER, DB_PASS, $options);
            $tmpPdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e2) {
            die('Database connection failed: ' . $e2->getMessage());
        }
    }

    init_db_schema($pdo);
    return $pdo;
}

/** Ensure tables exist and seed default initial data if empty. */
function init_db_schema(PDO $db): void
{
    static $initialized = false;
    if ($initialized) return;
    $initialized = true;

    // 1. Create Admins table
    $db->exec("CREATE TABLE IF NOT EXISTS `admins` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `username` VARCHAR(50) NOT NULL UNIQUE,
        `password` VARCHAR(255) NOT NULL,
        `name` VARCHAR(100) NOT NULL,
        `email` VARCHAR(100) NOT NULL,
        `role` VARCHAR(20) DEFAULT 'admin',
        `avatar` VARCHAR(255) DEFAULT NULL,
        `last_login` DATETIME DEFAULT NULL,
        `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // 2. Create Site Settings table
    $db->exec("CREATE TABLE IF NOT EXISTS `site_settings` (
        `setting_key` VARCHAR(100) PRIMARY KEY,
        `setting_value` TEXT,
        `setting_group` VARCHAR(50) DEFAULT 'general',
        `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // 3. Create Gallery table
    $db->exec("CREATE TABLE IF NOT EXISTS `gallery` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `title` VARCHAR(255) NOT NULL,
        `image_path` VARCHAR(255) NOT NULL,
        `category` VARCHAR(50) NOT NULL DEFAULT 'campus',
        `display_order` INT DEFAULT 0,
        `is_active` TINYINT DEFAULT 1,
        `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // 4. Create Faculty table
    $db->exec("CREATE TABLE IF NOT EXISTS `faculty` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `name` VARCHAR(100) NOT NULL,
        `designation` VARCHAR(150) NOT NULL,
        `qualification` VARCHAR(255) NOT NULL,
        `image_path` VARCHAR(255) NOT NULL,
        `category` VARCHAR(50) DEFAULT 'general',
        `display_order` INT DEFAULT 0,
        `is_active` TINYINT DEFAULT 1,
        `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // 5. Create News & Events table
    $db->exec("CREATE TABLE IF NOT EXISTS `news_events` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `title` VARCHAR(255) NOT NULL,
        `category` VARCHAR(50) NOT NULL DEFAULT 'Announcement',
        `event_day` VARCHAR(10) DEFAULT NULL,
        `event_month` VARCHAR(10) DEFAULT NULL,
        `content` TEXT NOT NULL,
        `is_ticker` TINYINT DEFAULT 0,
        `is_published` TINYINT DEFAULT 1,
        `display_order` INT DEFAULT 0,
        `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // 6. Create Enquiries table
    $db->exec("CREATE TABLE IF NOT EXISTS `enquiries` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `form_type` VARCHAR(50) NOT NULL DEFAULT 'contact',
        `parent_name` VARCHAR(100) NOT NULL,
        `email` VARCHAR(100) NOT NULL,
        `phone` VARCHAR(30) NOT NULL,
        `student_name` VARCHAR(100) DEFAULT NULL,
        `grade` VARCHAR(50) DEFAULT NULL,
        `subject` VARCHAR(150) DEFAULT NULL,
        `message` TEXT DEFAULT NULL,
        `status` ENUM('unread', 'read', 'replied', 'archived') DEFAULT 'unread',
        `admin_notes` TEXT DEFAULT NULL,
        `ip_address` VARCHAR(45) DEFAULT NULL,
        `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // 7. Create Testimonials table
    $db->exec("CREATE TABLE IF NOT EXISTS `testimonials` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `quote` TEXT NOT NULL,
        `author_name` VARCHAR(100) NOT NULL,
        `author_title` VARCHAR(100) NOT NULL,
        `rating` TINYINT DEFAULT 5,
        `is_active` TINYINT DEFAULT 1,
        `display_order` INT DEFAULT 0,
        `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // --- SEED DEFAULT ADMIN IF EMPTY ---
    $adminCount = (int) $db->query("SELECT COUNT(*) FROM `admins`")->fetchColumn();
    if ($adminCount === 0) {
        $stmt = $db->prepare("INSERT INTO `admins` (`username`, `password`, `name`, `email`, `role`) VALUES (?, ?, ?, ?, ?)");
        $defaultPasswordHash = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt->execute(['admin', $defaultPasswordHash, 'School Administrator', 'admin@blossompublicschool.in', 'superadmin']);
    }

    // --- SEED SITE SETTINGS IF EMPTY ---
    $settingsCount = (int) $db->query("SELECT COUNT(*) FROM `site_settings`")->fetchColumn();
    if ($settingsCount === 0) {
        $defaultSettings = [
            'school_name'      => ['Blossom Public School', 'identity'],
            'school_short'     => ['Blossom', 'identity'],
            'school_tagline'   => ['Where Every Child Blossoms', 'identity'],
            'school_board'     => ['CBSE', 'identity'],
            'school_grades'    => ['Nursery to Class VIII', 'identity'],
            'school_city'      => ['Saharanpur', 'identity'],
            'school_state'     => ['Uttar Pradesh', 'identity'],
            'school_est'       => ['2005', 'identity'],
            'school_affil_no'  => ['2133456', 'identity'],
            
            'school_address_1' => ['Subhash Nagar, Nakhasa Bazar', 'contact'],
            'school_address_2' => ['Saharanpur, Uttar Pradesh 247001', 'contact'],
            'school_phone'     => ['+91 98972 12345', 'contact'],
            'school_phone_alt' => ['+91 94120 54321', 'contact'],
            'school_email'     => ['info@blossompublicschool.in', 'contact'],
            'school_hours'     => ['Mon – Sat · 8:00 AM to 2:30 PM', 'contact'],
            'school_office'    => ['Office: Mon – Sat · 8:30 AM to 4:00 PM', 'contact'],
            'school_map_embed' => ['https://maps.google.com/maps?q=Subhash+Nagar,+Nakhasa+Bazar,+Saharanpur,+Uttar+Pradesh+247001&t=&z=15&ie=UTF8&iwloc=&output=embed', 'contact'],

            'social_instagram' => ['https://www.instagram.com/blossompublicschool/', 'social'],
            'social_facebook'  => ['https://www.facebook.com/blossompublicschool/', 'social'],
            'social_youtube'   => ['https://www.youtube.com/@blossompublicschool', 'social'],
            'social_whatsapp'  => ['https://wa.me/919897212345', 'social'],

            'stat_students'    => ['1200', 'stats'],
            'stat_teachers'    => ['60', 'stats'],
            'stat_years'       => ['20', 'stats'],
            'stat_clubs'       => ['18', 'stats'],

            'seo_keywords'     => ['Blossom Public School Saharanpur, CBSE School Saharanpur, Best school in Saharanpur, School Admission Saharanpur, Top CBSE School Saharanpur', 'seo'],
            'seo_description'  => ['Blossom Public School is a premier CBSE affiliated school in Saharanpur offering quality education from Nursery to Class VIII.', 'seo'],
            'announcement_banner' => ['Admissions open for Nursery to Class VIII — Session 2026-27 | Limited seats per section', 'announcement'],
            'announcement_active' => ['1', 'announcement'],

            'principal_name'   => ['Mrs. Sunita Sharma', 'principal'],
            'principal_title'  => ['Principal · M.A., B.Ed.', 'principal'],
            'principal_quote'  => ['“Ask our children what they learnt today. Their answer is our real report card.”', 'principal'],
            'principal_msg_1'  => ['Dear Parents, thank you for considering Blossom Public School for your child. A school makes two kinds of promises — the ones printed in a prospectus, and the ones kept on an ordinary Tuesday morning. We care far more about the second kind.', 'principal'],
            'principal_msg_2'  => ['Our teachers are asked to do something harder than finishing the syllabus: to make sure it is understood. That means asking a child to explain rather than repeat, calling a parent about one weak topic before it becomes three, and making room in the week for music, sport and art.', 'principal'],
            'principal_msg_3'  => ['We are strict about a few things — punctuality, courtesy, honesty and clean work — and deliberately relaxed about the rest. Come and visit us. Watch a class in progress. That will tell you everything.', 'principal'],
        ];

        $stmt = $db->prepare("INSERT INTO `site_settings` (`setting_key`, `setting_value`, `setting_group`) VALUES (?, ?, ?)");
        foreach ($defaultSettings as $k => $v) {
            $stmt->execute([$k, $v[0], $v[1]]);
        }
    }

    // --- SEED GALLERY IF EMPTY ---
    $galCount = (int) $db->query("SELECT COUNT(*) FROM `gallery`")->fetchColumn();
    if ($galCount === 0) {
        $photos = [
            ['Principal\'s Office — Seated at Desk', 'principal.jpg', 'achievements', 1],
            ['Principal Felicitating Student Achievers', 'achievers.jpg', 'achievements', 2],
            ['Main Building Atrium & Courtyard', 'gallery/campus-courtyard.jpg', 'campus', 3],
            ['Multi-Level Campus Balconies with Floral Decor', 'gallery/campus-balconies.jpg', 'campus', 4],
            ['Classroom Corridor & Computer Lab Entrance', 'gallery/computer-lab-hall.jpg', 'classroom', 5],
            ['School Corridor & Reception Area', 'gallery/campus-corridor.jpg', 'campus', 6],
            ['Bougainvillea Balcony Walkway', 'gallery/balcony-walkway.jpg', 'campus', 7],
            ['Rooftop Green Potted Cypress Garden', 'gallery/rooftop-garden.jpg', 'activities', 8],
            ['Blooming Pink Bougainvillea Terrace', 'gallery/floral-terrace.jpg', 'activities', 9],
            ['Annual Day Cultural Performance', 'gallery/school-event-01.jpg', 'events', 10],
            ['Independence Day Celebration', 'gallery/school-event-02.jpg', 'events', 11],
            ['Inter-House Quiz Competition', 'gallery/school-event-03.jpg', 'events', 12],
            ['Science Exhibition Display', 'gallery/school-event-04.jpg', 'events', 13],
            ['Republic Day March Past', 'gallery/school-event-05.jpg', 'events', 14],
            ['Teachers Day Celebration', 'gallery/school-event-06.jpg', 'events', 15],
            ['Children Day Funfair & Games', 'gallery/school-event-07.jpg', 'events', 16],
            ['Annual Sports Meet', 'gallery/school-event-08.jpg', 'events', 17],
            ['Prize Distribution Ceremony', 'gallery/school-event-09.jpg', 'events', 18],
            ['Art & Craft Exhibition', 'gallery/school-event-10.jpg', 'events', 19],
            ['Storytelling & Drama Session', 'gallery/school-event-11.jpg', 'events', 20],
            ['Inter-School Debate Championship', 'gallery/school-event-12.jpg', 'events', 21],
            ['Music & Choir Performance', 'gallery/school-event-13.jpg', 'events', 22],
            ['School Assembly Gathering', 'gallery/school-event-14.jpg', 'events', 23],
            ['Plantation & Environment Drive', 'gallery/school-event-15.jpg', 'events', 24],
            ['Yoga & Physical Fitness Session', 'gallery/school-event-16.jpg', 'events', 25],
            ['Parent-Teacher Interaction Day', 'gallery/school-event-17.jpg', 'events', 26],
            ['Robotics & Science Activity', 'gallery/school-event-18.jpg', 'events', 27],
            ['Classroom Group Activity', 'gallery/school-event-19.jpg', 'classroom', 28],
            ['Reading Hour in Library', 'gallery/school-event-20.jpg', 'classroom', 29],
            ['Smart Board Interactive Lesson', 'gallery/school-event-21.jpg', 'classroom', 30],
            ['Science Lab Practical Session', 'gallery/school-event-22.jpg', 'classroom', 31],
            ['Computer Lab Coding Activity', 'gallery/school-event-23.jpg', 'classroom', 32],
            ['Junior Wing Play & Learn', 'gallery/school-event-24.jpg', 'classroom', 33],
            ['Pre-Primary Fun Learning Activity', 'gallery/school-event-25.jpg', 'classroom', 34],
            ['Campus Green Corner', 'gallery/school-event-26.jpg', 'activities', 35],
            ['Outdoor Games & Athletics', 'gallery/school-event-27.jpg', 'activities', 36],
            ['Student Achievement Felicitations', 'gallery/school-event-28.jpg', 'achievements', 37],
            ['Excellence Award Presentation', 'gallery/school-event-29.jpg', 'achievements', 38],
            ['School Festivities & Celebrations', 'gallery/school-event-30.jpg', 'events', 39],
        ];
        $stmt = $db->prepare("INSERT INTO `gallery` (`title`, `image_path`, `category`, `display_order`) VALUES (?, ?, ?, ?)");
        foreach ($photos as $p) {
            $stmt->execute([$p[0], $p[1], $p[2], $p[3]]);
        }
    }

    // --- SEED FACULTY IF EMPTY ---
    $facCount = (int) $db->query("SELECT COUNT(*) FROM `faculty`")->fetchColumn();
    if ($facCount === 0) {
        $staff = [
            ['Mrs. Sunita Sharma', 'Principal · School Leadership', 'M.A., B.Ed. · 20+ years in school education', 'staff/principal.jpg', 1],
            ['Mr. Rajesh Verma', 'Vice Principal · Academic Supervision', 'M.Sc., B.Ed. · Curriculum and assessment', 'staff/vice.jpg', 2],
            ['Mrs. Pooja Gupta', 'Pre-Primary Coordinator · Nursery to UKG', 'M.A., NTT · Early childhood specialist', 'staff/coord-pre.jpg', 3],
            ['Mrs. Anjali Saini', 'Primary Coordinator · Class I to V', 'M.A., B.Ed. · Language and reading', 'staff/coord-pri.jpg', 4],
            ['Mr. Amit Kumar', 'Middle Wing Coordinator · Class VI to VIII', 'M.Sc., B.Ed. · Science and mathematics', 'staff/coord-mid.jpg', 5],
            ['Ms. Neha Chaudhary', 'Student Counsellor · Wellbeing & Care', 'M.A. Psychology · Child counselling', 'staff/counsellor.jpg', 6],
        ];
        $stmt = $db->prepare("INSERT INTO `faculty` (`name`, `designation`, `qualification`, `image_path`, `display_order`) VALUES (?, ?, ?, ?, ?)");
        foreach ($staff as $s) {
            $stmt->execute([$s[0], $s[1], $s[2], $s[3], $s[4]]);
        }
    }

    // --- SEED NEWS & EVENTS IF EMPTY ---
    $newsCount = (int) $db->query("SELECT COUNT(*) FROM `news_events`")->fetchColumn();
    if ($newsCount === 0) {
        $news = [
            ['Admissions open for the new session', 'Announcement', '12', 'Aug', 'Application forms for Nursery to Class VIII are now available at the school office and online.', 1, 1],
            ['Independence Day preparations begin', 'Event', '05', 'Aug', 'House-wise march past, patriotic songs and a special assembly are being rehearsed.', 0, 2],
            ['Inter-school quiz — first position', 'Achievement', '28', 'Jul', 'Our Middle Wing team brought home the trophy from the district-level general knowledge quiz.', 1, 3],
            ['Parent–Teacher Meeting', 'Notice', '20', 'Jul', 'Term-one progress will be shared class-wise. Attendance of at least one parent is requested.', 1, 4],
        ];
        $stmt = $db->prepare("INSERT INTO `news_events` (`title`, `category`, `event_day`, `event_month`, `content`, `is_ticker`, `display_order`) VALUES (?, ?, ?, ?, ?, ?, ?)");
        foreach ($news as $n) {
            $stmt->execute([$n[0], $n[1], $n[2], $n[3], $n[4], $n[5], $n[6]]);
        }
    }

    // 8. Create Sections Why Choose Us table
    $db->exec("CREATE TABLE IF NOT EXISTS `sections_why_choose` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `icon` VARCHAR(50) NOT NULL DEFAULT 'check',
        `title` VARCHAR(255) NOT NULL,
        `description` TEXT NOT NULL,
        `display_order` INT DEFAULT 0,
        `is_active` TINYINT DEFAULT 1,
        `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // 9. Create Academic Wings table
    $db->exec("CREATE TABLE IF NOT EXISTS `academic_wings` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `wing_key` VARCHAR(50) NOT NULL UNIQUE,
        `name` VARCHAR(150) NOT NULL,
        `class_range` VARCHAR(150) NOT NULL,
        `image_path` VARCHAR(255) NOT NULL,
        `lede` TEXT NOT NULL,
        `points` TEXT NOT NULL,
        `display_order` INT DEFAULT 0,
        `is_active` TINYINT DEFAULT 1,
        `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // 10. Create Core Values table
    $db->exec("CREATE TABLE IF NOT EXISTS `core_values` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `icon` VARCHAR(50) NOT NULL DEFAULT 'heart',
        `title` VARCHAR(150) NOT NULL,
        `description` TEXT NOT NULL,
        `display_order` INT DEFAULT 0,
        `is_active` TINYINT DEFAULT 1,
        `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // 11. Create Facilities List table
    $db->exec("CREATE TABLE IF NOT EXISTS `facilities_list` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `category` VARCHAR(50) DEFAULT 'campus',
        `icon` VARCHAR(50) DEFAULT 'laptop',
        `title` VARCHAR(255) NOT NULL,
        `description` TEXT NOT NULL,
        `image_path` VARCHAR(255) DEFAULT NULL,
        `display_order` INT DEFAULT 0,
        `is_active` TINYINT DEFAULT 1,
        `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // --- SEED TESTIMONIALS IF EMPTY ---
    $testCount = (int) $db->query("SELECT COUNT(*) FROM `testimonials`")->fetchColumn();
    if ($testCount === 0) {
        $quotes = [
            ['My daughter went from hiding behind me at the gate to leading the assembly. The teachers noticed her before I did.', 'Mrs. Anita Sharma', 'Parent of Riya (Class III)', 5, 1],
            ['What convinced us was the follow-up. A teacher called about one weak topic in maths — nobody had to chase them.', 'Mr. Suresh Agarwal', 'Parent of Aarav (Class VI)', 5, 2],
            ['Clean campus, punctual buses, and a class teacher who actually answers. As a working parent, that is everything.', 'Dr. Ritu Verma', 'Parent of Kabir (Class I)', 5, 3],
        ];
        $stmt = $db->prepare("INSERT INTO `testimonials` (`quote`, `author_name`, `author_title`, `rating`, `display_order`) VALUES (?, ?, ?, ?, ?)");
        foreach ($quotes as $q) {
            $stmt->execute([$q[0], $q[1], $q[2], $q[3], $q[4]]);
        }
    }

    // --- SEED SECTIONS WHY CHOOSE IF EMPTY ---
    $whyCount = (int) $db->query("SELECT COUNT(*) FROM `sections_why_choose`")->fetchColumn();
    if ($whyCount === 0) {
        $whyItems = [
            ['users', 'Attentive Class Sizes', 'Sections are capped so teaching stays personal and no child slips quietly behind.', 1],
            ['bulb', 'Concept-Led Learning', 'Activity, enquiry and application — children explain the why, not just recite the what.', 2],
            ['shield', 'Safety You Can Verify', 'CCTV-monitored corridors, controlled entry, verified staff and a trained first-aid room.', 3],
            ['palette', 'Arts, Sport & Music', 'Timetabled — not optional. Every child performs, plays and creates each term.', 4],
            ['laptop', 'Smart, Practical Rooms', 'Digital boards, a hands-on science lab and a computer lab used weekly by every class.', 5],
            ['heart', 'Values That Travel', 'Courtesy, honesty and responsibility taught as habits, reinforced by house mentors.', 6],
        ];
        $stmt = $db->prepare("INSERT INTO `sections_why_choose` (`icon`, `title`, `description`, `display_order`) VALUES (?, ?, ?, ?)");
        foreach ($whyItems as $w) {
            $stmt->execute($w);
        }
    }

    // --- SEED ACADEMIC WINGS IF EMPTY ---
    $wingsCount = (int) $db->query("SELECT COUNT(*) FROM `academic_wings`")->fetchColumn();
    if ($wingsCount === 0) {
        $wingsItems = [
            ['pre-primary', 'Pre-Primary Wing', 'Nursery · LKG · UKG', 'pre-primary.jpg', 'The years that decide whether a child finds school a happy place. Everything here is play with a purpose.', "Phonics-led pre-reading and sound blending\nNumber readiness through counting material and games\nFine motor work — tracing, threading, colouring, clay\nRhymes, storytelling, puppet play and show-and-tell\nToilet-trained care with trained caregivers in every section\nNo homework. Learning finishes at the school gate.", 1],
            ['primary', 'Primary Wing', 'Class I to Class V', 'primary.jpg', 'Where the fundamentals are locked in — fluent reading, accurate mental maths and clear handwriting.', "English, Hindi, Mathematics, EVS and General Knowledge\nComputer literacy and library periods every week\nMental maths drills and structured spelling programme\nEVS taught through observation, field walks and projects\nArt, music, dance and games on the regular timetable\nContinuous assessment — no single high-stakes paper", 2],
            ['middle', 'Middle Wing', 'Class VI to Class VIII', 'middle.jpg', 'Subject specialists take over, laboratory work begins, and children learn how to study — not just what.', "Science with regular laboratory practicals\nMathematics with reasoning and word-problem focus\nSocial Science: History, Civics and Geography\nThird language, ICT and project-based assessment\nPresentation, debate and written-expression training\nOlympiad, quiz and scholarship exam preparation", 3],
        ];
        $stmt = $db->prepare("INSERT INTO `academic_wings` (`wing_key`, `name`, `class_range`, `image_path`, `lede`, `points`, `display_order`) VALUES (?, ?, ?, ?, ?, ?, ?)");
        foreach ($wingsItems as $wi) {
            $stmt->execute($wi);
        }
    }

    // --- SEED CORE VALUES IF EMPTY ---
    $valCount = (int) $db->query("SELECT COUNT(*) FROM `core_values`")->fetchColumn();
    if ($valCount === 0) {
        $valItems = [
            ['shield', 'Integrity', 'Doing the right thing when the marks do not depend on it.', 1],
            ['users', 'Respect', 'For teachers, for staff, for classmates who are different.', 2],
            ['award', 'Diligence', 'Finishing what you start, at the standard you are capable of.', 3],
            ['heart', 'Empathy', 'Noticing the child sitting alone, and doing something about it.', 4],
            ['leaf', 'Responsibility', 'For your books, your words, your classroom and your planet.', 5],
        ];
        $stmt = $db->prepare("INSERT INTO `core_values` (`icon`, `title`, `description`, `display_order`) VALUES (?, ?, ?, ?)");
        foreach ($valItems as $vi) {
            $stmt->execute($vi);
        }
    }

    // --- SEED FACILITIES LIST IF EMPTY ---
    $facListCount = (int) $db->query("SELECT COUNT(*) FROM `facilities_list`")->fetchColumn();
    if ($facListCount === 0) {
        $facItems = [
            ['campus', 'laptop', 'Smart Classrooms', 'Airy, well-lit rooms with digital boards, so a concept can be shown as well as explained.', 'classroom.jpg', 1],
            ['campus', 'flask', 'Science Laboratory', 'A working lab where Middle Wing students handle apparatus themselves under supervision.', 'lab.jpg', 2],
            ['campus', 'laptop', 'Computer Laboratory', 'One machine per child in a period, with a structured ICT syllabus from the Primary Wing upward.', 'computer.jpg', 3],
            ['campus', 'library', 'Library & Reading Room', 'Graded readers, reference sets and story collections — with a timetabled period for every class.', 'library.jpg', 4],
            ['campus', 'ball', 'Sports Ground', 'Space for athletics, cricket, football, kho-kho and kabaddi, plus indoor games for wet days.', 'sports.jpg', 5],
            ['campus', 'music', 'Music, Dance & Art', 'Dedicated rooms and instructors — because a stage changes a shy child faster than advice does.', 'arts.jpg', 6],
            ['safety', 'camera', 'CCTV coverage', 'Corridors, entry points, the ground and common areas are monitored through the day.', 'safety.jpg', 7],
            ['safety', 'shield', 'Controlled entry & exit', 'Gate security, visitor logging and a strict pick-up protocol — children leave only with an authorised adult.', NULL, 8],
            ['safety', 'heart', 'Medical room & first aid', 'A dedicated care room, trained first-aid staff and immediate parent notification for any incident.', NULL, 9],
            ['safety', 'users', 'Verified staff', 'Background-checked teachers and support staff, with female caregivers assigned to the Pre-Primary Wing.', NULL, 10],
            ['transport', 'bus', 'School Transport', 'Buses that run on time, on routes you can track across the city.', 'bus.jpg', 11],
            ['extras', 'leaf', 'Green Campus', 'Planted, shaded corners and an Eco Club that actually maintains them.', NULL, 12],
            ['extras', 'bulb', 'Activity Room', 'Space for clubs, storytelling, drama and hands-on project work.', NULL, 13],
            ['extras', 'trophy', 'House System', 'Four houses competing all year in sport, quizzing, art and debate.', NULL, 14],
            ['extras', 'chat', 'Parent Portal', 'Circulars, results and attendance shared promptly through official channels.', NULL, 15],
        ];
        $stmt = $db->prepare("INSERT INTO `facilities_list` (`category`, `icon`, `title`, `description`, `image_path`, `display_order`) VALUES (?, ?, ?, ?, ?, ?)");
        foreach ($facItems as $fi) {
            $stmt->execute($fi);
        }
    }
}

/** Get a site setting value with fallback */
function get_setting(string $key, string $default = ''): string
{
    try {
        $db = get_db();
        $stmt = $db->prepare("SELECT `setting_value` FROM `site_settings` WHERE `setting_key` = ? LIMIT 1");
        $stmt->execute([$key]);
        $val = $stmt->fetchColumn();
        return ($val !== false && $val !== null) ? (string) $val : $default;
    } catch (Exception $e) {
        return $default;
    }
}

/** Update or insert a site setting */
function set_setting(string $key, string $value, string $group = 'general'): bool
{
    try {
        $db = get_db();
        $stmt = $db->prepare("INSERT INTO `site_settings` (`setting_key`, `setting_value`, `setting_group`) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE `setting_value` = VALUES(`setting_value`), `setting_group` = VALUES(`setting_group`)");
        return $stmt->execute([$key, $value, $group]);
    } catch (Exception $e) {
        return false;
    }
}
