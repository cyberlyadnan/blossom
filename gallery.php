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

$photos = [
    ['principal.jpg',                'Principal\'s Office — Seated at Desk',             'achievements'],
    ['achievers.jpg',                'Principal Felicitating Student Achievers',         'achievements'],
    ['gallery/campus-courtyard.jpg', 'Main Building Atrium & Courtyard',               'campus'],
    ['gallery/campus-balconies.jpg', 'Multi-Level Campus Balconies with Floral Decor',  'campus'],
    ['gallery/computer-lab-hall.jpg','Classroom Corridor & Computer Lab Entrance',     'classroom'],
    ['gallery/campus-corridor.jpg',  'School Corridor & Reception Area',                'campus'],
    ['gallery/balcony-walkway.jpg',  'Bougainvillea Balcony Walkway',                   'campus'],
    ['gallery/rooftop-garden.jpg',   'Rooftop Green Potted Cypress Garden',             'activities'],
    ['gallery/floral-terrace.jpg',   'Blooming Pink Bougainvillea Terrace',             'activities'],
    ['gallery/school-event-01.jpg',  'Annual Day Cultural Performance',                 'events'],
    ['gallery/school-event-02.jpg',  'Independence Day Celebration',                    'events'],
    ['gallery/school-event-03.jpg',  'Inter-House Quiz Competition',                    'events'],
    ['gallery/school-event-04.jpg',  'Science Exhibition Display',                      'events'],
    ['gallery/school-event-05.jpg',  'Republic Day March Past',                         'events'],
    ['gallery/school-event-06.jpg',  'Teachers Day Celebration',                        'events'],
    ['gallery/school-event-07.jpg',  'Children Day Funfair & Games',                    'events'],
    ['gallery/school-event-08.jpg',  'Annual Sports Meet',                              'events'],
    ['gallery/school-event-09.jpg',  'Prize Distribution Ceremony',                     'events'],
    ['gallery/school-event-10.jpg',  'Art & Craft Exhibition',                          'events'],
    ['gallery/school-event-11.jpg',  'Storytelling & Drama Session',                    'events'],
    ['gallery/school-event-12.jpg',  'Inter-School Debate Championship',                'events'],
    ['gallery/school-event-13.jpg',  'Music & Choir Performance',                       'events'],
    ['gallery/school-event-14.jpg',  'School Assembly Gathering',                       'events'],
    ['gallery/school-event-15.jpg',  'Plantation & Environment Drive',                  'events'],
    ['gallery/school-event-16.jpg',  'Yoga & Physical Fitness Session',                 'events'],
    ['gallery/school-event-17.jpg',  'Parent-Teacher Interaction Day',                  'events'],
    ['gallery/school-event-18.jpg',  'Robotics & Science Activity',                     'events'],
    ['gallery/school-event-19.jpg',  'Classroom Group Activity',                        'classroom'],
    ['gallery/school-event-20.jpg',  'Reading Hour in Library',                         'classroom'],
    ['gallery/school-event-21.jpg',  'Smart Board Interactive Lesson',                  'classroom'],
    ['gallery/school-event-22.jpg',  'Science Lab Practical Session',                   'classroom'],
    ['gallery/school-event-23.jpg',  'Computer Lab Coding Activity',                    'classroom'],
    ['gallery/school-event-24.jpg',  'Junior Wing Play & Learn',                        'classroom'],
    ['gallery/school-event-25.jpg',  'Pre-Primary Fun Learning Activity',               'classroom'],
    ['gallery/school-event-26.jpg',  'Campus Green Corner',                             'activities'],
    ['gallery/school-event-27.jpg',  'Outdoor Games & Athletics',                       'activities'],
    ['gallery/school-event-28.jpg',  'Student Achievement Felicitations',               'achievements'],
    ['gallery/school-event-29.jpg',  'Excellence Award Presentation',                   'achievements'],
    ['gallery/school-event-30.jpg',  'School Festivities & Celebrations',               'events'],
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
