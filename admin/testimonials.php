<?php
/* =====================================================================
 |  BLOSSOM PUBLIC SCHOOL  —  ADMIN TESTIMONIALS MANAGEMENT
 * ===================================================================== */

declare(strict_types=1);

$admin_page_title = 'Parent Reviews & Testimonials';
require_once __DIR__ . '/header.php';

$db = get_db();

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (!csrf_valid($_POST['_token'] ?? null)) {
        flash_set('err', 'Invalid session token. Please try again.');
        header('Location: ' . url('admin/testimonials.php'));
        exit;
    }

    $action = $_POST['action'] ?? '';

    if ($action === 'add' || $action === 'edit') {
        $quote       = trim((string) ($_POST['quote'] ?? ''));
        $authorName  = trim((string) ($_POST['author_name'] ?? ''));
        $authorTitle = trim((string) ($_POST['author_title'] ?? ''));
        $rating      = (int) ($_POST['rating'] ?? 5);
        $isActive    = isset($_POST['is_active']) ? 1 : 0;
        $order       = (int) ($_POST['display_order'] ?? 0);
        $id          = (int) ($_POST['id'] ?? 0);

        if ($quote === '' || $authorName === '') {
            flash_set('err', 'Review quote and author name are required.');
            header('Location: ' . url('admin/testimonials.php'));
            exit;
        }

        if ($action === 'add') {
            $stmt = $db->prepare("INSERT INTO `testimonials` (`quote`, `author_name`, `author_title`, `rating`, `is_active`, `display_order`) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$quote, $authorName, $authorTitle, $rating, $isActive, $order]);
            flash_set('ok', 'Parent testimonial added!');
        } else {
            $stmt = $db->prepare("UPDATE `testimonials` SET `quote` = ?, `author_name` = ?, `author_title` = ?, `rating` = ?, `is_active` = ?, `display_order` = ? WHERE `id` = ?");
            $stmt->execute([$quote, $authorName, $authorTitle, $rating, $isActive, $order, $id]);
            flash_set('ok', 'Parent testimonial updated!');
        }

        header('Location: ' . url('admin/testimonials.php'));
        exit;
    }

    if ($action === 'delete') {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0) {
            $stmt = $db->prepare("DELETE FROM `testimonials` WHERE `id` = ?");
            $stmt->execute([$id]);
            flash_set('ok', 'Review deleted.');
        }
        header('Location: ' . url('admin/testimonials.php'));
        exit;
    }
}

$stmt = $db->query("SELECT * FROM `testimonials` ORDER BY `display_order` ASC, `id` DESC");
$testimonials = $stmt->fetchAll();
?>

<!-- Header Actions -->
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem; flex-wrap:wrap; gap:1rem;">
  <div>
    <h2 style="font-size:1.4rem; font-weight:700; color:var(--adm-navy);">Parent Testimonials &amp; Reviews</h2>
    <p style="color:var(--adm-text-muted); font-size:0.9rem;">Manage feedback and parent quotes displayed on the homepage.</p>
  </div>
  <button class="btn btn-gold" onclick="openModal('addTestModal')">
    <span>➕ Add Testimonial</span>
  </button>
</div>

<!-- Testimonials Cards Grid -->
<div class="grid-stats" style="grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));">
  <?php foreach ($testimonials as $t): ?>
    <div class="card" style="display:flex; flex-direction:column; justify-content:space-between; padding:1.25rem;">
      <div>
        <div style="color:var(--adm-gold); margin-bottom:0.5rem; font-size:1.1rem;">
          <?= str_repeat('★', (int) $t['rating']) ?>
        </div>
        <p style="font-size:0.92rem; font-style:italic; line-height:1.4; color:var(--adm-text); margin-bottom:1rem;">
          “<?= e($t['quote']) ?>”
        </p>
      </div>

      <div style="border-top:1px solid var(--adm-border); padding-top:0.75rem; display:flex; align-items:center; justify-content:space-between;">
        <div>
          <strong style="font-size:0.9rem; color:var(--adm-navy);"><?= e($t['author_name']) ?></strong>
          <div style="font-size:0.78rem; color:var(--adm-text-muted);"><?= e($t['author_title']) ?></div>
        </div>

        <div style="display:flex; gap:0.35rem;">
          <button class="btn btn-outline btn-sm" onclick='editTest(<?= json_encode($t, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'>
            ✏️ Edit
          </button>

          <form method="POST" action="" style="display:inline;" onsubmit="return confirm('Delete review?')">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" value="<?= $t['id'] ?>">
            <button type="submit" class="btn btn-danger btn-sm">🗑️</button>
          </form>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<!-- Add Modal -->
