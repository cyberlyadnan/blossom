<?php
/* =====================================================================
 |  BLOSSOM PUBLIC SCHOOL  —  ADMIN GALLERY MANAGEMENT
 * ===================================================================== */

declare(strict_types=1);

require_once __DIR__ . '/auth.php';
require_admin_auth();

$cats = [
    'events'       => 'Events & Celebrations',
    'campus'       => 'Campus & Architecture',
    'classroom'    => 'Classrooms & Learning',
    'achievements' => 'Achievements & Awards',
    'activities'   => 'Activities & Environment',
];

$db = get_db();

/* Handle Form Actions BEFORE any HTML output */
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $action    = $_POST['action'] ?? '';
    $filterCat = trim((string) ($_POST['current_cat'] ?? 'all'));
    $isAjax    = !empty($_POST['ajax']) || (strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest');
    $redirectUrl = url('admin/gallery.php' . ($filterCat !== 'all' ? '?cat=' . urlencode($filterCat) : ''));

    if (!csrf_valid($_POST['_token'] ?? null)) {
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Invalid session token']);
            exit;
        }
        flash_set('err', 'Invalid session token. Please try again.');
        header('Location: ' . $redirectUrl);
        exit;
    }

    if ($action === 'add' || $action === 'edit') {
        $title    = trim((string) ($_POST['title'] ?? ''));
        $category = trim((string) ($_POST['category'] ?? 'campus'));
        $order    = (int) ($_POST['display_order'] ?? 0);
        $isActive = isset($_POST['is_active']) ? 1 : 0;
        $id       = (int) ($_POST['id'] ?? 0);

        if ($title === '') {
            flash_set('err', 'Photo title is required.');
            header('Location: ' . $redirectUrl);
            exit;
        }

        $imagePath = trim((string) ($_POST['image_path_existing'] ?? ''));

        // Handle File Upload if provided
        if (isset($_FILES['photo_file']) && $_FILES['photo_file']['error'] === UPLOAD_ERR_OK) {
            $tmpPath = $_FILES['photo_file']['tmp_name'];
            $origName = basename($_FILES['photo_file']['name']);
            $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));

            $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
            if (in_array($ext, $allowed, true)) {
                $targetDir = __DIR__ . '/../assets/img/gallery/';
                if (!is_dir($targetDir)) {
                    @mkdir($targetDir, 0775, true);
                }
                $newFileName = 'upload_' . time() . '_' . preg_replace('/[^a-z0-9_-]/i', '_', pathinfo($origName, PATHINFO_FILENAME)) . '.' . $ext;
                $targetPath = $targetDir . $newFileName;
                
                if (move_uploaded_file($tmpPath, $targetPath)) {
                    $imagePath = 'gallery/' . $newFileName;
                }
            }
        }

        if ($imagePath === '') {
            $imagePath = 'gallery/campus-courtyard.jpg';
        }

        if ($action === 'add') {
            $stmt = $db->prepare("INSERT INTO `gallery` (`title`, `image_path`, `category`, `display_order`, `is_active`) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$title, $imagePath, $category, $order, $isActive]);
            flash_set('ok', 'New photo successfully added to gallery!');
        } else {
            $stmt = $db->prepare("UPDATE `gallery` SET `title` = ?, `image_path` = ?, `category` = ?, `display_order` = ?, `is_active` = ? WHERE `id` = ?");
            $stmt->execute([$title, $imagePath, $category, $order, $isActive, $id]);
            flash_set('ok', 'Gallery photo successfully updated!');
        }

        header('Location: ' . $redirectUrl);
        exit;
    }

    if ($action === 'delete') {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0) {
            $stmt = $db->prepare("DELETE FROM `gallery` WHERE `id` = ?");
            $stmt->execute([$id]);
            flash_set('ok', 'Photo deleted from gallery.');
        }
        header('Location: ' . $redirectUrl);
        exit;
    }

    if ($action === 'toggle') {
        $id = (int) ($_POST['id'] ?? 0);
        $newActive = 0;
        if ($id > 0) {
            $stmt = $db->prepare("UPDATE `gallery` SET `is_active` = IF(`is_active` = 1, 0, 1) WHERE `id` = ?");
            $stmt->execute([$id]);

            $checkStmt = $db->prepare("SELECT `is_active` FROM `gallery` WHERE `id` = ?");
            $checkStmt->execute([$id]);
            $newActive = (int) $checkStmt->fetchColumn();

            flash_set('ok', $newActive ? 'Photo is now published on the website.' : 'Photo is now hidden from the website.');
        }

        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'is_active' => $newActive, 'id' => $id]);
            exit;
        }

        header('Location: ' . $redirectUrl);
        exit;
    }
}

