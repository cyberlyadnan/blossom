<?php
/* =====================================================================
 |  BLOSSOM PUBLIC SCHOOL  —  HOMEPAGE COUNTER STATS MANAGEMENT
 * ===================================================================== */

require_once __DIR__ . '/auth.php';
require_admin_auth();

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (!csrf_valid($_POST['_token'] ?? null)) {
        flash_set('err', 'Invalid session token. Please try again.');
        header('Location: ' . url('admin/settings-stats.php'));
        exit;
    }

    $keys = [
        'stat_students' => 'stats',
        'stat_teachers' => 'stats',
        'stat_years'    => 'stats',
        'stat_clubs'    => 'stats',
    ];

    foreach ($keys as $key => $group) {
        if (isset($_POST[$key])) {
            set_setting($key, trim((string) $_POST[$key]), $group);
        }
    }

    flash_set('ok', 'Homepage counter statistics updated successfully!');
    header('Location: ' . url('admin/settings-stats.php'));
    exit;
}

$admin_page_title = 'Headline Numbers & Counter Stats';
require_once __DIR__ . '/header.php';
?>

<div class="card">
  <div style="margin-bottom:1.25rem;">
    <h2 style="font-size:1.4rem; font-weight:700; color:var(--adm-navy);"><?= icon('trophy') ?> Homepage Headline Numbers</h2>
    <p style="color:var(--adm-text-muted); font-size:0.9rem;">Manage counter statistics displayed in the animated stats bar on the website homepage.</p>
  </div>

  <form method="POST" action="">
    <?= csrf_field() ?>

    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:1.25rem;">
      <div class="form-group">
        <label class="form-label">Total Students Counter</label>
        <input type="number" name="stat_students" class="form-control" value="<?= e(get_setting('stat_students', '1200')) ?>">
      </div>

      <div class="form-group">
        <label class="form-label">Qualified Teachers Counter</label>
        <input type="number" name="stat_teachers" class="form-control" value="<?= e(get_setting('stat_teachers', '60')) ?>">
      </div>

      <div class="form-group">
        <label class="form-label">Years of Trust</label>
        <input type="number" name="stat_years" class="form-control" value="<?= e(get_setting('stat_years', '20')) ?>">
      </div>

      <div class="form-group">
        <label class="form-label">Clubs &amp; Activities Counter</label>
        <input type="number" name="stat_clubs" class="form-control" value="<?= e(get_setting('stat_clubs', '18')) ?>">
      </div>
    </div>

    <div style="margin-top:1.5rem; border-top:1px solid #e2e8f0; padding-top:1.25rem;">
      <button type="submit" class="btn btn-gold" style="padding:0.75rem 1.75rem;">
        💾 Save Counter Stats
      </button>
    </div>
  </form>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
