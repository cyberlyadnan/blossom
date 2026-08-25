<?php
$page_title = 'Home';
require_once __DIR__ . '/includes/header.php';

/* Hero slides — drop hero-1.jpg / hero-2.jpg / hero-3.jpg into assets/img
   and they will be used automatically instead of the gradient panels. */
$slides = [
    ['file' => 'hero-1.jpg', 'tone' => 'ph-4'],
    ['file' => 'hero-2.jpg', 'tone' => 'ph-2'],
    ['file' => 'hero-3.jpg', 'tone' => 'ph-7'],
];
?>

<!-- ============ HERO ============ -->
<section class="hero">
  <div class="hero__bg">
    <?php foreach ($slides as $i => $s):
        $path = __DIR__ . '/assets/img/' . $s['file'];
        $style = is_file($path)
            ? 'background-image:url(' . asset('img/' . $s['file']) . ');background-size:cover;background-position:center'
            : '';
    ?>
      <div class="hero__slide <?= $s['tone'] ?><?= $i === 0 ? ' is-active' : '' ?>" style="<?= e($style) ?>"></div>
    <?php endforeach; ?>
  </div>

  <div class="wrap hero__in">
    <span class="hero__badge"><span class="dot"></span>Admissions Open · Session <?= date('Y') ?>–<?= date('y', strtotime('+1 year')) ?></span>
    <h1>Where every child <em>blossoms</em> into their best self.</h1>
    <p class="hero__text">
      A <?= e(SCHOOL_BOARD) ?> school in <?= e(SCHOOL_CITY) ?> for <?= e(SCHOOL_GRADES) ?> — built on strong
      fundamentals, small attentive classrooms and a campus where curiosity is never switched off.
    </p>
    <div class="hero__actions">
      <a class="btn btn--gold" href="<?= e(url('admissions.php#enquiry')) ?>">Begin Admission <?= icon('arrow') ?></a>
      <a class="btn btn--light" href="<?= e(url('about.php')) ?>">Discover the School</a>
    </div>

    <div class="hero__meta">
      <div><b><?= e(SCHOOL_BOARD) ?></b><span>Curriculum</span></div>
      <div><b>Nur–VIII</b><span>Classes Offered</span></div>
      <div><b>1:25</b><span>Teacher Ratio</span></div>
      <div><b><?= e(SCHOOL_EST) ?></b><span>Established</span></div>
    </div>
  </div>

  <div class="hero__dots" role="tablist" aria-label="Hero slides">
    <?php foreach ($slides as $i => $s): ?>
      <button class="<?= $i === 0 ? 'is-active' : '' ?>" aria-label="Slide <?= $i + 1 ?>"></button>
    <?php endforeach; ?>
  </div>
</section>

<!-- ============ QUICK ACCESS ============ -->
<section class="quick">
  <div class="wrap">
    <div class="quick__grid" data-reveal>
      <?php
      $quick = [
          ['book',     'Admission Process',  'Four simple steps, start to seat.',        'admissions.php'],
          ['calendar', 'Academic Calendar',  'Terms, holidays and examination dates.',   'academics.php#calendar'],
          ['bus',      'Transport Routes',   'GPS-tracked buses across the city.',       'facilities.php#transport'],
          ['chat',     'Talk to Us',         'Speak with our admissions desk today.',    'contact.php'],
      ];
      foreach ($quick as $q): ?>
        <a class="quick__card" href="<?= e(url($q[3])) ?>">
          <span class="quick__ico"><?= icon($q[0]) ?></span>
          <span>
            <h4><?= e($q[1]) ?></h4>
            <p><?= e($q[2]) ?></p>
          </span>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ NOTICE TICKER ============ -->
<div class="ticker">
  <span class="ticker__tag">Latest</span>
  <div class="ticker__track">
    <?php $notices = [
        'Admissions open for Nursery to Class VIII — limited seats per section',
        'Annual Day rehearsals begin this month — parents invited',
        'Inter-house Science Quiz winners announced',
        'Parent–Teacher Meeting scheduled for the last Saturday of the month',
    ];
    foreach ([1, 2] as $pass) {
        foreach ($notices as $n) echo '<span>' . e($n) . '</span>';
    } ?>
  </div>
</div>

