<?php
/* =====================================================================
 |  BLOSSOM PUBLIC SCHOOL  —  SOCIAL MEDIA LINKS MANAGEMENT
 * ===================================================================== */

require_once __DIR__ . '/auth.php';
require_admin_auth();

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (!csrf_valid($_POST['_token'] ?? null)) {
        flash_set('err', 'Invalid session token. Please try again.');
        header('Location: ' . url('admin/settings-social.php'));
        exit;
    }

    $keys = [
        'social_instagram' => 'social',
        'social_facebook'  => 'social',
        'social_youtube'   => 'social',
        'social_whatsapp'  => 'social',
    ];

    foreach ($keys as $key => $group) {
        if (isset($_POST[$key])) {
            set_setting($key, trim((string) $_POST[$key]), $group);
        }
    }

    flash_set('ok', 'Social media URLs and WhatsApp link updated successfully!');
    header('Location: ' . url('admin/settings-social.php'));
    exit;
}

$admin_page_title = 'Social Media Channels';
require_once __DIR__ . '/header.php';
?>

<div class="card">
  <div style="margin-bottom:1.25rem;">
    <h2 style="font-size:1.4rem; font-weight:700; color:var(--adm-navy);"><svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="vertical-align:middle; margin-right:4px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg> Social Media Channels</h2>
    <p style="color:var(--adm-text-muted); font-size:0.9rem;">Manage Instagram, Facebook, YouTube, and WhatsApp messaging links displayed on topbar, header, and footer.</p>
  </div>

  <form method="POST" action="">
    <?= csrf_field() ?>

    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap:1.25rem;">
      <div class="form-group">
        <label class="form-label">Instagram Profile URL</label>
        <input type="url" name="social_instagram" class="form-control" value="<?= e(get_setting('social_instagram', 'https://www.instagram.com/blossompublicschool/')) ?>">
      </div>

      <div class="form-group">
        <label class="form-label">Facebook Page URL</label>
        <input type="url" name="social_facebook" class="form-control" value="<?= e(get_setting('social_facebook', 'https://www.facebook.com/blossompublicschool/')) ?>">
      </div>

      <div class="form-group">
        <label class="form-label">YouTube Channel URL</label>
        <input type="url" name="social_youtube" class="form-control" value="<?= e(get_setting('social_youtube', 'https://www.youtube.com/@blossompublicschool')) ?>">
      </div>

      <div class="form-group">
        <label class="form-label">WhatsApp Direct Link</label>
        <input type="url" name="social_whatsapp" class="form-control" value="<?= e(get_setting('social_whatsapp', 'https://wa.me/919897212345')) ?>">
      </div>
    </div>

    <div style="margin-top:1.5rem; border-top:1px solid #e2e8f0; padding-top:1.25rem;">
      <button type="submit" class="btn btn-gold" style="padding:0.75rem 1.75rem;">
        💾 Save Social Links
      </button>
    </div>
  </form>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
