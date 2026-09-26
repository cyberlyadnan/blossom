<?php
/* =====================================================================
 |  BLOSSOM PUBLIC SCHOOL  —  CAMPUS FACILITIES & SAFETY MANAGER
 * ===================================================================== */

require_once __DIR__ . '/auth.php';
require_admin_auth();

$db = get_db();

// Handle Form Submissions
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (!csrf_valid($_POST['_token'] ?? null)) {
        flash_set('err', 'Invalid session token. Please try again.');
        header('Location: ' . url('admin/sections-facilities.php'));
        exit;
    }

    $formType = $_POST['form_type'] ?? '';

    // Update Facilities Headings
    if ($formType === 'update_headings') {
        if (isset($_POST['facilities_title'])) set_setting('facilities_title', trim((string) $_POST['facilities_title']), 'sections');
        if (isset($_POST['safety_title'])) set_setting('safety_title', trim((string) $_POST['safety_title']), 'sections');
        if (isset($_POST['safety_lede'])) set_setting('safety_lede', trim((string) $_POST['safety_lede']), 'sections');
        if (isset($_POST['transport_title'])) set_setting('transport_title', trim((string) $_POST['transport_title']), 'sections');
        if (isset($_POST['transport_lede'])) set_setting('transport_lede', trim((string) $_POST['transport_lede']), 'sections');

        flash_set('ok', 'Facilities and Safety headings updated successfully!');
        header('Location: ' . url('admin/sections-facilities.php'));
        exit;
    }

    // CRUD Facilities
    if ($formType === 'facilities') {
        $action = $_POST['action'] ?? '';
        if ($action === 'save') {
            $id = (int) ($_POST['id'] ?? 0);
            $cat = trim((string) ($_POST['category'] ?? 'campus'));
            $icon = trim((string) ($_POST['icon'] ?? 'laptop'));
            $title = trim((string) ($_POST['title'] ?? ''));
            $desc = trim((string) ($_POST['description'] ?? ''));
            $order = (int) ($_POST['display_order'] ?? 0);
            $active = isset($_POST['is_active']) ? 1 : 0;

            $imgPath = $_POST['existing_image'] ?? null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
                    $filename = 'fac_' . time() . '.' . $ext;
                    $target = __DIR__ . '/../assets/img/' . $filename;
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                        $imgPath = $filename;
                    }
                }
            }

            if ($title !== '') {
                if ($id > 0) {
                    $stmt = $db->prepare("UPDATE `facilities_list` SET `category` = ?, `icon` = ?, `title` = ?, `description` = ?, `image_path` = ?, `display_order` = ?, `is_active` = ? WHERE `id` = ?");
                    $stmt->execute([$cat, $icon, $title, $desc, $imgPath, $order, $active, $id]);
                    flash_set('ok', 'Facility item updated!');
                } else {
                    $stmt = $db->prepare("INSERT INTO `facilities_list` (`category`, `icon`, `title`, `description`, `image_path`, `display_order`, `is_active`) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$cat, $icon, $title, $desc, $imgPath, $order, $active]);
                    flash_set('ok', 'Facility item added!');
                }
            }
        } elseif ($action === 'delete') {
            $id = (int) ($_POST['id'] ?? 0);
            if ($id > 0) {
                $stmt = $db->prepare("DELETE FROM `facilities_list` WHERE `id` = ?");
                $stmt->execute([$id]);
                flash_set('ok', 'Facility deleted.');
            }
        }
        header('Location: ' . url('admin/sections-facilities.php'));
        exit;
    }
}

$admin_page_title = 'Campus Facilities & Safety';
require_once __DIR__ . '/header.php';

$facList = $db->query("SELECT * FROM `facilities_list` ORDER BY `display_order` ASC, `id` ASC")->fetchAll();
?>

