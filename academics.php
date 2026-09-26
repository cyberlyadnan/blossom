<?php
require_once __DIR__ . '/includes/config.php';
$page_title    = 'Academics & CBSE Curriculum (Nursery to Class VIII)';
$page_desc     = 'Explore the comprehensive CBSE academic curriculum at Blossom Public School Saharanpur across Pre-Primary, Primary, and Middle wings with activity-based learning.';
$page_keywords = 'CBSE curriculum Saharanpur, Blossom Public School academics, primary school syllabus, middle wing courses, pre-primary nursery education Saharanpur';
require_once __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
  <div class="wrap page-hero__in">
    <nav class="crumbs" aria-label="Breadcrumb"><a href="<?= e(url('index')) ?>">Home</a><span>/</span>Academics</nav>
    <h1>Academics</h1>
    <p>A <?= e(SCHOOL_BOARD) ?> programme from Nursery to Class VIII, taught so that children understand — not merely remember.</p>
  </div>
</section>

<!-- ============ APPROACH ============ -->
<section class="section" id="curriculum">
  <div class="wrap split split--wide">
    <div data-reveal>
      <span class="eyebrow">Our Curriculum</span>
      <h2>The syllabus is the floor, not the ceiling.</h2>
      <div class="rule"></div>
      <p class="lede mt-3">
        We follow the <?= e(SCHOOL_BOARD) ?> framework in full, then teach it the way children actually
        learn: concrete before abstract, spoken before written, application before assessment.
      </p>
      <p class="mt-2">
        Lessons are planned around a single question — <em>what should a child be able to do at the end
        of this class that they could not do at the start?</em> Teachers check that answer before moving
        on, using quick oral checks, worksheets and hands-on tasks rather than waiting for a term exam
        to reveal a gap.
      </p>
      <ul class="checklist mt-3">
        <li><?= icon('check') ?><span><strong>Concept-first.</strong> Children explain their reasoning aloud before writing it down.</span></li>
        <li><?= icon('check') ?><span><strong>Activity-led.</strong> Science is done, not dictated; maths uses material before symbols.</span></li>
        <li><?= icon('check') ?><span><strong>Reading at the centre.</strong> Timetabled library periods and graded readers for every class.</span></li>
        <li><?= icon('check') ?><span><strong>Bilingual confidence.</strong> Strong English alongside a firm foundation in Hindi.</span></li>
      </ul>
    </div>
    <div data-reveal data-delay="1">
      <div class="frame"><?= media('classroom.jpg', 'In the classroom', 5, '', '4/5') ?></div>
    </div>
  </div>
</section>

<!-- ============ WINGS ============ -->
<section class="section section--cream">
  <div class="wrap">
    <div class="head head--center" data-reveal>
      <span class="eyebrow eyebrow--center">The Three Wings</span>
      <h2>How the programme grows with your child</h2>
      <div class="rule"></div>
    </div>

    <?php
    $wings = [
        [
            'id' => 'pre-primary', 'name' => 'Pre-Primary Wing', 'range' => 'Nursery · LKG · UKG',
            'img' => 'pre-primary.jpg', 'tone' => 3,
            'lede' => 'The years that decide whether a child finds school a happy place. Everything here is play with a purpose.',
            'points' => [
                'Phonics-led pre-reading and sound blending',
                'Number readiness through counting material and games',
                'Fine motor work — tracing, threading, colouring, clay',
                'Rhymes, storytelling, puppet play and show-and-tell',
                'Toilet-trained care with trained caregivers in every section',
                'No homework. Learning finishes at the school gate.',
            ],
        ],
        [
            'id' => 'primary', 'name' => 'Primary Wing', 'range' => 'Class I to Class V',
            'img' => 'primary.jpg', 'tone' => 1,
            'lede' => 'Where the fundamentals are locked in — fluent reading, accurate mental maths and clear handwriting.',
            'points' => [
                'English, Hindi, Mathematics, EVS and General Knowledge',
                'Computer literacy and library periods every week',
                'Mental maths drills and structured spelling programme',
                'EVS taught through observation, field walks and projects',
                'Art, music, dance and games on the regular timetable',
                'Continuous assessment — no single high-stakes paper',
            ],
        ],
        [
            'id' => 'middle', 'name' => 'Middle Wing', 'range' => 'Class VI to Class VIII',
            'img' => 'middle.jpg', 'tone' => 2,
            'lede' => 'Subject specialists take over, laboratory work begins, and children learn how to study — not just what.',
            'points' => [
                'Science with regular laboratory practicals',
                'Mathematics with reasoning and word-problem focus',
                'Social Science: History, Civics and Geography',
                'Third language, ICT and project-based assessment',
                'Presentation, debate and written-expression training',
                'Olympiad, quiz and scholarship exam preparation',
            ],
        ],
    ];
    foreach ($wings as $i => $w): ?>
      <div class="split split--wide mt-4" id="<?= e($w['id']) ?>" style="<?= $i % 2 ? 'direction:rtl' : '' ?>">
        <div data-reveal style="direction:ltr">
          <?= media($w['img'], $w['name'], $w['tone'], '', '16/11') ?>
        </div>
        <div data-reveal data-delay="1" style="direction:ltr">
          <span class="pcard__tag"><?= e($w['range']) ?></span>
          <h2 class="mt-2" style="font-size:clamp(1.6rem,2.6vw,2.2rem)"><?= e($w['name']) ?></h2>
          <p class="lede mt-2"><?= e($w['lede']) ?></p>
          <ul class="checklist mt-3">
            <?php foreach ($w['points'] as $p): ?>
              <li><?= icon('check') ?><span><?= e($p) ?></span></li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
      <?php if ($i < 2): ?><div class="divider"></div><?php endif; ?>
    <?php endforeach; ?>
  </div>
