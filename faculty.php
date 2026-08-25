<?php
require_once __DIR__ . '/includes/config.php';
$page_title = 'Our Faculty';
$page_desc  = 'The teaching team at ' . SCHOOL_NAME . ' — qualifications, training and the way we teach.';
require_once __DIR__ . '/includes/header.php';

/* Replace the names and subjects below with your actual staff.
   Add a photo at assets/img/staff/<file> and it will be used automatically. */
$staff = [
    ['staff/principal.jpg', 'Principal',            'School Leadership',        'M.A., B.Ed. · 20+ years in school education'],
    ['staff/vice.jpg',      'Vice Principal',       'Academic Supervision',     'M.Sc., B.Ed. · Curriculum and assessment'],
    ['staff/coord-pre.jpg', 'Pre-Primary Coordinator', 'Nursery to UKG',        'M.A., NTT · Early childhood specialist'],
    ['staff/coord-pri.jpg', 'Primary Coordinator',  'Class I to V',             'M.A., B.Ed. · Language and reading'],
    ['staff/coord-mid.jpg', 'Middle Wing Coordinator', 'Class VI to VIII',      'M.Sc., B.Ed. · Science and mathematics'],
    ['staff/counsellor.jpg','Student Counsellor',   'Wellbeing & Guidance',     'M.A. Psychology · Child counselling'],
];
?>

<section class="page-hero">
  <div class="wrap page-hero__in">
    <nav class="crumbs" aria-label="Breadcrumb"><a href="<?= e(url('index.php')) ?>">Home</a><span>/</span><a href="<?= e(url('about.php')) ?>">About</a><span>/</span>Faculty</nav>
    <h1>Our Faculty</h1>
    <p>Qualified, trained and — the part that matters most — genuinely interested in the children in front of them.</p>
  </div>
</section>

<section class="section">
  <div class="wrap split split--wide">
    <div data-reveal>
      <span class="eyebrow">The Teaching Team</span>
      <h2>A school is only ever as good as the adults in its classrooms.</h2>
      <div class="rule"></div>
      <p class="lede mt-3">
        Every teacher at <?= e(SCHOOL_NAME) ?> is qualified for the stage they teach, and every one of
        them is expected to do more than deliver a syllabus — to know where each child stands, and to
        say so plainly to parents.
      </p>
      <ul class="checklist mt-3">
        <li><?= icon('check') ?><span>B.Ed. / NTT qualified subject and class teachers</span></li>
        <li><?= icon('check') ?><span>Regular in-service training in pedagogy and child safety</span></li>
        <li><?= icon('check') ?><span>Background verification for all teaching and support staff</span></li>
        <li><?= icon('check') ?><span>A named class teacher parents can reach directly</span></li>
      </ul>
    </div>
    <div data-reveal data-delay="1">
      <div class="frame"><?= media('faculty.jpg', 'Our Teachers', 1, '', '4/3') ?></div>
    </div>
  </div>
</section>

<section class="section section--cream">
  <div class="wrap">
    <div class="head head--center" data-reveal>
      <span class="eyebrow eyebrow--center">Leadership &amp; Coordinators</span>
      <h2>The people who run the school day</h2>
      <div class="rule"></div>
    </div>
    <div class="grid g-3">
      <?php foreach ($staff as $i => $s): ?>
        <article class="pcard" data-reveal data-delay="<?= $i % 3 ?>">
          <?= media($s[0], $s[1], ($i % 8) + 1, '', '1/1') ?>
          <div class="pcard__body">
            <span class="pcard__tag"><?= e($s[2]) ?></span>
            <h3 style="font-size:1.15rem"><?= e($s[1]) ?></h3>
            <p style="font-size:.88rem"><?= e($s[3]) ?></p>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
    <p class="center mt-4 field__hint" data-reveal>Names and photographs are updated at the start of each session.</p>
  </div>
</section>

<section class="section section--tight">
  <div class="wrap">
    <div class="cta" data-reveal>
      <div class="cta__in">
        <div>
          <h2>Interested in teaching with us?</h2>
          <p>We are always glad to hear from good teachers. Send your CV to our office.</p>
        </div>
        <div class="btn-row">
          <a class="btn btn--gold" href="mailto:<?= e(SCHOOL_EMAIL) ?>?subject=Application%20for%20Teaching%20Position"><?= icon('mail') ?> Email Your CV</a>
          <a class="btn btn--light" href="<?= e(url('contact.php')) ?>">Contact the Office</a>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
