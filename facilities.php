<?php
require_once __DIR__ . '/includes/config.php';
$page_title = 'Facilities';
$page_desc  = 'Classrooms, science and computer labs, library, sports ground, transport, safety and medical care at ' . SCHOOL_NAME . '.';
require_once __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
  <div class="wrap page-hero__in">
    <nav class="crumbs" aria-label="Breadcrumb"><a href="<?= e(url('index.php')) ?>">Home</a><span>/</span>Facilities</nav>
    <h1>Campus &amp; Facilities</h1>
    <p>Everything on this page exists to do one job — make good teaching easier and keep children safe while it happens.</p>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <div class="head head--center" data-reveal>
      <span class="eyebrow eyebrow--center">On Campus</span>
      <h2>Built for learning, playing and growing up well</h2>
      <div class="rule"></div>
    </div>

    <div class="grid g-3">
      <?php
      $items = [
          ['laptop',  'Smart Classrooms',    'classroom.jpg', 1, 'Airy, well-lit rooms with digital boards, so a concept can be shown as well as explained.'],
          ['flask',   'Science Laboratory',  'lab.jpg',       5, 'A working lab where Middle Wing students handle apparatus themselves under supervision.'],
          ['laptop',  'Computer Laboratory', 'computer.jpg',  7, 'One machine per child in a period, with a structured ICT syllabus from the Primary Wing upward.'],
          ['library', 'Library & Reading Room', 'library.jpg', 4, 'Graded readers, reference sets and story collections — with a timetabled period for every class.'],
          ['ball',    'Sports Ground',       'sports.jpg',    6, 'Space for athletics, cricket, football, kho-kho and kabaddi, plus indoor games for wet days.'],
          ['music',   'Music, Dance & Art',  'arts.jpg',      3, 'Dedicated rooms and instructors — because a stage changes a shy child faster than advice does.'],
      ];
      foreach ($items as $i => $f): ?>
        <article class="pcard" data-reveal data-delay="<?= $i % 3 ?>">
          <?= media($f[2], $f[1], $f[3], '', '16/10') ?>
          <div class="pcard__body">
            <span class="card__ico" style="width:44px;height:44px;border-radius:12px;margin-bottom:6px"><?= icon($f[0]) ?></span>
            <h3><?= e($f[1]) ?></h3>
            <p><?= e($f[4]) ?></p>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ SAFETY ============ -->
<section class="section section--navy" id="safety">
  <div class="wrap split split--wide">
    <div data-reveal>
      <span class="eyebrow">Safety &amp; Care</span>
      <h2>The part of a school no parent should have to ask twice about</h2>
      <div class="rule"></div>
      <p class="lede mt-3">
        Safety is not a feature we advertise — it is a routine we run every single day, and one you are
        welcome to inspect whenever you visit.
      </p>
      <ul class="ilist mt-4">
        <li><span class="ico"><?= icon('camera') ?></span><div><h4>CCTV coverage</h4><p>Corridors, entry points, the ground and common areas are monitored through the day.</p></div></li>
        <li><span class="ico"><?= icon('shield') ?></span><div><h4>Controlled entry &amp; exit</h4><p>Gate security, visitor logging and a strict pick-up protocol — children leave only with an authorised adult.</p></div></li>
        <li><span class="ico"><?= icon('heart') ?></span><div><h4>Medical room &amp; first aid</h4><p>A dedicated care room, trained first-aid staff and immediate parent notification for any incident.</p></div></li>
        <li><span class="ico"><?= icon('users') ?></span><div><h4>Verified staff</h4><p>Background-checked teachers and support staff, with female caregivers assigned to the Pre-Primary Wing.</p></div></li>
      </ul>
    </div>
    <div data-reveal data-delay="1">
      <?= media('safety.jpg', 'Safe Campus', 4, '', '4/5') ?>
    </div>
  </div>
</section>

<!-- ============ TRANSPORT ============ -->
<section class="section section--cream" id="transport">
  <div class="wrap split split--wide">
    <div data-reveal>
      <?= media('bus.jpg', 'School Transport', 2, '', '16/11') ?>
    </div>
    <div data-reveal data-delay="1">
      <span class="eyebrow">Transport</span>
      <h2 style="font-size:clamp(1.6rem,2.6vw,2.2rem)">Buses that run on time, on routes you can track</h2>
      <div class="rule"></div>
      <p class="lede mt-3">
        Our fleet covers the main residential routes across <?= e(SCHOOL_CITY) ?>. Every bus carries a
        trained attendant in addition to the driver, and Pre-Primary children are handed over only to a
        listed guardian.
      </p>
      <ul class="checklist mt-3">
        <li><?= icon('check') ?><span>GPS-enabled vehicles with speed governors</span></li>
        <li><?= icon('check') ?><span>A female attendant on every Pre-Primary route</span></li>
        <li><?= icon('check') ?><span>Verified, licensed and regularly retrained drivers</span></li>
        <li><?= icon('check') ?><span>Fixed stops, published timings and a route-wise WhatsApp group</span></li>
        <li><?= icon('check') ?><span>Fire extinguisher and first-aid kit in every bus</span></li>
      </ul>
      <div class="btn-row mt-3">
        <a class="btn" href="<?= e(url('contact.php')) ?>">Check My Route <?= icon('arrow') ?></a>
      </div>
    </div>
  </div>
</section>

<!-- ============ EXTRAS ============ -->
<section class="section">
  <div class="wrap">
    <div class="head head--center" data-reveal>
      <span class="eyebrow eyebrow--center">And Also</span>
      <h2>The small things that make a school work</h2>
      <div class="rule"></div>
    </div>
    <div class="grid g-4">
      <?php
      $extra = [
          ['leaf',    'Green Campus',      'Planted, shaded corners and an Eco Club that actually maintains them.'],
          ['bulb',    'Activity Room',     'Space for clubs, storytelling, drama and hands-on project work.'],
          ['trophy',  'House System',      'Four houses competing all year in sport, quizzing, art and debate.'],
          ['chat',    'Parent Portal',     'Circulars, results and attendance shared promptly through official channels.'],
      ];
      foreach ($extra as $i => $x): ?>
        <article class="card" data-reveal data-delay="<?= $i ?>">
          <span class="card__ico"><?= icon($x[0]) ?></span>
          <h3 style="font-size:1.1rem"><?= e($x[1]) ?></h3>
          <p class="mt-1" style="font-size:.9rem"><?= e($x[2]) ?></p>
        </article>
      <?php endforeach; ?>
    </div>

    <div class="cta mt-4" data-reveal>
      <div class="cta__in">
        <div>
          <h2>See it for yourself.</h2>
          <p>Photographs only go so far. Walk the campus on any working day.</p>
        </div>
        <div class="btn-row">
          <a class="btn btn--gold" href="<?= e(url('gallery.php')) ?>">Open the Gallery <?= icon('arrow') ?></a>
          <a class="btn btn--light" href="<?= e(url('contact.php')) ?>">Plan a Visit</a>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
