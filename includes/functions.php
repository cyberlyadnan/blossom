<?php
/* Shared helpers. Included by every page through header.php. */

declare(strict_types=1);

/** Escape for HTML output. */
function e(?string $s): string
{
    return htmlspecialchars((string) $s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Build a site URL. */
function url(string $path = ''): string
{
    return BASE_URL . '/' . ltrim($path, '/');
}

/** Build an asset URL. */
function asset(string $path): string
{
    $filePath = __DIR__ . '/../assets/' . ltrim($path, '/');
    $ver = file_exists($filePath) ? '?v=' . filemtime($filePath) : '';
    return BASE_URL . '/assets/' . ltrim($path, '/') . $ver;
}

/** True when $file is the page currently being viewed. */
function is_current(string $file): bool
{
    $current = basename($_SERVER['SCRIPT_NAME'] ?? '');
    return $current === strtok($file, '#');
}

/**
 * Renders a photo if it exists in /assets/img, otherwise a styled placeholder.
 * Drop a real file at assets/img/<name> and it is picked up automatically.
 *
 * @param string $name   file name inside assets/img (e.g. 'campus.jpg')
 * @param string $label  caption shown on the placeholder
 * @param int    $tone   1-8, picks the placeholder colour blend
 * @param string $class  extra CSS classes for the wrapper
 * @param string $ratio  CSS aspect-ratio, e.g. '16/9', '4/3', '1/1'
 */
function media(string $name, string $label = '', int $tone = 1, string $class = '', string $ratio = '4/3'): string
{
    $baseDir = __DIR__ . '/../assets/img/';
    $file = $baseDir . $name;
    $foundName = null;

    if (is_file($file)) {
        $foundName = $name;
    } else {
        $info = pathinfo($name);
        $dirname = ($info['dirname'] !== '.' && $info['dirname'] !== '') ? $info['dirname'] . '/' : '';
        $filename = trim($info['filename']);
        $exts = ['png', 'jpg', 'jpeg', 'webp', 'PNG', 'JPG', 'JPEG'];
        foreach ($exts as $ext) {
            $altName = $dirname . $filename . '.' . $ext;
            if (is_file($baseDir . $altName)) {
                $foundName = $altName;
                break;
            }
        }
    }

    $style = 'aspect-ratio:' . $ratio . ';';
    if ($foundName !== null) {
        return '<figure class="media ' . e($class) . '" style="' . e($style) . '">'
             . '<img src="' . e(asset('img/' . $foundName)) . '" alt="' . e($label) . '" loading="lazy" decoding="async">'
             . '</figure>';
    }
    $tone = max(1, min(8, $tone));
    return '<figure class="media media--ph ph-' . $tone . ' ' . e($class) . '" style="' . e($style) . '" role="img" aria-label="' . e($label) . '">'
         . '<span class="media__mark" aria-hidden="true">' . blossom_glyph() . '</span>'
         . ($label !== '' ? '<figcaption class="media__label">' . e($label) . '</figcaption>' : '')
         . '</figure>';
}

/** The little blossom mark used inside placeholders. */
function blossom_glyph(): string
{
    return '<svg viewBox="0 0 48 48" fill="none" aria-hidden="true">'
         . '<g fill="currentColor">'
         . '<path d="M24 6c3.6 0 6.2 2.9 6.2 6.4 0 1.3-.4 2.6-1 3.6 1.1-.6 2.4-1 3.7-1 3.5 0 6.4 2.7 6.4 6.2s-2.9 6.3-6.4 6.3c-1.3 0-2.6-.4-3.7-1 .6 1.1 1 2.3 1 3.6 0 3.5-2.6 6.5-6.2 6.5s-6.2-3-6.2-6.5c0-1.3.4-2.5 1-3.6-1.1.6-2.4 1-3.7 1-3.5 0-6.4-2.8-6.4-6.3s2.9-6.2 6.4-6.2c1.3 0 2.6.4 3.7 1-.6-1-1-2.3-1-3.6C17.8 8.9 20.4 6 24 6Z" opacity=".92"/>'
         . '<circle cx="24" cy="21.4" r="4.1" opacity=".55"/>'
         . '</g></svg>';
}

/** Inline school crest (SVG or official logo image). */
function crest(int $size = 44): string
{
    $file = __DIR__ . '/../assets/img/logos.png';
    if (is_file($file)) {
        return '<img class="crest-img" src="' . e(asset('img/logos.png')) . '" alt="' . e(SCHOOL_NAME) . ' Logo" width="' . $size . '" height="' . $size . '" style="object-fit:contain; width:' . $size . 'px; height:' . $size . 'px; border-radius:50%; display:inline-block; vertical-align:middle;">';
    }
    $s = (string) $size;
    return '<svg class="crest" width="' . $s . '" height="' . $s . '" viewBox="0 0 64 64" fill="none" aria-hidden="true">'
         . '<defs><linearGradient id="cg1" x1="0" y1="0" x2="1" y2="1">'
         . '<stop offset="0" stop-color="#F3D778"/><stop offset=".5" stop-color="#D9AF3C"/><stop offset="1" stop-color="#B8891C"/>'
         . '</linearGradient></defs>'
         . '<path d="M32 2 58 11v22c0 14-11 24.5-26 29C17 57.5 6 47 6 33V11L32 2Z" fill="url(#cg1)"/>'
         . '<path d="M32 6.6 53.6 14v19c0 11.7-9.2 20.6-21.6 24.5C19.6 53.6 10.4 44.7 10.4 33V14L32 6.6Z" fill="#0E2A57"/>'
         . '<g fill="url(#cg1)">'
         . '<path d="M32 15.5c2.6 0 4.5 2.1 4.5 4.6 0 .9-.2 1.8-.7 2.6.8-.4 1.7-.7 2.6-.7 2.5 0 4.6 2 4.6 4.5s-2.1 4.5-4.6 4.5c-.9 0-1.8-.2-2.6-.7.5.8.7 1.7.7 2.6 0 2.5-1.9 4.7-4.5 4.7s-4.5-2.2-4.5-4.7c0-.9.2-1.8.7-2.6-.8.5-1.7.7-2.6.7-2.5 0-4.6-2-4.6-4.5s2.1-4.5 4.6-4.5c.9 0 1.8.3 2.6.7-.5-.8-.7-1.7-.7-2.6 0-2.5 1.9-4.6 4.5-4.6Z"/>'
         . '<circle cx="32" cy="26.5" r="2.9" fill="#0E2A57"/>'
         . '<path d="M20 41.5h24v2.2H20zM22.5 45.6h19v1.8h-19z" opacity=".85"/>'
         . '</g></svg>';
}

/* ---------------------------------------------------------------- */
/*  CSRF + flash messages                                            */
/* ---------------------------------------------------------------- */

function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="_token" value="' . e(csrf_token()) . '">';
}

function csrf_valid(?string $token): bool
{
    return is_string($token) && !empty($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $token);
}

function flash_set(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function flash_get(): ?array
{
    if (empty($_SESSION['flash'])) {
        return null;
    }
    $f = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $f;
}

/** Remember submitted values so the form can be re-filled after an error. */
function old(string $key, string $default = ''): string
{
    return e($_SESSION['old'][$key] ?? $default);
}

function old_clear(): void
{
    unset($_SESSION['old']);
}

/** Field-level errors from the last submission. */
function field_error(string $key): string
{
    $err = $_SESSION['errors'][$key] ?? '';
    return $err !== '' ? '<span class="field__error">' . e($err) . '</span>' : '';
}

function has_error(string $key): bool
{
    return !empty($_SESSION['errors'][$key]);
}

function errors_clear(): void
{
    unset($_SESSION['errors']);
}
