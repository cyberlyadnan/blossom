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
    'all'        => 'All Photos',
    'campus'     => 'Campus',
    'classroom'  => 'Classrooms',
    'events'     => 'Events',
    'sports'     => 'Sports',
    'activities' => 'Activities',
];

$photos = [
    ['gallery/campus-1.jpg',     'The main building',              'campus'],
    ['gallery/class-1.jpg',      'A morning lesson in progress',   'classroom'],
    ['gallery/event-1.jpg',      'Annual Day performance',         'events'],
    ['gallery/sports-1.jpg',     'Inter-house athletics meet',     'sports'],
    ['gallery/activity-1.jpg',   'Art and craft period',           'activities'],
    ['gallery/campus-2.jpg',     'Assembly ground',                'campus'],
    ['gallery/class-2.jpg',      'Science practical, Middle Wing', 'classroom'],
    ['gallery/event-2.jpg',      'Independence Day celebration',   'events'],
    ['gallery/sports-2.jpg',     'Sports Day march past',          'sports'],
    ['gallery/activity-2.jpg',   'Storytelling in the library',    'activities'],
    ['gallery/campus-3.jpg',     'The green corner',               'campus'],
    ['gallery/event-3.jpg',      'Prize distribution ceremony',    'events'],
];
?>

<section class="page-hero">
  <div class="wrap page-hero__in">
    <nav class="crumbs" aria-label="Breadcrumb"><a href="<?= e(url('index.php')) ?>">Home</a><span>/</span>Gallery</nav>
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
        <a class="btn btn--ghost" href="<?= e(url('facilities.php')) ?>">Tour the Facilities</a>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
