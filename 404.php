<?php
http_response_code(404);
$page_title = 'Page Not Found';
require_once __DIR__ . '/includes/header.php';
?>
<section class="page-hero">
  <div class="wrap page-hero__in">
    <h1 style="font-size:clamp(3rem,9vw,6rem);line-height:1">404</h1>
    <p>That page has wandered off the timetable. Let's get you back to class.</p>
    <div class="btn-row mt-3">
      <a class="btn btn--gold" href="<?= e(url('index.php')) ?>">Back to Home <?= icon('arrow') ?></a>
      <a class="btn btn--light" href="<?= e(url('contact.php')) ?>">Contact the Office</a>
    </div>
  </div>
</section>
<section class="section">
  <div class="wrap">
    <div class="grid g-4">
      <?php foreach ([['About', 'about.php'], ['Academics', 'academics.php'], ['Admissions', 'admissions.php'], ['Gallery', 'gallery.php']] as $l): ?>
        <a class="card" href="<?= e(url($l[1])) ?>"><span class="card__ico"><?= icon('arrow') ?></span><h3 style="font-size:1.05rem"><?= e($l[0]) ?></h3></a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
