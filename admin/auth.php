<?php
/* =====================================================================
 |  BLOSSOM PUBLIC SCHOOL  —  ADMIN AUTHENTICATION & HELPERS
 * ===================================================================== */

declare(strict_types=1);

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

function is_admin_logged_in(): bool
{
    return !empty($_SESSION['admin_user']) && isset($_SESSION['admin_user']['id']);
}

function require_admin_auth(): void
{
    if (!is_admin_logged_in()) {
        flash_set('err', 'Please log in to access the Admin Panel.');
        header('Location: ' . url('admin/login.php'));
        exit;
    }
}

function get_logged_admin(): array
{
    return $_SESSION['admin_user'] ?? [
        'id' => 0,
        'name' => 'Administrator',
        'username' => 'admin',
        'email' => 'admin@blossompublicschool.in',
        'role' => 'admin'
    ];
}
