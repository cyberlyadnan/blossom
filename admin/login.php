<?php
/* =====================================================================
 |  BLOSSOM PUBLIC SCHOOL  —  ADMIN LOGIN
 * ===================================================================== */

declare(strict_types=1);

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/auth.php';

// If already logged in, redirect to dashboard
if (is_admin_logged_in()) {
    header('Location: ' . url('admin/index.php'));
    exit;
}

$error = '';
$username = '';

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (!csrf_valid($_POST['_token'] ?? null)) {
        $error = 'Security session expired. Please submit again.';
    } else {
        $username = trim((string) ($_POST['username'] ?? ''));
        $password = trim((string) ($_POST['password'] ?? ''));

        if ($username === '' || $password === '') {
            $error = 'Please enter both username and password.';
        } else {
            try {
                $db = get_db();
                $stmt = $db->prepare("SELECT * FROM `admins` WHERE `username` = ? OR `email` = ? LIMIT 1");
                $stmt->execute([$username, $username]);
                $user = $stmt->fetch();

                if ($user && password_verify($password, $user['password'])) {
                    // Update last login
                    $updateStmt = $db->prepare("UPDATE `admins` SET `last_login` = NOW() WHERE `id` = ?");
                    $updateStmt->execute([$user['id']]);

                    // Save session
                    unset($user['password']);
                    $_SESSION['admin_user'] = $user;

                    flash_set('ok', 'Welcome back, ' . $user['name'] . '!');
                    header('Location: ' . url('admin/index.php'));
                    exit;
                } else {
                    $error = 'Invalid username or password.';
                }
            } catch (Exception $e) {
                $error = 'Database authentication error: ' . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login — Blossom Public School</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    :root {
      --navy: #0e2a57;
      --navy-dark: #07152b;
      --gold: #d9af3c;
      --gold-hover: #b8891c;
      --bg: #091830;
      --card-bg: rgba(255, 255, 255, 0.96);
      --font-main: 'Outfit', -apple-system, sans-serif;
    }
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: var(--font-main);
      background: radial-gradient(circle at top right, #11284a 0%, #071224 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1.5rem;
      color: #1e293b;
    }
    .login-box {
      width: 100%;
      max-width: 440px;
      background: var(--card-bg);
      border-radius: 20px;
      padding: 2.5rem;
      box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.1);
      position: relative;
      overflow: hidden;
    }
    .login-box::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 6px;
      background: linear-gradient(90deg, var(--gold), #f3d778, var(--gold));
    }
    .login-header {
      text-align: center;
      margin-bottom: 2rem;
    }
    .login-logo {
      margin-bottom: 0.85rem;
      display: inline-block;
    }
    .login-title {
      font-size: 1.5rem;
      font-weight: 800;
      color: var(--navy);
      letter-spacing: -0.02em;
    }
    .login-subtitle {
      font-size: 0.88rem;
      color: #64748b;
      margin-top: 0.25rem;
    }
    .alert-danger {
      background: #fef2f2;
      border: 1px solid #fecaca;
      color: #991b1b;
      padding: 0.85rem 1rem;
      border-radius: 10px;
      font-size: 0.88rem;
      font-weight: 500;
      margin-bottom: 1.5rem;
    }
    .form-group {
      margin-bottom: 1.35rem;
    }
    .form-label {
      display: block;
      font-size: 0.85rem;
      font-weight: 700;
      color: #334155;
      margin-bottom: 0.4rem;
      text-transform: uppercase;
      letter-spacing: 0.04em;
    }
    .form-control {
      width: 100%;
      padding: 0.85rem 1rem;
      font-size: 0.95rem;
      font-family: inherit;
      border: 1.5px solid #cbd5e1;
      border-radius: 10px;
      background: #f8fafc;
      transition: all 0.2s ease;
    }
    .form-control:focus {
      outline: none;
      border-color: var(--gold);
      background: #ffffff;
      box-shadow: 0 0 0 4px rgba(217, 175, 60, 0.2);
    }
    .btn-submit {
      width: 100%;
      padding: 0.95rem;
      background: var(--navy);
      color: #ffffff;
      font-size: 1rem;
      font-weight: 700;
      font-family: inherit;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      transition: background 0.2s ease, transform 0.1s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      box-shadow: 0 4px 12px rgba(14, 42, 87, 0.25);
    }
    .btn-submit:hover {
      background: #153970;
      transform: translateY(-1px);
    }
    .btn-submit:active {
      transform: translateY(0);
    }
    .demo-credentials {
      margin-top: 1.85rem;
      background: #f1f5f9;
      border-radius: 10px;
      padding: 0.85rem 1rem;
      font-size: 0.82rem;
      color: #475569;
      text-align: center;
      border: 1px dashed #cbd5e1;
    }
    .demo-credentials code {
      background: #e2e8f0;
      padding: 0.15rem 0.4rem;
      border-radius: 4px;
      font-weight: 700;
      color: var(--navy);
    }
    .back-link {
      display: block;
      text-align: center;
      margin-top: 1.25rem;
      font-size: 0.85rem;
      color: #64748b;
      text-decoration: none;
      font-weight: 600;
    }
    .back-link:hover { color: var(--navy); }
  </style>
</head>
<body>

<div class="login-box">
  <div class="login-header">
    <div class="login-logo"><?= crest(54) ?></div>
    <h1 class="login-title"><?= e(SCHOOL_SHORT) ?> Admin Panel</h1>
    <p class="login-subtitle">Sign in to manage website data, gallery &amp; enquiries</p>
  </div>

  <?php if ($error): ?>
    <div class="alert-danger">⚠️ <?= e($error) ?></div>
  <?php endif; ?>

  <form method="POST" action="">
    <?= csrf_field() ?>
    
    <div class="form-group">
      <label class="form-label">Username or Email</label>
      <input type="text" name="username" class="form-control" value="<?= e($username) ?>" placeholder="Enter admin username" required autofocus>
    </div>

    <div class="form-group">
      <label class="form-label">Password</label>
      <input type="password" name="password" class="form-control" placeholder="Enter password" required>
    </div>

    <button type="submit" class="btn-submit">
      <span>Sign In to Dashboard</span> →
    </button>
  </form>


  <a href="<?= e(url('index')) ?>" class="back-link">← Back to Public Website</a>
</div>

</body>
</html>
