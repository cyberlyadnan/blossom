<?php
/* =====================================================================
 |  BLOSSOM PUBLIC SCHOOL  —  ADMIN SITE SETTINGS & SEO MANAGEMENT
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

        // SEO & Announcement
        'seo_keywords'        => 'seo',
        'seo_description'     => 'seo',
        'announcement_banner' => 'announcement',
        'announcement_active' => 'announcement',

        // Principal Message
        'principal_name'   => 'principal',
        'principal_title'  => 'principal',
        'principal_quote'  => 'principal',
        'principal_msg_1'  => 'principal',
        'principal_msg_2'  => 'principal',
        'principal_msg_3'  => 'principal',
    ];

    $updated = 0;
    foreach ($settingsMap as $key => $group) {
        $val = isset($_POST[$key]) ? trim((string) $_POST[$key]) : '';
        if (set_setting($key, $val, $group)) {
            $updated++;
        }
    }

    flash_set('ok', 'Website content, SEO settings, and Principal message updated successfully!');
    header('Location: ' . url('admin/settings.php'));
    exit;
}

$admin_page_title = 'Site Settings, SEO & Content';
require_once __DIR__ . '/header.php';
?>

<div class="tabs-container">
  <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem; flex-wrap:wrap; gap:1rem;">
    <div>
      <h2 style="font-size:1.4rem; font-weight:700; color:var(--adm-navy);">Website Configuration, SEO &amp; Content Management</h2>
      <p style="color:var(--adm-text-muted); font-size:0.9rem;">Manage school identity, contact information, SEO keywords, top announcement banner, and Principal's desk message.</p>
    </div>
  </div>

  <div class="nav-tabs">
    <button type="button" class="tab-btn active" data-tab="tab-identity"><?= icon('book') ?> <span>School Identity</span></button>
    <button type="button" class="tab-btn" data-tab="tab-contact"><?= icon('phone') ?> <span>Contact &amp; Address</span></button>
    <button type="button" class="tab-btn" data-tab="tab-seo"><?= icon('globe') ?> <span>SEO &amp; Announcement</span></button>
    <button type="button" class="tab-btn" data-tab="tab-principal"><?= icon('user') ?> <span>Principal's Message</span></button>
    <button type="button" class="tab-btn" data-tab="tab-social"><svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg> <span>Social Links</span></button>
    <button type="button" class="tab-btn" data-tab="tab-stats"><?= icon('trophy') ?> <span>Headline Numbers</span></button>
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
          <label class="form-label">Short Name</label>
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

    <!-- TAB 3: SEO & Announcement Banner -->
    <div class="tab-pane card" id="tab-seo" style="display:none;">
      <div class="card-header">
        <h3 class="card-title">🔍 Search Engine Optimization (SEO) &amp; Top Banner</h3>
      </div>
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
    </div>

    <!-- TAB 4: Principal's Desk & Message -->
    <div class="tab-pane card" id="tab-principal" style="display:none;">
      <div class="card-header">
        <h3 class="card-title">🎓 Principal's Message &amp; Leadership Bio</h3>
      </div>
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
    </div>

    <!-- TAB 5: Social Links -->
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

    <!-- TAB 6: Headline Stats -->
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
        💾 Save All Settings &amp; Content
      </button>
    </div>
  </form>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