</section>

<!-- ============ ASSESSMENT ============ -->
<section class="section">
  <div class="wrap">
    <div class="head head--center" data-reveal>
      <span class="eyebrow eyebrow--center">Assessment</span>
      <h2>Measured often, so nothing is discovered too late</h2>
      <div class="rule"></div>
      <p class="lede mt-2">Assessment at <?= e(SCHOOL_SHORT) ?> is diagnostic before it is judgemental — its first job is to tell the teacher what to reteach.</p>
    </div>

    <div class="grid g-4">
      <?php
      $ass = [
          ['chat',     'Oral Checks',        'Daily, informal. A child who can explain it has understood it.'],
          ['file',     'Class Worksheets',   'Short written checks that reveal gaps within the same week.'],
          ['calendar', 'Periodic Tests',     'Scheduled, syllabus-limited and shared with parents in advance.'],
          ['award',    'Term Examinations',  'Two terms, with detailed subject-wise feedback, not just a number.'],
      ];
      foreach ($ass as $i => $a): ?>
        <article class="card" data-reveal data-delay="<?= $i ?>">
          <span class="card__ico"><?= icon($a[0]) ?></span>
          <h3 style="font-size:1.1rem"><?= e($a[1]) ?></h3>
          <p class="mt-1" style="font-size:.9rem"><?= e($a[2]) ?></p>
        </article>
      <?php endforeach; ?>
    </div>

    <div class="split split--wide mt-4" id="calendar" style="align-items:start">
      <div data-reveal>
        <h3>The academic year at a glance</h3>
        <p class="mt-2" style="font-size:.94rem">Indicative structure — exact dates are published in the school diary at the start of each session.</p>
        <div class="table-wrap mt-3">
          <table class="tbl">
            <thead><tr><th>Period</th><th>What happens</th></tr></thead>
            <tbody>
              <tr><td>April</td><td>New session begins · orientation for new families</td></tr>
              <tr><td>July</td><td>Periodic Test I · first Parent–Teacher Meeting</td></tr>
              <tr><td>September</td><td>Term I Examination · half-yearly reports issued</td></tr>
              <tr><td>November</td><td>Annual Sports Meet · inter-house competitions</td></tr>
              <tr><td>December</td><td>Periodic Test II · Annual Day</td></tr>
              <tr><td>March</td><td>Term II Examination · results and promotion</td></tr>
            </tbody>
          </table>
        </div>
      </div>
      <div data-reveal data-delay="1">
        <div class="panel panel--cream">
          <span class="eyebrow">Beyond the Books</span>
          <h3>Houses, clubs and competitions</h3>
          <p class="mt-2" style="font-size:.94rem">
            Every child belongs to a house from Class I, and every house competes through the year in
            sport, quizzing, debate, art and music. It is how a shy child ends up on a stage.
          </p>
          <div class="tag-row mt-3">
            <span class="tag">Science Club</span><span class="tag">Reading Club</span>
            <span class="tag">Art &amp; Craft</span><span class="tag">Music &amp; Dance</span>
            <span class="tag">Eco Club</span><span class="tag">Quiz Club</span>
            <span class="tag">Sports</span><span class="tag">Public Speaking</span>
          </div>
          <a class="btn btn--ghost mt-3" href="<?= e(url('facilities')) ?>">See the facilities <?= icon('arrow') ?></a>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section section--tight">
  <div class="wrap">
    <div class="cta" data-reveal>
      <div class="cta__in">
        <div>
          <h2>Want to see a lesson in progress?</h2>
          <p>We welcome parents into working classrooms — not staged demonstrations.</p>
        </div>
        <div class="btn-row">
          <a class="btn btn--gold" href="<?= e(url('admissions#enquiry')) ?>">Book a Visit <?= icon('arrow') ?></a>
          <a class="btn btn--light" href="<?= e(url('contact')) ?>">Contact the Office</a>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
