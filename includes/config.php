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

/* ------------------------------------------------------------------ */
/*  IDENTITY                                                           */
/* ------------------------------------------------------------------ */
const SCHOOL_NAME       = 'Blossom Public School';
const SCHOOL_SHORT      = 'Blossom';
const SCHOOL_TAGLINE    = 'Where Every Child Blossoms';
const SCHOOL_BOARD      = 'CBSE';
const SCHOOL_GRADES     = 'Nursery to Class VIII';
const SCHOOL_CITY       = 'Saharanpur';
const SCHOOL_STATE      = 'Uttar Pradesh';
const SCHOOL_EST        = '2005';                                   // «PLACEHOLDER»
const SCHOOL_AFFIL_NO   = 'XXXXXXX';                                // «PLACEHOLDER» CBSE affiliation number

/* ------------------------------------------------------------------ */
/*  CONTACT                                                            */
/* ------------------------------------------------------------------ */
const SCHOOL_ADDRESS_1  = 'Street / Locality Name';                 // «PLACEHOLDER»
const SCHOOL_ADDRESS_2  = 'Saharanpur, Uttar Pradesh 247001';       // «PLACEHOLDER» pin code
const SCHOOL_PHONE      = '+91 00000 00000';                        // «PLACEHOLDER»
const SCHOOL_PHONE_ALT  = '+91 00000 00000';                        // «PLACEHOLDER»
const SCHOOL_EMAIL      = 'info@blossompublicschool.in';            // «PLACEHOLDER»
const SCHOOL_HOURS      = 'Mon – Sat · 8:00 AM to 2:30 PM';
const SCHOOL_OFFICE     = 'Office: Mon – Sat · 8:30 AM to 4:00 PM';

/* Google Maps embed. Paste the "Embed a map" iframe SRC from Google Maps. */
const SCHOOL_MAP_EMBED  = '';                                        // «PLACEHOLDER» leave '' to show a styled fallback

/* ------------------------------------------------------------------ */
/*  SOCIAL                                                             */
/* ------------------------------------------------------------------ */
const SOCIAL_INSTAGRAM  = 'https://www.instagram.com/blossompublicschool/';
const SOCIAL_FACEBOOK   = '#';                                       // «PLACEHOLDER»
const SOCIAL_YOUTUBE    = '#';                                       // «PLACEHOLDER»
const SOCIAL_WHATSAPP   = 'https://wa.me/910000000000';              // «PLACEHOLDER» 91 + number

/* ------------------------------------------------------------------ */
/*  ENQUIRY FORM                                                       */
/* ------------------------------------------------------------------ */
const ENQUIRY_TO_EMAIL  = SCHOOL_EMAIL;   // where enquiries are mailed
const ENQUIRY_SEND_MAIL = false;          // set true only on a live server with mail() configured
const ENQUIRY_LOG_FILE  = __DIR__ . '/../data/enquiries.csv';

/* ------------------------------------------------------------------ */
/*  HEADLINE NUMBERS (shown on the homepage counter strip)             */
/* ------------------------------------------------------------------ */
const STAT_STUDENTS     = 1200;   // «PLACEHOLDER»
const STAT_TEACHERS     = 60;     // «PLACEHOLDER»
const STAT_YEARS        = 20;     // «PLACEHOLDER»
const STAT_CLUBS        = 18;     // «PLACEHOLDER»

/* ------------------------------------------------------------------ */
/*  BASE URL — auto-detected, works in any sub-folder of htdocs         */
/* ------------------------------------------------------------------ */
$__dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
define('BASE_URL', rtrim($__dir === '/' ? '' : $__dir, '/'));

/* ------------------------------------------------------------------ */
/*  NAVIGATION                                                         */
/* ------------------------------------------------------------------ */
$NAV = [
    ['label' => 'Home',       'file' => 'index.php'],
    ['label' => 'About',      'file' => 'about.php', 'children' => [
        ['label' => 'Our Story',            'file' => 'about.php#story'],
        ['label' => 'Vision & Mission',     'file' => 'about.php#vision'],
        ['label' => "Principal's Message",  'file' => 'about.php#principal'],
        ['label' => 'Our Faculty',          'file' => 'faculty.php'],
    ]],
    ['label' => 'Academics',  'file' => 'academics.php', 'children' => [
        ['label' => 'Curriculum',        'file' => 'academics.php#curriculum'],
        ['label' => 'Pre-Primary Wing',  'file' => 'academics.php#pre-primary'],
        ['label' => 'Primary Wing',      'file' => 'academics.php#primary'],
        ['label' => 'Middle Wing',       'file' => 'academics.php#middle'],
    ]],
    ['label' => 'Facilities', 'file' => 'facilities.php'],
    ['label' => 'Gallery',    'file' => 'gallery.php'],
    ['label' => 'Admissions', 'file' => 'admissions.php'],
    ['label' => 'Contact',    'file' => 'contact.php'],
];
