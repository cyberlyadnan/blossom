<?php
/* =====================================================================
 |  BLOSSOM PUBLIC SCHOOL  —  PAGE SECTIONS & CONTENT CRUD MANAGER
 * ===================================================================== */

require_once __DIR__ . '/auth.php';
require_admin_auth();

$db = get_db();

// Handle Form Submissions
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (!csrf_valid($_POST['_token'] ?? null)) {
        flash_set('err', 'Invalid session token. Please try again.');
        header('Location: ' . url('admin/sections.php'));
        exit;
    }

    $formType = $_POST['form_type'] ?? '';

    // 1. UPDATE SECTION HEADINGS & TEXT (Settings)
    if ($formType === 'update_headings') {
        $settingKeys = [
            'why_choose_title', 'why_choose_subtitle',
            'curriculum_title', 'curriculum_lede', 'curriculum_p2',
            'vision_title', 'vision_text', 'mission_text', 'promise_text',
            'values_title', 'values_subtitle',
            'facilities_title', 'safety_title', 'safety_lede',
            'transport_title', 'transport_lede'
        ];

        foreach ($settingKeys as $k) {
            if (isset($_POST[$k])) {
                set_setting($k, trim((string) $_POST[$k]), 'sections');
            }
        }
        flash_set('ok', 'Section headings and description texts updated successfully!');
        header('Location: ' . url('admin/sections.php'));
        exit;
    }

    // 2. CRUD WHY CHOOSE ITEMS
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
                    flash_set('ok', 'Why Choose item updated!');
                } else {
                    $stmt = $db->prepare("INSERT INTO `sections_why_choose` (`icon`, `title`, `description`, `display_order`, `is_active`) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$icon, $title, $desc, $order, $active]);
                    flash_set('ok', 'Why Choose item added!');
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
        header('Location: ' . url('admin/sections.php'));
        exit;
    }

    // 3. CRUD ACADEMIC WINGS
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

            // Handle image upload if provided
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
        header('Location: ' . url('admin/sections.php'));
        exit;
    }

    // 4. CRUD CORE VALUES
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
        header('Location: ' . url('admin/sections.php'));
        exit;
    }

    // 5. CRUD FACILITIES
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
        header('Location: ' . url('admin/sections.php'));
        exit;
    }
}

$admin_page_title = 'Page Sections & Content Manager';
require_once __DIR__ . '/header.php';

// Fetch Data
$whyChooseList = $db->query("SELECT * FROM `sections_why_choose` ORDER BY `display_order` ASC, `id` ASC")->fetchAll();
$wingsList     = $db->query("SELECT * FROM `academic_wings` ORDER BY `display_order` ASC, `id` ASC")->fetchAll();
$coreValuesList= $db->query("SELECT * FROM `core_values` ORDER BY `display_order` ASC, `id` ASC")->fetchAll();
$facList       = $db->query("SELECT * FROM `facilities_list` ORDER BY `display_order` ASC, `id` ASC")->fetchAll();
?>

