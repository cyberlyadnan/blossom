<!-- ============ FOOTER ============ -->
<footer class="footer">
  <div class="wrap">
    <div class="footer__grid">

      <div>
        <div class="footer__brand">
          <?= crest(50) ?>
          <span class="brand__text">
            <span class="brand__name"><?= e(SCHOOL_NAME) ?></span>
            <span class="brand__sub"><?= e(SCHOOL_TAGLINE) ?></span>
          </span>
        </div>
        <p>A <?= e(SCHOOL_BOARD) ?> school in <?= e(SCHOOL_CITY) ?> for <?= e(SCHOOL_GRADES) ?>, where academic rigour meets genuine warmth — and every child is known by name.</p>
        <div class="socials">
          <a href="<?= e(SOCIAL_INSTAGRAM) ?>" target="_blank" rel="noopener" aria-label="Instagram"><?= icon('instagram') ?></a>
          <a href="<?= e(SOCIAL_FACEBOOK) ?>" target="_blank" rel="noopener" aria-label="Facebook"><?= icon('facebook') ?></a>
          <a href="<?= e(SOCIAL_YOUTUBE) ?>" target="_blank" rel="noopener" aria-label="YouTube"><?= icon('youtube') ?></a>
          <a href="<?= e(SOCIAL_WHATSAPP) ?>" target="_blank" rel="noopener" aria-label="WhatsApp"><?= icon('whatsapp') ?></a>
        </div>
      </div>

      <div>
        <h4>Explore</h4>
        <ul class="footer__links">
          <li><a href="<?= e(url('about.php')) ?>">About the School</a></li>
          <li><a href="<?= e(url('academics.php')) ?>">Academics</a></li>
          <li><a href="<?= e(url('facilities.php')) ?>">Facilities</a></li>
          <li><a href="<?= e(url('faculty.php')) ?>">Our Faculty</a></li>
          <li><a href="<?= e(url('gallery.php')) ?>">Gallery</a></li>
        </ul>
      </div>

      <div>
        <h4>Admissions</h4>
        <ul class="footer__links">
          <li><a href="<?= e(url('admissions.php')) ?>">Admission Process</a></li>
          <li><a href="<?= e(url('admissions.php#criteria')) ?>">Age Criteria</a></li>
          <li><a href="<?= e(url('admissions.php#documents')) ?>">Documents Required</a></li>
          <li><a href="<?= e(url('admissions.php#faq')) ?>">Admission FAQs</a></li>
          <li><a href="<?= e(url('contact.php')) ?>">Visit the Campus</a></li>
        </ul>
      </div>

      <div>
        <h4>Reach Us</h4>
        <ul class="footer__contact">
          <li><?= icon('pin') ?><span><?= e(SCHOOL_ADDRESS_1) ?><br><?= e(SCHOOL_ADDRESS_2) ?></span></li>
          <li><?= icon('phone') ?><span><a href="tel:<?= e(str_replace(' ', '', SCHOOL_PHONE)) ?>"><?= e(SCHOOL_PHONE) ?></a><br><a href="tel:<?= e(str_replace(' ', '', SCHOOL_PHONE_ALT)) ?>"><?= e(SCHOOL_PHONE_ALT) ?></a></span></li>
          <li><?= icon('mail') ?><span><a href="mailto:<?= e(SCHOOL_EMAIL) ?>"><?= e(SCHOOL_EMAIL) ?></a></span></li>
          <li><?= icon('clock') ?><span><?= e(SCHOOL_HOURS) ?><br><?= e(SCHOOL_OFFICE) ?></span></li>
        </ul>
      </div>

    </div>

    <div class="footer__bar">
      <p>&copy; <span data-year><?= date('Y') ?></span> <?= e(SCHOOL_NAME) ?>, <?= e(SCHOOL_CITY) ?>. All rights reserved.</p>
      <p><a href="<?= e(url('contact.php')) ?>">Contact</a> &nbsp;·&nbsp; <a href="<?= e(url('admissions.php')) ?>">Admissions</a> &nbsp;·&nbsp; Affiliation No. <?= e(SCHOOL_AFFIL_NO) ?></p>
    </div>
  </div>
</footer>

<!-- ============ FLOATING ACTIONS ============ -->
<div class="floaters">
  <a class="fl-wa" href="<?= e(SOCIAL_WHATSAPP) ?>" target="_blank" rel="noopener" aria-label="Chat on WhatsApp"><?= icon('whatsapp') ?></a>
  <a class="fl-call" href="tel:<?= e(str_replace(' ', '', SCHOOL_PHONE)) ?>" aria-label="Call the school"><?= icon('phone') ?></a>
  <button class="fl-top" aria-label="Back to top"><?= icon('chevron') ?></button>
</div>

<!-- ============ LIGHTBOX ============ -->
<div class="lightbox" role="dialog" aria-modal="true" aria-label="Photo viewer">
  <button class="lightbox__close" aria-label="Close">&times;</button>
  <div class="lightbox__inner">
    <div class="media-slot"></div>
    <p class="lightbox__cap"></p>
  </div>
</div>

<script src="<?= e(asset('js/main.js')) ?>" defer></script>
</body>
</html>

<?php errors_clear(); old_clear(); ?>
