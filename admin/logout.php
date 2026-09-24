<?php
/* =====================================================================
 |  BLOSSOM PUBLIC SCHOOL  —  ADMIN LOGOUT HANDLER
 * ===================================================================== */

declare(strict_types=1);

require_once __DIR__ . '/auth.php';

unset($_SESSION['admin_user']);
flash_set('ok', 'You have been logged out successfully.');
header('Location: ' . url('admin/login.php'));
exit;
