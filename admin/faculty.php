<?php
/* =====================================================================
 |  BLOSSOM PUBLIC SCHOOL  —  ADMIN FACULTY MANAGEMENT
 * ===================================================================== */

require_once __DIR__ . '/auth.php';
require_admin_auth();

$db = get_db();

// Handle Form Actions: Add / Edit / Delete / Toggle
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (!csrf_valid($_POST['_token'] ?? null)) {
        flash_set('err', 'Invalid session token. Please try again.');
        header('Location: ' . url('admin/faculty.php'));
        exit;
    }

    $action = $_POST['action'] ?? '';

    if ($action === 'add' || $action === 'edit') {
        $name          = trim((string) ($_POST['name'] ?? ''));
        $designation   = trim((string) ($_POST['designation'] ?? ''));
        $qualification = trim((string) ($_POST['qualification'] ?? ''));
        $order         = (int) ($_POST['display_order'] ?? 0);
        $isActive      = isset($_POST['is_active']) ? 1 : 0;
        $id            = (int) ($_POST['id'] ?? 0);

        if ($name === '' || $designation === '') {
            flash_set('err', 'Teacher name and designation are required.');
            header('Location: ' . url('admin/faculty.php'));
            exit;
        }

        $imagePath = trim((string) ($_POST['image_path_existing'] ?? ''));

        // Upload file if provided
        if (isset($_FILES['photo_file']) && $_FILES['photo_file']['error'] === UPLOAD_ERR_OK) {
            $tmpPath  = $_FILES['photo_file']['tmp_name'];
            $origName = basename($_FILES['photo_file']['name']);
            $ext      = strtolower(pathinfo($origName, PATHINFO_EXTENSION));

            $allowed = ['jpg', 'jpeg', 'png', 'webp'];
            if (in_array($ext, $allowed, true)) {
                $targetDir = __DIR__ . '/../assets/img/staff/';
                if (!is_dir($targetDir)) {
                    @mkdir($targetDir, 0775, true);
                }
                $newFileName = 'staff_' . time() . '_' . preg_replace('/[^a-z0-9_-]/i', '_', pathinfo($origName, PATHINFO_FILENAME)) . '.' . $ext;
                $targetPath  = $targetDir . $newFileName;

                if (move_uploaded_file($tmpPath, $targetPath)) {
                    $imagePath = 'staff/' . $newFileName;
                }
            }
        }

        if ($imagePath === '') {
            $imagePath = 'staff/principal.jpg';
        }

        if ($action === 'add') {
            $stmt = $db->prepare("INSERT INTO `faculty` (`name`, `designation`, `qualification`, `image_path`, `display_order`, `is_active`) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $designation, $qualification, $imagePath, $order, $isActive]);
            flash_set('ok', 'New faculty member added!');
        } else {
            $stmt = $db->prepare("UPDATE `faculty` SET `name` = ?, `designation` = ?, `qualification` = ?, `image_path` = ?, `display_order` = ?, `is_active` = ? WHERE `id` = ?");
            $stmt->execute([$name, $designation, $qualification, $imagePath, $order, $isActive, $id]);
            flash_set('ok', 'Faculty member details updated!');
        }

        header('Location: ' . url('admin/faculty.php'));
        exit;
    }

    if ($action === 'delete') {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0) {
            $stmt = $db->prepare("DELETE FROM `faculty` WHERE `id` = ?");
            $stmt->execute([$id]);
            flash_set('ok', 'Faculty member removed.');
        }
        header('Location: ' . url('admin/faculty.php'));
        exit;
    }
}

$admin_page_title = 'Faculty & Staff Management';
require_once __DIR__ . '/header.php';

$stmt = $db->query("SELECT * FROM `faculty` ORDER BY `display_order` ASC, `id` ASC");
$staffList = $stmt->fetchAll();
?>

<!-- Header Actions -->
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem; flex-wrap:wrap; gap:1rem;">
  <div>
    <h2 style="font-size:1.4rem; font-weight:700; color:var(--adm-navy);">Faculty &amp; Teaching Staff</h2>
    <p style="color:var(--adm-text-muted); font-size:0.9rem;">Manage teachers, leadership team, coordinators and counsellors.</p>
  </div>
  <button class="btn btn-gold" onclick="openModal('addFacultyModal')">
    <?= icon('plus') ?> <span>Add New Faculty</span>
  </button>
</div>

