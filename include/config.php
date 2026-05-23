<?php

define("WEB_TITLE", "Northwest Registered Online Banking");
define("WEB_URL",   (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . ($_SERVER['HTTP_HOST'] ?? 'northwestregisteredonlinebanking.com'));
define("WEB_EMAIL", "support@northwestregisteredonlinebanking.com");

$web_url = WEB_URL;

// ── MySQL / cPanel Database Connection ───────────────────────────────────────
// In cPanel: go to MySQL Databases, create a database and a user, assign the
// user to the database with All Privileges, then fill in the three values below.
// cPanel usually prefixes names with your account username, e.g.:
//   northwe_bankpro   and   northwe_dbuser

function dbConnect() {
    $host     = 'localhost';               // always localhost on cPanel
    $port     = '3306';                    // default MySQL port
    $database = 'YOUR_DATABASE_NAME';      // << CHANGE THIS
    $username = 'YOUR_DATABASE_USER';      // << CHANGE THIS
    $password = 'YOUR_DATABASE_PASSWORD';  // << CHANGE THIS

    $dsn = "mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4";

    try {
        $conn = new PDO($dsn, $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $conn;
    } catch (PDOException $e) {
        error_log("DB Connection failed: " . $e->getMessage());
        die("A database error occurred. Please try again later.");
    }
}

function inputValidation($value): string {
    return trim(htmlspecialchars(htmlentities($value)));
}
