<?php
/* =====================================================================
 |  BLOSSOM PUBLIC SCHOOL  —  ACADEMIC WINGS & CURRICULUM MANAGER
 * ===================================================================== */

require_once __DIR__ . '/auth.php';
require_admin_auth();

$db = get_db();

// Handle Form Submissions
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (!csrf_valid($_POST['_token'] ?? null)) {
        flash_set('err', 'Invalid session token. Please try again.');
        header('Location: ' . url('admin/sections-academics.php'));
        exit;
    }

    $formType = $_POST['form_type'] ?? '';

    // Update Curriculum Headings
    if ($formType === 'update_headings') {
        if (isset($_POST['curriculum_title'])) set_setting('curriculum_title', trim((string) $_POST['curriculum_title']), 'sections');
        if (isset($_POST['curriculum_lede'])) set_setting('curriculum_lede', trim((string) $_POST['curriculum_lede']), 'sections');
        if (isset($_POST['curriculum_p2'])) set_setting('curriculum_p2', trim((string) $_POST['curriculum_p2']), 'sections');

        flash_set('ok', 'Curriculum approach texts updated successfully!');
        header('Location: ' . url('admin/sections-academics.php'));
        exit;
    }

    // CRUD Wings
    if ($formType === 'wings') {
        $action = $_POST['action'] ?? '';
        if ($action === 'save') {
            $id = (int) ($_POST['id'] ?? 0);
            $wingKey = trim((string) ($_POST['wing_key'] ?? ''));
            $name = trim((string) ($_POST['name'] ?? ''));
            $range = trim((string) ($_POST['class_range'] ?? ''));
            $lede = trim((string) ($_POST['lede'] ?? ''));
            $points = trim((string) ($_POST['points'] ?? ''));
            $order = (int) ($_POST['display_order'] ?? 0);
            $active = isset($_POST['is_active']) ? 1 : 0;

            $imgPath = $_POST['existing_image'] ?? 'primary.jpg';
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
                    $filename = 'wing_' . time() . '.' . $ext;
                    $target = __DIR__ . '/../assets/img/' . $filename;
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                        $imgPath = $filename;
                    }
                }
            }

            if ($name !== '') {
                if ($id > 0) {
                    $stmt = $db->prepare("UPDATE `academic_wings` SET `wing_key` = ?, `name` = ?, `class_range` = ?, `image_path` = ?, `lede` = ?, `points` = ?, `display_order` = ?, `is_active` = ? WHERE `id` = ?");
                    $stmt->execute([$wingKey, $name, $range, $imgPath, $lede, $points, $order, $active, $id]);
                    flash_set('ok', 'Academic wing updated!');
                } else {
                    $stmt = $db->prepare("INSERT INTO `academic_wings` (`wing_key`, `name`, `class_range`, `image_path`, `lede`, `points`, `display_order`, `is_active`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$wingKey, $name, $range, $imgPath, $lede, $points, $order, $active]);
                    flash_set('ok', 'Academic wing added!');
                }
            }
        } elseif ($action === 'delete') {
            $id = (int) ($_POST['id'] ?? 0);
            if ($id > 0) {
                $stmt = $db->prepare("DELETE FROM `academic_wings` WHERE `id` = ?");
                $stmt->execute([$id]);
                flash_set('ok', 'Wing deleted.');
            }
        }
        header('Location: ' . url('admin/sections-academics.php'));
        exit;
    }
}

$admin_page_title = 'Academic Wings & Curriculum';
require_once __DIR__ . '/header.php';

$wingsList = $db->query("SELECT * FROM `academic_wings` ORDER BY `display_order` ASC, `id` ASC")->fetchAll();
?>