<div class="tabs-container">
  <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem; flex-wrap:wrap; gap:1rem;">
    <div>
      <h2 style="font-size:1.4rem; font-weight:700; color:var(--adm-navy);">Page Sections &amp; Website Content Manager</h2>
      <p style="color:var(--adm-text-muted); font-size:0.9rem;">Manage Why Choose Us, Academic Wings, Curriculum, Core Values, Facilities, Safety &amp; Transport sections.</p>
    </div>
  </div>

  <div class="nav-tabs">
    <button type="button" class="tab-btn active" data-tab="tab-why"><?= icon('check') ?> <span>Why Choose Us</span></button>
    <button type="button" class="tab-btn" data-tab="tab-wings"><?= icon('book') ?> <span>Academic Wings &amp; Curriculum</span></button>
    <button type="button" class="tab-btn" data-tab="tab-values"><?= icon('heart') ?> <span>Vision, Mission &amp; Values</span></button>
    <button type="button" class="tab-btn" data-tab="tab-facilities"><?= icon('laptop') ?> <span>Campus Facilities</span></button>
  </div>

  <!-- TAB 1: WHY CHOOSE US -->
  <div class="tab-pane card" id="tab-why">
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
      <button type="submit" class="btn btn-gold btn-sm mt-2">💾 Save Section Headings</button>
    </form>

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
      <h3 style="font-size:1.1rem; font-weight:700; color:var(--adm-navy);">Why Choose Us Cards (6 Promises)</h3>
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

  <!-- TAB 2: ACADEMIC WINGS & CURRICULUM -->
  <div class="tab-pane card" id="tab-wings" style="display:none;">
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

  <!-- TAB 3: VISION, MISSION & CORE VALUES -->
  <div class="tab-pane card" id="tab-values" style="display:none;">
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

  <!-- TAB 4: CAMPUS FACILITIES -->
  <div class="tab-pane card" id="tab-facilities" style="display:none;">
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
          <label class="form-label">Safety Section Title</label>
          <input type="text" name="safety_title" class="form-control" value="<?= e(get_setting('safety_title', 'The part of a school no parent should have to ask twice about')) ?>">
        </div>
        <div class="form-group">
          <label class="form-label">Transport Section Title</label>
          <input type="text" name="transport_title" class="form-control" value="<?= e(get_setting('transport_title', 'Buses that run on time, on routes you can track')) ?>">
        </div>
      </div>
      <button type="submit" class="btn btn-gold btn-sm mt-2">💾 Save Facilities Headings</button>
    </form>

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
      <h3 style="font-size:1.1rem; font-weight:700; color:var(--adm-navy);">Campus Facilities, Safety &amp; Transport Items</h3>
      <button class="btn btn-gold btn-sm" onclick="openModal('addFacModal')"><?= icon('plus') ?> Add Facility</button>
    </div>

    <div class="grid-stats" style="grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));">
      <?php foreach ($facList as $f): ?>
        <div class="card" style="padding:1rem; display:flex; flex-direction:column; justify-content:space-between;">
          <div>
            <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:0.5rem;">
              <span style="font-size:0.7rem; text-transform:uppercase; font-weight:700; padding:2px 8px; border-radius:4px; background:#f1f5f9; color:#475569;"><?= e($f['category']) ?></span>
              <span style="font-size:0.75rem; color:#94a3b8;">Order: <?= (int)$f['display_order'] ?></span>
            </div>
            <strong style="font-size:1rem; color:var(--adm-navy);"><?= e($f['title']) ?></strong>
            <p style="font-size:0.88rem; color:var(--adm-text-muted); line-height:1.4; margin-top:0.3rem;"><?= e($f['description']) ?></p>
          </div>
          <div style="border-top:1px solid #e2e8f0; margin-top:0.75rem; padding-top:0.5rem; display:flex; justify-content:flex-end; gap:0.35rem;">
            <button class="btn btn-outline btn-sm" onclick='editFac(<?= json_encode($f, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'><?= icon('edit') ?></button>
            <form method="POST" action="" style="display:inline;" onsubmit="return confirm('Delete facility?')">
              <?= csrf_field() ?>
              <input type="hidden" name="form_type" value="facilities">
              <input type="hidden" name="action" value="delete">
              <input type="hidden" name="id" value="<?= $f['id'] ?>">
              <button class="btn btn-outline btn-sm" style="color:#ef4444; border-color:#fca5a5;">✕</button>
            </form>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- MODALS -->
