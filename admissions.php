<?php
require_once __DIR__ . '/includes/enquiry.php';   // must run before any output
$page_title = 'Admissions';
$page_desc  = 'Admission process, age criteria, documents and enquiry form for ' . SCHOOL_NAME . ', ' . SCHOOL_CITY . '.';
require_once __DIR__ . '/includes/header.php';
$session = date('Y') . '–' . date('y', strtotime('+1 year'));
?>

<section class="page-hero">
  <div class="wrap page-hero__in">
    <nav class="crumbs" aria-label="Breadcrumb"><a href="<?= e(url('index')) ?>">Home</a><span>/</span>Admissions</nav>
    <h1>Admissions <?= e($session) ?></h1>
    <p>Open for Nursery to Class VIII. Sections are capped by design, so early applications are strongly advised.</p>
    <div class="btn-row mt-3">
      <a class="btn btn--gold" href="#enquiry">Fill the Enquiry Form <?= icon('arrow') ?></a>
      <a class="btn btn--light" href="tel:<?= e(str_replace(' ', '', SCHOOL_PHONE)) ?>"><?= icon('phone') ?> <?= e(SCHOOL_PHONE) ?></a>
    </div>
  </div>
</section>

<!-- ============ PROCESS ============ -->
<section class="section">
  <div class="wrap">
    <div class="head head--center" data-reveal>
      <span class="eyebrow eyebrow--center">The Process</span>
      <h2>Four steps, start to seat</h2>
      <div class="rule"></div>
      <p class="lede mt-2">No agents, no donation, no hidden stage. Everything below happens at the school office.</p>
    </div>

    <div class="steps">
      <?php
      $steps = [
          ['Enquire or visit', 'Submit the form below, call the office, or simply walk in on a working day. We will confirm seat availability for the class you need.'],
          ['Collect and submit the form', 'Collect the registration form from the office (or receive it by email), complete it and submit it with the documents listed below.'],
          ['Interaction', 'A short, friendly interaction — with the child for Pre-Primary, and a basic written assessment in English and Mathematics from Class I upward. It is used for placement, not for rejection.'],
          ['Confirmation and fee payment', 'On selection, the admission is confirmed against payment of the admission and first-term fee. You will receive the fee receipt, book list, uniform details and transport route on the same day.'],
      ];
      foreach ($steps as $i => $s): ?>
        <article class="step" data-reveal data-delay="<?= $i % 3 ?>">
          <h4><?= e($s[0]) ?></h4>
          <p><?= e($s[1]) ?></p>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ CRITERIA + DOCUMENTS ============ -->
<section class="section section--cream">
  <div class="wrap split split--wide" style="align-items:start">
    <div data-reveal id="criteria">
      <span class="eyebrow">Age Criteria</span>
      <h2 style="font-size:clamp(1.5rem,2.4vw,2.1rem)">Who can apply, and when</h2>
      <div class="rule mb-3"></div>
      <div class="table-wrap">
        <table class="tbl">
          <thead><tr><th>Class</th><th>Age as on 31 March</th></tr></thead>
          <tbody>
            <tr><td>Nursery</td><td>3+ years</td></tr>
            <tr><td>LKG</td><td>4+ years</td></tr>
            <tr><td>UKG</td><td>5+ years</td></tr>
            <tr><td>Class I</td><td>6+ years</td></tr>
            <tr><td>Class II – VIII</td><td>As per the previous class completed</td></tr>
          </tbody>
        </table>
      </div>
      <p class="field__hint mt-2">Age criteria follow the norms in force for the session. The office will confirm eligibility for your child's date of birth.</p>
    </div>

    <div data-reveal data-delay="1" id="documents">
      <span class="eyebrow">Documents</span>
      <h2 style="font-size:clamp(1.5rem,2.4vw,2.1rem)">What to bring along</h2>
      <div class="rule mb-3"></div>
      <ul class="checklist">
        <li><?= icon('check') ?><span>Birth certificate (original for verification, one photocopy)</span></li>
        <li><?= icon('check') ?><span>Four recent passport-size photographs of the child</span></li>
        <li><?= icon('check') ?><span>Transfer Certificate from the previous school (Class I and above)</span></li>
        <li><?= icon('check') ?><span>Report card / mark sheet of the last class attended</span></li>
        <li><?= icon('check') ?><span>Aadhaar card of the child and both parents (photocopy)</span></li>
        <li><?= icon('check') ?><span>Address proof and two photographs of each parent</span></li>
        <li><?= icon('check') ?><span>Medical record — blood group, allergies, any ongoing condition</span></li>
      </ul>
      <div class="panel panel--cream mt-3">
        <h4><?= icon('bulb') ?> A note on fees</h4>
        <p class="mt-1" style="font-size:.92rem">
          Our fee structure is published and identical for every family — there is no donation, capitation
          or development levy of any kind. The office will hand you the complete term-wise breakdown in
          writing before you commit to anything.
        </p>
      </div>
    </div>
  </div>
</section>

