<?php
require_once __DIR__ . '/includes/config.php';
$page_title = 'Gallery';
$page_desc  = 'Photographs of campus life, events, sport and activities at ' . SCHOOL_NAME . ', ' . SCHOOL_CITY . '.';
require_once __DIR__ . '/includes/header.php';

/* ---------------------------------------------------------------------
 |  TO ADD REAL PHOTOS: drop image files into  assets/img/gallery/
 |  and add a row below — ['file.jpg', 'Caption', 'category'].
 |  Categories must match one of the filter keys in $cats.
 * ------------------------------------------------------------------- */
$cats = [
    'all'          => 'All Photos',
    'events'       => 'Events & Celebrations',
    'campus'       => 'Campus & Architecture',
    'classroom'    => 'Classrooms & Learning',
    'achievements' => 'Achievements & Awards',
    'activities'   => 'Activities & Environment',
];

try {
    $db = get_db();
    $stmt = $db->query("SELECT `image_path`, `title`, `category` FROM `gallery` WHERE `is_active` = 1 ORDER BY `display_order` ASC, `id` DESC");
    $dbPhotos = $stmt->fetchAll();
    $photos = [];
    foreach ($dbPhotos as $dp) {
        $photos[] = [$dp['image_path'], $dp['title'], $dp['category']];
    }
} catch (Exception $e) {
    $photos = [];
}
if (empty($photos)) {
    $photos = [
        ['principal.jpg', 'Principal\'s Office — Seated at Desk', 'achievements'],
        ['achievers.jpg', 'Principal Felicitating Student Achievers', 'achievements'],
    ];
}
?>

<section class="page-hero">
  <div class="wrap page-hero__in">
    <nav class="crumbs" aria-label="Breadcrumb"><a href="<?= e(url('index')) ?>">Home</a><span>/</span>Gallery</nav>
    <h1>Life at <?= e(SCHOOL_SHORT) ?></h1>
    <p>Ordinary days and big occasions — the assembly ground, the science lab, the stage and the field.</p>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <div class="filters" data-reveal>
      <?php foreach ($cats as $key => $label): ?>
        <button data-filter="<?= e($key) ?>" class="<?= $key === 'all' ? 'is-active' : '' ?>"><?= e($label) ?></button>
      <?php endforeach; ?>
    </div>

    <div class="gal" data-reveal>
      <?php foreach ($photos as $i => $p): ?>
        <button class="gal__item" data-cat="<?= e($p[2]) ?>" data-caption="<?= e($p[1]) ?>" aria-label="View: <?= e($p[1]) ?>">
          <?= media($p[0], $p[1], ($i % 8) + 1, '', '4/3') ?>
        </button>
      <?php endforeach; ?>
    </div>

    <div class="panel panel--cream mt-4 center" data-reveal>
      <h3>More photographs, every week</h3>
      <p class="mt-2" style="font-size:.95rem">
        We post assemblies, competitions and classroom moments on Instagram as they happen.
      </p>
      <div class="btn-row mt-3" style="justify-content:center">
        <a class="btn btn--gold" href="<?= e(SOCIAL_INSTAGRAM) ?>" target="_blank" rel="noopener"><?= icon('instagram') ?> Follow on Instagram</a>
        <a class="btn btn--ghost" href="<?= e(url('facilities')) ?>">Tour the Facilities</a>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