<!-- 1. Add/Edit Why Choose -->
<div class="modal-overlay" id="whyModal">
  <div class="modal-content" style="max-width:500px;">
    <div class="modal-header">
      <h3 class="modal-title" id="whyModalTitle">Add Why Choose Item</h3>
      <button class="modal-close" onclick="closeModal('whyModal')">✕</button>
    </div>
    <form method="POST" action="">
      <?= csrf_field() ?>
      <input type="hidden" name="form_type" value="why_choose">
      <input type="hidden" name="action" value="save">
      <input type="hidden" name="id" id="why_id" value="0">
      <div class="modal-body">
        <div class="form-group">
          <label class="form-label">Icon Name</label>
          <input type="text" name="icon" id="why_icon" class="form-control" value="check" placeholder="e.g. users, bulb, shield, palette, laptop, heart" required>
        </div>
        <div class="form-group">
          <label class="form-label">Title</label>
          <input type="text" name="title" id="why_title" class="form-control" required>
        </div>
        <div class="form-group">
          <label class="form-label">Description</label>
          <textarea name="description" id="why_desc" class="form-control" rows="3" required></textarea>
        </div>
        <div class="form-group">
          <label class="form-label">Display Order</label>
          <input type="number" name="display_order" id="why_order" class="form-control" value="1">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('whyModal')">Cancel</button>
        <button type="submit" class="btn btn-gold">Save Item</button>
      </div>
    </form>
  </div>
</div>

<!-- 2. Add/Edit Wing -->
<div class="modal-overlay" id="wingModal">
  <div class="modal-content" style="max-width:550px;">
    <div class="modal-header">
      <h3 class="modal-title" id="wingModalTitle">Add Academic Wing</h3>
      <button class="modal-close" onclick="closeModal('wingModal')">✕</button>
    </div>
    <form method="POST" action="" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <input type="hidden" name="form_type" value="wings">
      <input type="hidden" name="action" value="save">
      <input type="hidden" name="id" id="wing_id" value="0">
      <input type="hidden" name="existing_image" id="wing_existing_image" value="primary.jpg">
      <div class="modal-body">
        <div class="form-group">
          <label class="form-label">Wing Key (unique)</label>
          <input type="text" name="wing_key" id="wing_key" class="form-control" placeholder="pre-primary, primary, middle" required>
        </div>
        <div class="form-group">
          <label class="form-label">Wing Name</label>
          <input type="text" name="name" id="wing_name" class="form-control" required>
        </div>
        <div class="form-group">
          <label class="form-label">Class Range</label>
          <input type="text" name="class_range" id="wing_range" class="form-control" placeholder="e.g. Class I to V" required>
        </div>
        <div class="form-group">
          <label class="form-label">Lede Description</label>
          <textarea name="lede" id="wing_lede" class="form-control" rows="2" required></textarea>
        </div>
        <div class="form-group">
          <label class="form-label">Feature Points (1 per line)</label>
          <textarea name="points" id="wing_points" class="form-control" rows="4" placeholder="Enter points..."></textarea>
        </div>
        <div class="form-group">
          <label class="form-label">Wing Image</label>
          <input type="file" name="image" class="form-control" accept="image/*">
        </div>
        <div class="form-group">
          <label class="form-label">Display Order</label>
          <input type="number" name="display_order" id="wing_order" class="form-control" value="1">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('wingModal')">Cancel</button>
        <button type="submit" class="btn btn-gold">Save Wing</button>
      </div>
    </form>
  </div>
</div>

<!-- 3. Add/Edit Core Value -->
<div class="modal-overlay" id="valModal">
  <div class="modal-content" style="max-width:500px;">
    <div class="modal-header">
      <h3 class="modal-title" id="valModalTitle">Add Habit Core Value</h3>
      <button class="modal-close" onclick="closeModal('valModal')">✕</button>
    </div>
    <form method="POST" action="">
      <?= csrf_field() ?>
      <input type="hidden" name="form_type" value="core_values">
      <input type="hidden" name="action" value="save">
      <input type="hidden" name="id" id="val_id" value="0">
      <div class="modal-body">
        <div class="form-group">
          <label class="form-label">Icon Name</label>
          <input type="text" name="icon" id="val_icon" class="form-control" value="shield" required>
        </div>
        <div class="form-group">
          <label class="form-label">Value Title</label>
          <input type="text" name="title" id="val_title" class="form-control" required>
        </div>
        <div class="form-group">
          <label class="form-label">Description / Habit</label>
          <textarea name="description" id="val_desc" class="form-control" rows="3" required></textarea>
        </div>
        <div class="form-group">
          <label class="form-label">Display Order</label>
          <input type="number" name="display_order" id="val_order" class="form-control" value="1">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('valModal')">Cancel</button>
        <button type="submit" class="btn btn-gold">Save Value</button>
      </div>
    </form>
  </div>
