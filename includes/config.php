<?php
/* =====================================================================
 |  BLOSSOM PUBLIC SCHOOL  —  SITE CONFIGURATION
 |  ---------------------------------------------------------------
 |  ▶ EDIT THIS FILE FIRST. Everything marked  «PLACEHOLDER»  should be
 |    replaced with the school's real details. Nothing else needs to
 |    change for the site to work.
 * ===================================================================== */

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/db.php';

/* ------------------------------------------------------------------ */
/*  IDENTITY                                                           */
/* ------------------------------------------------------------------ */
define('SCHOOL_NAME',     get_setting('school_name', 'Blossom Public School'));
define('SCHOOL_SHORT',    get_setting('school_short', 'Blossom'));
define('SCHOOL_TAGLINE',  get_setting('school_tagline', 'Where Every Child Blossoms'));
define('SCHOOL_BOARD',    get_setting('school_board', 'CBSE'));
define('SCHOOL_GRADES',   get_setting('school_grades', 'Nursery to Class VIII'));
define('SCHOOL_CITY',     get_setting('school_city', 'Saharanpur'));
define('SCHOOL_STATE',    get_setting('school_state', 'Uttar Pradesh'));
define('SCHOOL_EST',      get_setting('school_est', '2005'));
define('SCHOOL_AFFIL_NO', get_setting('school_affil_no', '2133456'));

/* ------------------------------------------------------------------ */
/*  CONTACT                                                            */
/* ------------------------------------------------------------------ */
define('SCHOOL_ADDRESS_1', get_setting('school_address_1', 'Subhash Nagar, Nakhasa Bazar'));
define('SCHOOL_ADDRESS_2', get_setting('school_address_2', 'Saharanpur, Uttar Pradesh 247001'));
define('SCHOOL_PHONE',     get_setting('school_phone', '+91 98972 12345'));
define('SCHOOL_PHONE_ALT', get_setting('school_phone_alt', '+91 94120 54321'));
define('SCHOOL_EMAIL',     get_setting('school_email', 'info@blossompublicschool.in'));
define('SCHOOL_HOURS',     get_setting('school_hours', 'Mon – Sat · 8:00 AM to 2:30 PM'));
define('SCHOOL_OFFICE',    get_setting('school_office', 'Office: Mon – Sat · 8:30 AM to 4:00 PM'));

/* Google Maps embed for Saharanpur location. */
define('SCHOOL_MAP_EMBED', get_setting('school_map_embed', 'https://maps.google.com/maps?q=Subhash+Nagar,+Nakhasa+Bazar,+Saharanpur,+Uttar+Pradesh+247001&t=&z=15&ie=UTF8&iwloc=&output=embed'));

/* ------------------------------------------------------------------ */
/*  SOCIAL                                                             */
/* ------------------------------------------------------------------ */
define('SOCIAL_INSTAGRAM', get_setting('social_instagram', 'https://www.instagram.com/blossompublicschool/'));
define('SOCIAL_FACEBOOK',  get_setting('social_facebook', 'https://www.facebook.com/blossompublicschool/'));
define('SOCIAL_YOUTUBE',   get_setting('social_youtube', 'https://www.youtube.com/@blossompublicschool'));
define('SOCIAL_WHATSAPP',  get_setting('social_whatsapp', 'https://wa.me/919897212345'));

/* ------------------------------------------------------------------ */
/*  ENQUIRY FORM                                                       */
/* ------------------------------------------------------------------ */
const ENQUIRY_TO_EMAIL  = SCHOOL_EMAIL;   // where enquiries are mailed
const ENQUIRY_SEND_MAIL = false;          // set true only on a live server with mail() configured
const ENQUIRY_LOG_FILE  = __DIR__ . '/../data/enquiries.csv';

/* ------------------------------------------------------------------ */
/*  HEADLINE NUMBERS (shown on the homepage counter strip)             */
/* ------------------------------------------------------------------ */
define('STAT_STUDENTS', (int) get_setting('stat_students', '1200'));
define('STAT_TEACHERS', (int) get_setting('stat_teachers', '60'));
define('STAT_YEARS',    (int) get_setting('stat_years', '20'));
define('STAT_CLUBS',    (int) get_setting('stat_clubs', '18'));

/* ------------------------------------------------------------------ */
/*  BASE URL — auto-detected, works in root or admin sub-folder         */
/* ------------------------------------------------------------------ */
$__dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
if (basename($__dir) === 'admin') {
    $__dir = dirname($__dir);
}
define('BASE_URL', rtrim($__dir === '/' ? '' : $__dir, '/'));

/* ------------------------------------------------------------------ */
/*  NAVIGATION                                                         */
/* ------------------------------------------------------------------ */
$NAV = [
    ['label' => 'Home',       'file' => 'index'],
    ['label' => 'About',      'file' => 'about', 'children' => [
        ['label' => 'Our Story',            'file' => 'about#story'],
        ['label' => 'Vision & Mission',     'file' => 'about#vision'],
        ['label' => "Principal's Message",  'file' => 'about#principal'],
        ['label' => 'Our Faculty',          'file' => 'faculty'],
    ]],
    ['label' => 'Academics',  'file' => 'academics', 'children' => [
        ['label' => 'Curriculum',        'file' => 'academics#curriculum'],
        ['label' => 'Pre-Primary Wing',  'file' => 'academics#pre-primary'],
        ['label' => 'Primary Wing',      'file' => 'academics#primary'],
        ['label' => 'Middle Wing',       'file' => 'academics#middle'],
    ]],
    ['label' => 'Facilities', 'file' => 'facilities'],
    ['label' => 'Gallery',    'file' => 'gallery'],
    ['label' => 'Admissions', 'file' => 'admissions'],
    ['label' => 'Contact',    'file' => 'contact'],
];
