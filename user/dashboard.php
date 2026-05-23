<?php
$pageName = "Dashboard";
include_once("layouts/header.php");
if(!$_SESSION['acct_no']) { header("location:../login.php"); die; }
if(@!$_COOKIE['firstVisit']){
    setcookie("firstVisit", "no", time() + 3600);
}
unset($_SESSION['wire_transfer'], $_SESSION['dom_transfer']);

// ── Stats data ──
$acct_id = $user_id;

// Total transactions count and sum
$sqlStats = "SELECT COUNT(*) as cnt, SUM(CASE WHEN trans_type='1' THEN amount ELSE 0 END) as total_in, SUM(CASE WHEN trans_type='2' THEN amount ELSE 0 END) as total_out FROM transactions WHERE user_id=:uid";
$stmtStats = $conn->prepare($sqlStats);
$stmtStats->execute(['uid' => $acct_id]);
$stats = $stmtStats->fetch(PDO::FETCH_ASSOC);
$total_in  = $stats['total_in']  ?? 0;
$total_out = $stats['total_out'] ?? 0;

// Monthly stats
$sqlMonthly = "SELECT COUNT(*) as cnt, SUM(amount) as total FROM transactions WHERE user_id=:uid AND EXTRACT(MONTH FROM TO_TIMESTAMP(created_at,'YYYY-MM-DD')) = EXTRACT(MONTH FROM NOW())";
$stmtMonthly = $conn->prepare($sqlMonthly);
$stmtMonthly->execute(['uid' => $acct_id]);
$monthly = $stmtMonthly->fetch(PDO::FETCH_ASSOC);

// Pending transfers
$sqlPending = "SELECT COUNT(*) as cnt FROM domestic_transfer WHERE acct_id=:uid AND dom_status=0";
$stmtPending = $conn->prepare($sqlPending);
$stmtPending->execute(['uid' => $acct_id]);
$pendingDom = $stmtPending->fetch(PDO::FETCH_ASSOC);

$sqlPendingWire = "SELECT COUNT(*) as cnt FROM wire_transfer WHERE acct_id=:uid AND wire_status=0";
$stmtPW = $conn->prepare($sqlPendingWire);
$stmtPW->execute(['uid' => $acct_id]);
$pendingWire = $stmtPW->fetch(PDO::FETCH_ASSOC);

$pending_count = ($pendingDom['cnt'] ?? 0) + ($pendingWire['cnt'] ?? 0);

// Active loans
$sqlLoans = "SELECT COUNT(*) as cnt, SUM(amount) as total FROM loan WHERE acct_id=:uid";
$stmtLoansData = $conn->prepare($sqlLoans);
$stmtLoansData->execute(['uid' => $acct_id]);
$loanData = $stmtLoansData->fetch(PDO::FETCH_ASSOC);

// Recent transactions (last 8)
$sqlRecent = "SELECT * FROM transactions WHERE user_id=:uid ORDER BY trans_id DESC LIMIT 8";
$stmtRecent = $conn->prepare($sqlRecent);
$stmtRecent->execute(['uid' => $acct_id]);
$recentTxns = $stmtRecent->fetchAll(PDO::FETCH_ASSOC);

// Monthly chart data (last 6 months credit/debit)
$chartData = [];
for($m = 5; $m >= 0; $m--) {
    $label = date('M', strtotime("-{$m} months"));
    $monthNum = date('n', strtotime("-{$m} months"));
    $yearNum  = date('Y', strtotime("-{$m} months"));
    $sqlC = "SELECT COALESCE(SUM(CASE WHEN trans_type='1' THEN amount ELSE 0 END),0) as cr, COALESCE(SUM(CASE WHEN trans_type='2' THEN amount ELSE 0 END),0) as dr FROM transactions WHERE user_id=:uid AND EXTRACT(MONTH FROM TO_TIMESTAMP(created_at,'YYYY-MM-DD'))=:m AND EXTRACT(YEAR FROM TO_TIMESTAMP(created_at,'YYYY-MM-DD'))=:y";
    $stmtC = $conn->prepare($sqlC);
    $stmtC->execute(['uid' => $acct_id, 'm' => $monthNum, 'y' => $yearNum]);
    $cd = $stmtC->fetch(PDO::FETCH_ASSOC);
    $chartData[] = ['label' => $label, 'cr' => (float)$cd['cr'], 'dr' => (float)$cd['dr']];
}

