<?php
/* =====================================================================
 |  BLOSSOM PUBLIC SCHOOL  —  ADMIN DASHBOARD
 * ===================================================================== */

declare(strict_types=1);

$admin_page_title = 'Dashboard Overview';
require_once __DIR__ . '/header.php';

// Fetch summary metrics
$totalGallery   = 0;
$totalFaculty   = 0;
$totalEnquiries = 0;
$unreadEnquiries = 0;
$recentEnquiries = [];
$recentPhotos    = [];

try {
    $db = get_db();

    $totalGallery    = (int) $db->query("SELECT COUNT(*) FROM `gallery`")->fetchColumn();
    $totalFaculty    = (int) $db->query("SELECT COUNT(*) FROM `faculty`")->fetchColumn();
    $totalEnquiries  = (int) $db->query("SELECT COUNT(*) FROM `enquiries`")->fetchColumn();
    $unreadEnquiries = (int) $db->query("SELECT COUNT(*) FROM `enquiries` WHERE `status` = 'unread'")->fetchColumn();

    $stmtEnq = $db->query("SELECT * FROM `enquiries` ORDER BY `id` DESC LIMIT 6");
    $recentEnquiries = $stmtEnq->fetchAll();

    $stmtPhotos = $db->query("SELECT * FROM `gallery` ORDER BY `id` DESC LIMIT 6");
    $recentPhotos = $stmtPhotos->fetchAll();
} catch (Exception $e) {
    // Graceful fallback
}
?>

<!-- Headline Stats Cards -->
<div class="grid-stats">
  <div class="stat-widget accent-gold">
    <div>
      <div class="stat-number"><?= number_format($totalGallery) ?></div>
      <div class="stat-label">Gallery Photos</div>
    </div>
    <div class="stat-icon">🖼️</div>
  </div>

  <div class="stat-widget accent-rose">
    <div>
      <div class="stat-number"><?= number_format($unreadEnquiries) ?></div>
      <div class="stat-label">Unread Enquiries</div>
    </div>
    <div class="stat-icon">📩</div>
  </div>

  <div class="stat-widget accent-emerald">
    <div>
      <div class="stat-number"><?= number_format($totalFaculty) ?></div>
      <div class="stat-label">Faculty Staff</div>
    </div>
    <div class="stat-icon">👨‍🏫</div>
  </div>

  <div class="stat-widget accent-indigo">
    <div>
      <div class="stat-number"><?= number_format($totalEnquiries) ?></div>
      <div class="stat-label">Total Leads</div>
    </div>
    <div class="stat-icon">📋</div>
  </div>
</div>

<!-- Quick Shortcuts -->
<div class="card">
  <div class="card-header">
    <h2 class="card-title">⚡ Quick Management Shortcuts</h2>
  </div>
  <div style="display:flex; flex-wrap:wrap; gap:0.75rem;">
    <a href="<?= e(url('admin/gallery.php?action=add')) ?>" class="btn btn-gold">
      <span>🖼️ Add Gallery Photo</span>
    </a>
    <a href="<?= e(url('admin/enquiries.php')) ?>" class="btn btn-primary">
      <span>📩 View Enquiries Inbox (<?= $unreadEnquiries ?> unread)</span>
    </a>
    <a href="<?= e(url('admin/settings.php')) ?>" class="btn btn-outline">
      <span>⚙️ Update Contact &amp; Phone Numbers</span>
    </a>
    <a href="<?= e(url('admin/faculty.php?action=add')) ?>" class="btn btn-outline">
      <span>👨‍🏫 Add Faculty Member</span>
    </a>
    <a href="<?= e(url('admin/news.php?action=add')) ?>" class="btn btn-outline">
      <span>📣 Post Announcement</span>
    </a>
  </div>
</div>

<!-- Two Column Section -->
<div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 1.5rem;">
  
  <!-- Recent Enquiries -->
  <div class="card">
    <div class="card-header">
      <h2 class="card-title">📩 Recent Enquiries</h2>
      <a href="<?= e(url('admin/enquiries.php')) ?>" class="btn btn-outline btn-sm">View All →</a>
    </div>

    <?php if (empty($recentEnquiries)): ?>
      <p style="color:var(--adm-text-muted); font-size:0.9rem; text-align:center; padding: 2rem 0;">No enquiries submitted yet.</p>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>Parent Name</th>
              <th>Phone</th>
              <th>Type</th>
              <th>Status</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($recentEnquiries as $enq): ?>
              <tr>
                <td>
                  <strong><?= e($enq['parent_name']) ?></strong>
                  <?php if (!empty($enq['student_name'])): ?>
                    <div style="font-size:0.78rem; color:var(--adm-text-muted);">Student: <?= e($enq['student_name']) ?> (<?= e($enq['grade'] ?? '') ?>)</div>
                  <?php endif; ?>
                </td>
                <td><a href="tel:<?= e($enq['phone']) ?>" style="color:inherit;text-decoration:none;font-weight:600"><?= e($enq['phone']) ?></a></td>
                <td><span class="badge badge-category"><?= e(ucfirst($enq['form_type'])) ?></span></td>
                <td>
                  <span class="badge badge-<?= e($enq['status']) ?>">
                    <?= e(ucfirst($enq['status'])) ?>
                  </span>
                </td>
                <td style="font-size:0.8rem; color:var(--adm-text-muted);"><?= date('M d, g:ia', strtotime($enq['created_at'])) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>

  <!-- Recent Gallery Uploads -->
  <div class="card">
    <div class="card-header">
      <h2 class="card-title">🖼️ Recent Gallery Uploads</h2>
      <a href="<?= e(url('admin/gallery.php')) ?>" class="btn btn-outline btn-sm">Manage Gallery →</a>
    </div>

    <?php if (empty($recentPhotos)): ?>
      <p style="color:var(--adm-text-muted); font-size:0.9rem; text-align:center; padding: 2rem 0;">No photos uploaded yet.</p>
    <?php else: ?>
      <div class="adm-gallery-grid" style="grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));">
        <?php foreach ($recentPhotos as $ph): ?>
          <div class="adm-gallery-card">
            <?= media($ph['image_path'], $ph['title'], 1, '', '4/3') ?>
            <div class="adm-gallery-body" style="padding:0.5rem 0.65rem;">
              <div class="adm-gallery-title" style="font-size:0.78rem; line-height:1.2; height:2.4em; overflow:hidden;"><?= e($ph['title']) ?></div>
              <div class="adm-gallery-footer" style="margin-top:0.3rem; padding-top:0.3rem;">
                <span class="badge badge-category" style="font-size:0.68rem; padding:0.1rem 0.4rem;"><?= e($ph['category']) ?></span>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>

</div>

<!-- System Status Card -->
<div class="card mt-4">
  <div class="card-header">
    <h2 class="card-title">ℹ️ System Configuration</h2>
  </div>
  <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:1rem; font-size:0.88rem;">
    <div><strong>School Name:</strong> <?= e(SCHOOL_NAME) ?></div>
    <div><strong>Database Name:</strong> <?= e(DB_NAME) ?></div>
    <div><strong>Database User:</strong> <?= e(DB_USER) ?></div>
    <div><strong>Primary Phone:</strong> <?= e(SCHOOL_PHONE) ?></div>
    <div><strong>Official Email:</strong> <?= e(SCHOOL_EMAIL) ?></div>
    <div><strong>Board Affiliation:</strong> <?= e(SCHOOL_BOARD) ?> (<?= e(SCHOOL_AFFIL_NO) ?>)</div>
  </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
