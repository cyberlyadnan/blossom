<?php
/* =====================================================================
 |  BLOSSOM PUBLIC SCHOOL  —  ADMIN NEWS, EVENTS & TICKER
 * ===================================================================== */

require_once __DIR__ . '/auth.php';
require_admin_auth();

$db = get_db();

// Handle Form Submissions
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (!csrf_valid($_POST['_token'] ?? null)) {
        flash_set('err', 'Invalid session token. Please try again.');
        header('Location: ' . url('admin/news.php'));
        exit;
    }

    $action = $_POST['action'] ?? '';

    if ($action === 'add' || $action === 'edit') {
        $title       = trim((string) ($_POST['title'] ?? ''));
        $category    = trim((string) ($_POST['category'] ?? 'Announcement'));
        $eventDay    = trim((string) ($_POST['event_day'] ?? ''));
        $eventMonth  = trim((string) ($_POST['event_month'] ?? ''));
        $content     = trim((string) ($_POST['content'] ?? ''));
        $isTicker    = isset($_POST['is_ticker']) ? 1 : 0;
        $isPublished = isset($_POST['is_published']) ? 1 : 0;
        $order       = (int) ($_POST['display_order'] ?? 0);
        $id          = (int) ($_POST['id'] ?? 0);

        if ($title === '') {
            flash_set('err', 'Title is required.');
            header('Location: ' . url('admin/news.php'));
            exit;
        }

        if ($action === 'add') {
            $stmt = $db->prepare("INSERT INTO `news_events` (`title`, `category`, `event_day`, `event_month`, `content`, `is_ticker`, `is_published`, `display_order`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$title, $category, $eventDay, $eventMonth, $content, $isTicker, $isPublished, $order]);
            flash_set('ok', 'News & event item added!');
        } else {
            $stmt = $db->prepare("UPDATE `news_events` SET `title` = ?, `category` = ?, `event_day` = ?, `event_month` = ?, `content` = ?, `is_ticker` = ?, `is_published` = ?, `display_order` = ? WHERE `id` = ?");
            $stmt->execute([$title, $category, $eventDay, $eventMonth, $content, $isTicker, $isPublished, $order, $id]);
            flash_set('ok', 'News item updated successfully!');
        }

        header('Location: ' . url('admin/news.php'));
        exit;
    }

    if ($action === 'delete') {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0) {
            $stmt = $db->prepare("DELETE FROM `news_events` WHERE `id` = ?");
            $stmt->execute([$id]);
            flash_set('ok', 'Item deleted.');
        }
        header('Location: ' . url('admin/news.php'));
        exit;
    }
}

$admin_page_title = 'News, Events & Ticker';
require_once __DIR__ . '/header.php';

$stmt = $db->query("SELECT * FROM `news_events` ORDER BY `display_order` ASC, `id` DESC");
$items = $stmt->fetchAll();
?>

<!-- Header Actions -->
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem; flex-wrap:wrap; gap:1rem;">
  <div>
    <h2 style="font-size:1.4rem; font-weight:700; color:var(--adm-navy);">News, Notices &amp; Ticker</h2>
    <p style="color:var(--adm-text-muted); font-size:0.9rem;">Manage school announcements, event dates, and scrolling ticker notices.</p>
  </div>
  <button class="btn btn-gold" onclick="openModal('addNewsModal')">
    <?= icon('plus') ?> <span>Add News / Event</span>
  </button>
</div>

<!-- News Table -->
<div class="card">
  <?php if (empty($items)): ?>
    <p style="text-align:center; padding:2rem; color:var(--adm-text-muted)">No news or events posted yet.</p>
  <?php else: ?>
    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th>Date</th>
            <th>Category</th>
            <th>Title &amp; Content</th>
            <th>Ticker Bar</th>
            <th>Status</th>
            <th style="text-align:right;">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($items as $item): ?>
            <tr>
              <td>
                <?php if ($item['event_day'] || $item['event_month']): ?>
                  <strong><?= e($item['event_day']) ?> <?= e($item['event_month']) ?></strong>
                <?php else: ?>
                  <span style="color:var(--adm-text-muted)">-</span>
                <?php endif; ?>
              </td>
              <td><span class="badge badge-category"><?= e($item['category']) ?></span></td>
              <td>
                <strong style="font-size:0.95rem;"><?= e($item['title']) ?></strong>
                <p style="font-size:0.82rem; color:var(--adm-text-muted); margin-top:0.2rem;"><?= e($item['content']) ?></p>
              </td>
              <td>
                <?php if ($item['is_ticker']): ?>
                  <span class="badge badge-replied">Shown on Ticker</span>
                <?php else: ?>
                  <span style="font-size:0.8rem; color:var(--adm-text-muted)">Off</span>
                <?php endif; ?>
              </td>
              <td>
                <span class="badge badge-<?= $item['is_published'] ? 'active' : 'inactive' ?>">
                  <?= $item['is_published'] ? 'Published' : 'Draft' ?>
                </span>
              </td>
              <td style="text-align:right; white-space:nowrap;">
                <button class="btn btn-outline btn-sm" onclick='editNews(<?= json_encode($item, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'>
                  <?= icon('edit') ?> <span>Edit</span>
                </button>

                <form method="POST" action="" style="display:inline;" onsubmit="return confirm('Delete news item?')">
                  <?= csrf_field() ?>
                  <input type="hidden" name="action" value="delete">
                  <input type="hidden" name="id" value="<?= $item['id'] ?>">
                  <button type="submit" class="btn btn-icon-danger btn-sm" title="Delete"><?= icon('trash') ?></button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

