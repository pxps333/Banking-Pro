<?php
ob_start();
//require_once("./include/loginFunction.php");
////require_once ('./session.php');
//$sql = "SELECT * FROM settings WHERE id ='1'";
//$stmt = $conn->prepare($sql);
//$stmt->execute();
//
//$page = $stmt->fetch(PDO::FETCH_ASSOC);
//
//$title = $page['url_name'];
//
//$pageTitle = $title;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>admin - Login </title>
    <link rel="icon" type="image/x-icon" href="./assets/img/favicon.ico"/>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&display=swap" rel="stylesheet">
    <link href="./bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="./assets/css/plugins.css" rel="stylesheet" type="text/css" />
    <link href="./assets/css/authentication/form-2.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <link rel="stylesheet" type="text/css" href="./assets/css/forms/theme-checkbox-radio.css">
    <link rel="stylesheet" type="text/css" href="./assets/css/forms/switches.css">


    <!-- BEGIN THEME GLOBAL STYLES -->
    <link href="./assets/css/scrollspyNav.css" rel="stylesheet" type="text/css" />
    <link href="./plugins/animate/animate.css" rel="stylesheet" type="text/css" />
    <script src="./plugins/sweetalerts/promise-polyfill.js"></script>
    <link href="./plugins/sweetalerts/sweetalert2.min.css" rel="stylesheet" type="text/css" />
    <link href="./plugins/sweetalerts/sweetalert.css" rel="stylesheet" type="text/css" />
    <link href="./assets/css/components/custom-sweetalert.css" rel="stylesheet" type="text/css" />
    <script src="./assets/js/libs/jquery-3.1.1.min.js"></script>

    <!-- END THEME GLOBAL STYLES -->
    <style>
        .btn-primary { background: linear-gradient(135deg,#2563eb,#60a5fa) !important; border: none !important; border-radius: 10px !important; font-weight: 600 !important; letter-spacing: 0.01em; box-shadow: 0 4px 18px rgba(59,130,246,0.3) !important; transition: all 0.2s !important; }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 8px 28px rgba(59,130,246,0.45) !important; }
        .bp-dm-toggle { position: fixed; top: 16px; right: 16px; z-index: 9999; width: 36px; height: 36px; border-radius: 10px; border: 1px solid rgba(255,255,255,0.12); background: rgba(255,255,255,0.06); color: #94a3b8; cursor: pointer; font-size: 17px; display: flex; align-items: center; justify-content: center; transition: all 0.2s; }
        .bp-dm-toggle:hover { border-color: rgba(59,130,246,0.4); color: #3b82f6; box-shadow: 0 0 14px rgba(59,130,246,0.15); }
        .bp-dm-toggle .icon-sun { display: none; }
        .bp-dm-toggle .icon-moon { display: block; }
        body.dm-light .bp-dm-toggle .icon-sun { display: block; }
        body.dm-light .bp-dm-toggle .icon-moon { display: none; }
        body.dm-light { background: #f8fafc !important; }
        body.dm-light .form-content { background: #fff !important; box-shadow: 0 8px 40px rgba(0,0,0,0.08) !important; }
    </style>
    <script>
    (function(){
      var t = localStorage.getItem('bp_theme') || 'dark';
      if(t === 'light') document.documentElement.classList.add('dm-light-init');
    })();
    </script>
</head>
<body class="form">
<button class="bp-dm-toggle" id="bpToggle" title="Toggle dark/light">
  <span class="icon-moon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg></span><span class="icon-sun"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg></span>
</button>
<script>
(function(){
  function applyDm(m){ if(m==='light'){document.body.classList.add('dm-light');}else{document.body.classList.remove('dm-light');} localStorage.setItem('bp_theme',m); }
  applyDm(localStorage.getItem('bp_theme')||'dark');
  document.addEventListener('DOMContentLoaded',function(){
    var btn=document.getElementById('bpToggle');
    if(btn) btn.addEventListener('click',function(){ applyDm(document.body.classList.contains('dm-light')?'dark':'light'); });
  });
})();
</script>
