<?php
/* =====================================================================
 |  BLOSSOM PUBLIC SCHOOL  —  ADMIN ENQUIRIES & LEADS INBOX
 * ===================================================================== */

require_once __DIR__ . '/auth.php';
require_admin_auth();

$db = get_db();

// Handle Status Update or Notes or Deletion
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (!csrf_valid($_POST['_token'] ?? null)) {
        flash_set('err', 'Invalid session token. Please try again.');
        header('Location: ' . url('admin/enquiries.php'));
        exit;
    }

    $action = $_POST['action'] ?? '';
    $id     = (int) ($_POST['id'] ?? 0);

    if ($action === 'update_status' && $id > 0) {
        $newStatus  = trim((string) ($_POST['status'] ?? 'read'));
        $adminNotes = trim((string) ($_POST['admin_notes'] ?? ''));

        $stmt = $db->prepare("UPDATE `enquiries` SET `status` = ?, `admin_notes` = ? WHERE `id` = ?");
        $stmt->execute([$newStatus, $adminNotes, $id]);
        flash_set('ok', 'Enquiry status updated.');
        header('Location: ' . url('admin/enquiries.php'));
        exit;
    }

    if ($action === 'delete' && $id > 0) {
        $stmt = $db->prepare("DELETE FROM `enquiries` WHERE `id` = ?");
        $stmt->execute([$id]);
        flash_set('ok', 'Enquiry deleted.');
        header('Location: ' . url('admin/enquiries.php'));
        exit;
    }

    if ($action === 'export_csv') {
        $stmt = $db->query("SELECT * FROM `enquiries` ORDER BY `id` DESC");
        $all = $stmt->fetchAll();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="blossom_enquiries_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Date', 'Type', 'Parent Name', 'Email', 'Phone', 'Student Name', 'Grade', 'Subject', 'Message', 'Status', 'Admin Notes']);
        
        foreach ($all as $row) {
            fputcsv($output, [
                $row['id'],
                $row['created_at'],
                $row['form_type'],
                $row['parent_name'],
                $row['email'],
                $row['phone'],
                $row['student_name'],
                $row['grade'],
                $row['subject'],
                $row['message'],
                $row['status'],
                $row['admin_notes']
            ]);
        }
        fclose($output);
        exit;
    }
}

$admin_page_title = 'Enquiries & Admission Leads';
require_once __DIR__ . '/header.php';

// Filters & Search
$statusFilter = trim((string) ($_GET['status'] ?? 'all'));
$searchQuery  = trim((string) ($_GET['q'] ?? ''));

$sql = "SELECT * FROM `enquiries` WHERE 1=1";
$params = [];

if ($statusFilter !== 'all' && in_array($statusFilter, ['unread', 'read', 'replied', 'archived'], true)) {
    $sql .= " AND `status` = ?";
    $params[] = $statusFilter;
}

if ($searchQuery !== '') {
    $sql .= " AND (`parent_name` LIKE ? OR `email` LIKE ? OR `phone` LIKE ? OR `student_name` LIKE ?)";
    $term = '%' . $searchQuery . '%';
    $params[] = $term;
    $params[] = $term;
    $params[] = $term;
    $params[] = $term;
}

$sql .= " ORDER BY `id` DESC";
$stmt = $db->prepare($sql);
$stmt->execute($params);
$enquiries = $stmt->fetchAll();
?>

<!-- Header Actions -->
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem; flex-wrap:wrap; gap:1rem;">
  <div>
    <h2 style="font-size:1.4rem; font-weight:700; color:var(--adm-navy);">Admissions &amp; Contact Enquiries</h2>
    <p style="color:var(--adm-text-muted); font-size:0.9rem;">View, reply to, track and manage incoming website form leads.</p>
  </div>

  <form method="POST" action="" style="display:inline;">
    <?= csrf_field() ?>
    <input type="hidden" name="action" value="export_csv">
    <button type="submit" class="btn btn-outline">
      <span>📥 Export All to CSV</span>
    </button>
  </form>
