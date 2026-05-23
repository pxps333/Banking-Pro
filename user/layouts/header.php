<?php
ob_start();
require_once ('../session.php');
require_once("../include/loginFunction.php");
require_once("../include/userClass.php");
require_once ("../include/twilioController.php");

if(!$_SESSION['acct_no']) {
    header("location:../login.php");
    exit;
}

$conn = dbConnect();

$sql = "SELECT * FROM settings WHERE id ='1'";
$stmt = $conn->prepare($sql);
$stmt->execute();
$page = $stmt->fetch(PDO::FETCH_ASSOC);

$title       = $page['url_name'];
$pageTitle   = $title;
$url_email   = $page['url_email'];
$livechat    = $page['livechat'];
$trans_limit_min = $page['trans_limit_min'];
$trans_limit_max = $page['trans_limit_max'];

$viesConn = "SELECT * FROM users WHERE acct_no = :acct_no";
$stmt = $conn->prepare($viesConn);
$stmt->execute([':acct_no' => $_SESSION['acct_no']]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$user_id    = $row['id'];
$acct_stat  = $row['acct_status'];
$limitRemain = $row['limit_remain'];
$acct_balance = $row['acct_balance'];
$avail_balance = $row['avail_balance'];
$fullName   = $row['firstname']." ".$row['lastname'];
$email      = $row['acct_email'];

$sqlLog = "SELECT * FROM audit_logs ORDER BY datenow DESC";
$stmtLog = $conn->prepare($sqlLog);
$stmtLog->execute();
$logs = $stmtLog->fetch(PDO::FETCH_ASSOC);
$device    = $logs['device'] ?? '';
$ipAddress = $logs['ipAddress'] ?? '';
$datenow   = $logs['datenow'] ?? '';

$sql = "SELECT * FROM temp_trans WHERE acct_id =:acct_id ORDER BY wire_id DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->execute(['acct_id' => $user_id]);
$temp_trans = $stmt->fetch(PDO::FETCH_ASSOC);

$currency = currency($row);
$userStatus = userStatus($row);

$title_obj    = new pageTitle();
$email_message = new message();
$sendMail      = new emailMessage();
$sendSms       = new twilioController();

$sql2 = "SELECT * FROM card WHERE user_id=:user_id";
$cardstmt = $conn->prepare($sql2);
$cardstmt->execute(['user_id' => $user_id]);
$cardCheck = $cardstmt->fetch(PDO::FETCH_ASSOC);

if ($row['acct_currency'] === 'USD')       { $currency = "$"; }
elseif ($row['acct_currency'] === 'Euro')  { $currency = "€"; }
elseif ($row['acct_currency'] === 'Yuan')  { $currency = "¥"; }
elseif ($row['acct_currency'] === 'GBP')   { $currency = "£"; }
elseif ($row['acct_currency'] === 'CAD')   { $currency = "CA$"; }

// Fetch recent transactions for notification dropdown
$sqlNotif = "SELECT * FROM transactions WHERE user_id=:uid ORDER BY trans_id DESC LIMIT 5";
$stmtNotif = $conn->prepare($sqlNotif);
$stmtNotif->execute(['uid' => $user_id]);
$notifTransactions = $stmtNotif->fetchAll(PDO::FETCH_ASSOC);

// Fetch recent loans for message dropdown
$sqlLoans = "SELECT * FROM loan WHERE acct_id=:uid ORDER BY loan_id DESC LIMIT 3";
$stmtLoans = $conn->prepare($sqlLoans);
$stmtLoans->execute(['uid' => $user_id]);
$notifLoans = $stmtLoans->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title><?= htmlspecialchars($pageName) ?> — <?= htmlspecialchars($pageTitle) ?></title>
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.min.css">
    <link rel="stylesheet" href="../plugins/font-icons/fontawesome/css/fontawesome.css">
    <link rel="stylesheet" href="../plugins/font-icons/fontawesome/css/regular.css">

    <!-- Core styles -->
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/dashboard/modern_dash.css" rel="stylesheet">
    <link href="../plugins/apex/apexcharts.css" rel="stylesheet">
    <link href="../plugins/sweetalerts/sweetalert2.min.css" rel="stylesheet">
    <link href="../plugins/sweetalerts/sweetalert.css" rel="stylesheet">
    <link href="../assets/css/components/custom-sweetalert.css" rel="stylesheet">
    <link href="../plugins/notification/snackbar/snackbar.min.css" rel="stylesheet">
    <link href="../plugins/file-upload/file-upload-with-preview.min.css" rel="stylesheet">
    <link href="../plugins/dropify/dropify.min.css" rel="stylesheet">
    <link href="../assets/css/users/account-setting.css" rel="stylesheet">
    <link href="../assets/css/components/custom-modal.css" rel="stylesheet">
    <link href="../assets/css/card/card.css" rel="stylesheet">
    <link href="../assets/css/card/displayCard.css" rel="stylesheet">
    <link href="../assets/css/elements/alert.css" rel="stylesheet">
    <link href="../assets/css/forms/custom-clipboard.css" rel="stylesheet">
    <link href="../plugins/table/datatable/datatables.css" rel="stylesheet">

    <!-- Apply theme before paint to avoid flash -->
    <script>
    (function(){
        var t = localStorage.getItem('bp_theme') || 'light';
        if(t === 'dark') document.documentElement.classList.add('bp-dark-pre');
    })();
    </script>
    <style>
    html.bp-dark-pre body { background: #0f1117 !important; }
    /* Compatibility shim for old widgets that use legacy CSS */
    .widget, .widget-two, .widget-account-invoice-three, .widget-table-two {
        background: var(--bp-surface) !important;
        border: 1px solid var(--bp-border) !important;
        border-radius: var(--bp-radius) !important;
        box-shadow: var(--bp-shadow) !important;
        color: var(--bp-text) !important;
    }
    .widget-content-area, .invoice-list { background: var(--bp-surface) !important; }
    body.bp-dark .widget, body.bp-dark .widget-two { background: var(--bp-surface) !important; color: var(--bp-text) !important; }
    /* DataTables compat */
    table.dataTable thead th, table.dataTable tbody td { color: var(--bp-text2) !important; border-color: var(--bp-border) !important; }
    body.bp-dark table.dataTable { background: var(--bp-surface) !important; }
    /* SweetAlert compat */
    .swal2-popup { background: var(--bp-surface) !important; color: var(--bp-text) !important; }
    </style>

    <script src="../assets/js/libs/jquery-3.1.1.min.js"></script>
    <script src="../plugins/sweetalerts/promise-polyfill.js"></script>
</head>

<body>

<!-- Loader -->
<div id="bp-loader">
    <div class="bp-spinner"></div>
</div>

<!-- Mobile overlay -->
<div class="bp-overlay" id="bpOverlay"></div>

<!-- Sidebar -->
<aside class="bp-sidebar" id="bpSidebar">
    <div class="bp-sidebar-brand">
        <img src="../assets/images/logo/<?= htmlspecialchars($page['image'] ?? 'logo.png') ?>" alt="<?= htmlspecialchars($pageTitle) ?>">
        <span><?= htmlspecialchars($pageTitle) ?></span>
    </div>

    <div class="bp-sidebar-user">
        <img src="../assets/profile/<?= htmlspecialchars($row['image'] ?? 'default.png') ?>" alt="avatar" class="bp-sidebar-user-av">
        <div class="bp-sidebar-user-info">
            <h6><?= htmlspecialchars($fullName) ?></h6>
            <span><?= htmlspecialchars($row['acct_type'] ?? 'Account') ?></span>
        </div>
    </div>

    <nav class="bp-sidebar-nav">
        <div class="bp-sidebar-label">Main</div>
        <ul style="list-style:none;padding:0;margin:0;">
            <li class="bp-nav-item">
                <a href="./dashboard.php" class="bp-nav-link <?php active('dashboard.php'); ?>">
                    <i class="ri-home-4-line"></i> Dashboard
                </a>
            </li>
        </ul>

        <div class="bp-sidebar-label" style="margin-top:10px;">Banking</div>
        <ul style="list-style:none;padding:0;margin:0;">
            <li class="bp-nav-item">
                <a href="./deposit.php" class="bp-nav-link <?php active('deposit.php'); ?>">
                    <i class="ri-add-circle-line"></i> Online Deposit
                </a>
            </li>
            <li class="bp-nav-item">
                <a href="./domestic-transfer.php" class="bp-nav-link <?php active('domestic-transfer.php'); ?>">
                    <i class="ri-send-plane-line"></i> Domestic Transfer
                </a>
            </li>
            <li class="bp-nav-item">
                <a href="./wire-transfer.php" class="bp-nav-link <?php active('wire-transfer.php'); ?>">
                    <i class="ri-global-line"></i> Wire Transfer
                </a>
            </li>
            <li class="bp-nav-item">
                <a href="./withdrawal.php" class="bp-nav-link <?php active('withdrawal.php'); ?>">
                    <i class="ri-hand-coin-line"></i> Withdrawal
                </a>
            </li>
        </ul>

        <div class="bp-sidebar-label" style="margin-top:10px;">Services</div>
        <ul style="list-style:none;padding:0;margin:0;">
            <li class="bp-nav-item">
                <a href="./card.php" class="bp-nav-link <?php active('card.php'); ?>">
                    <i class="ri-bank-card-line"></i> Virtual Card
                </a>
            </li>
            <li class="bp-nav-item">
                <a href="./loan.php" class="bp-nav-link <?php active('loan.php'); ?>">
                    <i class="ri-money-dollar-circle-line"></i> Loans &amp; Mortgages
                </a>
            </li>
        </ul>

        <div class="bp-sidebar-label" style="margin-top:10px;">Transactions</div>
        <ul style="list-style:none;padding:0;margin:0;">
            <li class="bp-nav-item">
                <a href="#txLogs" class="bp-nav-link bp-nav-collapsible" id="txToggle">
                    <i class="ri-receipt-line"></i> All Logs
                    <i class="ri-arrow-right-s-line bp-nav-chevron" style="margin-left:auto;"></i>
                </a>
                <ul class="bp-nav-submenu" id="txLogs">
                    <li><a href="./credit-debit_transaction.php" class="<?php active('credit-debit_transaction.php'); ?>">Credit / Debit</a></li>
                    <li><a href="./wire-transaction.php" class="<?php active('wire-transaction.php'); ?>">Wire</a></li>
                    <li><a href="./domestic-transaction.php" class="<?php active('domestic-transaction.php'); ?>">Domestic</a></li>
                    <li><a href="./loan-transaction.php" class="<?php active('loan-transaction.php'); ?>">Loan</a></li>
                    <li><a href="./withdrawal-transaction.php" class="<?php active('withdrawal-transaction.php'); ?>">Withdrawals</a></li>
                </ul>
            </li>
        </ul>

        <div class="bp-sidebar-label" style="margin-top:10px;">Account</div>
        <ul style="list-style:none;padding:0;margin:0;">
            <li class="bp-nav-item">
                <a href="./profile.php" class="bp-nav-link <?php active('profile.php'); ?>">
                    <i class="ri-user-line"></i> My Profile
                </a>
            </li>
            <li class="bp-nav-item">
                <a href="./account-manager.php" class="bp-nav-link <?php active('account-manager.php'); ?>">
                    <i class="ri-user-star-line"></i> Account Manager
                </a>
            </li>
            <li class="bp-nav-item">
                <a href="./logout.php" class="bp-nav-link">
                    <i class="ri-logout-box-r-line"></i> Sign Out
                </a>
            </li>
        </ul>
    </nav>
</aside>

<!-- Top Navbar -->
<header class="bp-navbar">
    <button class="bp-navbar-toggle" id="bpSidebarToggle" aria-label="Toggle sidebar">
        <i class="ri-menu-line" style="font-size:20px;"></i>
    </button>

    <div class="bp-navbar-spacer"></div>

    <!-- Dark mode toggle -->
    <button class="bp-dm-btn" id="bpDmToggle" title="Toggle dark/light mode">
        <span class="moon"><i class="ri-moon-line" style="font-size:16px;"></i></span>
        <span class="sun"><i class="ri-sun-line" style="font-size:16px;"></i></span>
    </button>

    <!-- Notifications -->
    <div class="bp-dropdown">
        <button class="bp-navbar-icon" id="bpNotifBtn" title="Notifications">
            <i class="ri-notification-3-line" style="font-size:19px;"></i>
            <?php if(count($notifTransactions) > 0): ?>
            <span class="bp-navbar-badge"></span>
            <?php endif; ?>
        </button>
        <div class="bp-dropdown-menu" id="bpNotifMenu" style="min-width:300px;">
            <div style="padding:12px 16px 8px;font-size:.78rem;font-weight:700;color:var(--bp-text3);text-transform:uppercase;letter-spacing:.07em;">Recent Transactions</div>
            <?php foreach($notifTransactions as $notif):
                $ntype = ($notif['trans_type'] == '1') ? 'Credit' : 'Debit';
                $ncolor = ($notif['trans_type'] == '1') ? 'var(--bp-green)' : 'var(--bp-red)';
                $nicon  = ($notif['trans_type'] == '1') ? 'ri-arrow-down-line' : 'ri-arrow-up-line';
            ?>
            <div class="bp-dropdown-item">
                <div style="width:32px;height:32px;border-radius:8px;background:<?= ($notif['trans_type']=='1') ? 'rgba(16,185,129,.12)' : 'rgba(239,68,68,.12)' ?>;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="<?= $nicon ?>" style="color:<?= $ncolor ?>;font-size:15px;"></i>
                </div>
                <div style="flex:1;min-width:0;">
                    <div style="font-size:.8rem;font-weight:600;color:var(--bp-text);"><?= htmlspecialchars(substr($notif['description'],0,28)) ?>...</div>
                    <div style="font-size:.72rem;color:<?= $ncolor ?>;font-weight:700;"><?= $currency.number_format($notif['amount'],2) ?></div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php if(empty($notifTransactions)): ?>
            <div style="text-align:center;padding:24px;font-size:.8rem;color:var(--bp-text3);">No recent transactions</div>
            <?php endif; ?>
            <div class="bp-dropdown-divider"></div>
            <a href="./credit-debit_transaction.php" class="bp-dropdown-item" style="justify-content:center;color:var(--bp-primary);font-size:.8rem;font-weight:700;">View All Transactions</a>
        </div>
    </div>

    <!-- Loans bell -->
    <div class="bp-dropdown">
        <button class="bp-navbar-icon" id="bpMsgBtn" title="Loan Updates">
            <i class="ri-mail-line" style="font-size:19px;"></i>
            <?php if(count($notifLoans) > 0): ?>
            <span class="bp-navbar-badge" style="background:var(--bp-orange);"></span>
            <?php endif; ?>
        </button>
        <div class="bp-dropdown-menu" id="bpMsgMenu" style="min-width:280px;">
            <div style="padding:12px 16px 8px;font-size:.78rem;font-weight:700;color:var(--bp-text3);text-transform:uppercase;letter-spacing:.07em;">Loan Updates</div>
            <?php foreach($notifLoans as $ln):
                $lnStatus = loanStatus($ln);
            ?>
            <div class="bp-dropdown-item">
                <div style="width:32px;height:32px;border-radius:8px;background:rgba(245,158,11,.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="ri-money-dollar-circle-line" style="color:var(--bp-orange);font-size:15px;"></i>
                </div>
                <div>
                    <div style="font-size:.8rem;font-weight:600;color:var(--bp-text);"><?= $currency.number_format($ln['amount'],2) ?></div>
                    <div style="font-size:.72rem;color:var(--bp-text3);"><?= $lnStatus ?></div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php if(empty($notifLoans)): ?>
            <div style="text-align:center;padding:24px;font-size:.8rem;color:var(--bp-text3);">No loan activity</div>
            <?php endif; ?>
            <div class="bp-dropdown-divider"></div>
            <a href="./loan-transaction.php" class="bp-dropdown-item" style="justify-content:center;color:var(--bp-primary);font-size:.8rem;font-weight:700;">View All Loans</a>
        </div>
    </div>

    <!-- User profile -->
    <div class="bp-dropdown">
        <img src="../assets/profile/<?= htmlspecialchars($row['image'] ?? 'default.png') ?>" class="bp-navbar-avatar" id="bpProfileBtn" alt="avatar">
        <div class="bp-dropdown-menu" id="bpProfileMenu">
            <div class="bp-dropdown-header">
                <img src="../assets/profile/<?= htmlspecialchars($row['image'] ?? 'default.png') ?>" alt="avatar">
                <div class="bp-dropdown-header-info">
                    <h6><?= htmlspecialchars($fullName) ?></h6>
                    <span><?= htmlspecialchars($row['acct_email'] ?? '') ?></span>
                </div>
            </div>
            <div class="bp-dropdown-divider"></div>
            <a href="./profile.php" class="bp-dropdown-item">
                <i class="ri-user-line"></i> My Profile
            </a>
            <a href="./edit-profile.php" class="bp-dropdown-item">
                <i class="ri-settings-3-line"></i> Settings
            </a>
            <a href="./loan-transaction.php" class="bp-dropdown-item">
                <i class="ri-inbox-line"></i> My Inbox
            </a>
            <div class="bp-dropdown-divider"></div>
            <a href="./logout.php" class="bp-dropdown-item" style="color:var(--bp-red);">
                <i class="ri-logout-box-r-line"></i> Sign Out
            </a>
        </div>
    </div>
</header>

<!-- Main -->
<main class="bp-main">
<div class="bp-content">