<!-- ============ WELCOME ============ -->
<section class="section">
  <div class="wrap split split--wide">
    <div data-reveal>
      <div class="frame">
        <?= media('campus.jpg', 'Our Campus', 1, '', '4/3') ?>
      </div>
    </div>
    <div data-reveal data-delay="1">
      <span class="eyebrow">Welcome to <?= e(SCHOOL_SHORT) ?></span>
      <h2>A school small enough to know your child, serious enough to shape them.</h2>
      <div class="rule"></div>
      <p class="lede mt-3">
        <?= e(SCHOOL_NAME) ?> was founded on a simple conviction: children learn best when they feel
        seen. Our classrooms in <?= e(SCHOOL_CITY) ?> are deliberately sized so that every child is
        spoken to by name, every day — and every teacher knows exactly where each learner stands.
      </p>
      <p class="mt-2">
        From the first days of Nursery through the demanding years of Middle School, we follow the
        <?= e(SCHOOL_BOARD) ?> framework with an emphasis on conceptual clarity over rote learning,
        supported by activity-led teaching, structured reading and consistent parent communication.
      </p>
      <ul class="checklist mt-3">
        <li><?= icon('check') ?><span><strong>Concept-first teaching</strong> — understanding before memorising, in every subject.</span></li>
        <li><?= icon('check') ?><span><strong>Small sections</strong> so remedial help reaches a child in days, not terms.</span></li>
        <li><?= icon('check') ?><span><strong>A safe, watched campus</strong> — CCTV coverage, verified staff and trained caregivers.</span></li>
      </ul>
      <div class="btn-row mt-3">
        <a class="btn" href="<?= e(url('about.php')) ?>">More About Us <?= icon('arrow') ?></a>
        <a class="link-arrow" href="<?= e(url('about.php#principal')) ?>">Read the Principal's message</a>
      </div>
    </div>
  </div>
</section>

<!-- ============ WHY CHOOSE ============ -->
<section class="section section--cream">
  <div class="wrap">
    <div class="head head--center" data-reveal>
      <span class="eyebrow eyebrow--center">Why Parents Choose Us</span>
      <h2>Six things we refuse to compromise on</h2>
      <div class="rule"></div>
      <p class="lede mt-2">Every promise below is something you can walk in and verify on any working day.</p>
    </div>

    <div class="grid g-3">
      <?php
      $why = [
          ['users',   'Attentive Class Sizes',   'Sections are capped so teaching stays personal and no child slips quietly behind.'],
          ['bulb',    'Concept-Led Learning',    'Activity, enquiry and application — children explain the why, not just recite the what.'],
          ['shield',  'Safety You Can Verify',   'CCTV-monitored corridors, controlled entry, verified staff and a trained first-aid room.'],
          ['palette', 'Arts, Sport & Music',     'Timetabled — not optional. Every child performs, plays and creates each term.'],
          ['laptop',  'Smart, Practical Rooms',  'Digital boards, a hands-on science lab and a computer lab used weekly by every class.'],
          ['heart',   'Values That Travel',      'Courtesy, honesty and responsibility taught as habits, reinforced by house mentors.'],
      ];
      foreach ($why as $i => $w): ?>
        <article class="card" data-reveal data-delay="<?= $i % 3 ?>">
          <span class="card__num"><?= str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) ?></span>
          <span class="card__ico"><?= icon($w[0]) ?></span>
          <h3><?= e($w[1]) ?></h3>
          <p><?= e($w[2]) ?></p>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ ACADEMIC WINGS ============ -->
<section class="section">
  <div class="wrap">
    <div class="head head--center" data-reveal>
      <span class="eyebrow eyebrow--center">Academics</span>
      <h2>Three wings, one continuous journey</h2>
      <div class="rule"></div>
      <p class="lede mt-2">The curriculum grows with the child — from play and phonics to structured study skills.</p>
    </div>

    <div class="grid g-3">
      <?php
      $wings = [
          ['Pre-Primary Wing', 'Nursery · LKG · UKG', 'pre-primary.jpg', 3, 'Play-based days built around phonics, number sense, motor skills and confident speech.',
            ['Phonics & pre-reading', 'Number readiness', 'Rhymes, art and free play', 'Toilet-trained care & caregivers']],
          ['Primary Wing', 'Class I to V', 'primary.jpg', 1, 'Where fundamentals are locked in — reading fluency, mental maths and clear written expression.',
            ['English, Hindi & Mathematics', 'EVS with field activities', 'Weekly library & computer periods', 'Continuous, low-stress assessment']],
          ['Middle Wing', 'Class VI to VIII', 'middle.jpg', 2, 'Subject specialists, laboratory work and study habits that carry a child into the board years.',
            ['Science with practicals', 'Social Science & third language', 'Project & presentation work', 'Olympiad and quiz coaching']],
      ];
      foreach ($wings as $i => $w): ?>
        <article class="pcard" data-reveal data-delay="<?= $i ?>">
          <?= media($w[2], $w[0], $w[3], '', '16/10') ?>
          <div class="pcard__body">
            <span class="pcard__tag"><?= e($w[1]) ?></span>
            <h3><?= e($w[0]) ?></h3>
            <p><?= e($w[4]) ?></p>
            <ul>
              <?php foreach ($w[5] as $li): ?><li><?= e($li) ?></li><?php endforeach; ?>
            </ul>
            <div class="pcard__foot">
              <a class="link-arrow" href="<?= e(url('academics.php')) ?>">Explore the curriculum</a>
            </div>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ STATS ============ -->
