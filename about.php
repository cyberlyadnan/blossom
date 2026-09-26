<?php
require_once __DIR__ . '/includes/config.php';
$page_title    = 'About Our School, Legacy & Leadership';
$page_desc     = 'Discover the story, vision, core educational values and Principal leadership behind Blossom Public School Saharanpur — a trusted CBSE school since 2005.';
$page_keywords = 'About Blossom Public School, Saharanpur school history, Principal message Blossom Public School, school core values, CBSE school Saharanpur';
require_once __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
  <div class="wrap page-hero__in">
    <nav class="crumbs" aria-label="Breadcrumb">
      <a href="<?= e(url('index')) ?>">Home</a><span>/</span>About
    </nav>
    <h1>About <?= e(SCHOOL_NAME) ?></h1>
    <p>A neighbourhood school in <?= e(SCHOOL_CITY) ?> with an unfashionable belief: that attention, not scale, is what makes a child flourish.</p>
  </div>
</section>

<!-- ============ STORY ============ -->
<section class="section" id="story">
  <div class="wrap split split--wide">
    <div data-reveal>
      <span class="eyebrow">Our Story</span>
      <h2>Built quietly, one classroom at a time.</h2>
      <div class="rule"></div>
      <p class="lede mt-3">
        <?= e(SCHOOL_NAME) ?> began with a handful of classrooms, a few determined teachers and one
        promise to the first families who trusted us — that no child here would be a number on a
        register.
      </p>
      <p class="mt-2">
        That promise has survived every year of growth since. As the school expanded from pre-primary
        into a full <?= e(SCHOOL_BOARD) ?> programme up to Class VIII, we kept sections small on
        purpose, kept the fee structure transparent, and kept the principal's door open. Parents in
        <?= e(SCHOOL_CITY) ?> came to us the way good schools are always found — because another
        parent told them to.
      </p>
      <p class="mt-2">
        Today the campus holds well-lit classrooms, a working science laboratory, a computer lab, a
        library children actually borrow from, and a ground where every class gets its turn. What has
        not changed is the ratio of adults who know each child by name.
      </p>
      <div class="tag-row mt-3">
        <span class="tag"><?= e(SCHOOL_BOARD) ?> Curriculum</span>
        <span class="tag"><?= e(SCHOOL_GRADES) ?></span>
        <span class="tag">Est. <?= e(SCHOOL_EST) ?></span>
        <span class="tag"><?= e(SCHOOL_CITY) ?>, <?= e(SCHOOL_STATE) ?></span>
      </div>
    </div>
    <div data-reveal data-delay="1">
      <div class="frame"><?= media('achievers.jpg', 'The Campus', 2, '', '4/5') ?></div>
    </div>
  </div>
</section>

<!-- ============ VISION / MISSION ============ -->
<section class="section section--cream" id="vision">
  <div class="wrap">
    <div class="head head--center" data-reveal>
      <span class="eyebrow eyebrow--center">What Guides Us</span>
      <h2><?= e(get_setting('vision_title', 'Vision, mission and the values in between')) ?></h2>
      <div class="rule"></div>
    </div>
    <div class="grid g-3">
      <?php
      $vm = [
          ['globe', 'Our Vision', get_setting('vision_text', 'To be the school in ' . SCHOOL_CITY . ' that families choose for the person their child becomes — confident, considerate and genuinely curious — not merely for the marks they carry home.')],
          ['bulb',  'Our Mission', get_setting('mission_text', 'To deliver the ' . SCHOOL_BOARD . ' curriculum with unusual care: concept-first teaching, small attentive sections, timetabled arts and sport, and honest, frequent communication with every parent.')],
          ['heart', 'Our Promise', get_setting('promise_text', 'That every child is known by name, every difficulty is spotted early, every talent is given a stage — and that no parent ever has to chase the school for an answer.')],
      ];
      foreach ($vm as $i => $v): ?>
        <article class="card" data-reveal data-delay="<?= $i ?>">
          <span class="card__ico"><?= icon($v[0]) ?></span>
          <h3><?= e($v[1]) ?></h3>
          <p class="mt-1"><?= e($v[2]) ?></p>
        </article>
      <?php endforeach; ?>
    </div>

    <div class="divider"></div>

    <div class="head head--center" data-reveal>
      <h2 style="font-size:clamp(1.5rem,2.4vw,2.1rem)"><?= e(get_setting('values_title', 'The five values we teach as habits')) ?></h2>
      <p class="lede mt-2"><?= e(get_setting('values_subtitle', 'Not posters on a wall — behaviours our house mentors look for, name and reward every week.')) ?></p>
    </div>
    <div class="grid g-4" style="grid-template-columns:repeat(auto-fit,minmax(200px,1fr))">
      <?php
      try {
          $db = get_db();
          $dbValues = $db->query("SELECT * FROM `core_values` WHERE `is_active` = 1 ORDER BY `display_order` ASC, `id` ASC")->fetchAll();
      } catch (Exception $e) { $dbValues = []; }

      if (empty($dbValues)) {
          $dbValues = [
              ['icon' => 'shield', 'title' => 'Integrity',      'description' => 'Doing the right thing when the marks do not depend on it.'],
              ['icon' => 'users',  'title' => 'Respect',        'description' => 'For teachers, for staff, for classmates who are different.'],
              ['icon' => 'award',  'title' => 'Diligence',      'description' => 'Finishing what you start, at the standard you are capable of.'],
              ['icon' => 'heart',  'title' => 'Empathy',        'description' => 'Noticing the child sitting alone, and doing something about it.'],
              ['icon' => 'leaf',   'title' => 'Responsibility', 'description' => 'For your books, your words, your classroom and your planet.'],
          ];
      }
      foreach ($dbValues as $i => $v): ?>
        <article class="card" data-reveal data-delay="<?= $i % 4 ?>">
          <span class="card__ico"><?= icon($v['icon']) ?></span>
          <h3 style="font-size:1.12rem"><?= e($v['title']) ?></h3>
          <p class="mt-1" style="font-size:.9rem"><?= e($v['description']) ?></p>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ PRINCIPAL ============ -->
