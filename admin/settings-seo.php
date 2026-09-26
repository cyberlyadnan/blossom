<?php
/* =====================================================================
 |  BLOSSOM PUBLIC SCHOOL  —  SEO & ANNOUNCEMENT BANNER MANAGEMENT
 * ===================================================================== */

require_once __DIR__ . '/auth.php';
require_admin_auth();

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (!csrf_valid($_POST['_token'] ?? null)) {
        flash_set('err', 'Invalid session token. Please try again.');
        header('Location: ' . url('admin/settings-seo.php'));
        exit;
    }

    $seoDesc = trim((string) ($_POST['seo_description'] ?? ''));
    $seoKeys = trim((string) ($_POST['seo_keywords'] ?? ''));
    $bannerText = trim((string) ($_POST['announcement_banner'] ?? ''));
    $bannerActive = isset($_POST['announcement_active']) ? '1' : '0';

    set_setting('seo_description', $seoDesc, 'seo');
    set_setting('seo_keywords', $seoKeys, 'seo');
    set_setting('announcement_banner', $bannerText, 'announcement');
    set_setting('announcement_active', $bannerActive, 'announcement');

    flash_set('ok', 'SEO meta settings and announcement banner updated successfully!');
    header('Location: ' . url('admin/settings-seo.php'));
    exit;
}

$admin_page_title = 'SEO & Announcement Banner';
require_once __DIR__ . '/header.php';
?>

<div class="card">
  <div style="margin-bottom:1.25rem;">
    <h2 style="font-size:1.4rem; font-weight:700; color:var(--adm-navy);"><?= icon('globe') ?> Search Engine Optimization (SEO) &amp; Top Banner</h2>
    <p style="color:var(--adm-text-muted); font-size:0.9rem;">Manage meta descriptions, Google ranking keywords, and top announcement banner displayed across the site.</p>
  </div>

  <form method="POST" action="">
    <?= csrf_field() ?>

    <div style="display:flex; flex-direction:column; gap:1.25rem;">
      <div class="form-group">
        <label class="form-label">SEO Meta Description</label>
        <textarea name="seo_description" class="form-control" rows="3" placeholder="Enter site meta description..."><?= e(get_setting('seo_description', 'Blossom Public School is a premier CBSE affiliated school in Saharanpur offering quality education from Nursery to Class VIII.')) ?></textarea>
        <div class="form-hint">Appears in Google search snippets below the title link.</div>
      </div>

      <div class="form-group">
        <label class="form-label">SEO Target Keywords (comma separated)</label>
        <input type="text" name="seo_keywords" class="form-control" value="<?= e(get_setting('seo_keywords', 'Blossom Public School Saharanpur, CBSE School Saharanpur, Best school in Saharanpur, School Admission Saharanpur, Top CBSE School Saharanpur')) ?>">
        <div class="form-hint">Keywords targeted for local search rankings in Saharanpur &amp; UP.</div>
      </div>

      <div style="border-top:1px solid #e2e8f0; padding-top:1.25rem; margin-top:0.5rem;">
        <h4 style="font-size:1.05rem; font-weight:700; color:var(--adm-navy); margin-bottom:0.75rem;">📣 Top Announcement Bar</h4>
        <div class="form-group">
          <label class="form-label">Announcement Banner Text</label>
          <input type="text" name="announcement_banner" class="form-control" value="<?= e(get_setting('announcement_banner', 'Admissions open for Nursery to Class VIII — Session 2026-27 | Limited seats per section')) ?>">
          <div class="form-hint">Headline shown at the very top of every website page.</div>
        </div>

        <div class="form-group" style="margin-top:0.75rem;">
          <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer; font-weight:600;">
            <input type="checkbox" name="announcement_active" value="1" <?= get_setting('announcement_active', '1') === '1' ? 'checked' : '' ?>>
            Enable Top Announcement Banner
          </label>
        </div>
      </div>
    </div>

    <div style="margin-top:1.5rem; border-top:1px solid #e2e8f0; padding-top:1.25rem;">
      <button type="submit" class="btn btn-gold" style="padding:0.75rem 1.75rem;">
        💾 Save SEO &amp; Announcement
      </button>
    </div>
  </form>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
