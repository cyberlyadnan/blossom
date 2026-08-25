<?php
/* ---------------------------------------------------------------------
 |  Admission / contact enquiry handler.
 |  Included by admissions.php and contact.php BEFORE any output.
 |  Validates, stores to data/enquiries.csv, optionally emails, then
 |  redirects (Post/Redirect/Get) so a refresh never re-submits.
 * ------------------------------------------------------------------- */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST' || ($_POST['form'] ?? '') === '') {
    return;
}

$redirect = $_POST['_redirect'] ?? basename($_SERVER['SCRIPT_NAME']);
$redirect = preg_replace('/[^A-Za-z0-9._#-]/', '', $redirect);

/* 1. Bot traps -------------------------------------------------------- */
if (!empty($_POST['website'])) {                       // honeypot filled = bot
    flash_set('ok', 'Thank you! Your enquiry has been received.');
    header('Location: ' . url($redirect));
    exit;
}
if (!csrf_valid($_POST['_token'] ?? null)) {
    flash_set('err', 'Your session expired. Please submit the form again.');
    header('Location: ' . url($redirect));
    exit;
}

/* 2. Collect ---------------------------------------------------------- */
$fields = ['parent', 'email', 'phone', 'student', 'grade', 'subject', 'message'];
$in = [];
foreach ($fields as $f) {
    $in[$f] = trim((string) ($_POST[$f] ?? ''));
    $in[$f] = preg_replace('/[\r\n]+/', ' ', $in[$f]);
}
$in['message'] = trim((string) ($_POST['message'] ?? ''));   // keep line breaks in message

/* 3. Validate --------------------------------------------------------- */
$errors = [];
if ($in['parent'] === '' || mb_strlen($in['parent']) < 3) {
    $errors['parent'] = 'Please enter your full name.';
}
if ($in['email'] === '' || !filter_var($in['email'], FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Please enter a valid email address.';
}
$digits = preg_replace('/\D+/', '', $in['phone']);
if (strlen($digits) < 10 || strlen($digits) > 13) {
    $errors['phone'] = 'Please enter a valid 10-digit mobile number.';
}
if ($_POST['form'] === 'admission' && $in['grade'] === '') {
    $errors['grade'] = 'Please choose a class.';
}
if (mb_strlen($in['message']) > 2000) {
    $errors['message'] = 'Please keep your message under 2000 characters.';
}

if ($errors) {
    $_SESSION['errors'] = $errors;
    $_SESSION['old']    = $in;
    flash_set('err', 'Please check the highlighted fields and try again.');
    header('Location: ' . url($redirect));
    exit;
}

/* 4. Store ------------------------------------------------------------ */
$dir = dirname(ENQUIRY_LOG_FILE);
if (!is_dir($dir)) {
    @mkdir($dir, 0775, true);
}
$isNew = !is_file(ENQUIRY_LOG_FILE);
if ($fh = @fopen(ENQUIRY_LOG_FILE, 'a')) {
    if ($isNew) {
        fputcsv($fh, ['Received', 'Type', 'Parent', 'Email', 'Phone', 'Student', 'Class', 'Subject', 'Message', 'IP']);
    }
    fputcsv($fh, [
        date('Y-m-d H:i:s'),
        $_POST['form'],
        $in['parent'], $in['email'], $in['phone'],
        $in['student'], $in['grade'], $in['subject'], $in['message'],
        $_SERVER['REMOTE_ADDR'] ?? '',
    ]);
    fclose($fh);
}

/* 5. Email (only when explicitly enabled in config) -------------------- */
if (ENQUIRY_SEND_MAIL) {
    $subject = ($_POST['form'] === 'admission' ? 'Admission enquiry' : 'Website enquiry')
             . ' from ' . $in['parent'];
    $body = "New enquiry from the website\n\n"
          . "Name    : {$in['parent']}\n"
          . "Email   : {$in['email']}\n"
          . "Phone   : {$in['phone']}\n"
          . "Student : {$in['student']}\n"
          . "Class   : {$in['grade']}\n"
          . "Subject : {$in['subject']}\n\n"
          . "Message:\n{$in['message']}\n\n"
          . "Received: " . date('d M Y, g:i a') . "\n";
    $headers = 'From: ' . SCHOOL_NAME . ' Website <' . ENQUIRY_TO_EMAIL . ">\r\n"
             . 'Reply-To: ' . $in['email'] . "\r\n"
             . "Content-Type: text/plain; charset=UTF-8\r\n";
    @mail(ENQUIRY_TO_EMAIL, $subject, $body, $headers);
}

/* 6. Done ------------------------------------------------------------- */
errors_clear();
old_clear();
flash_set('ok', 'Thank you, ' . $in['parent'] . '. Your enquiry has been received — our admissions team will contact you within one working day.');
header('Location: ' . url($redirect) . '#enquiry');
exit;
