<?php
/* =====================================================================
 |  BLOSSOM PUBLIC SCHOOL  —  ADMIN SITE SETTINGS & CONTACT
 * ===================================================================== */

require_once __DIR__ . '/auth.php';
require_admin_auth();

// Handle Form Submission
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (!csrf_valid($_POST['_token'] ?? null)) {
        flash_set('err', 'Invalid session token. Please try again.');
        header('Location: ' . url('admin/settings.php'));
        exit;
    }

    $settingsMap = [
        // Identity
        'school_name'      => 'identity',
        'school_short'     => 'identity',
        'school_tagline'   => 'identity',
        'school_board'     => 'identity',
        'school_grades'    => 'identity',
        'school_city'      => 'identity',
        'school_state'     => 'identity',
        'school_est'       => 'identity',
        'school_affil_no'  => 'identity',
        
        // Contact
        'school_address_1' => 'contact',
        'school_address_2' => 'contact',
        'school_phone'     => 'contact',
        'school_phone_alt' => 'contact',
        'school_email'     => 'contact',
        'school_hours'     => 'contact',
        'school_office'    => 'contact',
        'school_map_embed' => 'contact',

        // Social
        'social_instagram' => 'social',
        'social_facebook'  => 'social',
        'social_youtube'   => 'social',
        'social_whatsapp'  => 'social',

        // Stats
        'stat_students'    => 'stats',
        'stat_teachers'    => 'stats',
        'stat_years'       => 'stats',
        'stat_clubs'       => 'stats',
    ];

    $updated = 0;
    foreach ($settingsMap as $key => $group) {
        if (isset($_POST[$key])) {
            $val = trim((string) $_POST[$key]);
            if (set_setting($key, $val, $group)) {
                $updated++;
            }
        }
    }

    flash_set('ok', 'Site settings and contact details updated successfully!');
    header('Location: ' . url('admin/settings.php'));
    exit;
}

$admin_page_title = 'Site Settings & Contact Details';
require_once __DIR__ . '/header.php';
?>

<div class="tabs-container">
  <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem; flex-wrap:wrap; gap:1rem;">
    <div>
      <h2 style="font-size:1.4rem; font-weight:700; color:var(--adm-navy);">Website Configuration &amp; Contact</h2>
      <p style="color:var(--adm-text-muted); font-size:0.9rem;">Update phone numbers, school details, location maps and social links.</p>
    </div>
  </div>

  <div class="nav-tabs">
    <button class="tab-btn active" data-tab="tab-identity"><?= icon('book') ?> School Identity</button>
    <button class="tab-btn" data-tab="tab-contact"><?= icon('phone') ?> Contact &amp; Address</button>
    <button class="tab-btn" data-tab="tab-social"><?= icon('globe') ?> Social Links</button>
    <button class="tab-btn" data-tab="tab-stats"><?= icon('trophy') ?> Headline Numbers</button>
  </div>

  <form method="POST" action="">
    <?= csrf_field() ?>

    <!-- TAB 1: School Identity -->
    <div class="tab-pane card" id="tab-identity">
      <div class="card-header">
        <h3 class="card-title"><?= icon('book') ?> School Branding &amp; Identity</h3>
      </div>
      <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap:1.25rem;">
        <div class="form-group">
          <label class="form-label">Full School Name</label>
          <input type="text" name="school_name" class="form-control" value="<?= e(get_setting('school_name', 'Blossom Public School')) ?>" required>
        </div>

        <div class="form-group">
          <label class="form-label">Short Name (used in headers)</label>
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
    </div>

    <!-- TAB 2: Contact Details -->
    <div class="tab-pane card" id="tab-contact" style="display:none;">
      <div class="card-header">
        <h3 class="card-title">📞 Contact Numbers, Hours &amp; Location</h3>
      </div>
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
          <div class="form-hint">Embed link used on the contact page iframe map.</div>
        </div>
      </div>
    </div>

    <!-- TAB 3: Social Links -->
    <div class="tab-pane card" id="tab-social" style="display:none;">
      <div class="card-header">
        <h3 class="card-title">🌐 Social Media Channels</h3>
      </div>
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
    </div>

    <!-- TAB 4: Headline Stats -->
    <div class="tab-pane card" id="tab-stats" style="display:none;">
      <div class="card-header">
        <h3 class="card-title">📊 Homepage Counter Strip Numbers</h3>
      </div>
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
    </div>

    <div style="margin-top:1.5rem;">
      <button type="submit" class="btn btn-gold" style="font-size:1.05rem; padding:0.85rem 2rem;">
        💾 Save All Settings
      </button>
    </div>
  </form>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