<!-- Add News Modal -->
<div class="modal-overlay" id="addNewsModal">
  <div class="modal-container">
    <div class="modal-header">
      <h3 class="modal-title"><?= icon('file') ?> Add News or Event</h3>
      <button class="modal-close" onclick="closeModal('addNewsModal')">&times;</button>
    </div>
    <form method="POST" action="">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="add">
      <div class="modal-body">
        <div class="form-group">
          <label class="form-label">Title *</label>
          <input type="text" name="title" class="form-control" placeholder="e.g. Admissions open for session 2026-27" required>
        </div>

        <div class="form-group">
          <label class="form-label">Category *</label>
          <select name="category" class="form-control" required>
            <option value="Announcement">Announcement</option>
            <option value="Event">Event</option>
            <option value="Achievement">Achievement</option>
            <option value="Notice">Notice</option>
          </select>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
          <div class="form-group">
            <label class="form-label">Event Day (e.g. 15)</label>
            <input type="text" name="event_day" class="form-control" placeholder="15">
          </div>
          <div class="form-group">
            <label class="form-label">Event Month (e.g. Aug)</label>
            <input type="text" name="event_month" class="form-control" placeholder="Aug">
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Content / Details</label>
          <textarea name="content" class="form-control" placeholder="Enter description or summary..."></textarea>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
          <div class="form-group" style="display:flex; align-items:center; gap:0.5rem; margin-top:0.5rem;">
            <input type="checkbox" name="is_ticker" value="1" id="addTicker" style="width:18px; height:18px;">
            <label for="addTicker" class="form-label" style="margin:0; cursor:pointer;">Show in Homepage Ticker</label>
          </div>
          <div class="form-group" style="display:flex; align-items:center; gap:0.5rem; margin-top:0.5rem;">
            <input type="checkbox" name="is_published" value="1" checked id="addPub" style="width:18px; height:18px;">
            <label for="addPub" class="form-label" style="margin:0; cursor:pointer;">Publish Immediately</label>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('addNewsModal')">Cancel</button>
        <button type="submit" class="btn btn-gold">Save News</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit News Modal -->
<div class="modal-overlay" id="editNewsModal">
  <div class="modal-container">
    <div class="modal-header">
      <h3 class="modal-title">✏️ Edit News / Event</h3>
      <button class="modal-close" onclick="closeModal('editNewsModal')">&times;</button>
    </div>
    <form method="POST" action="">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="edit">
      <input type="hidden" name="id" id="editNewsId">
      <div class="modal-body">
        <div class="form-group">
          <label class="form-label">Title *</label>
          <input type="text" name="title" id="editNewsTitle" class="form-control" required>
        </div>

        <div class="form-group">
          <label class="form-label">Category *</label>
          <select name="category" id="editNewsCat" class="form-control" required>
            <option value="Announcement">Announcement</option>
            <option value="Event">Event</option>
            <option value="Achievement">Achievement</option>
            <option value="Notice">Notice</option>
          </select>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
          <div class="form-group">
            <label class="form-label">Event Day</label>
            <input type="text" name="event_day" id="editNewsDay" class="form-control">
          </div>
          <div class="form-group">
            <label class="form-label">Event Month</label>
            <input type="text" name="event_month" id="editNewsMonth" class="form-control">
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Content / Details</label>
          <textarea name="content" id="editNewsContent" class="form-control"></textarea>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
          <div class="form-group" style="display:flex; align-items:center; gap:0.5rem; margin-top:0.5rem;">
            <input type="checkbox" name="is_ticker" value="1" id="editNewsTicker" style="width:18px; height:18px;">
            <label for="editNewsTicker" class="form-label" style="margin:0; cursor:pointer;">Show in Homepage Ticker</label>
          </div>
          <div class="form-group" style="display:flex; align-items:center; gap:0.5rem; margin-top:0.5rem;">
            <input type="checkbox" name="is_published" value="1" id="editNewsPub" style="width:18px; height:18px;">
            <label for="editNewsPub" class="form-label" style="margin:0; cursor:pointer;">Published</label>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('editNewsModal')">Cancel</button>
        <button type="submit" class="btn btn-primary">Update Item</button>
      </div>
    </form>
  </div>
</div>

<script>
function editNews(item) {
  document.getElementById('editNewsId').value = item.id;
  document.getElementById('editNewsTitle').value = item.title;
  document.getElementById('editNewsCat').value = item.category;
  document.getElementById('editNewsDay').value = item.event_day || '';
  document.getElementById('editNewsMonth').value = item.event_month || '';
  document.getElementById('editNewsContent').value = item.content || '';
  document.getElementById('editNewsTicker').checked = (item.is_ticker == 1);
  document.getElementById('editNewsPub').checked = (item.is_published == 1);
  openModal('editNewsModal');
}
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
