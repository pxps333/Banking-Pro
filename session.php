<?php

// ── Secure session settings (must be set before session_start) ────────────────
ini_set('session.cookie_httponly', 1);   // blocks JS from reading the cookie
ini_set('session.cookie_secure',   1);   // only send cookie over HTTPS
ini_set('session.use_strict_mode', 1);   // reject unrecognised session IDs
ini_set('session.cookie_samesite', 'Lax');

// ── Session timeout (30 minutes of inactivity) ────────────────────────────────
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    session_unset();
    session_destroy();
    header('Location:./login.php');
    exit;
}
$_SESSION['LAST_ACTIVITY'] = time();

// ── Error reporting: show nothing to visitors in production ───────────────────
error_reporting(0);
ini_set('display_errors', 0);
