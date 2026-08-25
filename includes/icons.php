<?php
/* Inline SVG icon set (stroke based, currentColor). Usage: echo icon('book'); */

function icon(string $name, string $class = ''): string
{
    static $p = [
        'phone'     => '<path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2Z"/>',
        'mail'      => '<path d="M4 4h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2Z"/><path d="m22 7-10 6L2 7"/>',
        'pin'       => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>',
        'clock'     => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3.2 1.9"/>',
        'book'      => '<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/>',
        'users'     => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.9M16 3.1a4 4 0 0 1 0 7.8"/>',
        'award'     => '<circle cx="12" cy="8" r="6"/><path d="m8.2 13.9-1.4 7.4L12 18.6l5.2 2.7-1.4-7.4"/>',
        'bus'       => '<path d="M4 17h16M6 17v2M18 17v2"/><rect x="3" y="4" width="18" height="13" rx="2"/><path d="M3 10h18M8 4v6M16 4v6"/><circle cx="7.5" cy="14" r=".8" fill="currentColor"/><circle cx="16.5" cy="14" r=".8" fill="currentColor"/>',
        'flask'     => '<path d="M9 2v6.2L3.6 18A2 2 0 0 0 5.3 21h13.4a2 2 0 0 0 1.7-3L15 8.2V2"/><path d="M8 2h8M6.5 15h11"/>',
        'palette'   => '<path d="M12 21a9 9 0 1 1 9-9c0 1.7-1.3 3-3 3h-1.5a2.5 2.5 0 0 0-1.8 4.2A1.9 1.9 0 0 1 12 21Z"/><circle cx="7.5" cy="11" r="1.2" fill="currentColor"/><circle cx="10.5" cy="7" r="1.2" fill="currentColor"/><circle cx="15" cy="8" r="1.2" fill="currentColor"/>',
        'music'     => '<path d="M9 18V5l11-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="17" cy="16" r="3"/>',
        'trophy'    => '<path d="M8 21h8M12 17v4M7 4h10v5a5 5 0 0 1-10 0V4Z"/><path d="M17 5h3v2a4 4 0 0 1-3.5 4M7 5H4v2a4 4 0 0 0 3.5 4"/>',
        'shield'    => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/><path d="m9 12 2 2 4-4"/>',
        'heart'     => '<path d="M20.8 5.6a5.2 5.2 0 0 0-7.4 0L12 7l-1.4-1.4a5.2 5.2 0 1 0-7.4 7.4L12 21.5l8.8-8.5a5.2 5.2 0 0 0 0-7.4Z"/>',
        'laptop'    => '<rect x="3" y="4" width="18" height="12" rx="2"/><path d="M2 20h20"/>',
        'library'   => '<path d="M4 3h4v18H4zM10 3h4v18h-4z"/><path d="m16.5 4.3 3.4.9-4 15-3.4-.9"/>',
        'star'      => '<path d="m12 2.5 2.9 6 6.6.9-4.8 4.6 1.2 6.5-5.9-3.1-5.9 3.1 1.2-6.5L2.5 9.4l6.6-.9Z" fill="currentColor" stroke="none"/>',
        'check'     => '<path d="m5 12.5 4.5 4.5L19 7"/>',
        'calendar'  => '<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 10h18"/>',
        'ball'      => '<circle cx="12" cy="12" r="9"/><path d="M12 3c3 3 3 15 0 18M3.5 9h17M3.5 15h17"/>',
        'leaf'      => '<path d="M11 20A7 7 0 0 1 4 13c0-6 5-9 16-10 0 10-3 15-9 16Z"/><path d="M4 21c1-6 5-9 9-10"/>',
        'camera'    => '<path d="M4 7h3l1.5-2h7L17 7h3a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2Z"/><circle cx="12" cy="13" r="3.6"/>',
        'globe'     => '<circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a15 15 0 0 1 0 18 15 15 0 0 1 0-18Z"/>',
        'bulb'      => '<path d="M9 18h6M10 22h4"/><path d="M12 2a6.5 6.5 0 0 0-4 11.6c.6.5 1 1.3 1 2.1V17h6v-1.3c0-.8.4-1.6 1-2.1A6.5 6.5 0 0 0 12 2Z"/>',
        'arrow'     => '<path d="M5 12h14M13 6l6 6-6 6"/>',
        'chevron'   => '<path d="m6 9 6 6 6-6"/>',
        'download'  => '<path d="M12 3v13M7 12l5 5 5-5M4 21h16"/>',
        'file'      => '<path d="M14 2H7a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7Z"/><path d="M14 2v5h5M9 13h6M9 17h4"/>',
        'chat'      => '<path d="M21 12a8 8 0 0 1-11.6 7.1L3 21l1.9-6.4A8 8 0 1 1 21 12Z"/>',
        'sparkle'   => '<path d="M12 3v4M12 17v4M3 12h4M17 12h4M6 6l2.5 2.5M15.5 15.5 18 18M18 6l-2.5 2.5M8.5 15.5 6 18"/>',
        'instagram' => '<rect x="2.5" y="2.5" width="19" height="19" rx="5.4"/><circle cx="12" cy="12" r="4.2"/><circle cx="17.4" cy="6.6" r="1.1" fill="currentColor" stroke="none"/>',
        'facebook'  => '<path d="M14.5 8.5H17V5h-2.5A4.5 4.5 0 0 0 10 9.5V12H7.5v3.5H10V22h3.5v-6.5H16l.5-3.5h-3V9.5c0-.6.4-1 1-1Z"/>',
        'youtube'   => '<path d="M22 12s0-3.4-.4-5a2.6 2.6 0 0 0-1.8-1.8C18 4.7 12 4.7 12 4.7s-6 0-7.8.5A2.6 2.6 0 0 0 2.4 7C2 8.6 2 12 2 12s0 3.4.4 5a2.6 2.6 0 0 0 1.8 1.8c1.8.5 7.8.5 7.8.5s6 0 7.8-.5A2.6 2.6 0 0 0 21.6 17c.4-1.6.4-5 .4-5Z"/><path d="m10 15 5-3-5-3v6Z" fill="currentColor" stroke="none"/>',
        'whatsapp'  => '<path d="M3 21l1.7-5.1A8.4 8.4 0 1 1 8.3 19.4L3 21Z"/><path d="M9 8.5c.2 1 .8 2.2 1.7 3.1.9.9 2 1.5 3 1.7l.9-1.3 2.1 1-.5 1.5c-1.7.6-4-.4-5.7-2.1S7.8 8.5 8.4 6.8L9.9 6.3l1 2.1L9 8.5Z" fill="currentColor" stroke="none"/>',
    ];

    $d = $p[$name] ?? $p['sparkle'];
    $cls = $class !== '' ? ' class="' . htmlspecialchars($class, ENT_QUOTES) . '"' : '';
    return '<svg' . $cls . ' viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" '
         . 'stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $d . '</svg>';
}
