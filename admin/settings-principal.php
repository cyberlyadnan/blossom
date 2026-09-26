<?php
/* =====================================================================
 |  BLOSSOM PUBLIC SCHOOL  —  PRINCIPAL'S DESK MANAGEMENT
 * ===================================================================== */

require_once __DIR__ . '/auth.php';
require_admin_auth();

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (!csrf_valid($_POST['_token'] ?? null)) {
        flash_set('err', 'Invalid session token. Please try again.');
        header('Location: ' . url('admin/settings-principal.php'));
        exit;
    }

    $keys = [
        'principal_name'  => 'principal',
        'principal_title' => 'principal',
        'principal_quote' => 'principal',
        'principal_msg_1' => 'principal',
        'principal_msg_2' => 'principal',
        'principal_msg_3' => 'principal',
    ];

    foreach ($keys as $key => $group) {
        if (isset($_POST[$key])) {
            set_setting($key, trim((string) $_POST[$key]), $group);
        }
    }

    flash_set('ok', 'Principal message and leadership bio updated successfully!');
    header('Location: ' . url('admin/settings-principal.php'));
    exit;
}

$admin_page_title = 'Principal\'s Desk & Leadership Message';
require_once __DIR__ . '/header.php';
?>

<div class="card">
  <div style="margin-bottom:1.25rem;">
    <h2 style="font-size:1.4rem; font-weight:700; color:var(--adm-navy);"><?= icon('user') ?> Principal's Message &amp; Leadership Bio</h2>
    <p style="color:var(--adm-text-muted); font-size:0.9rem;">Manage Principal's name, qualifications, featured quote, and letter to parents.</p>
  </div>

  <form method="POST" action="">
    <?= csrf_field() ?>

    <div style="display:flex; flex-direction:column; gap:1.25rem;">
      <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap:1.25rem;">
        <div class="form-group">
          <label class="form-label">Principal Name</label>
          <input type="text" name="principal_name" class="form-control" value="<?= e(get_setting('principal_name', 'Mrs. Sunita Sharma')) ?>" required>
        </div>

        <div class="form-group">
          <label class="form-label">Principal Title / Qualification</label>
          <input type="text" name="principal_title" class="form-control" value="<?= e(get_setting('principal_title', 'Principal · M.A., B.Ed.')) ?>">
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Featured Quote</label>
        <input type="text" name="principal_quote" class="form-control" value="<?= e(get_setting('principal_quote', '“Ask our children what they learnt today. Their answer is our real report card.”')) ?>">
      </div>

      <div class="form-group">
        <label class="form-label">Message Paragraph 1</label>
        <textarea name="principal_msg_1" class="form-control" rows="3"><?= e(get_setting('principal_msg_1', 'Dear Parents, thank you for considering Blossom Public School for your child.')) ?></textarea>
      </div>

      <div class="form-group">
        <label class="form-label">Message Paragraph 2</label>
        <textarea name="principal_msg_2" class="form-control" rows="3"><?= e(get_setting('principal_msg_2', 'Our teachers are asked to do something harder than finishing the syllabus: to make sure it is understood.')) ?></textarea>
      </div>

      <div class="form-group">
        <label class="form-label">Message Paragraph 3</label>
        <textarea name="principal_msg_3" class="form-control" rows="3"><?= e(get_setting('principal_msg_3', 'We are strict about a few things — punctuality, courtesy, honesty and clean work.')) ?></textarea>
      </div>
    </div>

    <div style="margin-top:1.5rem; border-top:1px solid #e2e8f0; padding-top:1.25rem;">
      <button type="submit" class="btn btn-gold" style="padding:0.75rem 1.75rem;">
        💾 Save Principal's Message
      </button>
    </div>
  </form>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
