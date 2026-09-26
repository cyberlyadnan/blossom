<?php
/* =====================================================================
 |  BLOSSOM PUBLIC SCHOOL  —  WHY PARENTS CHOOSE US SECTION MANAGER
 * ===================================================================== */

require_once __DIR__ . '/auth.php';
require_admin_auth();

$db = get_db();

// Handle Form Submissions
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (!csrf_valid($_POST['_token'] ?? null)) {
        flash_set('err', 'Invalid session token. Please try again.');
        header('Location: ' . url('admin/sections-why.php'));
        exit;
    }

    $formType = $_POST['form_type'] ?? '';

    // Update Section Headings
    if ($formType === 'update_headings') {
        if (isset($_POST['why_choose_title'])) set_setting('why_choose_title', trim((string) $_POST['why_choose_title']), 'sections');
        if (isset($_POST['why_choose_subtitle'])) set_setting('why_choose_subtitle', trim((string) $_POST['why_choose_subtitle']), 'sections');
        
        flash_set('ok', 'Why Choose Us headings updated successfully!');
        header('Location: ' . url('admin/sections-why.php'));
        exit;
    }

    // CRUD Items
    if ($formType === 'why_choose') {
        $action = $_POST['action'] ?? '';
        if ($action === 'save') {
            $id = (int) ($_POST['id'] ?? 0);
            $icon = trim((string) ($_POST['icon'] ?? 'check'));
            $title = trim((string) ($_POST['title'] ?? ''));
            $desc = trim((string) ($_POST['description'] ?? ''));
            $order = (int) ($_POST['display_order'] ?? 0);
            $active = isset($_POST['is_active']) ? 1 : 0;

            if ($title !== '') {
                if ($id > 0) {
                    $stmt = $db->prepare("UPDATE `sections_why_choose` SET `icon` = ?, `title` = ?, `description` = ?, `display_order` = ?, `is_active` = ? WHERE `id` = ?");
                    $stmt->execute([$icon, $title, $desc, $order, $active, $id]);
                    flash_set('ok', 'Why Choose card updated!');
                } else {
                    $stmt = $db->prepare("INSERT INTO `sections_why_choose` (`icon`, `title`, `description`, `display_order`, `is_active`) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$icon, $title, $desc, $order, $active]);
                    flash_set('ok', 'Why Choose card added!');
                }
            }
        } elseif ($action === 'delete') {
            $id = (int) ($_POST['id'] ?? 0);
            if ($id > 0) {
                $stmt = $db->prepare("DELETE FROM `sections_why_choose` WHERE `id` = ?");
                $stmt->execute([$id]);
                flash_set('ok', 'Item deleted.');
            }
        }
        header('Location: ' . url('admin/sections-why.php'));
        exit;
    }
}

$admin_page_title = 'Why Parents Choose Us';
require_once __DIR__ . '/header.php';

$whyChooseList = $db->query("SELECT * FROM `sections_why_choose` ORDER BY `display_order` ASC, `id` ASC")->fetchAll();
?>

