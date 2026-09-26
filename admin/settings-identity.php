<?php
/* =====================================================================
 |  BLOSSOM PUBLIC SCHOOL  —  SCHOOL BRANDING & IDENTITY MANAGEMENT
 * ===================================================================== */

require_once __DIR__ . '/auth.php';
require_admin_auth();

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (!csrf_valid($_POST['_token'] ?? null)) {
        flash_set('err', 'Invalid session token. Please try again.');
        header('Location: ' . url('admin/settings-identity.php'));
        exit;
    }

    $keys = [
        'school_name'     => 'identity',
        'school_short'    => 'identity',
        'school_tagline'  => 'identity',
        'school_board'    => 'identity',
        'school_grades'   => 'identity',
        'school_city'     => 'identity',
        'school_state'    => 'identity',
        'school_est'      => 'identity',
        'school_affil_no' => 'identity',
    ];

    foreach ($keys as $key => $group) {
        if (isset($_POST[$key])) {
            set_setting($key, trim((string) $_POST[$key]), $group);
        }
    }

    flash_set('ok', 'School identity and branding settings updated successfully!');
    header('Location: ' . url('admin/settings-identity.php'));
    exit;
}

$admin_page_title = 'School Branding & Identity';
require_once __DIR__ . '/header.php';
?>

<div class="card">
  <div style="margin-bottom:1.25rem;">
    <h2 style="font-size:1.4rem; font-weight:700; color:var(--adm-navy);"><?= icon('book') ?> School Branding &amp; Identity</h2>
    <p style="color:var(--adm-text-muted); font-size:0.9rem;">Manage school official name, short title, tagline, board affiliation, grades offered, and established year.</p>
  </div>

  <form method="POST" action="">
    <?= csrf_field() ?>

    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap:1.25rem;">
      <div class="form-group">
        <label class="form-label">Full School Name</label>
        <input type="text" name="school_name" class="form-control" value="<?= e(get_setting('school_name', 'Blossom Public School')) ?>" required>
      </div>

      <div class="form-group">
        <label class="form-label">Short Name (Header Title)</label>
        <input type="text" name="school_short" class="form-control" value="<?= e(get_setting('school_short', 'Blossom')) ?>" required>
      </div>

      <div class="form-group">
        <label class="form-label">School Tagline</label>
        <input type="text" name="school_tagline" class="form-control" value="<?= e(get_setting('school_tagline', 'Where Every Child Blossoms')) ?>">
      </div>

      <div class="form-group">
        <label class="form-label">Affiliation Board</label>
        <input type="text" name="school_board" class="form-control" value="<?= e(get_setting('school_board', 'CBSE')) ?>">
      </div>

      <div class="form-group">
        <label class="form-label">Classes Offered</label>
        <input type="text" name="school_grades" class="form-control" value="<?= e(get_setting('school_grades', 'Nursery to Class VIII')) ?>">
      </div>

      <div class="form-group">
        <label class="form-label">City</label>
        <input type="text" name="school_city" class="form-control" value="<?= e(get_setting('school_city', 'Saharanpur')) ?>">
      </div>

      <div class="form-group">
        <label class="form-label">State</label>
        <input type="text" name="school_state" class="form-control" value="<?= e(get_setting('school_state', 'Uttar Pradesh')) ?>">
      </div>

      <div class="form-group">
        <label class="form-label">Established Year</label>
        <input type="text" name="school_est" class="form-control" value="<?= e(get_setting('school_est', '2005')) ?>">
      </div>

      <div class="form-group">
        <label class="form-label">Board Affiliation Number</label>
        <input type="text" name="school_affil_no" class="form-control" value="<?= e(get_setting('school_affil_no', '2133456')) ?>">
      </div>
    </div>

    <div style="margin-top:1.5rem; border-top:1px solid #e2e8f0; padding-top:1.25rem;">
      <button type="submit" class="btn btn-gold" style="padding:0.75rem 1.75rem;">
        💾 Save School Identity
      </button>
    </div>
  </form>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
