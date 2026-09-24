<?php
/* =====================================================================
 |  BLOSSOM PUBLIC SCHOOL  —  ADMIN ACCOUNT PROFILE & SECURITY
 * ===================================================================== */

require_once __DIR__ . '/auth.php';
require_admin_auth();

$db = get_db();
$admin = get_logged_admin();
$adminId = (int) $admin['id'];

// Handle Profile & Password Updates
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (!csrf_valid($_POST['_token'] ?? null)) {
        flash_set('err', 'Invalid session token. Please try again.');
        header('Location: ' . url('admin/profile.php'));
        exit;
    }

    $action = $_POST['action'] ?? '';

    if ($action === 'update_profile') {
        $name     = trim((string) ($_POST['name'] ?? ''));
        $email    = trim((string) ($_POST['email'] ?? ''));
        $username = trim((string) ($_POST['username'] ?? ''));

        if ($name === '' || $email === '' || $username === '') {
            flash_set('err', 'All profile fields are required.');
            header('Location: ' . url('admin/profile.php'));
            exit;
        }

        try {
            $stmt = $db->prepare("UPDATE `admins` SET `name` = ?, `email` = ?, `username` = ? WHERE `id` = ?");
            $stmt->execute([$name, $email, $username, $adminId]);

            // Update active session
            $_SESSION['admin_user']['name']     = $name;
            $_SESSION['admin_user']['email']    = $email;
            $_SESSION['admin_user']['username'] = $username;

            flash_set('ok', 'Profile details updated successfully!');
        } catch (Exception $e) {
            flash_set('err', 'Username or email already in use.');
        }

        header('Location: ' . url('admin/profile.php'));
        exit;
    }

    if ($action === 'change_password') {
        $currPass = trim((string) ($_POST['current_password'] ?? ''));
        $newPass  = trim((string) ($_POST['new_password'] ?? ''));
        $confPass = trim((string) ($_POST['confirm_password'] ?? ''));

        if ($currPass === '' || $newPass === '' || $confPass === '') {
            flash_set('err', 'All password fields are required.');
            header('Location: ' . url('admin/profile.php'));
            exit;
        }

        if ($newPass !== $confPass) {
            flash_set('err', 'New password and confirmation password do not match.');
            header('Location: ' . url('admin/profile.php'));
            exit;
        }

        if (strlen($newPass) < 6) {
            flash_set('err', 'New password must be at least 6 characters long.');
            header('Location: ' . url('admin/profile.php'));
            exit;
        }

        // Verify current password
        $stmt = $db->prepare("SELECT `password` FROM `admins` WHERE `id` = ? LIMIT 1");
        $stmt->execute([$adminId]);
        $hash = $stmt->fetchColumn();

        if ($hash && password_verify($currPass, (string)$hash)) {
            $newHash = password_hash($newPass, PASSWORD_DEFAULT);
            $upStmt = $db->prepare("UPDATE `admins` SET `password` = ? WHERE `id` = ?");
            $upStmt->execute([$newHash, $adminId]);

            flash_set('ok', 'Admin password changed successfully!');
        } else {
            flash_set('err', 'Current password is incorrect.');
        }

        header('Location: ' . url('admin/profile.php'));
        exit;
    }
}

$admin_page_title = 'Admin Account & Security';
require_once __DIR__ . '/header.php';
?>

<div style="max-width:800px; margin:0 auto;">
  <div class="card">
    <div class="card-header">
      <h2 class="card-title">👤 Account Details</h2>
    </div>
    
    <form method="POST" action="">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="update_profile">

      <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem;">
        <div class="form-group">
          <label class="form-label">Full Name *</label>
          <input type="text" name="name" class="form-control" value="<?= e($admin['name']) ?>" required>
        </div>

        <div class="form-group">
          <label class="form-label">Username *</label>
          <input type="text" name="username" class="form-control" value="<?= e($admin['username']) ?>" required>
        </div>

        <div class="form-group" style="grid-column: 1 / -1;">
          <label class="form-label">Email Address *</label>
          <input type="email" name="email" class="form-control" value="<?= e($admin['email']) ?>" required>
        </div>
      </div>

      <div style="margin-top:1rem;">
        <button type="submit" class="btn btn-primary">Update Profile Information</button>
      </div>
    </form>
  </div>

  <div class="card mt-4">
    <div class="card-header">
      <h2 class="card-title">🔒 Change Admin Password</h2>
    </div>

    <form method="POST" action="">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="change_password">

      <div class="form-group">
        <label class="form-label">Current Password *</label>
        <input type="password" name="current_password" class="form-control" placeholder="Enter current password" required>
      </div>

      <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem;">
        <div class="form-group">
          <label class="form-label">New Password *</label>
          <input type="password" name="new_password" class="form-control" placeholder="Minimum 6 characters" required>
        </div>

        <div class="form-group">
          <label class="form-label">Confirm New Password *</label>
          <input type="password" name="confirm_password" class="form-control" placeholder="Re-enter new password" required>
        </div>
      </div>

      <div style="margin-top:1rem;">
        <button type="submit" class="btn btn-gold">Update Password</button>
      </div>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
