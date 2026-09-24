<?php
/* =====================================================================
 |  BLOSSOM PUBLIC SCHOOL  —  ADMIN PANEL HEADER
 * ===================================================================== */

declare(strict_types=1);

require_once __DIR__ . '/auth.php';
require_admin_auth();

$admin = get_logged_admin();
$currentPage = basename($_SERVER['SCRIPT_NAME']);

// Count unread enquiries
$unreadCount = 0;
try {
    $db = get_db();
    $unreadCount = (int) $db->query("SELECT COUNT(*) FROM `enquiries` WHERE `status` = 'unread'")->fetchColumn();
} catch (Exception $e) {}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($admin_page_title ?? 'Admin Dashboard') ?> — Blossom Public School</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= e(url('admin/assets/css/admin.css')) ?>">
</head>
<body>

<div class="admin-wrapper">
  <!-- Sidebar -->
  <aside class="admin-sidebar" id="adminSidebar">
    <div class="sidebar-header">
      <?= crest(38) ?>
      <div>
        <div class="sidebar-brand-title"><?= e(SCHOOL_SHORT) ?> Panel</div>
        <div class="sidebar-brand-sub">Administration</div>
      </div>
    </div>

    <nav class="sidebar-nav">
      <div class="nav-section-title">Main Navigation</div>
      
      <a href="<?= e(url('admin/index.php')) ?>" class="sidebar-link <?= $currentPage === 'index.php' ? 'active' : '' ?>">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
        <span>Dashboard</span>
      </a>

      <a href="<?= e(url('admin/gallery.php')) ?>" class="sidebar-link <?= $currentPage === 'gallery.php' ? 'active' : '' ?>">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        <span>Gallery Data</span>
      </a>

      <a href="<?= e(url('admin/settings.php')) ?>" class="sidebar-link <?= $currentPage === 'settings.php' ? 'active' : '' ?>">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        <span>Site Settings & Contact</span>
      </a>

      <a href="<?= e(url('admin/enquiries.php')) ?>" class="sidebar-link <?= $currentPage === 'enquiries.php' ? 'active' : '' ?>">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        <span>Enquiries & Leads</span>
        <?php if ($unreadCount > 0): ?>
          <span class="sidebar-badge"><?= $unreadCount ?></span>
        <?php endif; ?>
      </a>

      <div class="nav-section-title">Content & Media</div>

      <a href="<?= e(url('admin/faculty.php')) ?>" class="sidebar-link <?= $currentPage === 'faculty.php' ? 'active' : '' ?>">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        <span>Faculty & Staff</span>
      </a>

      <a href="<?= e(url('admin/news.php')) ?>" class="sidebar-link <?= $currentPage === 'news.php' ? 'active' : '' ?>">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.888T11 3a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1h6a1 1 0 001-1v-2.888m0-9.224a1 1 0 011-1h6a1 1 0 011 1v14a1 1 0 01-1 1h-6a1 1 0 01-1-1v-2.888"/></svg>
        <span>News, Events & Ticker</span>
      </a>

      <a href="<?= e(url('admin/testimonials.php')) ?>" class="sidebar-link <?= $currentPage === 'testimonials.php' ? 'active' : '' ?>">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
        <span>Parent Reviews</span>
      </a>

      <div class="nav-section-title">System</div>

      <a href="<?= e(url('admin/profile.php')) ?>" class="sidebar-link <?= $currentPage === 'profile.php' ? 'active' : '' ?>">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
        <span>Admin Account</span>
      </a>

      <a href="<?= e(url('index.php')) ?>" target="_blank" class="sidebar-link">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
        <span>View Public Website</span>
      </a>

      <a href="<?= e(url('admin/logout.php')) ?>" class="sidebar-link" style="color:#f87171">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
        <span>Logout</span>
      </a>
    </nav>

    <div class="sidebar-footer">
      <div class="user-mini-card">
        <div class="user-avatar"><?= mb_substr($admin['name'] ?? 'A', 0, 1) ?></div>
        <div class="user-info">
          <div class="user-name"><?= e($admin['name']) ?></div>
          <div class="user-role"><?= e(ucfirst($admin['role'] ?? 'admin')) ?></div>
        </div>
      </div>
    </div>
  </aside>

  <!-- Main Content Area -->
  <div class="admin-main">
    <header class="admin-topbar">
      <div class="topbar-left">
        <button class="sidebar-toggle-btn" id="sidebarToggleBtn" aria-label="Toggle Sidebar">&#9776;</button>
        <h1 class="page-title"><?= e($admin_page_title ?? 'Dashboard') ?></h1>
      </div>

      <div class="topbar-right">
        <a href="<?= e(url('index.php')) ?>" target="_blank" class="btn btn-outline btn-sm" title="View Website">
          <?= icon('globe') ?> <span>Visit Website</span>
        </a>
        <a href="<?= e(url('admin/profile.php')) ?>" class="btn-icon" title="My Profile">
          <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
        </a>
      </div>
    </header>

    <main class="admin-content">
      <?php if ($flash = flash_get()): ?>
        <div class="flash-alert flash-alert--<?= $flash['type'] === 'ok' ? 'ok' : 'err' ?>">
          <span><?= $flash['type'] === 'ok' ? '✓' : '⚠️' ?> <?= e($flash['message']) ?></span>
          <button style="background:none;border:none;cursor:pointer;font-size:1.1rem;" onclick="this.parentElement.remove()">✕</button>
        </div>
      <?php endif; ?>