</div>

<!-- Search & Filter Bar -->
<div class="card" style="padding:1rem 1.25rem;">
  <form method="GET" action="" style="display:flex; gap:1rem; flex-wrap:wrap; align-items:center; justify-content:space-between;">
    <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
      <a href="<?= e(url('admin/enquiries.php?status=all')) ?>" class="btn btn-sm <?= $statusFilter === 'all' ? 'btn-primary' : 'btn-outline' ?>">All</a>
      <a href="<?= e(url('admin/enquiries.php?status=unread')) ?>" class="btn btn-sm <?= $statusFilter === 'unread' ? 'btn-primary' : 'btn-outline' ?>">Unread</a>
      <a href="<?= e(url('admin/enquiries.php?status=read')) ?>" class="btn btn-sm <?= $statusFilter === 'read' ? 'btn-primary' : 'btn-outline' ?>">Read</a>
      <a href="<?= e(url('admin/enquiries.php?status=replied')) ?>" class="btn btn-sm <?= $statusFilter === 'replied' ? 'btn-primary' : 'btn-outline' ?>">Replied</a>
    </div>

    <div style="display:flex; gap:0.5rem; flex:1; max-width:340px;">
      <input type="text" name="q" class="form-control" placeholder="Search by name, email, phone..." value="<?= e($searchQuery) ?>" style="padding:0.4rem 0.75rem;">
      <button type="submit" class="btn btn-primary btn-sm">Search</button>
      <?php if ($searchQuery !== ''): ?>
        <a href="<?= e(url('admin/enquiries.php')) ?>" class="btn btn-outline btn-sm">Clear</a>
      <?php endif; ?>
    </div>
  </form>
</div>

<!-- Enquiries Table -->
<div class="card">
  <?php if (empty($enquiries)): ?>
    <div style="text-align:center; padding:3rem 1rem;">
      <div style="font-size:2.5rem; margin-bottom:0.5rem;">📭</div>
      <h3>No enquiries found</h3>
      <p style="color:var(--adm-text-muted); font-size:0.9rem;" class="mt-1">
        <?= $searchQuery !== '' ? 'No results matched your search term.' : 'Submitted leads from admission and contact forms will show here.' ?>
      </p>
    </div>
  <?php else: ?>
    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th>Date</th>
            <th>Type</th>
            <th>Parent Info</th>
            <th>Student Details</th>
            <th>Status</th>
            <th style="text-align:right;">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($enquiries as $enq): ?>
            <tr style="<?= $enq['status'] === 'unread' ? 'background:rgba(217, 175, 60, 0.05); font-weight:600;' : '' ?>">
              <td style="white-space:nowrap; font-size:0.82rem;">
                <?= date('d M Y', strtotime($enq['created_at'])) ?><br>
                <span style="color:var(--adm-text-muted); font-size:0.75rem;"><?= date('g:i A', strtotime($enq['created_at'])) ?></span>
              </td>
              <td>
                <span class="badge badge-category"><?= e(ucfirst($enq['form_type'])) ?></span>
              </td>
              <td>
                <strong style="font-size:0.95rem;"><?= e($enq['parent_name']) ?></strong><br>
                <span style="font-size:0.82rem;">📞 <a href="tel:<?= e($enq['phone']) ?>" style="color:inherit"><?= e($enq['phone']) ?></a></span><br>
                <span style="font-size:0.82rem; color:var(--adm-text-muted)">✉️ <?= e($enq['email']) ?></span>
              </td>
              <td style="font-size:0.88rem;">
                <?php if (!empty($enq['student_name'])): ?>
                  <strong>Child:</strong> <?= e($enq['student_name']) ?><br>
                  <span style="color:var(--adm-text-muted)">Grade: <?= e($enq['grade'] ?? 'N/A') ?></span>
                <?php else: ?>
                  <span style="color:var(--adm-text-muted)">Subject: <?= e($enq['subject'] ?: 'General Enquiry') ?></span>
                <?php endif; ?>
              </td>
              <td>
                <span class="badge badge-<?= e($enq['status']) ?>">
                  <?= e(ucfirst($enq['status'])) ?>
                </span>
              </td>
              <td style="text-align:right; white-space:nowrap;">
                <button class="btn btn-gold btn-sm" onclick='viewEnquiry(<?= json_encode($enq, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'>
                  👁️ View Details
                </button>

                <form method="POST" action="" style="display:inline;" onsubmit="return confirm('Delete this enquiry?')">
                  <?= csrf_field() ?>
                  <input type="hidden" name="action" value="delete">
                  <input type="hidden" name="id" value="<?= $enq['id'] ?>">
                  <button type="submit" class="btn btn-danger btn-sm" title="Delete">🗑️</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