</div>

<!-- 4. Add/Edit Facility -->
<div class="modal-overlay" id="facModal">
  <div class="modal-content" style="max-width:520px;">
    <div class="modal-header">
      <h3 class="modal-title" id="facModalTitle">Add Facility / Safety Item</h3>
      <button class="modal-close" onclick="closeModal('facModal')">✕</button>
    </div>
    <form method="POST" action="" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <input type="hidden" name="form_type" value="facilities">
      <input type="hidden" name="action" value="save">
      <input type="hidden" name="id" id="fac_id" value="0">
      <input type="hidden" name="existing_image" id="fac_existing_image" value="">
      <div class="modal-body">
        <div class="form-group">
          <label class="form-label">Category</label>
          <select name="category" id="fac_cat" class="form-control">
            <option value="campus">On Campus Facilities</option>
            <option value="safety">Safety &amp; Care</option>
            <option value="transport">Transport</option>
            <option value="extras">And Also (Extras)</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Icon Name</label>
          <input type="text" name="icon" id="fac_icon" class="form-control" value="laptop" required>
        </div>
        <div class="form-group">
          <label class="form-label">Title</label>
          <input type="text" name="title" id="fac_title" class="form-control" required>
        </div>
        <div class="form-group">
          <label class="form-label">Description</label>
          <textarea name="description" id="fac_desc" class="form-control" rows="3" required></textarea>
        </div>
        <div class="form-group">
          <label class="form-label">Image (optional)</label>
          <input type="file" name="image" class="form-control" accept="image/*">
        </div>
        <div class="form-group">
          <label class="form-label">Display Order</label>
          <input type="number" name="display_order" id="fac_order" class="form-control" value="1">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('facModal')">Cancel</button>
        <button type="submit" class="btn btn-gold">Save Facility</button>
      </div>
    </form>
  </div>
</div>

<script>
function openModal(id) {
  document.getElementById(id).classList.add('active');
}
function closeModal(id) {
  document.getElementById(id).classList.remove('active');
}
function editWhy(item) {
  document.getElementById('whyModalTitle').innerText = 'Edit Why Choose Item';
  document.getElementById('why_id').value = item.id;
  document.getElementById('why_icon').value = item.icon;
  document.getElementById('why_title').value = item.title;
  document.getElementById('why_desc').value = item.description;
  document.getElementById('why_order').value = item.display_order;
  openModal('whyModal');
}
function editWing(item) {
  document.getElementById('wingModalTitle').innerText = 'Edit Academic Wing';
  document.getElementById('wing_id').value = item.id;
  document.getElementById('wing_key').value = item.wing_key;
  document.getElementById('wing_name').value = item.name;
  document.getElementById('wing_range').value = item.class_range;
  document.getElementById('wing_lede').value = item.lede;
  document.getElementById('wing_points').value = item.points;
  document.getElementById('wing_existing_image').value = item.image_path;
  document.getElementById('wing_order').value = item.display_order;
  openModal('wingModal');
}
function editVal(item) {
  document.getElementById('valModalTitle').innerText = 'Edit Core Value';
  document.getElementById('val_id').value = item.id;
  document.getElementById('val_icon').value = item.icon;
  document.getElementById('val_title').value = item.title;
  document.getElementById('val_desc').value = item.description;
  document.getElementById('val_order').value = item.display_order;
  openModal('valModal');
}
function editFac(item) {
  document.getElementById('facModalTitle').innerText = 'Edit Facility Item';
  document.getElementById('fac_id').value = item.id;
  document.getElementById('fac_cat').value = item.category;
  document.getElementById('fac_icon').value = item.icon;
  document.getElementById('fac_title').value = item.title;
  document.getElementById('fac_desc').value = item.description;
  document.getElementById('fac_existing_image').value = item.image_path || '';
  document.getElementById('fac_order').value = item.display_order;
  openModal('facModal');
}
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