<section class="section" id="principal">
  <div class="wrap split">
    <div data-reveal>
      <div class="frame frame--left"><?= media('principal.jpg', 'Principal', 4, 'media--tall', '3/4') ?></div>
    </div>
    <div data-reveal data-delay="1">
      <span class="eyebrow">Principal's Message</span>
      <h2 style="font-size:clamp(1.35rem,2.2vw,1.85rem);line-height:1.25"><?= e(PRINCIPAL_QUOTE) ?></h2>
      <div class="rule"></div>
      <p class="lede mt-3">
        <?= e(PRINCIPAL_MSG_1) ?>
      </p>
      <?php if (!empty(PRINCIPAL_MSG_2)): ?>
        <p class="mt-2">
          <?= e(PRINCIPAL_MSG_2) ?>
        </p>
      <?php endif; ?>
      <?php if (!empty(PRINCIPAL_MSG_3)): ?>
        <p class="mt-2">
          <?= e(PRINCIPAL_MSG_3) ?>
        </p>
      <?php endif; ?>
      <p class="mt-3" style="font-family:var(--font-display);font-size:1.2rem;color:var(--ink);font-weight:700">
        <?= e(PRINCIPAL_NAME) ?><br>
        <span style="font-size:.82rem;font-family:var(--font-body);color:var(--muted);font-weight:600;letter-spacing:.06em"><?= e(PRINCIPAL_TITLE) ?> · <?= e(SCHOOL_NAME) ?></span>
      </p>
    </div>
  </div>
</section>

<!-- ============ AT A GLANCE ============ -->
<section class="section section--tight section--navy">
  <div class="wrap">
    <div class="head head--center" data-reveal>
      <span class="eyebrow eyebrow--center">At a Glance</span>
      <h2>The school in numbers</h2>
      <div class="rule"></div>
    </div>
    <div class="stats" data-reveal>
      <div class="stat"><b data-count="<?= STAT_STUDENTS ?>" data-suffix="+">0</b><span>Students on Roll</span></div>
      <div class="stat"><b data-count="<?= STAT_TEACHERS ?>" data-suffix="+">0</b><span>Teaching Staff</span></div>
      <div class="stat"><b data-count="<?= STAT_YEARS ?>" data-suffix="+">0</b><span>Years of Service</span></div>
      <div class="stat"><b data-count="25">0</b><span>Max Class Size</span></div>
    </div>
  </div>
</section>

<!-- ============ CTA ============ -->
<section class="section section--tight">
  <div class="wrap">
    <div class="cta" data-reveal>
      <div class="cta__in">
        <div>
          <h2>Meet the people behind the school.</h2>
          <p>Our faculty is the reason parents stay. Read about the team teaching your child.</p>
        </div>
        <div class="btn-row">
          <a class="btn btn--gold" href="<?= e(url('faculty')) ?>">Meet Our Faculty <?= icon('arrow') ?></a>
          <a class="btn btn--light" href="<?= e(url('admissions#enquiry')) ?>">Apply for Admission</a>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
