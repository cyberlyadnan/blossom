<?php
/* =====================================================================
 |  BLOSSOM PUBLIC SCHOOL  —  VISION, MISSION & CORE VALUES MANAGER
 * ===================================================================== */

require_once __DIR__ . '/auth.php';
require_admin_auth();

$db = get_db();

// Handle Form Submissions
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (!csrf_valid($_POST['_token'] ?? null)) {
        flash_set('err', 'Invalid session token. Please try again.');
        header('Location: ' . url('admin/sections-values.php'));
        exit;
    }

    $formType = $_POST['form_type'] ?? '';

    // Update Vision/Mission & Headings
    if ($formType === 'update_headings') {
        if (isset($_POST['vision_text'])) set_setting('vision_text', trim((string) $_POST['vision_text']), 'sections');
        if (isset($_POST['mission_text'])) set_setting('mission_text', trim((string) $_POST['mission_text']), 'sections');
        if (isset($_POST['promise_text'])) set_setting('promise_text', trim((string) $_POST['promise_text']), 'sections');
        if (isset($_POST['values_title'])) set_setting('values_title', trim((string) $_POST['values_title']), 'sections');
        if (isset($_POST['values_subtitle'])) set_setting('values_subtitle', trim((string) $_POST['values_subtitle']), 'sections');

        flash_set('ok', 'Vision, Mission & Core Values text updated successfully!');
        header('Location: ' . url('admin/sections-values.php'));
        exit;
    }

    // CRUD Core Values
    if ($formType === 'core_values') {
        $action = $_POST['action'] ?? '';
        if ($action === 'save') {
            $id = (int) ($_POST['id'] ?? 0);
            $icon = trim((string) ($_POST['icon'] ?? 'heart'));
            $title = trim((string) ($_POST['title'] ?? ''));
            $desc = trim((string) ($_POST['description'] ?? ''));
            $order = (int) ($_POST['display_order'] ?? 0);
            $active = isset($_POST['is_active']) ? 1 : 0;

            if ($title !== '') {
                if ($id > 0) {
                    $stmt = $db->prepare("UPDATE `core_values` SET `icon` = ?, `title` = ?, `description` = ?, `display_order` = ?, `is_active` = ? WHERE `id` = ?");
                    $stmt->execute([$icon, $title, $desc, $order, $active, $id]);
                    flash_set('ok', 'Core Value updated!');
                } else {
                    $stmt = $db->prepare("INSERT INTO `core_values` (`icon`, `title`, `description`, `display_order`, `is_active`) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$icon, $title, $desc, $order, $active]);
                    flash_set('ok', 'Core Value added!');
                }
            }
        } elseif ($action === 'delete') {
            $id = (int) ($_POST['id'] ?? 0);
            if ($id > 0) {
                $stmt = $db->prepare("DELETE FROM `core_values` WHERE `id` = ?");
                $stmt->execute([$id]);
                flash_set('ok', 'Value deleted.');
            }
        }
        header('Location: ' . url('admin/sections-values.php'));
        exit;
    }
}

$admin_page_title = 'Vision, Mission & Core Values';
require_once __DIR__ . '/header.php';

$coreValuesList = $db->query("SELECT * FROM `core_values` ORDER BY `display_order` ASC, `id` ASC")->fetchAll();
?>