<!-- ============ ENQUIRY FORM ============ -->
<section class="section" id="enquiry">
  <div class="wrap split split--wide" style="align-items:start">
    <div data-reveal>
      <span class="eyebrow">Admission Enquiry</span>
      <h2>Tell us about your child</h2>
      <div class="rule"></div>
      <p class="lede mt-3">
        Fill this in and our admissions team will call you within one working day with seat
        availability, the fee structure and a convenient time to visit.
      </p>

      <div class="info-panel mt-3">
        <h3>Admissions Desk</h3>
        <ul class="ilist mt-3">
          <li><span class="ico"><?= icon('phone') ?></span><div><h4>Call</h4><p><a href="tel:<?= e(str_replace(' ', '', SCHOOL_PHONE)) ?>"><?= e(SCHOOL_PHONE) ?></a> · <a href="tel:<?= e(str_replace(' ', '', SCHOOL_PHONE_ALT)) ?>"><?= e(SCHOOL_PHONE_ALT) ?></a></p></div></li>
          <li><span class="ico"><?= icon('mail') ?></span><div><h4>Email</h4><p><a href="mailto:<?= e(SCHOOL_EMAIL) ?>"><?= e(SCHOOL_EMAIL) ?></a></p></div></li>
          <li><span class="ico"><?= icon('clock') ?></span><div><h4>Office Hours</h4><p><?= e(SCHOOL_OFFICE) ?></p></div></li>
          <li><span class="ico"><?= icon('pin') ?></span><div><h4>Campus</h4><p><?= e(SCHOOL_ADDRESS_1) ?>, <?= e(SCHOOL_ADDRESS_2) ?></p></div></li>
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

        <h3>Admission Enquiry Form</h3>
        <p class="field__hint mb-2">Fields marked <span class="req" style="color:var(--err)">*</span> are required.</p>

        <form class="form" method="post" action="<?= e(url('admissions')) ?>#enquiry" novalidate>
          <?= csrf_field() ?>
          <input type="hidden" name="form" value="admission">
          <input type="hidden" name="_redirect" value="admissions">
          <div class="hp"><label>Leave this empty <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>

          <div class="form__row">
            <div class="field<?= has_error('parent') ? ' has-error' : '' ?>">
              <label for="parent">Parent / Guardian Name <span class="req">*</span></label>
              <input type="text" id="parent" name="parent" value="<?= old('parent') ?>" placeholder="Full name" required>
              <?= field_error('parent') ?>
            </div>
            <div class="field<?= has_error('phone') ? ' has-error' : '' ?>">
              <label for="phone">Mobile Number <span class="req">*</span></label>
              <input type="tel" id="phone" name="phone" value="<?= old('phone') ?>" placeholder="10-digit mobile" required>
              <?= field_error('phone') ?>
            </div>
          </div>

          <div class="field<?= has_error('email') ? ' has-error' : '' ?>">
            <label for="email">Email Address <span class="req">*</span></label>
            <input type="email" id="email" name="email" value="<?= old('email') ?>" placeholder="you@example.com" required>
            <?= field_error('email') ?>
          </div>

          <div class="form__row">
            <div class="field">
              <label for="student">Child's Name</label>
              <input type="text" id="student" name="student" value="<?= old('student') ?>" placeholder="Student name">
            </div>
            <div class="field<?= has_error('grade') ? ' has-error' : '' ?>">
              <label for="grade">Class Applying For <span class="req">*</span></label>
              <select id="grade" name="grade" required>
                <option value="">Select a class</option>
                <?php
                $classes = ['Nursery', 'LKG', 'UKG', 'Class I', 'Class II', 'Class III', 'Class IV', 'Class V', 'Class VI', 'Class VII', 'Class VIII'];
                foreach ($classes as $c): ?>
                  <option value="<?= e($c) ?>"<?= old('grade') === $c ? ' selected' : '' ?>><?= e($c) ?></option>
                <?php endforeach; ?>
              </select>
              <?= field_error('grade') ?>
            </div>
          </div>

          <div class="field<?= has_error('message') ? ' has-error' : '' ?>">
            <label for="message">Anything you would like us to know?</label>
            <textarea id="message" name="message" placeholder="Questions about transport, fees, a campus visit…"><?= old('message') ?></textarea>
            <?= field_error('message') ?>
          </div>

          <button type="submit" class="btn btn--gold btn--block">Submit Enquiry <?= icon('arrow') ?></button>
          <p class="field__hint center">We use your details only to respond to this enquiry.</p>
        </form>
      </div>
    </div>
  </div>
</section>

<!-- ============ FAQ ============ -->
<section class="section section--cream" id="faq">
  <div class="wrap" style="max-width:900px">
    <div class="head head--center" data-reveal>
      <span class="eyebrow eyebrow--center">Admission FAQs</span>
      <h2>Questions parents ask us most</h2>
      <div class="rule"></div>
    </div>
    <div data-acc-group data-reveal>
      <?php
      $faqs = [
          ['When do admissions open for the new session?', 'Registration usually opens a few months before the session begins in April, and continues until sections are full. Mid-session admissions are considered against vacancies, so it is always worth calling the office.'],
          ['Is there an entrance examination?', 'For Pre-Primary there is only a friendly interaction with the child and a short conversation with parents. From Class I upward there is a basic written assessment in English and Mathematics, used to place the child correctly — not to filter families.'],
          ['Do you charge a donation or capitation fee?', 'No. There is no donation, capitation or development charge. The published fee structure is the same for every family and is given to you in writing.'],
          ['Is transport available in my area?', 'The school runs buses across the main routes in ' . SCHOOL_CITY . '. Share your locality with the office and they will confirm whether an existing route covers you and what the fare would be.'],
          ['What is the maximum class size?', 'Sections are capped so that teaching stays personal. This is the single thing we protect most carefully, which is also why classes fill early.'],
          ['Can I visit the school before applying?', 'Please do. Visit on any working day during office hours — no appointment is necessary, though a call ahead helps us make sure the right person is free to walk you around.'],
      ];
      foreach ($faqs as $i => $f): ?>
        <div class="acc">
          <button class="acc__q" aria-expanded="false"><span><?= e($f[0]) ?></span><span class="acc__sign">+</span></button>
          <div class="acc__a"><div><?= e($f[1]) ?></div></div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