<div class="modal-overlay" id="addTestModal">
  <div class="modal-container">
    <div class="modal-header">
      <h3 class="modal-title">💬 Add Parent Testimonial</h3>
      <button class="modal-close" onclick="closeModal('addTestModal')">&times;</button>
    </div>
    <form method="POST" action="">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="add">
      <div class="modal-body">
        <div class="form-group">
          <label class="form-label">Review Quote *</label>
          <textarea name="quote" class="form-control" placeholder="Enter parent review text..." required></textarea>
        </div>

        <div class="form-group">
          <label class="form-label">Parent Name *</label>
          <input type="text" name="author_name" class="form-control" placeholder="e.g. Mrs. Anita Sharma" required>
        </div>

        <div class="form-group">
          <label class="form-label">Parent Description / Title</label>
          <input type="text" name="author_title" class="form-control" placeholder="e.g. Parent of Riya (Class III)">
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
          <div class="form-group">
            <label class="form-label">Rating (Stars)</label>
            <select name="rating" class="form-control">
              <option value="5">5 Stars ★★★★★</option>
              <option value="4">4 Stars ★★★★☆</option>
              <option value="3">3 Stars ★★★☆☆</option>
            </select>
          </div>

          <div class="form-group" style="display:flex; align-items:center; gap:0.5rem; margin-top:1.8rem;">
            <input type="checkbox" name="is_active" value="1" checked id="addTestActive" style="width:18px; height:18px;">
            <label for="addTestActive" class="form-label" style="margin:0; cursor:pointer;">Published</label>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('addTestModal')">Cancel</button>
        <button type="submit" class="btn btn-gold">Save Review</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal-overlay" id="editTestModal">
  <div class="modal-container">
    <div class="modal-header">
      <h3 class="modal-title">✏️ Edit Parent Testimonial</h3>
      <button class="modal-close" onclick="closeModal('editTestModal')">&times;</button>
    </div>
    <form method="POST" action="">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="edit">
      <input type="hidden" name="id" id="editTestId">
      <div class="modal-body">
        <div class="form-group">
          <label class="form-label">Review Quote *</label>
          <textarea name="quote" id="editTestQuote" class="form-control" required></textarea>
        </div>

        <div class="form-group">
          <label class="form-label">Parent Name *</label>
          <input type="text" name="author_name" id="editTestName" class="form-control" required>
        </div>

        <div class="form-group">
          <label class="form-label">Parent Description / Title</label>
          <input type="text" name="author_title" id="editTestTitle" class="form-control">
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
          <div class="form-group">
            <label class="form-label">Rating</label>
            <select name="rating" id="editTestRating" class="form-control">
              <option value="5">5 Stars ★★★★★</option>
              <option value="4">4 Stars ★★★★☆</option>
              <option value="3">3 Stars ★★★☆☆</option>
            </select>
          </div>

          <div class="form-group" style="display:flex; align-items:center; gap:0.5rem; margin-top:1.8rem;">
            <input type="checkbox" name="is_active" value="1" id="editTestActive" style="width:18px; height:18px;">
            <label for="editTestActive" class="form-label" style="margin:0; cursor:pointer;">Published</label>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('editTestModal')">Cancel</button>
        <button type="submit" class="btn btn-primary">Update Review</button>
      </div>
    </form>
  </div>
</div>

<script>
function editTest(t) {
  document.getElementById('editTestId').value = t.id;
  document.getElementById('editTestQuote').value = t.quote;
  document.getElementById('editTestName').value = t.author_name;
  document.getElementById('editTestTitle').value = t.author_title || '';
  document.getElementById('editTestRating').value = t.rating || 5;
  document.getElementById('editTestActive').checked = (t.is_active == 1);
  openModal('editTestModal');
}
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