$breadcrumbs = [['Home','./dashboard.php'],['Dashboard',null]];
?>

<?php include_once('layouts/breadcrumb.php'); ?>

<!-- ── Balance Cards Row ── -->
<div class="bp-balance-grid">

    <div class="bp-balance-card bp-bc-blue">
        <div class="bp-bc-label">Total Balance</div>
        <div class="bp-bc-amount"><?= $currency.number_format($acct_balance,2) ?></div>
        <div class="bp-bc-footer">
            <i class="ri-arrow-up-line"></i> Main Account
        </div>
        <div class="bp-bc-icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2z"/><path d="M12 6v6l4 2"/></svg>
        </div>
    </div>

    <div class="bp-balance-card bp-bc-green">
        <div class="bp-bc-label">Available Balance</div>
        <div class="bp-bc-amount"><?= $currency.number_format($avail_balance,2) ?></div>
        <div class="bp-bc-footer">
            <i class="ri-checkbox-circle-line"></i> Ready to use
        </div>
        <div class="bp-bc-icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M17 9V7a5 5 0 0 0-10 0v2"/><rect x="3" y="9" width="18" height="13" rx="2"/></svg>
        </div>
    </div>

    <div class="bp-balance-card bp-bc-orange">
        <div class="bp-bc-label">Account Limit</div>
        <div class="bp-bc-amount"><?= $currency.number_format($row['acct_limit'] ?? 0,2) ?></div>
        <div class="bp-bc-footer">
            <i class="ri-bar-chart-line"></i> Remaining: <?= $currency.number_format($limitRemain ?? 0,2) ?>
        </div>
        <div class="bp-bc-icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
        </div>
    </div>

    <div class="bp-balance-card bp-bc-purple">
        <div class="bp-bc-label">Loan Balance</div>
        <div class="bp-bc-amount"><?= $currency.number_format($row['loan_balance'] ?? 0,2) ?></div>
        <div class="bp-bc-footer">
            <i class="ri-error-warning-line"></i> <?= $loanData['cnt'] ?? 0 ?> active loan(s)
        </div>
        <div class="bp-bc-icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
        </div>
    </div>

</div>

<!-- ── Stats Row ── -->
<div class="bp-stats-grid bp-mb-24">

    <div class="bp-stat-card">
        <div class="bp-stat-icon bp-si-green"><i class="ri-arrow-down-line" style="font-size:18px;"></i></div>
        <div class="bp-stat-value"><?= $currency.number_format($total_in,2) ?></div>
        <div class="bp-stat-label">Total Income</div>
        <span class="bp-stat-change up"><i class="ri-arrow-up-line"></i> All time</span>
    </div>

    <div class="bp-stat-card">
        <div class="bp-stat-icon bp-si-red"><i class="ri-arrow-up-line" style="font-size:18px;"></i></div>
        <div class="bp-stat-value"><?= $currency.number_format($total_out,2) ?></div>
        <div class="bp-stat-label">Total Spent</div>
        <span class="bp-stat-change down"><i class="ri-arrow-down-line"></i> All time</span>
    </div>

    <div class="bp-stat-card">
        <div class="bp-stat-icon bp-si-orange"><i class="ri-time-line" style="font-size:18px;"></i></div>
        <div class="bp-stat-value"><?= $pending_count ?></div>
        <div class="bp-stat-label">Pending Transfers</div>
        <span class="bp-stat-change" style="background:rgba(245,158,11,.1);color:var(--bp-orange);">Awaiting</span>
    </div>

    <div class="bp-stat-card">
        <div class="bp-stat-icon bp-si-blue"><i class="ri-exchange-line" style="font-size:18px;"></i></div>
        <div class="bp-stat-value"><?= $stats['cnt'] ?? 0 ?></div>
        <div class="bp-stat-label">Total Transactions</div>
        <span class="bp-stat-change up"><i class="ri-arrow-up-line"></i> All time</span>
    </div>

    <div class="bp-stat-card">
        <div class="bp-stat-icon bp-si-purple"><i class="ri-bank-card-line" style="font-size:18px;"></i></div>
        <div class="bp-stat-value"><?= $cardstmt->rowCount() > 0 ? 'Active' : 'None' ?></div>
        <div class="bp-stat-label">Virtual Card</div>
        <a href="./card.php" style="font-size:.72rem;color:var(--bp-primary);font-weight:600;margin-top:6px;display:inline-block;">Manage →</a>
    </div>

    <div class="bp-stat-card">
        <div class="bp-stat-icon bp-si-cyan"><i class="ri-shield-check-line" style="font-size:18px;"></i></div>
        <div class="bp-stat-value" style="font-size:.9rem;text-transform:capitalize;"><?= htmlspecialchars($acct_stat ?? 'Active') ?></div>
        <div class="bp-stat-label">Account Status</div>
        <span class="bp-stat-change up">Verified</span>
    </div>