<div class="card" style="margin-bottom:1.5rem;">
  <div style="margin-bottom:1rem;">
    <h2 style="font-size:1.4rem; font-weight:700; color:var(--adm-navy);">Why Parents Choose Us (6 Promises)</h2>
    <p style="color:var(--adm-text-muted); font-size:0.9rem;">Manage section headings, lede paragraph, and edit all 6 core promise cards.</p>
  </div>

  <form method="POST" action="" style="margin-bottom:1.5rem; border-bottom:1px solid #e2e8f0; padding-bottom:1.5rem;">
    <?= csrf_field() ?>
    <input type="hidden" name="form_type" value="update_headings">
    <h3 style="font-size:1.1rem; font-weight:700; color:var(--adm-navy); margin-bottom:1rem;">Headline &amp; Subtitle</h3>
    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap:1rem;">
      <div class="form-group">
        <label class="form-label">Section Title</label>
        <input type="text" name="why_choose_title" class="form-control" value="<?= e(get_setting('why_choose_title', 'Six things we refuse to compromise on')) ?>">
      </div>
      <div class="form-group">
        <label class="form-label">Section Subtitle / Lede</label>
        <input type="text" name="why_choose_subtitle" class="form-control" value="<?= e(get_setting('why_choose_subtitle', 'Every promise below is something you can walk in and verify on any working day.')) ?>">
      </div>
    </div>
    <button type="submit" class="btn btn-gold btn-sm mt-2">💾 Save Headings</button>
  </form>

  <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
    <h3 style="font-size:1.1rem; font-weight:700; color:var(--adm-navy);">Why Choose Us Cards</h3>
    <button class="btn btn-gold btn-sm" onclick="openModal('addWhyModal')"><?= icon('plus') ?> Add Card</button>
  </div>

  <div class="grid-stats" style="grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));">
    <?php foreach ($whyChooseList as $w): ?>
      <div class="card" style="padding:1rem; display:flex; flex-direction:column; justify-content:space-between;">
        <div>
          <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:0.5rem;">
            <span class="card__ico" style="width:32px; height:32px; font-size:0.9rem;"><?= icon($w['icon']) ?></span>
            <strong style="font-size:1rem; color:var(--adm-navy);"><?= e($w['title']) ?></strong>
          </div>
          <p style="font-size:0.88rem; color:var(--adm-text-muted); line-height:1.4;"><?= e($w['description']) ?></p>
        </div>
        <div style="border-top:1px solid #e2e8f0; margin-top:0.75rem; padding-top:0.5rem; display:flex; justify-content:space-between; align-items:center;">
          <span style="font-size:0.75rem; color:#94a3b8;">Order: <?= (int)$w['display_order'] ?></span>
          <div style="display:flex; gap:0.35rem;">
            <button class="btn btn-outline btn-sm" onclick='editWhy(<?= json_encode($w, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'><?= icon('edit') ?></button>
            <form method="POST" action="" style="display:inline;" onsubmit="return confirm('Delete item?')">
              <?= csrf_field() ?>
              <input type="hidden" name="form_type" value="why_choose">
              <input type="hidden" name="action" value="delete">
              <input type="hidden" name="id" value="<?= $w['id'] ?>">
              <button class="btn btn-outline btn-sm" style="color:#ef4444; border-color:#fca5a5;">✕</button>
            </form>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- Modal: Add/Edit Why Choose Card -->
<div class="modal-overlay" id="addWhyModal">
  <div class="modal-card">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
      <h3 style="font-size:1.2rem; font-weight:700; color:var(--adm-navy);" id="whyModalTitle">Add Why Choose Card</h3>
      <button style="background:none; border:none; font-size:1.2rem; cursor:pointer;" onclick="closeModal('addWhyModal')">✕</button>
    </div>
    <form method="POST" action="">
      <?= csrf_field() ?>
      <input type="hidden" name="form_type" value="why_choose">
      <input type="hidden" name="action" value="save">
      <input type="hidden" name="id" id="why_id" value="0">

      <div class="form-group">
        <label class="form-label">Icon Identifier (SVG icon)</label>
        <select name="icon" id="why_icon" class="form-control">
          <option value="check">Checkmark</option>
          <option value="heart">Heart</option>
          <option value="book">Book</option>
          <option value="user">User / Mentor</option>
          <option value="phone">Phone / Support</option>
          <option value="laptop">Laptop / Tech</option>
          <option value="trophy">Trophy / Award</option>
          <option value="star">Star</option>
          <option value="globe">Globe</option>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">Card Title</label>
        <input type="text" name="title" id="why_title" class="form-control" required placeholder="e.g. Clean & Safe Campus">
      </div>
      <div class="form-group">
        <label class="form-label">Card Description</label>
        <textarea name="description" id="why_desc" class="form-control" rows="3" required></textarea>
      </div>
      <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1rem;">
        <div class="form-group">
          <label class="form-label">Display Order</label>
          <input type="number" name="display_order" id="why_order" class="form-control" value="0">
        </div>
        <div class="form-group" style="display:flex; align-items:center; margin-top:1.5rem;">
          <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer;">
            <input type="checkbox" name="is_active" id="why_active" value="1" checked> Active
          </label>
        </div>
      </div>
      <div style="display:flex; justify-content:flex-end; gap:0.5rem; margin-top:1rem;">
        <button type="button" class="btn btn-outline" onclick="closeModal('addWhyModal')">Cancel</button>
        <button type="submit" class="btn btn-gold">💾 Save Card</button>
      </div>
    </form>
  </div>
</div>

<script>
function editWhy(item) {
  document.getElementById('whyModalTitle').innerText = 'Edit Why Choose Card';
  document.getElementById('why_id').value = item.id;
  document.getElementById('why_icon').value = item.icon || 'check';
  document.getElementById('why_title').value = item.title || '';
  document.getElementById('why_desc').value = item.description || '';
  document.getElementById('why_order').value = item.display_order || 0;
  document.getElementById('why_active').checked = item.is_active == 1;
  openModal('addWhyModal');
}
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