<section class="section section--tight section--navy">
  <div class="wrap">
    <div class="stats" data-reveal>
      <div class="stat"><b data-count="<?= STAT_STUDENTS ?>" data-suffix="+">0</b><span>Happy Students</span></div>
      <div class="stat"><b data-count="<?= STAT_TEACHERS ?>" data-suffix="+">0</b><span>Qualified Teachers</span></div>
      <div class="stat"><b data-count="<?= STAT_YEARS ?>" data-suffix="+">0</b><span>Years of Trust</span></div>
      <div class="stat"><b data-count="<?= STAT_CLUBS ?>">0</b><span>Clubs &amp; Activities</span></div>
    </div>
  </div>
</section>

<!-- ============ FACILITIES ============ -->
<section class="section section--cream">
  <div class="wrap">
    <div class="head" data-reveal>
      <span class="eyebrow">Campus &amp; Facilities</span>
      <h2>A campus built for learning, playing and staying safe</h2>
      <div class="rule"></div>
    </div>

    <div class="grid g-4">
      <?php
      $fac = [
          ['flask',   'Science Laboratory', 'lab.jpg',       5],
          ['library', 'Library &amp; Reading Room', 'library.jpg', 4],
          ['laptop',  'Computer Lab',       'computer.jpg',  1],
          ['ball',    'Sports Ground',      'sports.jpg',    6],
      ];
      foreach ($fac as $i => $f): ?>
        <article class="pcard" data-reveal data-delay="<?= $i ?>">
          <?= media($f[2], strip_tags($f[1]), $f[3], '', '1/1') ?>
          <div class="pcard__body">
            <h3 style="font-size:1.12rem"><?= $f[1] ?></h3>
          </div>
        </article>
      <?php endforeach; ?>
    </div>

    <div class="btn-row mt-4" data-reveal>
      <a class="btn" href="<?= e(url('facilities.php')) ?>">See All Facilities <?= icon('arrow') ?></a>
      <a class="btn btn--ghost" href="<?= e(url('gallery.php')) ?>">Browse the Gallery</a>
    </div>
  </div>
</section>

<!-- ============ PRINCIPAL ============ -->
<section class="section">
  <div class="wrap split" style="align-items:stretch">
    <div data-reveal>
      <div class="frame frame--left">
        <?= media('principal.jpg', 'Principal', 4, 'media--tall', '3/4') ?>
      </div>
    </div>
    <div data-reveal data-delay="1" style="display:flex;flex-direction:column;justify-content:center">
      <span class="eyebrow">From the Principal's Desk</span>
      <h2>“We do not prepare children only for exams. We prepare them for the rest of their lives.”</h2>
      <div class="rule"></div>
      <p class="lede mt-3">
        A school's worth is not measured by its buildings but by what its children become. At
        <?= e(SCHOOL_NAME) ?> we hold two things together at all times — high academic expectation and
        genuine kindness. Our teachers are trained to notice: the child who has stopped raising their
        hand, the one who needs a harder question, the one who simply needs to be asked how they are.
      </p>
      <p class="mt-2">
        To every parent considering us: come and see a working day. Watch a class, meet a teacher, ask
        our children what they learnt this morning. That visit will tell you more than any brochure can.
      </p>
      <p class="mt-3" style="font-family:var(--font-display);font-size:1.15rem;color:var(--ink);font-weight:700">
        Principal<br>
        <span style="font-size:.82rem;font-family:var(--font-body);color:var(--muted);font-weight:600;letter-spacing:.06em">
          <?= e(SCHOOL_NAME) ?>, <?= e(SCHOOL_CITY) ?>
        </span>
      </p>
    </div>
  </div>
</section>