<!-- Enquiry Details Modal -->
<div class="modal-overlay" id="viewEnquiryModal">
  <div class="modal-container" style="max-width:650px;">
    <div class="modal-header">
      <h3 class="modal-title">📩 Enquiry Details &amp; Status</h3>
      <button class="modal-close" onclick="closeModal('viewEnquiryModal')">&times;</button>
    </div>
    <form method="POST" action="">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="update_status">
      <input type="hidden" name="id" id="enqId">

      <div class="modal-body">
        <div style="background:var(--adm-bg); border-radius:10px; padding:1.25rem; margin-bottom:1.25rem;">
          <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; font-size:0.9rem; margin-bottom:1rem;">
            <div><strong>Parent Name:</strong> <span id="enqParent"></span></div>
            <div><strong>Received Date:</strong> <span id="enqDate"></span></div>
            <div><strong>Phone Number:</strong> <a id="enqPhoneLink" href="" style="color:var(--adm-navy); font-weight:700"></a></div>
            <div><strong>Email Address:</strong> <span id="enqEmail"></span></div>
            <div><strong>Student Name:</strong> <span id="enqStudent"></span></div>
            <div><strong>Requested Class:</strong> <span id="enqGrade"></span></div>
          </div>
          <div>
            <strong style="font-size:0.85rem; text-transform:uppercase; color:var(--adm-text-muted);">Submitted Message:</strong>
            <p id="enqMessage" style="margin-top:0.35rem; font-size:0.95rem; background:#fff; padding:0.85rem; border-radius:8px; border:1px solid var(--adm-border); white-space:pre-wrap;"></p>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Update Enquiry Status *</label>
          <select name="status" id="enqStatus" class="form-control" required>
            <option value="unread">Unread</option>
            <option value="read">Read / Reviewed</option>
            <option value="replied">Replied to Parent</option>
            <option value="archived">Archived</option>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Internal Admin Notes</label>
          <textarea name="admin_notes" id="enqNotes" class="form-control" placeholder="Add private notes about phone calls, follow-ups or admission status..."></textarea>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('viewEnquiryModal')">Close</button>
        <button type="submit" class="btn btn-gold">Save Status &amp; Notes</button>
      </div>
    </form>
  </div>
</div>

<script>
function viewEnquiry(enq) {
  document.getElementById('enqId').value = enq.id;
  document.getElementById('enqParent').innerText = enq.parent_name;
  document.getElementById('enqDate').innerText = enq.created_at;
  document.getElementById('enqPhoneLink').innerText = enq.phone;
  document.getElementById('enqPhoneLink').href = 'tel:' + enq.phone;
  document.getElementById('enqEmail').innerText = enq.email;
  document.getElementById('enqStudent').innerText = enq.student_name || 'N/A';
  document.getElementById('enqGrade').innerText = enq.grade || 'N/A';
  document.getElementById('enqMessage').innerText = enq.message || '(No message provided)';
  document.getElementById('enqStatus').value = enq.status;
  document.getElementById('enqNotes').value = enq.admin_notes || '';
  openModal('viewEnquiryModal');
}
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
