<?php
require_once __DIR__ . '/includes/enquiry.php';   // must run before any output
$page_title = 'Contact Us';
$page_desc  = 'Address, phone numbers, office hours and enquiry form for ' . SCHOOL_NAME . ', ' . SCHOOL_CITY . '.';
require_once __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
  <div class="wrap page-hero__in">
    <nav class="crumbs" aria-label="Breadcrumb"><a href="<?= e(url('index.php')) ?>">Home</a><span>/</span>Contact</nav>
    <h1>Contact Us</h1>
    <p>Call, write, or simply walk in during office hours. Someone from the school will always speak with you.</p>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <div class="grid g-4" data-reveal>
      <?php
      $cards = [
          ['pin',   'Visit the Campus', SCHOOL_ADDRESS_1 . '<br>' . SCHOOL_ADDRESS_2, ''],
          ['phone', 'Call the Office',  SCHOOL_PHONE . '<br>' . SCHOOL_PHONE_ALT, 'tel:' . str_replace(' ', '', SCHOOL_PHONE)],
          ['mail',  'Write to Us',      SCHOOL_EMAIL, 'mailto:' . SCHOOL_EMAIL],
          ['clock', 'Office Hours',     SCHOOL_HOURS . '<br>' . SCHOOL_OFFICE, ''],
      ];
      foreach ($cards as $i => $c): ?>
        <article class="card" data-reveal data-delay="<?= $i ?>">
          <span class="card__ico"><?= icon($c[0]) ?></span>
          <h3 style="font-size:1.08rem"><?= e($c[1]) ?></h3>
          <p class="mt-1" style="font-size:.92rem">
            <?php if ($c[3]): ?><a href="<?= e($c[3]) ?>" style="color:var(--navy-700);font-weight:600"><?= $c[2] ?></a><?php else: ?><?= $c[2] ?><?php endif; ?>
          </p>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section section--cream" id="enquiry">
  <div class="wrap split split--wide" style="align-items:start">
    <div data-reveal>
      <span class="eyebrow">Send a Message</span>
      <h2>How can we help?</h2>
      <div class="rule"></div>
      <p class="lede mt-3">
        Admissions, transport routes, fee details, a campus visit or a concern about your child — write
        to us here and the right person will get back to you within one working day.
      </p>

      <div class="info-panel mt-3">
        <h3>Quick answers</h3>
        <ul class="ilist mt-3">
          <li><span class="ico"><?= icon('book') ?></span><div><h4>Admissions</h4><p>Seat availability, forms and fee structure — <a href="<?= e(url('admissions.php')) ?>">see the admissions page</a>.</p></div></li>
          <li><span class="ico"><?= icon('bus') ?></span><div><h4>Transport</h4><p>Tell us your locality and we will confirm the nearest route and stop.</p></div></li>
          <li><span class="ico"><?= icon('users') ?></span><div><h4>Existing parents</h4><p>For anything class-specific, your class teacher is the fastest route.</p></div></li>
          <li><span class="ico"><?= icon('whatsapp') ?></span><div><h4>WhatsApp</h4><p><a href="<?= e(SOCIAL_WHATSAPP) ?>" target="_blank" rel="noopener">Message the school office</a></p></div></li>
        </ul>
      </div>
    </div>

    <div data-reveal data-delay="1">
      <div class="form-panel">
        <?php if ($flash): ?>
          <div class="alert alert--<?= $flash['type'] === 'ok' ? 'ok' : 'err' ?> mb-2">
            <?= icon($flash['type'] === 'ok' ? 'check' : 'shield') ?>
            <span><?= e($flash['message']) ?></span>
          </div>
        <?php endif; ?>

        <h3>Enquiry Form</h3>
        <p class="field__hint mb-2">Fields marked <span style="color:var(--err)">*</span> are required.</p>

        <form class="form" method="post" action="<?= e(url('contact.php')) ?>#enquiry" novalidate>
          <?= csrf_field() ?>
          <input type="hidden" name="form" value="contact">
          <input type="hidden" name="_redirect" value="contact.php">
          <div class="hp"><label>Leave this empty <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>

          <div class="form__row">
            <div class="field<?= has_error('parent') ? ' has-error' : '' ?>">
              <label for="parent">Your Name <span style="color:var(--err)">*</span></label>
              <input type="text" id="parent" name="parent" value="<?= old('parent') ?>" placeholder="Full name" required>
              <?= field_error('parent') ?>
            </div>
            <div class="field<?= has_error('phone') ? ' has-error' : '' ?>">
              <label for="phone">Mobile Number <span style="color:var(--err)">*</span></label>
              <input type="tel" id="phone" name="phone" value="<?= old('phone') ?>" placeholder="10-digit mobile" required>
              <?= field_error('phone') ?>
            </div>
          </div>

          <div class="field<?= has_error('email') ? ' has-error' : '' ?>">
            <label for="email">Email Address <span style="color:var(--err)">*</span></label>
            <input type="email" id="email" name="email" value="<?= old('email') ?>" placeholder="you@example.com" required>
            <?= field_error('email') ?>
          </div>

          <div class="field">
            <label for="subject">Subject</label>
            <select id="subject" name="subject">
              <?php foreach (['General enquiry', 'Admission enquiry', 'Transport / bus route', 'Fee related', 'Campus visit', 'Careers', 'Other'] as $s): ?>
                <option value="<?= e($s) ?>"<?= old('subject') === $s ? ' selected' : '' ?>><?= e($s) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="field<?= has_error('message') ? ' has-error' : '' ?>">
            <label for="message">Your Message</label>
            <textarea id="message" name="message" placeholder="How can we help?"><?= old('message') ?></textarea>
            <?= field_error('message') ?>
          </div>

          <button type="submit" class="btn btn--gold btn--block">Send Message <?= icon('arrow') ?></button>
          <p class="field__hint center">We use your details only to respond to this message.</p>
        </form>
      </div>
    </div>
  </div>
</section>

<!-- ============ MAP ============ -->
<section class="section section--tight">
  <div class="wrap">
    <div class="head head--center" data-reveal>
      <span class="eyebrow eyebrow--center">Find Us</span>
      <h2>On the map</h2>
      <div class="rule"></div>
    </div>
    <?php if (SCHOOL_MAP_EMBED !== ''): ?>
      <div style="border-radius:var(--r-lg);overflow:hidden;border:1px solid var(--line)" data-reveal>
        <iframe src="<?= e(SCHOOL_MAP_EMBED) ?>" width="100%" height="440" style="border:0;display:block"
                allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="School location"></iframe>
      </div>
    <?php else: ?>
      <div class="panel center" data-reveal style="background:var(--navy-50);border-style:dashed">
        <span class="card__ico mx-auto"><?= icon('pin') ?></span>
        <h3 class="mt-2"><?= e(SCHOOL_NAME) ?></h3>
        <p class="mt-1"><?= e(SCHOOL_ADDRESS_1) ?><br><?= e(SCHOOL_ADDRESS_2) ?></p>
        <p class="field__hint mt-2">
          To show a live map here, paste your Google Maps embed link into
          <code>includes/config.php</code> &rarr; <code>SCHOOL_MAP_EMBED</code>.
        </p>
        <a class="btn btn--ghost mt-3" href="https://www.google.com/maps/search/?api=1&amp;query=<?= rawurlencode(SCHOOL_NAME . ', ' . SCHOOL_CITY) ?>" target="_blank" rel="noopener">Open in Google Maps <?= icon('arrow') ?></a>
      </div>
    <?php endif; ?>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