<div class="card" style="margin-bottom:1.5rem;">
  <div style="margin-bottom:1rem;">
    <h2 style="font-size:1.4rem; font-weight:700; color:var(--adm-navy);">Campus Facilities &amp; Safety Manager</h2>
    <p style="color:var(--adm-text-muted); font-size:0.9rem;">Manage campus labs, sports area, safety infrastructure, transport bus fleet, and facility items.</p>
  </div>

  <form method="POST" action="" style="margin-bottom:1.5rem; border-bottom:1px solid #e2e8f0; padding-bottom:1.5rem;">
    <?= csrf_field() ?>
    <input type="hidden" name="form_type" value="update_headings">
    <h3 style="font-size:1.1rem; font-weight:700; color:var(--adm-navy); margin-bottom:1rem;">Facilities &amp; Safety Headings</h3>
    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap:1rem;">
      <div class="form-group">
        <label class="form-label">Campus Section Title</label>
        <input type="text" name="facilities_title" class="form-control" value="<?= e(get_setting('facilities_title', 'Built for learning, playing and growing up well')) ?>">
      </div>
      <div class="form-group">
        <label class="form-label">Safety Section Heading</label>
        <input type="text" name="safety_title" class="form-control" value="<?= e(get_setting('safety_title', 'Safety is not an afterthought')) ?>">
      </div>
      <div class="form-group">
        <label class="form-label">Safety Lede Text</label>
        <input type="text" name="safety_lede" class="form-control" value="<?= e(get_setting('safety_lede', 'CCTV coverage in corridors & buses, trained guards, and strict visitor verification.')) ?>">
      </div>
      <div class="form-group">
        <label class="form-label">Transport Section Heading</label>
        <input type="text" name="transport_title" class="form-control" value="<?= e(get_setting('transport_title', 'Safe & punctual bus routes across Saharanpur')) ?>">
      </div>
      <div class="form-group" style="grid-column: 1 / -1;">
        <label class="form-label">Transport Lede Text</label>
        <input type="text" name="transport_lede" class="form-control" value="<?= e(get_setting('transport_lede', 'GPS-tracked buses covering major hubs across the city with female attendants on primary routes.')) ?>">
      </div>
    </div>
    <button type="submit" class="btn btn-gold btn-sm mt-2">💾 Save Headings</button>
  </form>

  <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
    <h3 style="font-size:1.1rem; font-weight:700; color:var(--adm-navy);">Campus Facilities Items</h3>
    <button class="btn btn-gold btn-sm" onclick="openModal('addFacModal')"><?= icon('plus') ?> Add Facility</button>
  </div>

  <div class="grid-stats" style="grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));">
    <?php foreach ($facList as $fac): ?>
      <div class="card" style="padding:1rem;">
        <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:0.5rem;">
          <?php if ($fac['image_path']): ?>
            <img src="<?= e(asset('img/' . $fac['image_path'])) ?>" alt="Fac" style="width:50px; height:40px; object-fit:cover; border-radius:4px;">
          <?php else: ?>
            <span class="card__ico" style="width:36px; height:36px; font-size:0.9rem;"><?= icon($fac['icon']) ?></span>
          <?php endif; ?>
          <div>
            <strong style="font-size:1rem; color:var(--adm-navy);"><?= e($fac['title']) ?></strong>
            <div style="font-size:0.72rem; color:#0369a1; text-transform:uppercase; font-weight:700;"><?= e($fac['category']) ?></div>
          </div>
        </div>
        <p style="font-size:0.88rem; color:var(--adm-text-muted); line-height:1.4; margin-bottom:0.75rem;"><?= e($fac['description']) ?></p>
        <div style="display:flex; justify-content:flex-end; gap:0.35rem;">
          <button class="btn btn-outline btn-sm" onclick='editFac(<?= json_encode($fac, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'><?= icon('edit') ?></button>
          <form method="POST" action="" style="display:inline;" onsubmit="return confirm('Delete facility?')">
            <?= csrf_field() ?>
            <input type="hidden" name="form_type" value="facilities">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" value="<?= $fac['id'] ?>">
            <button class="btn btn-outline btn-sm" style="color:#ef4444; border-color:#fca5a5;">✕</button>
          </form>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- Modal: Add/Edit Facility -->
<div class="modal-overlay" id="addFacModal">
  <div class="modal-card">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
      <h3 style="font-size:1.2rem; font-weight:700; color:var(--adm-navy);" id="facModalTitle">Add Campus Facility</h3>
      <button style="background:none; border:none; font-size:1.2rem; cursor:pointer;" onclick="closeModal('addFacModal')">✕</button>
    </div>
    <form method="POST" action="" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <input type="hidden" name="form_type" value="facilities">
      <input type="hidden" name="action" value="save">
      <input type="hidden" name="id" id="fac_id" value="0">
      <input type="hidden" name="existing_image" id="fac_existing_image" value="">

      <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1rem;">
        <div class="form-group">
          <label class="form-label">Category</label>
          <select name="category" id="fac_cat" class="form-control">
            <option value="campus">Campus & Labs</option>
            <option value="safety">Safety & Security</option>
            <option value="transport">Transport & Fleet</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Icon Identifier</label>
          <select name="icon" id="fac_icon" class="form-control">
            <option value="laptop">Laptop / Computer Lab</option>
            <option value="book">Book / Library</option>
            <option value="trophy">Trophy / Sports</option>
            <option value="check">Checkmark / Security</option>
            <option value="phone">Phone / GPS</option>
            <option value="star">Star</option>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Facility Title</label>
        <input type="text" name="title" id="fac_title" class="form-control" required placeholder="e.g. Smart Computer Lab">
      </div>

      <div class="form-group">
        <label class="form-label">Description / Feature Details</label>
        <textarea name="description" id="fac_desc" class="form-control" rows="3" required></textarea>
      </div>

      <div class="form-group">
        <label class="form-label">Facility Photo (Optional)</label>
        <input type="file" name="image" class="form-control image-input-preview" data-preview="fac_preview_img" accept="image/*">
        <img id="fac_preview_img" src="" alt="Preview" style="max-height:80px; margin-top:0.5rem; display:none; border-radius:4px;">
      </div>

      <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1rem;">
        <div class="form-group">
          <label class="form-label">Display Order</label>
          <input type="number" name="display_order" id="fac_order" class="form-control" value="0">
        </div>
        <div class="form-group" style="display:flex; align-items:center; margin-top:1.5rem;">
          <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer;">
            <input type="checkbox" name="is_active" id="fac_active" value="1" checked> Active
          </label>
        </div>
      </div>

      <div style="display:flex; justify-content:flex-end; gap:0.5rem; margin-top:1rem;">
        <button type="button" class="btn btn-outline" onclick="closeModal('addFacModal')">Cancel</button>
        <button type="submit" class="btn btn-gold">💾 Save Facility</button>
      </div>
    </form>
  </div>
</div>

<script>
function editFac(fac) {
  document.getElementById('facModalTitle').innerText = 'Edit Campus Facility';
  document.getElementById('fac_id').value = fac.id;
  document.getElementById('fac_cat').value = fac.category || 'campus';
  document.getElementById('fac_icon').value = fac.icon || 'laptop';
  document.getElementById('fac_title').value = fac.title || '';
  document.getElementById('fac_desc').value = fac.description || '';
  document.getElementById('fac_existing_image').value = fac.image_path || '';
  document.getElementById('fac_order').value = fac.display_order || 0;
  document.getElementById('fac_active').checked = fac.is_active == 1;
  openModal('addFacModal');
}
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