</div>

<!-- ── Middle Row: Chart + Quick Actions ── -->
<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;margin-bottom:24px;" class="bp-row-chart">

    <!-- Chart -->
    <div class="bp-card">
        <div class="bp-card-header">
            <h3 class="bp-card-title">Transaction Overview</h3>
            <span class="bp-card-badge">Last 6 Months</span>
        </div>
        <div class="bp-card-body">
            <div id="bp-txn-chart" style="min-height:220px;"></div>
        </div>
    </div>

    <!-- Account Summary -->
    <div class="bp-card">
        <div class="bp-card-header">
            <h3 class="bp-card-title">Account Summary</h3>
        </div>
        <div class="bp-card-body">
            <div class="bp-acct-info">

                <div class="bp-acct-row">
                    <span class="bp-acct-row-label">Account No.</span>
                    <span class="bp-acct-row-val" style="font-family:monospace;font-size:.8rem;"><?= htmlspecialchars($row['acct_no']) ?></span>
                </div>
                <div class="bp-acct-row">
                    <span class="bp-acct-row-label">Account Type</span>
                    <span class="bp-acct-row-val"><?= htmlspecialchars($row['acct_type'] ?? '—') ?></span>
                </div>
                <div class="bp-acct-row">
                    <span class="bp-acct-row-label">Currency</span>
                    <span class="bp-acct-row-val"><?= htmlspecialchars($row['acct_currency'] ?? '—') ?></span>
                </div>
                <div class="bp-acct-row">
                    <span class="bp-acct-row-label">Last Login IP</span>
                    <span class="bp-acct-row-val" style="font-size:.75rem;color:var(--bp-text3);"><?= htmlspecialchars($ipAddress ?: '—') ?></span>
                </div>
                <div class="bp-acct-row">
                    <span class="bp-acct-row-label">Last Login</span>
                    <span class="bp-acct-row-val" style="font-size:.75rem;color:var(--bp-text3);"><?= htmlspecialchars(substr($datenow ?? '—', 0, 16)) ?></span>
                </div>

                <!-- Limit usage bar -->
                <?php
                $limitUsedPct = 0;
                if(!empty($row['acct_limit']) && $row['acct_limit'] > 0) {
                    $used = $row['acct_limit'] - ($limitRemain ?? 0);
                    $limitUsedPct = min(100, max(0, round(($used / $row['acct_limit']) * 100)));
                }
                ?>
                <div style="margin-top:6px;">
                    <div style="display:flex;justify-content:space-between;font-size:.72rem;color:var(--bp-text3);margin-bottom:6px;">
                        <span>Limit Used</span>
                        <span><?= $limitUsedPct ?>%</span>
                    </div>
                    <div class="bp-progress">
                        <div class="bp-progress-bar bp-pb-orange" style="width:<?= $limitUsedPct ?>%;"></div>
                    </div>
                </div>

                <div style="display:flex;gap:8px;margin-top:14px;flex-wrap:wrap;">
                    <a href="./domestic-transfer.php" class="bp-btn-primary" style="flex:1;justify-content:center;font-size:.78rem;padding:9px 10px;">
                        <i class="ri-send-plane-line"></i> Send
                    </a>
                    <a href="./deposit.php" class="bp-btn-outline" style="flex:1;justify-content:center;font-size:.78rem;padding:8px 10px;">
                        <i class="ri-add-line"></i> Deposit
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- ── Bottom Row: Recent Transactions + News ── -->
<div style="display:grid;grid-template-columns:3fr 2fr;gap:20px;margin-bottom:24px;" class="bp-row-bottom">

    <!-- Recent Transactions -->
    <div class="bp-card">
        <div class="bp-card-header">
            <h3 class="bp-card-title">Recent Transactions</h3>
            <a href="./credit-debit_transaction.php" class="bp-card-badge" style="text-decoration:none;">View All →</a>
        </div>
        <div class="bp-card-body" style="padding:0;">
            <?php if(empty($recentTxns)): ?>
            <div class="bp-empty">
                <i class="ri-exchange-line" style="font-size:40px;opacity:.25;display:block;margin-bottom:10px;"></i>
                <p>No transactions yet</p>
            </div>
            <?php else: ?>
            <div style="overflow-x:auto;">
            <table class="bp-table">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Description</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($recentTxns as $tx):
                    $isCredit = ($tx['trans_type'] == '1');
                    $txStatus = transStatus($tx);
                ?>
                <tr>
                    <td>
                        <?php if($isCredit): ?>
                        <span class="bp-tx-type bp-tx-credit"><i class="ri-arrow-down-line"></i> Credit</span>
                        <?php else: ?>
                        <span class="bp-tx-type bp-tx-debit"><i class="ri-arrow-up-line"></i> Debit</span>
                        <?php endif; ?>
                    </td>
                    <td class="<?= $isCredit ? 'bp-tx-amount-pos' : 'bp-tx-amount-neg' ?>">
                        <?= $isCredit ? '+' : '-' ?><?= $currency.number_format($tx['amount'],2) ?>
                    </td>
                    <td style="max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= htmlspecialchars(substr($tx['description'],0,30)) ?></td>
                    <td style="white-space:nowrap;font-size:.75rem;"><?= htmlspecialchars($tx['created_at']) ?></td>
                    <td><?= $txStatus ?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Financial News -->
    <div class="bp-card">
        <div class="bp-card-header">
            <h3 class="bp-card-title">Financial News</h3>
            <span class="bp-card-badge">Live Feed</span>
        </div>
        <div class="bp-card-body">
            <?php
            $newsItems = [
                ['emoji'=>'📈','source'=>'Reuters','title'=>'Global markets rally as inflation cools — investors optimistic on rate cuts','time'=>'2h ago'],
                ['emoji'=>'🏦','source'=>'Bloomberg','title'=>'Federal Reserve signals pause on interest rate hikes amid steady growth','time'=>'4h ago'],
                ['emoji'=>'💱','source'=>'FT','title'=>'Dollar weakens against major currencies as trade deficit narrows','time'=>'6h ago'],
                ['emoji'=>'🪙','source'=>'CoinDesk','title'=>'Bitcoin surges past key resistance level on institutional buying','time'=>'8h ago'],
                ['emoji'=>'📊','source'=>'WSJ','title'=>'S&P 500 notches fourth consecutive week of gains on tech rally','time'=>'10h ago'],
            ];
            foreach($newsItems as $news):
            ?>
            <div class="bp-news-item">
                <div class="bp-news-img"><?= $news['emoji'] ?></div>
                <div class="bp-news-body">
                    <div class="bp-news-source"><?= $news['source'] ?></div>
                    <div class="bp-news-title"><?= $news['title'] ?></div>
                    <div class="bp-news-time"><i class="ri-time-line"></i> <?= $news['time'] ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

