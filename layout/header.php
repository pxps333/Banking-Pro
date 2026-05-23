<?php
ob_start();
require_once("./include/loginFunction.php");
require_once ('./session.php');
$sql = "SELECT * FROM settings WHERE id ='1'";
$stmt = $conn->prepare($sql);
$stmt->execute();

$page = $stmt->fetch(PDO::FETCH_ASSOC);

$title = $page['url_name'];

$pageTitle = $title;
$BANK_PHONE = $page['url_tel'];

$title = new pageTitle();
$email_message = new message();
$sendMail = new emailMessage();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title><?=$pageTitle ?> - Login </title>
    <link rel="icon" type="image/x-icon" href="./assets/img/favicon.ico"/>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&display=swap" rel="stylesheet">
    <link href="./bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="./assets/css/plugins.css" rel="stylesheet" type="text/css" />
    <link href="./assets/css/authentication/form-2.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <link rel="stylesheet" type="text/css" href="./assets/css/forms/theme-checkbox-radio.css">
    <link rel="stylesheet" type="text/css" href="./assets/css/forms/switches.css">
    <link href="./assets/css/pages/error/style-400.css" rel="stylesheet" type="text/css" />


    <!-- BEGIN THEME GLOBAL STYLES -->
    <link href="./assets/css/scrollspyNav.css" rel="stylesheet" type="text/css" />
    <link href="./plugins/animate/animate.css" rel="stylesheet" type="text/css" />
    <link href="./plugins/notification/snackbar/snackbar.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="./assets/css/elements/alert.css">
    <script src="./plugins/sweetalerts/promise-polyfill.js"></script>
    <link href="./plugins/sweetalerts/sweetalert2.min.css" rel="stylesheet" type="text/css" />
    <link href="./plugins/sweetalerts/sweetalert.css" rel="stylesheet" type="text/css" />
    <link href="./assets/css/components/custom-sweetalert.css" rel="stylesheet" type="text/css" />
    <script src="./assets/js/libs/jquery-3.1.1.min.js"></script>

    <!-- END THEME GLOBAL STYLES -->
    <title>Pin</title>
    <style>
        
        button{
            margin:3px;
        }
        button{
            display: inline-block;
            border:1px solid #0a3bff;
            color: #0022ff;
            border-radius: 30px;
            -webkit-border-radius: 30px;
            -moz-border-radius: 30px;
            font-family: Verdana;
            width: auto;
            height: auto;
            font-size: 16px;
            padding: 10px 17px;
            background-color: #FCFAF9;
        }
        button:hover, button:active{
            border:1px solid #FFFFFF;
            color: #FFFDFC;
            background-color: #FC0000;
        }

        input[type=text], textarea {
            -webkit-transition: all 0.30s ease-in-out;
            -moz-transition: all 0.30s ease-in-out;
            -ms-transition: all 0.30s ease-in-out;
            -o-transition: all 0.30s ease-in-out;
            outline: none;
            padding: 3px 0px 3px 3px;
            margin: 5px 1px 3px 0px;
            border: 1px solid #DDDDDD;
        }

        input[type=text]:focus, textarea:focus {
            box-shadow: 0 0 5px rgba(250, 0, 0, 1);
            padding: 3px 0px 3px 3px;
            margin: 5px 1px 3px 0px;
            border: 1px solid rgba(250, 0, 0, 1);
        }
    </style>

    <style>
    /* Auth page branding */
    .bp-auth-logo { display:flex;flex-direction:column;align-items:center;margin-bottom:22px; }
    .bp-auth-logo img { height:58px;width:auto;object-fit:contain;filter:drop-shadow(0 0 12px rgba(59,130,246,0.28)); }
    .bp-auth-logo-name { font-size:1.05rem;font-weight:700;color:#3b82f6;letter-spacing:-0.01em;margin-top:6px; }
    .btn-primary { background:linear-gradient(135deg,#2563eb,#60a5fa) !important;border:none !important;border-radius:10px !important;font-weight:600 !important;box-shadow:0 4px 18px rgba(59,130,246,0.3) !important;transition:all .2s !important; }
    .btn-primary:hover { transform:translateY(-1px);box-shadow:0 8px 28px rgba(59,130,246,0.45) !important; }
    .forgot-pass-link { color:#3b82f6 !important;font-weight:600; }
    .forgot-pass-link:hover { color:#2563eb !important; }

    /* ── Dark/light toggle ── */
    .bp-dm-toggle-auth {
        position:fixed;top:16px;right:16px;z-index:9999;
        width:38px;height:38px;border-radius:10px;
        border:1px solid #e0e6ed;background:#fff;
        color:#3b3f5c;cursor:pointer;display:flex;
        align-items:center;justify-content:center;
        transition:all .2s;box-shadow:0 2px 8px rgba(0,0,0,0.08);
    }
    .bp-dm-toggle-auth:hover { border-color:#4361ee;color:#4361ee; }
    .bp-dm-toggle-auth .icon-sun { display:none; }
    .bp-dm-toggle-auth .icon-moon { display:block; }

    /* Light mode (default) */
    body.dm-light { background:#f8fafc !important; }
    body.dm-light .form-content { background:#fff !important;box-shadow:0 8px 40px rgba(0,0,0,0.08) !important; }

    /* Dark mode */
    body.dm-dark { background:#0d1117 !important; }
    body.dm-dark .form-container.outer { background:#0d1117 !important; }
    body.dm-dark .form-form { background:#0d1117 !important; }
    body.dm-dark .form-content {
        background:#161b2e !important;
        border-color:rgba(255,255,255,0.07) !important;
        box-shadow:0 4px 32px rgba(0,0,0,0.45) !important;
    }
    body.dm-dark .form-content h1 { color:#e2e8f0 !important; }
    body.dm-dark .form-content > p { color:#64748b !important; }
    body.dm-dark .field-wrapper label { color:#94a3b8 !important; }
    body.dm-dark .field-wrapper input {
        background:#1e2535 !important;
        border-color:rgba(255,255,255,0.09) !important;
        color:#e2e8f0 !important;
    }
    body.dm-dark .field-wrapper input:focus {
        border-color:#4361ee !important;
        background:#232b3e !important;
        box-shadow:0 0 0 3px rgba(67,97,238,0.18) !important;
    }
    body.dm-dark .field-wrapper svg:not(.feather-eye) { color:#64748b !important; fill:rgba(255,255,255,0.04) !important; }
    body.dm-dark .field-wrapper svg.feather-eye { color:#64748b !important; fill:rgba(255,255,255,0.04) !important; }
    body.dm-dark .forgot-pass-link { color:#60a5fa !important; }
    body.dm-dark .bp-dm-toggle-auth {
        background:#1e2535;border-color:rgba(255,255,255,0.1);color:#94a3b8;
    }
    body.dm-dark .bp-dm-toggle-auth .icon-sun { display:block; }
    body.dm-dark .bp-dm-toggle-auth .icon-moon { display:none; }
    </style>
    <script>
    (function(){
        function applyDm(m){
            if(m==='dark'){ document.body.classList.add('dm-dark'); document.body.classList.remove('dm-light'); }
            else { document.body.classList.remove('dm-dark'); document.body.classList.add('dm-light'); }
            localStorage.setItem('bp_theme', m);
        }
        document.addEventListener('DOMContentLoaded', function(){
            applyDm(localStorage.getItem('bp_theme') || 'light');
            var btn = document.getElementById('bpDmToggleAuth');
            if(btn) btn.addEventListener('click', function(){
                applyDm(document.body.classList.contains('dm-dark') ? 'light' : 'dark');
            });
        });
    })();
    </script>
</head>
