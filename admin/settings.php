<?php
/* =====================================================================
 |  BLOSSOM PUBLIC SCHOOL  —  SETTINGS ROUTER
 * ===================================================================== */

require_once __DIR__ . '/auth.php';
require_admin_auth();

$tab = $_GET['tab'] ?? 'identity';

$tabMap = [
    'identity'  => 'settings-identity.php',
    'contact'   => 'settings-contact.php',
    'seo'       => 'settings-seo.php',
    'principal' => 'settings-principal.php',
    'social'    => 'settings-social.php',
    'stats'     => 'settings-stats.php',
];

$target = $tabMap[$tab] ?? 'settings-identity.php';
header('Location: ' . url('admin/' . $target), true, 301);
exit;