<!-- Faculty Grid -->
<div class="grid-stats" style="grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));">
  <?php foreach ($staffList as $st): ?>
    <div class="card" style="padding:1.25rem; display:flex; flex-direction:column; justify-space-between;">
      <div style="display:flex; gap:1rem; align-items:center; margin-bottom:1rem;">
        <div style="width:70px; height:70px; border-radius:50%; overflow:hidden; flex-shrink:0; border:2px solid var(--adm-gold);">
          <?= media($st['image_path'], $st['name'], 1, '', '1/1') ?>
        </div>
        <div>
          <h3 style="font-size:1.05rem; font-weight:700; color:var(--adm-navy);"><?= e($st['name']) ?></h3>
          <div style="font-size:0.8rem; color:var(--adm-gold); font-weight:600; line-height:1.2; margin-top:0.2rem;"><?= e($st['designation']) ?></div>
        </div>
      </div>

      <p style="font-size:0.85rem; color:var(--adm-text-muted); flex:1; margin-bottom:1rem;">
        <?= e($st['qualification']) ?>
      </p>

      <div style="display:flex; align-items:center; justify-content:space-between; border-top:1px solid var(--adm-border); padding-top:0.75rem;">
        <span class="badge badge-<?= $st['is_active'] ? 'active' : 'inactive' ?>">
          <?= $st['is_active'] ? 'Active' : 'Inactive' ?>
        </span>

        <div style="display:flex; gap:0.35rem;">
          <button class="btn btn-outline btn-sm" onclick='editFaculty(<?= json_encode($st, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'>
            <?= icon('edit') ?> <span>Edit</span>
          </button>
          
          <form method="POST" action="" style="display:inline;" onsubmit="return confirm('Remove teacher?')">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" value="<?= $st['id'] ?>">
            <button type="submit" class="btn btn-icon-danger btn-sm" title="Delete"><?= icon('trash') ?></button>
          </form>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<!-- Add Faculty Modal -->
<div class="modal-overlay" id="addFacultyModal">
  <div class="modal-container">
    <div class="modal-header">
      <h3 class="modal-title"><?= icon('users') ?> Add New Faculty Member</h3>
      <button class="modal-close" onclick="closeModal('addFacultyModal')">&times;</button>
    </div>
    <form method="POST" action="" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="add">
      <div class="modal-body">
        <div class="form-group">
          <label class="form-label">Full Name *</label>
          <input type="text" name="name" class="form-control" placeholder="e.g. Mrs. Sunita Sharma" required>
        </div>

        <div class="form-group">
          <label class="form-label">Designation / Role *</label>
          <input type="text" name="designation" class="form-control" placeholder="e.g. Primary Coordinator · Class I to V" required>
        </div>

        <div class="form-group">
          <label class="form-label">Qualification &amp; Experience *</label>
          <input type="text" name="qualification" class="form-control" placeholder="e.g. M.A., B.Ed. · 12 years teaching experience" required>
        </div>

        <div class="form-group">
          <label class="form-label">Photo Upload</label>
          <input type="file" name="photo_file" class="form-control" accept="image/*">
        </div>

        <div class="form-group">
          <label class="form-label">Or Image Path</label>
          <input type="text" name="image_path_existing" class="form-control" placeholder="e.g. staff/principal.jpg">
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
          <div class="form-group">
            <label class="form-label">Display Order</label>
            <input type="number" name="display_order" class="form-control" value="0">
          </div>
          <div class="form-group" style="display:flex; align-items:center; gap:0.5rem; margin-top:1.8rem;">
            <input type="checkbox" name="is_active" value="1" checked id="addActiveStaff" style="width:18px; height:18px;">
            <label for="addActiveStaff" class="form-label" style="margin:0; cursor:pointer;">Active Member</label>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('addFacultyModal')">Cancel</button>
        <button type="submit" class="btn btn-gold">Save Faculty</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Faculty Modal -->
<div class="modal-overlay" id="editFacultyModal">
  <div class="modal-container">
    <div class="modal-header">
      <h3 class="modal-title"><?= icon('edit') ?> Edit Faculty Member</h3>
      <button class="modal-close" onclick="closeModal('editFacultyModal')">&times;</button>
    </div>
    <form method="POST" action="" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="edit">
      <input type="hidden" name="id" id="editStaffId">
      <div class="modal-body">
        <div class="form-group">
          <label class="form-label">Full Name *</label>
          <input type="text" name="name" id="editStaffName" class="form-control" required>
        </div>

        <div class="form-group">
          <label class="form-label">Designation / Role *</label>
          <input type="text" name="designation" id="editStaffDesig" class="form-control" required>
        </div>

        <div class="form-group">
          <label class="form-label">Qualification &amp; Experience *</label>
          <input type="text" name="qualification" id="editStaffQual" class="form-control" required>
        </div>

        <div class="form-group">
          <label class="form-label">Replace Photo File</label>
          <input type="file" name="photo_file" class="form-control" accept="image/*">
        </div>

        <div class="form-group">
          <label class="form-label">Current Image Path</label>
          <input type="text" name="image_path_existing" id="editStaffPath" class="form-control">
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
          <div class="form-group">
            <label class="form-label">Display Order</label>
            <input type="number" name="display_order" id="editStaffOrder" class="form-control">
          </div>
          <div class="form-group" style="display:flex; align-items:center; gap:0.5rem; margin-top:1.8rem;">
            <input type="checkbox" name="is_active" id="editStaffActive" value="1" style="width:18px; height:18px;">
            <label for="editStaffActive" class="form-label" style="margin:0; cursor:pointer;">Active</label>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('editFacultyModal')">Cancel</button>
        <button type="submit" class="btn btn-primary">Update Details</button>
      </div>
    </form>
  </div>
</div>

<script>
function editFaculty(st) {
  document.getElementById('editStaffId').value = st.id;
  document.getElementById('editStaffName').value = st.name;
  document.getElementById('editStaffDesig').value = st.designation;
  document.getElementById('editStaffQual').value = st.qualification;
  document.getElementById('editStaffPath').value = st.image_path;
  document.getElementById('editStaffOrder').value = st.display_order;
  document.getElementById('editStaffActive').checked = (st.is_active == 1);
  openModal('editFacultyModal');
}
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
