<?php
require_once __DIR__ . '/includes/config.php';
$page_title    = 'Campus Facilities, Labs & Infrastructure';
$page_desc     = 'Discover world-class campus facilities at Blossom Public School Saharanpur — smart digital classrooms, science lab, computer lab, library, sports, and GPS transport.';
$page_keywords = 'School facilities Saharanpur, smart classroom school, science lab Saharanpur, computer lab, school transport bus Saharanpur';
require_once __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
  <div class="wrap page-hero__in">
    <nav class="crumbs" aria-label="Breadcrumb"><a href="<?= e(url('index')) ?>">Home</a><span>/</span>Facilities</nav>
    <h1>Campus &amp; Facilities</h1>
    <p>Everything on this page exists to do one job — make good teaching easier and keep children safe while it happens.</p>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <div class="head head--center" data-reveal>
      <span class="eyebrow eyebrow--center">On Campus</span>
      <h2><?= e(get_setting('facilities_title', 'Built for learning, playing and growing up well')) ?></h2>
      <div class="rule"></div>
    </div>

    <div class="grid g-3">
      <?php
      try {
          $db = get_db();
          $campusFac = $db->query("SELECT * FROM `facilities_list` WHERE `category` = 'campus' AND `is_active` = 1 ORDER BY `display_order` ASC, `id` ASC")->fetchAll();
      } catch (Exception $e) { $campusFac = []; }

      if (empty($campusFac)) {
          $campusFac = [
              ['icon' => 'laptop',  'title' => 'Smart Classrooms',      'image_path' => 'classroom.jpg', 'display_order' => 1, 'description' => 'Airy, well-lit rooms with digital boards, so a concept can be shown as well as explained.'],
              ['icon' => 'flask',   'title' => 'Science Laboratory',    'image_path' => 'lab.jpg',       'display_order' => 5, 'description' => 'A working lab where Middle Wing students handle apparatus themselves under supervision.'],
              ['icon' => 'laptop',  'title' => 'Computer Laboratory',   'image_path' => 'computer.jpg',  'display_order' => 7, 'description' => 'One machine per child in a period, with a structured ICT syllabus from the Primary Wing upward.'],
              ['icon' => 'library', 'title' => 'Library & Reading Room','image_path' => 'library.jpg',   'display_order' => 4, 'description' => 'Graded readers, reference sets and story collections — with a timetabled period for every class.'],
              ['icon' => 'ball',    'title' => 'Sports Ground',         'image_path' => 'sports.jpg',    'display_order' => 6, 'description' => 'Space for athletics, cricket, football, kho-kho and kabaddi, plus indoor games for wet days.'],
              ['icon' => 'music',   'title' => 'Music, Dance & Art',    'image_path' => 'arts.jpg',      'display_order' => 3, 'description' => 'Dedicated rooms and instructors — because a stage changes a shy child faster than advice does.'],
          ];
      }
      foreach ($campusFac as $i => $f): ?>
        <article class="pcard" data-reveal data-delay="<?= $i % 3 ?>">
          <?= media($f['image_path'] ?? 'campus.jpg', $f['title'], (int) ($f['display_order'] ?? ($i + 1)), '', '16/10') ?>
          <div class="pcard__body">
            <span class="card__ico" style="width:44px;height:44px;border-radius:12px;margin-bottom:6px"><?= icon($f['icon']) ?></span>
            <h3><?= e($f['title']) ?></h3>
            <p><?= e($f['description']) ?></p>
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
      <h2><?= e(get_setting('safety_title', 'The part of a school no parent should have to ask twice about')) ?></h2>
      <div class="rule"></div>
      <p class="lede mt-3">
        <?= e(get_setting('safety_lede', 'Safety is not a feature we advertise — it is a routine we run every single day, and one you are welcome to inspect whenever you visit.')) ?>
      </p>
      <ul class="ilist mt-4">
        <?php
        try {
            $db = get_db();
            $safetyItems = $db->query("SELECT * FROM `facilities_list` WHERE `category` = 'safety' AND `is_active` = 1 ORDER BY `display_order` ASC, `id` ASC")->fetchAll();
        } catch (Exception $e) { $safetyItems = []; }

        if (empty($safetyItems)) {
            $safetyItems = [
                ['icon' => 'camera', 'title' => 'CCTV coverage',             'description' => 'Corridors, entry points, the ground and common areas are monitored through the day.'],
                ['icon' => 'shield', 'title' => 'Controlled entry & exit',   'description' => 'Gate security, visitor logging and a strict pick-up protocol — children leave only with an authorised adult.'],
                ['icon' => 'heart',  'title' => 'Medical room & first aid',  'description' => 'A dedicated care room, trained first-aid staff and immediate parent notification for any incident.'],
                ['icon' => 'users',  'title' => 'Verified staff',            'description' => 'Background-checked teachers and support staff, with female caregivers assigned to the Pre-Primary Wing.'],
            ];
        }
        foreach ($safetyItems as $s): ?>
          <li>
            <span class="ico"><?= icon($s['icon']) ?></span>
            <div>
              <h4><?= e($s['title']) ?></h4>
              <p><?= e($s['description']) ?></p>
            </div>
          </li>
        <?php endforeach; ?>
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
      <h2 style="font-size:clamp(1.6rem,2.6vw,2.2rem)"><?= e(get_setting('transport_title', 'Buses that run on time, on routes you can track')) ?></h2>
      <div class="rule"></div>
      <p class="lede mt-3">
        <?= e(get_setting('transport_lede', 'Our fleet covers the main residential routes across ' . SCHOOL_CITY . '. Every bus carries a trained attendant in addition to the driver, and Pre-Primary children are handed over only to a listed guardian.')) ?>
      </p>
      <ul class="checklist mt-3">
        <li><?= icon('check') ?><span>GPS-enabled vehicles with speed governors</span></li>
        <li><?= icon('check') ?><span>A female attendant on every Pre-Primary route</span></li>
        <li><?= icon('check') ?><span>Verified, licensed and regularly retrained drivers</span></li>
        <li><?= icon('check') ?><span>Fixed stops, published timings and a route-wise WhatsApp group</span></li>
        <li><?= icon('check') ?><span>Fire extinguisher and first-aid kit in every bus</span></li>
      </ul>
      <div class="btn-row mt-3">
        <a class="btn" href="<?= e(url('contact')) ?>">Check My Route <?= icon('arrow') ?></a>
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
      try {
          $db = get_db();
          $extraItems = $db->query("SELECT * FROM `facilities_list` WHERE `category` = 'extras' AND `is_active` = 1 ORDER BY `display_order` ASC, `id` ASC")->fetchAll();
      } catch (Exception $e) { $extraItems = []; }

      if (empty($extraItems)) {
          $extraItems = [
              ['icon' => 'leaf',   'title' => 'Green Campus',  'description' => 'Planted, shaded corners and an Eco Club that actually maintains them.'],
              ['icon' => 'bulb',   'title' => 'Activity Room', 'description' => 'Space for clubs, storytelling, drama and hands-on project work.'],
              ['icon' => 'trophy', 'title' => 'House System',  'description' => 'Four houses competing all year in sport, quizzing, art and debate.'],
              ['icon' => 'chat',   'title' => 'Parent Portal', 'description' => 'Circulars, results and attendance shared promptly through official channels.'],
          ];
      }
      foreach ($extraItems as $i => $x): ?>
        <article class="card" data-reveal data-delay="<?= $i ?>">
          <span class="card__ico"><?= icon($x['icon']) ?></span>
          <h3 style="font-size:1.1rem"><?= e($x['title']) ?></h3>
          <p class="mt-1" style="font-size:.9rem"><?= e($x['description']) ?></p>
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
          <a class="btn btn--gold" href="<?= e(url('gallery')) ?>">Open the Gallery <?= icon('arrow') ?></a>
          <a class="btn btn--light" href="<?= e(url('contact')) ?>">Plan a Visit</a>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
