<?php
/* =====================================================================
 |  BLOSSOM PUBLIC SCHOOL  —  CONTACT & LOCATION MANAGEMENT
 * ===================================================================== */

require_once __DIR__ . '/auth.php';
require_admin_auth();

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (!csrf_valid($_POST['_token'] ?? null)) {
        flash_set('err', 'Invalid session token. Please try again.');
        header('Location: ' . url('admin/settings-contact.php'));
        exit;
    }

    $keys = [
        'school_address_1' => 'contact',
        'school_address_2' => 'contact',
        'school_phone'     => 'contact',
        'school_phone_alt' => 'contact',
        'school_email'     => 'contact',
        'school_hours'     => 'contact',
        'school_office'    => 'contact',
        'school_map_embed' => 'contact',
    ];

    foreach ($keys as $key => $group) {
        if (isset($_POST[$key])) {
            set_setting($key, trim((string) $_POST[$key]), $group);
        }
    }

    flash_set('ok', 'Contact details, phone numbers, and location settings updated successfully!');
    header('Location: ' . url('admin/settings-contact.php'));
    exit;
}

$admin_page_title = 'Contact & Address Settings';
require_once __DIR__ . '/header.php';
?>

<div class="card">
  <div style="margin-bottom:1.25rem;">
    <h2 style="font-size:1.4rem; font-weight:700; color:var(--adm-navy);"><?= icon('phone') ?> Contact Numbers, Hours &amp; Location</h2>
    <p style="color:var(--adm-text-muted); font-size:0.9rem;">Manage phone numbers, email address, operating hours, school address, and Google Maps embed link.</p>
  </div>

  <form method="POST" action="">
    <?= csrf_field() ?>

    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap:1.25rem;">
      <div class="form-group">
        <label class="form-label">Primary Phone Number</label>
        <input type="text" name="school_phone" class="form-control" value="<?= e(get_setting('school_phone', '+91 98972 12345')) ?>" required>
      </div>

      <div class="form-group">
        <label class="form-label">Alternate Phone Number</label>
        <input type="text" name="school_phone_alt" class="form-control" value="<?= e(get_setting('school_phone_alt', '+91 94120 54321')) ?>">
      </div>

      <div class="form-group">
        <label class="form-label">Official Email Address</label>
        <input type="email" name="school_email" class="form-control" value="<?= e(get_setting('school_email', 'info@blossompublicschool.in')) ?>" required>
      </div>

      <div class="form-group">
        <label class="form-label">School Operating Hours</label>
        <input type="text" name="school_hours" class="form-control" value="<?= e(get_setting('school_hours', 'Mon – Sat · 8:00 AM to 2:30 PM')) ?>">
      </div>

      <div class="form-group">
        <label class="form-label">Office Hours</label>
        <input type="text" name="school_office" class="form-control" value="<?= e(get_setting('school_office', 'Office: Mon – Sat · 8:30 AM to 4:00 PM')) ?>">
      </div>

      <div class="form-group">
        <label class="form-label">Address Line 1</label>
        <input type="text" name="school_address_1" class="form-control" value="<?= e(get_setting('school_address_1', 'Subhash Nagar, Nakhasa Bazar')) ?>">
      </div>

      <div class="form-group">
        <label class="form-label">Address Line 2 (City &amp; PIN)</label>
        <input type="text" name="school_address_2" class="form-control" value="<?= e(get_setting('school_address_2', 'Saharanpur, Uttar Pradesh 247001')) ?>">
      </div>

      <div class="form-group" style="grid-column: 1 / -1;">
        <label class="form-label">Google Maps Embed URL</label>
        <input type="text" name="school_map_embed" class="form-control" value="<?= e(get_setting('school_map_embed', '')) ?>">
        <div class="form-hint">Embed iframe URL used on the public contact page map.</div>
      </div>
    </div>

    <div style="margin-top:1.5rem; border-top:1px solid #e2e8f0; padding-top:1.25rem;">
      <button type="submit" class="btn btn-gold" style="padding:0.75rem 1.75rem;">
        💾 Save Contact &amp; Address
      </button>
    </div>
  </form>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
