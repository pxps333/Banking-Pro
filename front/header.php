<?php
require __DIR__."/../include/loginFunction.php";
require_once __DIR__."/../session.php";

$sql = "SELECT * FROM settings WHERE id = 1";
$stmt = $conn->prepare($sql);
$stmt->execute();
$page = $stmt->fetch(PDO::FETCH_ASSOC);

$pageTitle   = $page['url_name'] ?? 'Capital Trust Bank';
$BANK_PHONE  = $page['url_tel'] ?? '';

$title        = new pageTitle();
$email_message = new message();
$sendMail      = new emailMessage();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($pageName ?? 'Home') ?> — <?= htmlspecialchars($pageTitle) ?></title>
  <meta name="description" content="<?= htmlspecialchars($pageTitle) ?> — Modern banking for the digital generation. Instant transfers, multi-currency accounts, virtual cards and more." />
  <link rel="icon" type="image/png" href="/front/images/favicon.png" />

  <!-- Google Fonts: Inter -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />

  <!-- Remix Icons -->
  <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet" />

  <!-- Premium CSS (navbar, footer, landing page) -->
  <link rel="stylesheet" href="/front/css/premium.css" />

  <?php if (empty($isHomePage)): ?>
  <!-- Legacy Bootstrap + theme CSS for inner pages -->
  <link rel="stylesheet" href="/front/css/bootstrap.min.css" />
  <link rel="stylesheet" href="/front/css/main.css" />
  <link rel="stylesheet" href="/front/css/responsive.css" />
  <link rel="stylesheet" href="/front/css/fontawesome.min.css" />
  <link rel="stylesheet" href="/front/css/icofont.min.css" />
  <link rel="stylesheet" href="/front/css/themify-icons.css" />
  <link rel="stylesheet" href="/front/css/linearicons.css" />
  <style>
    body { padding-top: 70px; }
    .page-banner-image-section { margin-top: 0; }
    .auto-container, .auto-container h2, .auto-container h3,
    .auto-container h4, .auto-container h5, .auto-container p,
    .sec-title h2, .sec-title .title, .lower-content p,
    .sidebar-widget ul li a, .text { color: #1F1B44 !important; }
    .main-header { display: none; }
  </style>
  <?php endif; ?>
</head>
<body>

<!-- ═══════════════════ NAVBAR ═══════════════════ -->
<nav class="ft-navbar" id="ftNav" aria-label="Main navigation">
  <div class="ft-nav-inner">

    <!-- Logo -->
    <a href="/" class="ft-logo" aria-label="<?= htmlspecialchars($pageTitle) ?> home">
      <img src="/assets/images/logo/<?= htmlspecialchars($page['image'] ?? 'logo.png') ?>" alt="<?= htmlspecialchars($pageTitle) ?> logo" />
      <span class="ft-logo-text"><?= htmlspecialchars($pageTitle) ?></span>
    </a>

    <!-- Desktop Nav Links -->
    <ul class="ft-nav-links" role="menubar">
      <li role="none">
        <a href="/" role="menuitem">Home</a>
      </li>
      <li role="none">
        <a href="#" role="menuitem" aria-haspopup="true">Personal <i class="ri-arrow-down-s-line"></i></a>
        <ul class="ft-dropdown" role="menu" aria-label="Personal">
          <li role="none"><a href="/p/ultimate-checking.php" role="menuitem">Ultimate Checking</a></li>
          <li role="none"><a href="/p/health-savings-account.php" role="menuitem">Health Savings (HSA)</a></li>
          <li role="none"><a href="/p/individual-retirement-account.php" role="menuitem">Retirement Account (IRA)</a></li>
        </ul>
      </li>
      <li role="none">
        <a href="#" role="menuitem" aria-haspopup="true">Business <i class="ri-arrow-down-s-line"></i></a>
        <ul class="ft-dropdown" role="menu" aria-label="Business">
          <li role="none"><a href="/p/business-essential-checking.php" role="menuitem">Essential Checking</a></li>
          <li role="none"><a href="/p/business-savings-account.php" role="menuitem">Business Savings</a></li>
          <li role="none"><a href="/p/overdraft-protection-sweeps.php" role="menuitem">Overdraft Protection</a></li>
        </ul>
      </li>
      <li role="none">
        <a href="#" role="menuitem" aria-haspopup="true">Loans <i class="ri-arrow-down-s-line"></i></a>
        <ul class="ft-dropdown" role="menu" aria-label="Loans">
          <li role="none"><a href="/p/home-mortgage-loans.php" role="menuitem">Home Mortgage</a></li>
          <li role="none"><a href="/p/personal-loans.php" role="menuitem">Personal Loans</a></li>
          <li role="none"><a href="/p/working-capital-loans.php" role="menuitem">Working Capital</a></li>
          <li role="none"><a href="/p/business-term-loans.php" role="menuitem">Business Term Loans</a></li>
        </ul>
      </li>
      <li role="none">
        <a href="#" role="menuitem" aria-haspopup="true">Services <i class="ri-arrow-down-s-line"></i></a>
        <ul class="ft-dropdown" role="menu" aria-label="Services">
          <li role="none"><a href="/p/online-banking.php" role="menuitem">Online Banking</a></li>
          <li role="none"><a href="/p/wire-transfers.php" role="menuitem">Wire Transfers</a></li>
          <li role="none"><a href="/p/lost-cards.php" role="menuitem">Lost or Stolen Cards</a></li>
        </ul>
      </li>
      <li role="none">
        <a href="/p/contact.php" role="menuitem">Contact</a>
      </li>
    </ul>

    <!-- Desktop Actions -->
    <div class="ft-nav-actions">
      <button class="ft-theme-toggle" id="themeToggle" aria-label="Toggle theme" title="Toggle dark/light mode">
        <span class="icon-moon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg></span>
        <span class="icon-sun"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg></span>
      </button>
      <a href="/login.php" class="ft-btn-ghost">Sign In</a>
      <a href="/signup/verify-registration.php" class="ft-btn-primary">Open Account</a>
    </div>

    <!-- Mobile Hamburger -->
    <button class="ft-hamburger" id="ftHamburger" aria-label="Toggle navigation" aria-expanded="false">
      <span></span><span></span><span></span>
    </button>

  </div>
</nav>

<!-- Mobile Menu -->
<div class="ft-mobile-menu" id="ftMobileMenu" role="navigation" aria-label="Mobile navigation">
  <div style="display:flex;align-items:center;justify-content:space-between;padding:0 4px 8px;">
    <span style="font-size:0.72rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.1em;">Navigation</span>
    <button class="ft-theme-toggle" id="themeToggleMobile" aria-label="Toggle theme" title="Toggle dark/light mode" style="width:36px;height:36px;">
      <span class="icon-moon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg></span>
      <span class="icon-sun"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg></span>
    </button>
  </div>
  <div class="ft-mobile-section">Personal</div>
  <a href="/p/ultimate-checking.php">Ultimate Checking</a>
  <a href="/p/health-savings-account.php">Health Savings (HSA)</a>
  <a href="/p/individual-retirement-account.php">Retirement Account (IRA)</a>
  <div class="ft-mobile-section">Business</div>
  <a href="/p/business-essential-checking.php">Essential Checking</a>
  <a href="/p/business-savings-account.php">Business Savings</a>
  <a href="/p/overdraft-protection-sweeps.php">Overdraft Protection</a>
  <div class="ft-mobile-section">Loans</div>
  <a href="/p/home-mortgage-loans.php">Home Mortgage</a>
  <a href="/p/personal-loans.php">Personal Loans</a>
  <a href="/p/working-capital-loans.php">Working Capital</a>
  <div class="ft-mobile-section">Services</div>
  <a href="/p/online-banking.php">Online Banking</a>
  <a href="/p/wire-transfers.php">Wire Transfers</a>
  <a href="/p/contact.php">Contact</a>
  <div class="ft-mobile-divider"></div>
  <div class="ft-mobile-actions">
    <a href="/login.php" class="ft-btn-ghost">Sign In</a>
    <a href="/signup/verify-registration.php" class="ft-btn-primary">Open Account</a>
  </div>
</div>

<!-- Page wrapper -->
<div id="ft-page">