<!-- ============ TESTIMONIALS ============ -->
<section class="section section--cream">
  <div class="wrap">
    <div class="head head--center" data-reveal>
      <span class="eyebrow eyebrow--center">Parent Voices</span>
      <h2>What our families say</h2>
      <div class="rule"></div>
    </div>

    <div class="grid g-3">
      <?php
      $quotes = [
          ['My daughter went from hiding behind me at the gate to leading the assembly. The teachers noticed her before I did.', 'Parent, Class III', 'A'],
          ['What convinced us was the follow-up. A teacher called about one weak topic in maths — nobody had to chase them.', 'Parent, Class VI', 'S'],
          ['Clean campus, punctual buses, and a class teacher who actually answers. As a working parent, that is everything.', 'Parent, Class I', 'R'],
      ];
      foreach ($quotes as $i => $q): ?>
        <figure class="quote" data-reveal data-delay="<?= $i ?>">
          <div class="stars"><?= str_repeat(icon('star'), 5) ?></div>
          <p><?= e($q[0]) ?></p>
          <figcaption class="quote__who">
            <span class="quote__ava"><?= e($q[2]) ?></span>
            <span><b>Verified Parent</b><span><?= e($q[1]) ?></span></span>
          </figcaption>
        </figure>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ NEWS ============ -->
<section class="section">
  <div class="wrap split split--wide" style="align-items:start">
    <div data-reveal>
      <span class="eyebrow">News &amp; Events</span>
      <h2>What's happening at school</h2>
      <div class="rule mb-3"></div>
      <?php
      $news = [
          ['12', 'Aug', 'Announcement', 'Admissions open for the new session', 'Application forms for Nursery to Class VIII are now available at the school office and online.'],
          ['05', 'Aug', 'Event', 'Independence Day preparations begin', 'House-wise march past, patriotic songs and a special assembly are being rehearsed.'],
          ['28', 'Jul', 'Achievement', 'Inter-school quiz — first position', 'Our Middle Wing team brought home the trophy from the district-level general knowledge quiz.'],
          ['20', 'Jul', 'Notice', 'Parent–Teacher Meeting', 'Term-one progress will be shared class-wise. Attendance of at least one parent is requested.'],
      ];
      foreach ($news as $n): ?>
        <article class="news">
          <div class="news__date"><b><?= e($n[0]) ?></b><span><?= e($n[1]) ?></span></div>
          <div>
            <span class="news__tag"><?= e($n[2]) ?></span>
            <h4><?= e($n[3]) ?></h4>
            <p><?= e($n[4]) ?></p>
          </div>
        </article>
      <?php endforeach; ?>
    </div>

    <div data-reveal data-delay="1">
      <div class="panel panel--cream">
        <span class="eyebrow">Admissions <?= date('Y') ?>–<?= date('y', strtotime('+1 year')) ?></span>
        <h3>Seats are limited by design.</h3>
        <p class="mt-2" style="font-size:.95rem">
          We cap every section to protect teaching quality, so classes fill early. Share your details and
          our admissions team will walk you through eligibility, fees and a campus visit.
        </p>
        <ul class="checklist mt-3">
          <li><?= icon('check') ?><span>No donation. Transparent, published fee structure.</span></li>
          <li><?= icon('check') ?><span>Campus visits welcome on any working day.</span></li>
          <li><?= icon('check') ?><span>Response within one working day.</span></li>
        </ul>
        <a class="btn btn--gold btn--block mt-3" href="<?= e(url('admissions.php#enquiry')) ?>">Enquire Now <?= icon('arrow') ?></a>
        <p class="mt-2 center" style="font-size:.84rem">
          or call <a href="tel:<?= e(str_replace(' ', '', SCHOOL_PHONE)) ?>" style="color:var(--navy-700);font-weight:700"><?= e(SCHOOL_PHONE) ?></a>
        </p>
      </div>
    </div>
  </div>
</section>

<!-- ============ CTA ============ -->
<section class="section section--tight">
  <div class="wrap">
    <div class="cta" data-reveal>
      <div class="cta__in">
        <div>
          <span class="eyebrow" style="color:var(--gold-300)">Visit Us</span>
          <h2>Come and see an ordinary working day.</h2>
          <p>No appointment theatre, no rehearsed tour. Walk the corridors, meet the teachers and decide for yourself.</p>
        </div>
        <div class="btn-row">
          <a class="btn btn--gold" href="<?= e(url('contact.php')) ?>">Plan a Visit <?= icon('arrow') ?></a>
          <a class="btn btn--light" href="tel:<?= e(str_replace(' ', '', SCHOOL_PHONE)) ?>"><?= icon('phone') ?> Call Now</a>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
