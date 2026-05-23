<?php
include_once("./layout/header.php");

$sql = "SELECT COUNT(*) as total, SUM(acct_balance) as total_balance FROM users";
$stmt = $conn->prepare($sql); $stmt->execute();
$users_stats = $stmt->fetch(PDO::FETCH_ASSOC);
$total_users = $users_stats['total'];
$total_balance = number_format((float)$users_stats['total_balance'], 2);

$sql = "SELECT SUM(amount) as total FROM wire_transfer";
$stmt = $conn->prepare($sql); $stmt->execute();
$wire_total = number_format((float)$stmt->fetch(PDO::FETCH_NUM)[0], 2);

$sql = "SELECT SUM(amount) as total FROM deposit";
$stmt = $conn->prepare($sql); $stmt->execute();
$dep_total = number_format((float)$stmt->fetch(PDO::FETCH_NUM)[0], 2);

$sql = "SELECT COUNT(*) as total FROM wire_transfer WHERE wire_status='0'";
$stmt = $conn->prepare($sql); $stmt->execute();
$pending_wire = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

$sql = "SELECT COUNT(*) as total FROM loan WHERE loan_status='0'";
$stmt = $conn->prepare($sql); $stmt->execute();
$pending_loans = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

$sql = "SELECT COUNT(*) as total FROM withdrawal WHERE status='0'";
$stmt = $conn->prepare($sql); $stmt->execute();
$pending_withdrawals = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

$sql = "SELECT firstname, lastname, acct_email, acct_type, acct_balance, acct_currency, acct_status FROM users ORDER BY id DESC LIMIT 8";
$stmt = $conn->prepare($sql); $stmt->execute();
$recent_users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div id="content" class="main-content">
<div class="layout-px-spacing">

<div class="adm-page-header">
  <div>
    <h1 class="adm-page-title">Admin Dashboard</h1>
    <nav class="adm-breadcrumb"><span>Overview & Analytics</span></nav>
  </div>
  <a href="./reguser.php" class="adm-btn adm-btn-primary"><i class="ri-user-add-line"></i> New Account</a>
</div>

<!-- Stat Cards -->
<div class="adm-stat-grid">
  <div class="adm-stat">
    <div class="adm-stat-icon blue"><i class="ri-group-line"></i></div>
    <div class="adm-stat-label">Total Users</div>
    <div class="adm-stat-value"><?= $total_users ?></div>
    <div class="adm-stat-sub">Registered accounts</div>
    <div class="adm-stat-accent blue"></div>
  </div>
  <div class="adm-stat">
    <div class="adm-stat-icon green"><i class="ri-bank-card-line"></i></div>
    <div class="adm-stat-label">Total Balance</div>
    <div class="adm-stat-value" style="font-size:1.3rem">$<?= $total_balance ?></div>
    <div class="adm-stat-sub">All user balances combined</div>
    <div class="adm-stat-accent green"></div>
  </div>
  <div class="adm-stat">
    <div class="adm-stat-icon cyan"><i class="ri-send-plane-line"></i></div>
    <div class="adm-stat-label">Wire Transfers</div>
    <div class="adm-stat-value" style="font-size:1.3rem">$<?= $wire_total ?></div>
    <div class="adm-stat-sub">Total wire volume</div>
    <div class="adm-stat-accent cyan"></div>
  </div>
  <div class="adm-stat">
    <div class="adm-stat-icon orange"><i class="ri-arrow-down-circle-line"></i></div>
    <div class="adm-stat-label">Total Deposits</div>
    <div class="adm-stat-value" style="font-size:1.3rem">$<?= $dep_total ?></div>
    <div class="adm-stat-sub">Total deposit volume</div>
    <div class="adm-stat-accent orange"></div>
  </div>
  <div class="adm-stat">
    <div class="adm-stat-icon red"><i class="ri-time-line"></i></div>
    <div class="adm-stat-label">Pending Actions</div>
    <div class="adm-stat-value"><?= $pending_wire + $pending_loans + $pending_withdrawals ?></div>
    <div class="adm-stat-sub"><?= $pending_wire ?> wire · <?= $pending_loans ?> loans · <?= $pending_withdrawals ?> withdrawals</div>
    <div class="adm-stat-accent red"></div>
  </div>
  <div class="adm-stat">
    <div class="adm-stat-icon purple"><i class="ri-exchange-funds-line"></i></div>
    <div class="adm-stat-label">Pending Loans</div>
    <div class="adm-stat-value"><?= $pending_loans ?></div>
    <div class="adm-stat-sub">Awaiting review</div>
    <div class="adm-stat-accent purple"></div>
  </div>
</div>

<!-- Quick links row -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:12px;margin-bottom:24px">
  <a href="./users.php" class="adm-btn adm-btn-outline" style="justify-content:center"><i class="ri-group-line"></i> All Users</a>
  <a href="./funduser.php" class="adm-btn adm-btn-outline" style="justify-content:center"><i class="ri-funds-line"></i> Fund User</a>
  <a href="./wire-trans.php" class="adm-btn adm-btn-outline" style="justify-content:center"><i class="ri-send-plane-line"></i> Wire</a>
  <a href="./loan-trans.php" class="adm-btn adm-btn-outline" style="justify-content:center"><i class="ri-bank-line"></i> Loans</a>
  <a href="./withdraw-trans.php" class="adm-btn adm-btn-outline" style="justify-content:center"><i class="ri-wallet-line"></i> Withdrawals</a>
  <a href="./settings.php" class="adm-btn adm-btn-outline" style="justify-content:center"><i class="ri-settings-3-line"></i> Settings</a>
</div>

<!-- Recent Users full-width -->
<div class="adm-card">
  <div class="adm-card-header">
    <h2 class="adm-card-title"><i class="ri-group-line"></i> Recent Users</h2>
    <a href="./users.php" class="adm-btn adm-btn-sm adm-btn-outline">View All</a>
  </div>
  <div class="adm-card-body" style="padding:0">
    <table class="adm-table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Type</th>
          <th>Balance</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($recent_users as $u):
          $curr_sym = $u['acct_currency'] === 'USD' ? '$' : ($u['acct_currency'] === 'Euro' ? '€' : ($u['acct_currency'] === 'GBP' ? '£' : '$'));
        ?>
        <tr>
          <td style="font-weight:600;font-size:.84rem"><?= htmlspecialchars(ucwords($u['firstname'].' '.$u['lastname'])) ?></td>
          <td style="font-size:.78rem;color:var(--adm-text3)"><?= htmlspecialchars($u['acct_email']) ?></td>
          <td><span class="adm-badge adm-badge-info"><?= htmlspecialchars($u['acct_type']) ?></span></td>
          <td style="font-weight:600"><?= $curr_sym.number_format((float)$u['acct_balance'],2) ?></td>
          <td><span class="adm-badge <?= strtolower($u['acct_status']) === 'active' ? 'adm-badge-success' : 'adm-badge-neutral' ?>"><?= ucfirst(htmlspecialchars($u['acct_status'])) ?></span></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

</div>
</div>

<?php include_once("./layout/footer.php"); ?>