// Fetch total count and per-category counts
$catCounts = [];
$totalCount = (int) $db->query("SELECT COUNT(*) FROM `gallery`")->fetchColumn();
$stmtCounts = $db->query("SELECT `category`, COUNT(*) as cnt FROM `gallery` GROUP BY `category`")->fetchAll();
foreach ($stmtCounts as $row) {
    $catCounts[$row['category']] = (int) $row['cnt'];
}

// Fetch photos with optional category filter
$filterCat = trim((string) ($_GET['cat'] ?? 'all'));
if ($filterCat !== 'all' && isset($cats[$filterCat])) {
    $stmt = $db->prepare("SELECT * FROM `gallery` WHERE `category` = ? ORDER BY `display_order` ASC, `id` DESC");
    $stmt->execute([$filterCat]);
} else {
    $stmt = $db->query("SELECT * FROM `gallery` ORDER BY `display_order` ASC, `id` DESC");
}
$photos = $stmt->fetchAll();

$admin_page_title = 'Gallery Management';
require_once __DIR__ . '/header.php';
?>

<!-- Header Actions -->
<div style="display:flex; justify-space-between; align-items:center; margin-bottom:1.5rem; flex-wrap:wrap; gap:1rem;">
  <div>
    <h2 style="font-size:1.4rem; font-weight:700; color:var(--adm-navy);">Manage Gallery Photos</h2>
    <p style="color:var(--adm-text-muted); font-size:0.9rem;">Add, edit captions, upload photos, change categories or toggle visibility.</p>
  </div>
  <button class="btn btn-gold" onclick="openModal('addPhotoModal')">
    <span>➕ Add New Photo</span>
  </button>
</div>

<!-- Filter Tabs -->
<div style="display:flex; gap:0.5rem; flex-wrap:wrap; margin-bottom:1.5rem;">
  <a href="<?= e(url('admin/gallery.php?cat=all')) ?>" class="btn btn-sm <?= $filterCat === 'all' ? 'btn-primary' : 'btn-outline' ?>">
    All Photos (<?= $totalCount ?>)
  </a>
  <?php foreach ($cats as $key => $label): 
    $cnt = $catCounts[$key] ?? 0;
  ?>
    <a href="<?= e(url('admin/gallery.php?cat=' . $key)) ?>" class="btn btn-sm <?= $filterCat === $key ? 'btn-primary' : 'btn-outline' ?>">
      <?= e($label) ?> (<?= $cnt ?>)
    </a>
  <?php endforeach; ?>
</div>

<!-- Photos Grid -->
<?php if (empty($photos)): ?>
  <div class="card" style="text-align:center; padding:3rem;">
    <div style="font-size:2.5rem; margin-bottom:0.5rem;">🖼️</div>
    <h3>No photos found in this category.</h3>
    <p class="mt-2" style="color:var(--adm-text-muted)">Click "Add New Photo" above to upload or add images.</p>
  </div>