</div>

<!-- ── Quick Actions ── -->
<div class="bp-card bp-mb-24">
    <div class="bp-card-header">
        <h3 class="bp-card-title">Quick Actions</h3>
    </div>
    <div class="bp-card-body">
        <div class="bp-quick-actions">
            <a href="./domestic-transfer.php" class="bp-qa-btn">
                <div class="bp-qa-icon bp-si-blue"><i class="ri-send-plane-line" style="font-size:18px;"></i></div>
                <span class="bp-qa-label">Domestic</span>
            </a>
            <a href="./wire-transfer.php" class="bp-qa-btn">
                <div class="bp-qa-icon bp-si-purple"><i class="ri-global-line" style="font-size:18px;"></i></div>
                <span class="bp-qa-label">Wire Transfer</span>
            </a>
            <a href="./deposit.php" class="bp-qa-btn">
                <div class="bp-qa-icon bp-si-green"><i class="ri-add-circle-line" style="font-size:18px;"></i></div>
                <span class="bp-qa-label">Deposit</span>
            </a>
            <a href="./withdrawal.php" class="bp-qa-btn">
                <div class="bp-qa-icon bp-si-orange"><i class="ri-hand-coin-line" style="font-size:18px;"></i></div>
                <span class="bp-qa-label">Withdraw</span>
            </a>
            <a href="./loan.php" class="bp-qa-btn">
                <div class="bp-qa-icon bp-si-red"><i class="ri-money-dollar-circle-line" style="font-size:18px;"></i></div>
                <span class="bp-qa-label">Apply Loan</span>
            </a>
            <a href="./card.php" class="bp-qa-btn">
                <div class="bp-qa-icon bp-si-cyan"><i class="ri-bank-card-line" style="font-size:18px;"></i></div>
                <span class="bp-qa-label">My Card</span>
            </a>
            <a href="./profile.php" class="bp-qa-btn">
                <div class="bp-qa-icon" style="background:rgba(16,185,129,.1);color:var(--bp-green);"><i class="ri-user-line" style="font-size:18px;"></i></div>
                <span class="bp-qa-label">Profile</span>
            </a>
            <a href="./credit-debit_transaction.php" class="bp-qa-btn">
                <div class="bp-qa-icon" style="background:rgba(107,114,128,.1);color:#6b7280;"><i class="ri-receipt-line" style="font-size:18px;"></i></div>
                <span class="bp-qa-label">History</span>
            </a>
        </div>
    </div>
