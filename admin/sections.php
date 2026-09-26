<?php
/* =====================================================================
 |  BLOSSOM PUBLIC SCHOOL  —  PAGE SECTIONS ROUTER
 * ===================================================================== */

require_once __DIR__ . '/auth.php';
require_admin_auth();

$tab = $_GET['tab'] ?? 'why';

$tabMap = [
    'why'        => 'sections-why.php',
    'wings'      => 'sections-academics.php',
    'values'     => 'sections-values.php',
    'facilities' => 'sections-facilities.php',
];

$target = $tabMap[$tab] ?? 'sections-why.php';
header('Location: ' . url('admin/' . $target), true, 301);
exit;
