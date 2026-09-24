<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/icons.php';

$page_title = $page_title ?? SCHOOL_NAME;
$page_desc  = $page_desc  ?? SCHOOL_NAME . ' — a ' . SCHOOL_BOARD . ' school in ' . SCHOOL_CITY . ' for ' . SCHOOL_GRADES . '. ' . SCHOOL_TAGLINE . '.';
$flash      = flash_get();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($page_title) ?> · <?= e(SCHOOL_NAME) ?></title>
<meta name="description" content="<?= e($page_desc) ?>">
<meta name="theme-color" content="#0B2A5B">
<meta property="og:type" content="website">
<meta property="og:title" content="<?= e($page_title) ?> · <?= e(SCHOOL_NAME) ?>">
<meta property="og:description" content="<?= e($page_desc) ?>">
<meta property="og:site_name" content="<?= e(SCHOOL_NAME) ?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600;1,700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= e(asset('css/style.css')) ?>">
<link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'><rect width='64' height='64' rx='14' fill='%230B2A5B'/><circle cx='32' cy='32' r='13' fill='%23D9AF3C'/><circle cx='32' cy='32' r='5' fill='%230B2A5B'/></svg>">
<script type="application/ld+json">
<?= json_encode([
    '@context' => 'https://schema.org',
    '@type'    => 'School',
    'name'     => SCHOOL_NAME,
    'slogan'   => SCHOOL_TAGLINE,
    'telephone'=> SCHOOL_PHONE,
    'email'    => SCHOOL_EMAIL,
    'address'  => [
        '@type' => 'PostalAddress',
        'streetAddress'   => SCHOOL_ADDRESS_1,
        'addressLocality' => SCHOOL_CITY,
        'addressRegion'   => SCHOOL_STATE,
        'addressCountry'  => 'IN',
    ],
    'sameAs' => array_values(array_filter([SOCIAL_INSTAGRAM, SOCIAL_FACEBOOK, SOCIAL_YOUTUBE], fn($u) => $u !== '#')),
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) ?>
</script>
</head>
<body>

<!-- ============ TOP BAR ============ -->
<div class="topbar">
  <div class="wrap topbar__in">
    <ul class="topbar__list">
      <li><a href="tel:<?= e(str_replace(' ', '', SCHOOL_PHONE)) ?>"><?= icon('phone') ?><?= e(SCHOOL_PHONE) ?></a></li>
      <li><a href="mailto:<?= e(SCHOOL_EMAIL) ?>"><?= icon('mail') ?><?= e(SCHOOL_EMAIL) ?></a></li>
      <li class="only-lg"><?= icon('clock') ?><?= e(SCHOOL_HOURS) ?></li>
    </ul>
    <div class="topbar__socials">
      <a href="<?= e(SOCIAL_INSTAGRAM) ?>" target="_blank" rel="noopener" aria-label="Instagram"><?= icon('instagram') ?></a>
      <a href="<?= e(SOCIAL_FACEBOOK) ?>" target="_blank" rel="noopener" aria-label="Facebook"><?= icon('facebook') ?></a>
      <a href="<?= e(SOCIAL_YOUTUBE) ?>" target="_blank" rel="noopener" aria-label="YouTube"><?= icon('youtube') ?></a>
    </div>
  </div>
</div>

<!-- ============ HEADER ============ -->
<header class="site-header">
  <div class="wrap header__in">
    <a class="brand" href="<?= e(url('index.php')) ?>">
      <?= crest(46) ?>
      <span class="brand__text">
        <span class="brand__name"><?= e(SCHOOL_NAME) ?></span>
        <span class="brand__sub"><?= e(SCHOOL_BOARD) ?> · <?= e(SCHOOL_CITY) ?></span>
      </span>
    </a>

    <nav class="nav" aria-label="Primary">
      <?php foreach ($NAV as $item): ?>
        <div class="nav__item<?= is_current($item['file']) ? ' is-active' : '' ?>">
          <a class="nav__link" href="<?= e(url($item['file'])) ?>">
            <?= e($item['label']) ?>
            <?php if (!empty($item['children'])) echo icon('chevron', 'nav__caret'); ?>
          </a>
          <?php if (!empty($item['children'])): ?>
            <div class="nav__sub">
              <?php foreach ($item['children'] as $child): ?>
                <a href="<?= e(url($child['file'])) ?>"><?= e($child['label']) ?></a>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </nav>

    <div class="header__cta">
      <a class="btn btn--gold btn--sm" href="<?= e(url('admissions.php#enquiry')) ?>">Apply for Admission</a>
      <button class="burger" aria-label="Open menu" aria-expanded="false" type="button">
        <svg class="burger__svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true" focusable="false">
          <line class="burger__line burger__line--top" x1="4" y1="6" x2="20" y2="6" />
          <line class="burger__line burger__line--mid" x1="4" y1="12" x2="20" y2="12" />
          <line class="burger__line burger__line--bot" x1="4" y1="18" x2="20" y2="18" />
        </svg>
      </button>
    </div>
  </div>
</header>

<!-- ============ MOBILE DRAWER ============ -->
<div class="scrim"></div>
<aside class="drawer" aria-label="Mobile menu">
  <div class="drawer__top">
    <a class="brand" href="<?= e(url('index.php')) ?>">
      <?= crest(38) ?>
      <span class="brand__text"><span class="brand__name"><?= e(SCHOOL_SHORT) ?></span><span class="brand__sub"><?= e(SCHOOL_BOARD) ?></span></span>
    </a>
    <button class="drawer__close" aria-label="Close menu" type="button"><?= icon('close') ?></button>
  </div>
  <nav class="drawer__nav">
    <?php foreach ($NAV as $item): ?>
      <a href="<?= e(url($item['file'])) ?>"><?= e($item['label']) ?></a>
      <?php if (!empty($item['children'])): ?>
        <div class="sub">
          <?php foreach ($item['children'] as $child): ?>
            <a href="<?= e(url($child['file'])) ?>">— <?= e($child['label']) ?></a>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    <?php endforeach; ?>
  </nav>
  <div class="drawer__foot">
    <a class="btn btn--gold btn--block" href="<?= e(url('admissions.php#enquiry')) ?>">Apply for Admission</a>
    <p style="margin-top:14px;font-size:.85rem"><?= icon('phone') ?> <a href="tel:<?= e(str_replace(' ', '', SCHOOL_PHONE)) ?>"><?= e(SCHOOL_PHONE) ?></a></p>
  </div>
</aside>
