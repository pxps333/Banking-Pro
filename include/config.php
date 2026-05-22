<?php

define("WEB_TITLE","Bankpro Banking");
define("WEB_URL", (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . ($_SERVER['HTTP_HOST'] ?? 'localhost'));
define("WEB_EMAIL","support@bankpro.com");

$web_url = WEB_URL;

function dbConnect(){
    $host = getenv('PGHOST') ?: 'localhost';
    $port = getenv('PGPORT') ?: '5432';
    $username = getenv('PGUSER') ?: 'postgres';
    $password = getenv('PGPASSWORD') ?: '';
    $database = getenv('PGDATABASE') ?: 'bankpro';
    $dns = "pgsql:host=$host;port=$port;dbname=$database";

    try {
        $conn = new PDO($dns, $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
}

function inputValidation($value): string
{
    return trim(htmlspecialchars(htmlentities($value)));
}