<?php else: ?>
  <div class="adm-gallery-grid">
    <?php foreach ($photos as $ph): ?>
      <div class="adm-gallery-card" id="galleryCard-<?= $ph['id'] ?>">
        <div style="position:relative;">
          <?= media($ph['image_path'], $ph['title'], 1, '', '4/3') ?>
          <span class="badge badge-<?= $ph['is_active'] ? 'active' : 'inactive' ?>" id="statusBadge-<?= $ph['id'] ?>" style="position:absolute; top:10px; right:10px; box-shadow:0 2px 8px rgba(0,0,0,0.3); z-index:10;">
            <?= $ph['is_active'] ? 'Published' : 'Hidden' ?>
          </span>
        </div>
        
        <div class="adm-gallery-body">
          <div class="adm-gallery-title"><?= e($ph['title']) ?></div>
          
          <div style="font-size:0.75rem; color:var(--adm-text-muted); margin-bottom:0.6rem;">
            Cat: <strong><?= e($cats[$ph['category']] ?? $ph['category']) ?></strong> · Order: <?= $ph['display_order'] ?>
          </div>

          <div class="adm-gallery-footer">
            <button class="btn btn-outline btn-sm" onclick='editPhoto(<?= json_encode($ph, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'>
              ✏️ Edit
            </button>
            
            <form method="POST" action="" class="toggle-form" style="display:inline;" onsubmit="return togglePhotoAjax(event, <?= $ph['id'] ?>)">
              <?= csrf_field() ?>
              <input type="hidden" name="action" value="toggle">
              <input type="hidden" name="id" value="<?= $ph['id'] ?>">
              <input type="hidden" name="current_cat" value="<?= e($filterCat) ?>">
              <button type="submit" class="btn btn-outline btn-sm toggle-btn" id="toggleBtn-<?= $ph['id'] ?>" title="Toggle visibility">
                <?= $ph['is_active'] ? '👁️ Hide' : '✨ Show' ?>
              </button>
            </form>

            <form method="POST" action="" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this photo?')">
              <?= csrf_field() ?>
              <input type="hidden" name="action" value="delete">
              <input type="hidden" name="id" value="<?= $ph['id'] ?>">
              <input type="hidden" name="current_cat" value="<?= e($filterCat) ?>">
              <button type="submit" class="btn btn-danger btn-sm" title="Delete photo">🗑️</button>
            </form>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>


<!-- Add Photo Modal -->
<div class="modal-overlay" id="addPhotoModal">
  <div class="modal-container">
    <div class="modal-header">
      <h3 class="modal-title">🖼️ Add New Gallery Photo</h3>
      <button class="modal-close" onclick="closeModal('addPhotoModal')">&times;</button>
    </div>
    <form method="POST" action="" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="add">
      <input type="hidden" name="current_cat" value="<?= e($filterCat) ?>">
      <div class="modal-body">
        <div class="form-group">
          <label class="form-label">Photo Title / Caption *</label>
          <input type="text" name="title" class="form-control" placeholder="e.g. Annual Sports Meet 2026" required>
        </div>

        <div class="form-group">
          <label class="form-label">Category *</label>
          <select name="category" class="form-control" required>
            <?php foreach ($cats as $k => $lbl): ?>
              <option value="<?= e($k) ?>" <?= $filterCat === $k ? 'selected' : '' ?>><?= e($lbl) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Upload Image File</label>
          <input type="file" name="photo_file" class="form-control image-input-preview" accept="image/*" data-preview="addPreviewImg">
          <div class="form-hint">Accepted formats: JPG, PNG, WEBP, GIF. Saved in <code>assets/img/gallery/</code></div>
          <img id="addPreviewImg" src="" style="max-height:140px; margin-top:0.75rem; border-radius:8px; display:none;">
        </div>

        <div class="form-group">
          <label class="form-label">Or Specify Image Path Filename</label>
          <input type="text" name="image_path_existing" class="form-control" placeholder="e.g. gallery/school-event-01.jpg">
          <div class="form-hint">Leave blank if uploading a file above.</div>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
          <div class="form-group">
            <label class="form-label">Display Order</label>
            <input type="number" name="display_order" class="form-control" value="0">
          </div>
          <div class="form-group" style="display:flex; align-items:center; gap:0.5rem; margin-top:1.8rem;">
            <input type="checkbox" name="is_active" id="addActive" value="1" checked style="width:18px; height:18px;">
            <label for="addActive" class="form-label" style="margin:0; cursor:pointer;">Publish Immediately</label>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('addPhotoModal')">Cancel</button>
        <button type="submit" class="btn btn-gold">Save &amp; Add Photo</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Photo Modal -->