</div>

<!-- Chart script -->
<script>
document.addEventListener('DOMContentLoaded', function(){
    var labels  = <?= json_encode(array_column($chartData,'label')) ?>;
    var credits = <?= json_encode(array_column($chartData,'cr')) ?>;
    var debits  = <?= json_encode(array_column($chartData,'dr')) ?>;

    var isDark = document.body.classList.contains('bp-dark');
    var textColor = isDark ? '#8a96b0' : '#5a6a85';
    var gridColor = isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)';

    var options = {
        series: [
            { name: 'Income', data: credits },
            { name: 'Expenses', data: debits }
        ],
        chart: {
            type: 'area',
            height: 220,
            toolbar: { show: false },
            background: 'transparent',
            sparkline: { enabled: false }
        },
        colors: ['#4361ee','#ef4444'],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.25,
                opacityTo: 0.02,
                stops: [0, 90, 100]
            }
        },
        stroke: { curve: 'smooth', width: 2.5 },
        dataLabels: { enabled: false },
        xaxis: {
            categories: labels,
            labels: { style: { colors: textColor, fontSize: '11px' } },
            axisBorder: { show: false },
            axisTicks: { show: false }
        },
        yaxis: {
            labels: {
                style: { colors: textColor, fontSize: '11px' },
                formatter: function(v){ return '<?= $currency ?>'+v.toLocaleString(); }
            }
        },
        grid: {
            borderColor: gridColor,
            strokeDashArray: 4
        },
        tooltip: {
            y: { formatter: function(v){ return '<?= $currency ?>'+Number(v).toLocaleString(); } }
        },
        legend: {
            labels: { colors: textColor }
        }
    };

    var chart = new ApexCharts(document.getElementById('bp-txn-chart'), options);
    chart.render();

    // Re-render on theme toggle
    document.getElementById('bpDmToggle').addEventListener('click', function(){
        setTimeout(function(){
            var d = document.body.classList.contains('bp-dark');
            chart.updateOptions({
                xaxis: { labels: { style: { colors: d ? '#8a96b0':'#5a6a85' } } },
                yaxis: { labels: { style: { colors: d ? '#8a96b0':'#5a6a85' } } },
                legend: { labels: { colors: d ? '#8a96b0':'#5a6a85' } },
                grid:   { borderColor: d ? 'rgba(255,255,255,0.05)':'rgba(0,0,0,0.05)' }
            });
        }, 50);
    });
});
</script>

<?php
include_once('layouts/footer.php');
?>
