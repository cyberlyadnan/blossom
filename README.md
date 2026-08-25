# Blossom Public School — Website

A complete, professional school website in **plain PHP**. No framework, no build step,
no database, no Composer. Drop it into XAMPP and it runs.

---

## 1. Run it

1. Make sure the folder sits inside your XAMPP web root:
   `/Applications/XAMPP/xamppfiles/htdocs/blossom`
2. Start **Apache** from the XAMPP control panel.
3. Open <http://localhost/blossom/>

That's it. It also works if you rename or move the folder — links are auto-detected.

---

## 2. Edit the school's details (do this first)

Everything school-specific lives in one file: **`includes/config.php`**.
Look for the `«PLACEHOLDER»` comments and replace them:

| What | Constant |
|---|---|
| Address | `SCHOOL_ADDRESS_1`, `SCHOOL_ADDRESS_2` |
| Phone numbers | `SCHOOL_PHONE`, `SCHOOL_PHONE_ALT` |
| Email | `SCHOOL_EMAIL` |
| Year established | `SCHOOL_EST` |
| CBSE affiliation number | `SCHOOL_AFFIL_NO` |
| Facebook / YouTube / WhatsApp links | `SOCIAL_*` |
| Student / teacher counts on the homepage | `STAT_*` |
| Google Maps embed | `SCHOOL_MAP_EMBED` |

**Google Map:** open Google Maps → find the school → *Share* → *Embed a map* → copy only the
`src="..."` URL from the iframe → paste it into `SCHOOL_MAP_EMBED`.

---

## 3. Add real photos

Every image on the site is a **placeholder that disappears the moment you add a real file**.
Just drop a picture with the matching name into `assets/img/` — no code change needed.

| File name | Where it appears |
|---|---|
| `hero-1.jpg`, `hero-2.jpg`, `hero-3.jpg` | Homepage hero slideshow (wide, ~1920×1080) |
| `campus.jpg`, `about-campus.jpg` | Homepage & About |
| `principal.jpg` | Principal's message (portrait, 3:4) |
| `pre-primary.jpg`, `primary.jpg`, `middle.jpg` | The three academic wings |
| `classroom.jpg`, `lab.jpg`, `computer.jpg`, `library.jpg`, `sports.jpg`, `arts.jpg` | Facilities |
| `safety.jpg`, `bus.jpg` | Safety & Transport sections |
| `faculty.jpg` | Faculty page |
| `assets/img/staff/*.jpg` | Individual staff photos (square) — see `faculty.php` |
| `assets/img/gallery/*.jpg` | Gallery page — see the list at the top of `gallery.php` |

Keep photos under ~400 KB each so pages stay fast.

---

## 4. The enquiry form

Both **Admissions** and **Contact** carry a working form with validation, spam protection
(honeypot + CSRF token) and no double-submission on refresh.

- Every submission is saved to **`data/enquiries.csv`** — open it in Excel any time.
- To also receive submissions by **email**, set `ENQUIRY_SEND_MAIL = true` in `config.php`.
  (This needs a mail server — it works on live hosting, usually not on local XAMPP.)
- The `data/` folder is protected from direct web access by its own `.htaccess`.

If the CSV is not being written on your machine, give the folder write permission:
`chmod 777 data`

---

## 5. Editing the content

| Page | File |
|---|---|
| Homepage | `index.php` |
| About / Vision / Principal | `about.php` |
| Academics & wings | `academics.php` |
| Admissions, FAQs, form | `admissions.php` |
| Facilities, safety, transport | `facilities.php` |
| Gallery | `gallery.php` |
| Faculty | `faculty.php` |
| Contact & map | `contact.php` |
| Header, nav, footer | `includes/header.php`, `includes/footer.php` |
| Navigation menu | the `$NAV` array in `includes/config.php` |
| Colours, fonts, all styling | `assets/css/style.css` (design tokens at the very top) |
| Sliders, menu, counters, lightbox | `assets/js/main.js` |

**Homepage notices ticker, news list and testimonials** are simple PHP arrays inside
`index.php` — edit the text between the quotes.

### Changing the colours
Open `assets/css/style.css` and edit the variables in the `:root` block at the top
(`--navy-800`, `--gold-500`, and so on). Every element on every page updates at once.

---

## 6. Going live

1. Upload the whole folder to your hosting (any shared host with PHP 7.4+ works).
2. Set `ENQUIRY_SEND_MAIL = true` in `config.php`.
3. Edit `.htaccess` and change `ErrorDocument 404 /blossom/404.php` to `/404.php`
   if the site sits at the domain root.
4. Make sure the `data/` folder is writable.

---

Built with plain PHP, hand-written CSS and vanilla JavaScript — nothing to install,
nothing to break.