<div class="card" style="margin-bottom:1.5rem;">
  <div style="margin-bottom:1rem;">
    <h2 style="font-size:1.4rem; font-weight:700; color:var(--adm-navy);">Vision, Mission &amp; Core Values Manager</h2>
    <p style="color:var(--adm-text-muted); font-size:0.9rem;">Manage school vision, mission statement, school promise, and the 5 core habit values.</p>
  </div>

  <form method="POST" action="" style="margin-bottom:1.5rem; border-bottom:1px solid #e2e8f0; padding-bottom:1.5rem;">
    <?= csrf_field() ?>
    <input type="hidden" name="form_type" value="update_headings">
    <h3 style="font-size:1.1rem; font-weight:700; color:var(--adm-navy); margin-bottom:1rem;">Vision, Mission &amp; Promise Statements</h3>
    <div style="display:grid; grid-template-columns: 1fr; gap:1rem;">
      <div class="form-group">
        <label class="form-label">Vision Statement</label>
        <textarea name="vision_text" class="form-control" rows="2"><?= e(get_setting('vision_text', 'To be the school in Saharanpur that families choose...')) ?></textarea>
      </div>
      <div class="form-group">
        <label class="form-label">Mission Statement</label>
        <textarea name="mission_text" class="form-control" rows="2"><?= e(get_setting('mission_text', 'To deliver the CBSE curriculum with unusual care...')) ?></textarea>
      </div>
      <div class="form-group">
        <label class="form-label">Our Promise Statement</label>
        <textarea name="promise_text" class="form-control" rows="2"><?= e(get_setting('promise_text', 'That every child is known by name...')) ?></textarea>
      </div>
      <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap:1rem;">
        <div class="form-group">
          <label class="form-label">Core Values Section Title</label>
          <input type="text" name="values_title" class="form-control" value="<?= e(get_setting('values_title', 'The five values we teach as habits')) ?>">
        </div>
        <div class="form-group">
          <label class="form-label">Core Values Subtitle</label>
          <input type="text" name="values_subtitle" class="form-control" value="<?= e(get_setting('values_subtitle', 'Not posters on a wall — behaviours our house mentors look for, name and reward every week.')) ?>">
        </div>
      </div>
    </div>
    <button type="submit" class="btn btn-gold btn-sm mt-2">💾 Save Vision &amp; Values Text</button>
  </form>

  <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
    <h3 style="font-size:1.1rem; font-weight:700; color:var(--adm-navy);">The 5 Core Habit Values</h3>
    <button class="btn btn-gold btn-sm" onclick="openModal('addValModal')"><?= icon('plus') ?> Add Value</button>
  </div>

  <div class="grid-stats" style="grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));">
    <?php foreach ($coreValuesList as $cv): ?>
      <div class="card" style="padding:1rem;">
        <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:0.5rem;">
          <span class="card__ico" style="width:32px; height:32px; font-size:0.9rem;"><?= icon($cv['icon']) ?></span>
          <strong style="font-size:1rem; color:var(--adm-navy);"><?= e($cv['title']) ?></strong>
        </div>
        <p style="font-size:0.88rem; color:var(--adm-text-muted); line-height:1.4; margin-bottom:0.75rem;"><?= e($cv['description']) ?></p>
        <div style="display:flex; justify-content:flex-end; gap:0.35rem;">
          <button class="btn btn-outline btn-sm" onclick='editVal(<?= json_encode($cv, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'><?= icon('edit') ?></button>
          <form method="POST" action="" style="display:inline;" onsubmit="return confirm('Delete value?')">
            <?= csrf_field() ?>
            <input type="hidden" name="form_type" value="core_values">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" value="<?= $cv['id'] ?>">
            <button class="btn btn-outline btn-sm" style="color:#ef4444; border-color:#fca5a5;">✕</button>
          </form>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- Modal: Add/Edit Core Value -->
<div class="modal-overlay" id="addValModal">
  <div class="modal-card">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
      <h3 style="font-size:1.2rem; font-weight:700; color:var(--adm-navy);" id="valModalTitle">Add Core Value</h3>
      <button style="background:none; border:none; font-size:1.2rem; cursor:pointer;" onclick="closeModal('addValModal')">✕</button>
    </div>
    <form method="POST" action="">
      <?= csrf_field() ?>
      <input type="hidden" name="form_type" value="core_values">
      <input type="hidden" name="action" value="save">
      <input type="hidden" name="id" id="val_id" value="0">

      <div class="form-group">
        <label class="form-label">Icon Identifier</label>
        <select name="icon" id="val_icon" class="form-control">
          <option value="heart">Heart</option>
          <option value="check">Checkmark</option>
          <option value="book">Book</option>
          <option value="user">User</option>
          <option value="trophy">Trophy</option>
          <option value="star">Star</option>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">Value Name / Title</label>
        <input type="text" name="title" id="val_title" class="form-control" required placeholder="e.g. Courtesy & Respect">
      </div>
      <div class="form-group">
        <label class="form-label">Value Explanation</label>
        <textarea name="description" id="val_desc" class="form-control" rows="3" required></textarea>
      </div>
      <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1rem;">
        <div class="form-group">
          <label class="form-label">Display Order</label>
          <input type="number" name="display_order" id="val_order" class="form-control" value="0">
        </div>
        <div class="form-group" style="display:flex; align-items:center; margin-top:1.5rem;">
          <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer;">
            <input type="checkbox" name="is_active" id="val_active" value="1" checked> Active
          </label>
        </div>
      </div>
      <div style="display:flex; justify-content:flex-end; gap:0.5rem; margin-top:1rem;">
        <button type="button" class="btn btn-outline" onclick="closeModal('addValModal')">Cancel</button>
        <button type="submit" class="btn btn-gold">💾 Save Value</button>
      </div>
    </form>
  </div>
</div>

<script>
function editVal(val) {
  document.getElementById('valModalTitle').innerText = 'Edit Core Value';
  document.getElementById('val_id').value = val.id;
  document.getElementById('val_icon').value = val.icon || 'heart';
  document.getElementById('val_title').value = val.title || '';
  document.getElementById('val_desc').value = val.description || '';
  document.getElementById('val_order').value = val.display_order || 0;
  document.getElementById('val_active').checked = val.is_active == 1;
  openModal('addValModal');
}
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