<div class="modal-overlay" id="editPhotoModal">
  <div class="modal-container">
    <div class="modal-header">
      <h3 class="modal-title">✏️ Edit Gallery Photo</h3>
      <button class="modal-close" onclick="closeModal('editPhotoModal')">&times;</button>
    </div>
    <form method="POST" action="" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="edit">
      <input type="hidden" name="id" id="editId">
      <input type="hidden" name="current_cat" value="<?= e($filterCat) ?>">
      <div class="modal-body">
        <div class="form-group">
          <label class="form-label">Photo Title / Caption *</label>
          <input type="text" name="title" id="editTitle" class="form-control" required>
        </div>

        <div class="form-group">
          <label class="form-label">Category *</label>
          <select name="category" id="editCategory" class="form-control" required>
            <?php foreach ($cats as $k => $lbl): ?>
              <option value="<?= e($k) ?>"><?= e($lbl) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Replace Image File (Optional)</label>
          <input type="file" name="photo_file" class="form-control image-input-preview" accept="image/*" data-preview="editPreviewImg">
        </div>

        <div class="form-group">
          <label class="form-label">Current Image Path</label>
          <input type="text" name="image_path_existing" id="editImagePath" class="form-control">
          <img id="editPreviewImg" src="" style="max-height:120px; margin-top:0.75rem; border-radius:8px; display:none;">
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
          <div class="form-group">
            <label class="form-label">Display Order</label>
            <input type="number" name="display_order" id="editOrder" class="form-control">
          </div>
          <div class="form-group" style="display:flex; align-items:center; gap:0.5rem; margin-top:1.8rem;">
            <input type="checkbox" name="is_active" id="editActive" value="1" style="width:18px; height:18px;">
            <label for="editActive" class="form-label" style="margin:0; cursor:pointer;">Published</label>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('editPhotoModal')">Cancel</button>
        <button type="submit" class="btn btn-primary">Update Photo</button>
      </div>
    </form>
  </div>
</div>

<script>
function editPhoto(ph) {
  document.getElementById('editId').value = ph.id;
  document.getElementById('editTitle').value = ph.title;
  document.getElementById('editCategory').value = ph.category;
  document.getElementById('editImagePath').value = ph.image_path;
  document.getElementById('editOrder').value = ph.display_order;
  document.getElementById('editActive').checked = (ph.is_active == 1);
  openModal('editPhotoModal');
}

function togglePhotoAjax(e, photoId) {
  // If user clicks normally without JS error, use fetch for instant toggle
  e.preventDefault();
  const form = e.target;
  const formData = new FormData(form);
  formData.append('ajax', '1');

  const btn = document.getElementById('toggleBtn-' + photoId);
  const badge = document.getElementById('statusBadge-' + photoId);
  if (btn) btn.disabled = true;

  fetch(form.action || window.location.href, {
    method: 'POST',
    body: formData,
    headers: { 'X-Requested-With': 'XMLHttpRequest' }
  })
  .then(res => res.json())
  .then(data => {
    if (data && data.success) {
      if (data.is_active == 1) {
        if (badge) {
          badge.className = 'badge badge-active';
          badge.innerText = 'Published';
        }
        if (btn) btn.innerHTML = '👁️ Hide';
      } else {
        if (badge) {
          badge.className = 'badge badge-inactive';
          badge.innerText = 'Hidden';
        }
        if (btn) btn.innerHTML = '✨ Show';
      }
    } else {
      // Fallback to normal form submit if json fails
      form.submit();
    }
  })
  .catch(err => {
    form.submit();
  })
  .finally(() => {
    if (btn) btn.disabled = false;
  });

  return false;
}
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