<div class="card" style="margin-bottom:1.5rem;">
  <div style="margin-bottom:1rem;">
    <h2 style="font-size:1.4rem; font-weight:700; color:var(--adm-navy);">Academic Wings &amp; Curriculum Manager</h2>
    <p style="color:var(--adm-text-muted); font-size:0.9rem;">Manage Foundational, Preparatory & Middle wing cards and syllabus philosophy texts.</p>
  </div>

  <form method="POST" action="" style="margin-bottom:1.5rem; border-bottom:1px solid #e2e8f0; padding-bottom:1.5rem;">
    <?= csrf_field() ?>
    <input type="hidden" name="form_type" value="update_headings">
    <h3 style="font-size:1.1rem; font-weight:700; color:var(--adm-navy); margin-bottom:1rem;">Curriculum Approach Content</h3>
    <div style="display:grid; grid-template-columns: 1fr; gap:1rem;">
      <div class="form-group">
        <label class="form-label">Curriculum Section Heading</label>
        <input type="text" name="curriculum_title" class="form-control" value="<?= e(get_setting('curriculum_title', 'The syllabus is the floor, not the ceiling.')) ?>">
      </div>
      <div class="form-group">
        <label class="form-label">Approach Paragraph 1 (Lede)</label>
        <textarea name="curriculum_lede" class="form-control" rows="2"><?= e(get_setting('curriculum_lede', 'We follow the CBSE framework in full...')) ?></textarea>
      </div>
      <div class="form-group">
        <label class="form-label">Approach Paragraph 2</label>
        <textarea name="curriculum_p2" class="form-control" rows="2"><?= e(get_setting('curriculum_p2', 'Lessons are planned around a single question...')) ?></textarea>
      </div>
    </div>
    <button type="submit" class="btn btn-gold btn-sm mt-2">💾 Save Curriculum Content</button>
  </form>

  <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
    <h3 style="font-size:1.1rem; font-weight:700; color:var(--adm-navy);">The Three Academic Wings</h3>
    <button class="btn btn-gold btn-sm" onclick="openModal('addWingModal')"><?= icon('plus') ?> Add Wing</button>
  </div>

  <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap:1.25rem;">
    <?php foreach ($wingsList as $wing): ?>
      <div class="card" style="padding:1.25rem;">
        <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:0.75rem;">
          <img src="<?= e(asset('img/' . $wing['image_path'])) ?>" alt="Wing" style="width:60px; height:45px; object-fit:cover; border-radius:6px;">
          <div>
            <h4 style="font-size:1.05rem; font-weight:700; color:var(--adm-navy);"><?= e($wing['name']) ?></h4>
            <span style="font-size:0.75rem; background:#e0f2fe; color:#0369a1; padding:2px 6px; border-radius:4px; font-weight:600;"><?= e($wing['class_range']) ?></span>
          </div>
        </div>
        <p style="font-size:0.88rem; color:var(--adm-text-muted); line-height:1.4; margin-bottom:0.75rem;"><?= e($wing['lede']) ?></p>
        <div style="font-size:0.8rem; background:#f8fafc; padding:0.5rem; border-radius:6px; margin-bottom:0.75rem;">
          <strong>Key Features:</strong>
          <ul style="padding-left:1rem; margin-top:0.25rem; margin-bottom:0;">
            <?php foreach (explode("\n", trim($wing['points'])) as $pt): if (trim($pt) !== ''): ?>
              <li><?= e($pt) ?></li>
            <?php endif; endforeach; ?>
          </ul>
        </div>
        <div style="display:flex; justify-content:flex-end; gap:0.5rem;">
          <button class="btn btn-outline btn-sm" onclick='editWing(<?= json_encode($wing, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'><?= icon('edit') ?> Edit</button>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- Modal: Add/Edit Academic Wing -->
<div class="modal-overlay" id="addWingModal">
  <div class="modal-card">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
      <h3 style="font-size:1.2rem; font-weight:700; color:var(--adm-navy);" id="wingModalTitle">Add Academic Wing</h3>
      <button style="background:none; border:none; font-size:1.2rem; cursor:pointer;" onclick="closeModal('addWingModal')">✕</button>
    </div>
    <form method="POST" action="" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <input type="hidden" name="form_type" value="wings">
      <input type="hidden" name="action" value="save">
      <input type="hidden" name="id" id="wing_id" value="0">
      <input type="hidden" name="existing_image" id="wing_existing_image" value="primary.jpg">

      <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1rem;">
        <div class="form-group">
          <label class="form-label">Wing Key (Unique ID)</label>
          <input type="text" name="wing_key" id="wing_key" class="form-control" placeholder="e.g. foundational" required>
        </div>
        <div class="form-group">
          <label class="form-label">Wing Name</label>
          <input type="text" name="name" id="wing_name" class="form-control" placeholder="e.g. Foundational Wing" required>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Class Range / Subtitle</label>
        <input type="text" name="class_range" id="wing_range" class="form-control" placeholder="e.g. Nursery – Class II · Ages 3 to 7" required>
      </div>

      <div class="form-group">
        <label class="form-label">Short Lede Paragraph</label>
        <textarea name="lede" id="wing_lede" class="form-control" rows="2" required></textarea>
      </div>

      <div class="form-group">
        <label class="form-label">Key Features / Bullet Points (One per line)</label>
        <textarea name="points" id="wing_points" class="form-control" rows="4" placeholder="Play-based phonics & numbers&#10;No heavy bags or rote memorization&#10;Activity-first classroom design"></textarea>
      </div>

      <div class="form-group">
        <label class="form-label">Wing Photo</label>
        <input type="file" name="image" class="form-control image-input-preview" data-preview="wing_preview_img" accept="image/*">
        <img id="wing_preview_img" src="" alt="Preview" style="max-height:80px; margin-top:0.5rem; display:none; border-radius:4px;">
      </div>

      <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1rem;">
        <div class="form-group">
          <label class="form-label">Display Order</label>
          <input type="number" name="display_order" id="wing_order" class="form-control" value="0">
        </div>
        <div class="form-group" style="display:flex; align-items:center; margin-top:1.5rem;">
          <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer;">
            <input type="checkbox" name="is_active" id="wing_active" value="1" checked> Active
          </label>
        </div>
      </div>

      <div style="display:flex; justify-content:flex-end; gap:0.5rem; margin-top:1rem;">
        <button type="button" class="btn btn-outline" onclick="closeModal('addWingModal')">Cancel</button>
        <button type="submit" class="btn btn-gold">💾 Save Wing</button>
      </div>
    </form>
  </div>
</div>

<script>
function editWing(wing) {
  document.getElementById('wingModalTitle').innerText = 'Edit Academic Wing';
  document.getElementById('wing_id').value = wing.id;
  document.getElementById('wing_key').value = wing.wing_key || '';
  document.getElementById('wing_name').value = wing.name || '';
  document.getElementById('wing_range').value = wing.class_range || '';
  document.getElementById('wing_lede').value = wing.lede || '';
  document.getElementById('wing_points').value = wing.points || '';
  document.getElementById('wing_existing_image').value = wing.image_path || 'primary.jpg';
  document.getElementById('wing_order').value = wing.display_order || 0;
  document.getElementById('wing_active').checked = wing.is_active == 1;
  openModal('addWingModal');
}
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
