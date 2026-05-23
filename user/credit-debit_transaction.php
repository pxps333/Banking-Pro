<?php
$pageName = "Credit / Debit Transactions";
include_once("layouts/header.php");
$breadcrumbs = [['Home','./dashboard.php'],['Transactions','#'],['Credit / Debit',null]];
include_once('layouts/breadcrumb.php');

$acct_id = userDetails('id');

if (!$_SESSION['acct_no']) {
    header("location:../login.php");
    die;
}

$sql = "SELECT * FROM transactions LEFT JOIN users ON transactions.user_id = users.id WHERE transactions.user_id = :acct_id ORDER BY transactions.trans_id DESC";
$stmt = $conn->prepare($sql);
$stmt->execute(['acct_id' => $acct_id]);
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="bp-card">
    <div class="bp-card-header" style="flex-wrap:wrap;gap:10px;">
        <h5 class="bp-card-title"><i class="ri-exchange-funds-line" style="color:var(--bp-primary);margin-right:6px;"></i>Credit / Debit History</h5>
        <div style="display:flex;gap:8px;">
            <button onclick="window.print()" class="bp-btn-outline" style="font-size:.78rem;padding:6px 14px;">
                <i class="ri-printer-line"></i> Print Statement
            </button>
        </div>
    </div>
    <div class="bp-card-body" style="padding:0;">
        <div class="table-responsive">
            <table class="table bp-datatable" style="width:100%">
                <thead>
                    <tr style="background:var(--bp-surface2);">
                        <th style="font-size:.75rem;font-weight:700;color:var(--bp-text3);text-transform:uppercase;letter-spacing:.06em;padding:12px 16px;border-bottom:1px solid var(--bp-border);">#</th>
                        <th style="font-size:.75rem;font-weight:700;color:var(--bp-text3);text-transform:uppercase;letter-spacing:.06em;padding:12px 16px;border-bottom:1px solid var(--bp-border);">Amount</th>
                        <th style="font-size:.75rem;font-weight:700;color:var(--bp-text3);text-transform:uppercase;letter-spacing:.06em;padding:12px 16px;border-bottom:1px solid var(--bp-border);">Type</th>
                        <th style="font-size:.75rem;font-weight:700;color:var(--bp-text3);text-transform:uppercase;letter-spacing:.06em;padding:12px 16px;border-bottom:1px solid var(--bp-border);">Sender / Receiver</th>
                        <th style="font-size:.75rem;font-weight:700;color:var(--bp-text3);text-transform:uppercase;letter-spacing:.06em;padding:12px 16px;border-bottom:1px solid var(--bp-border);">Description</th>
                        <th style="font-size:.75rem;font-weight:700;color:var(--bp-text3);text-transform:uppercase;letter-spacing:.06em;padding:12px 16px;border-bottom:1px solid var(--bp-border);">Date</th>
                        <th style="font-size:.75rem;font-weight:700;color:var(--bp-text3);text-transform:uppercase;letter-spacing:.06em;padding:12px 16px;border-bottom:1px solid var(--bp-border);">Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($transactions)): ?>
                    <tr>
                        <td colspan="7" style="text-align:center;padding:48px 16px;">
                            <div style="display:flex;flex-direction:column;align-items:center;gap:10px;">
                                <i class="ri-receipt-line" style="font-size:2.5rem;color:var(--bp-text3);opacity:.5;"></i>
                                <div style="font-size:.88rem;color:var(--bp-text3);">No transactions yet</div>
                            </div>
                        </td>
                    </tr>
                <?php else: $sn = 1; foreach ($transactions as $result):
                    $isCredit = ($result['trans_type'] == '1');
                    ?>
                    <tr style="border-bottom:1px solid var(--bp-border);">
                        <td style="padding:12px 16px;font-size:.82rem;color:var(--bp-text3);"><?= $sn++ ?></td>
                        <td style="padding:12px 16px;font-weight:700;font-size:.88rem;color:<?= $isCredit ? 'var(--bp-green)' : 'var(--bp-red)' ?>;">
                            <?= ($isCredit ? '+' : '−') . $currency . number_format((float)$result['amount'], 2) ?>
                        </td>
                        <td style="padding:12px 16px;">
                            <span style="font-size:.75rem;font-weight:700;padding:3px 10px;border-radius:20px;background:<?= $isCredit ? 'rgba(16,185,129,.12)' : 'rgba(239,68,68,.12)' ?>;color:<?= $isCredit ? 'var(--bp-green)' : 'var(--bp-red)' ?>;">
                                <?= $isCredit ? 'Credit' : 'Debit' ?>
                            </span>
                        </td>
                        <td style="padding:12px 16px;font-size:.84rem;color:var(--bp-text2);max-width:140px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="<?= htmlspecialchars($result['sender_name']) ?>"><?= htmlspecialchars($result['sender_name']) ?></td>
                        <td style="padding:12px 16px;font-size:.82rem;color:var(--bp-text2);max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="<?= htmlspecialchars($result['description']) ?>"><?= htmlspecialchars($result['description']) ?></td>
                        <td style="padding:12px 16px;font-size:.8rem;color:var(--bp-text3);">
                            <div><?= htmlspecialchars($result['created_at']) ?></div>
                            <div style="font-size:.72rem;opacity:.7;"><?= htmlspecialchars($result['time_created']) ?></div>
                        </td>
                        <td style="padding:12px 16px;">
                            <span style="font-size:.75rem;font-weight:700;padding:3px 10px;border-radius:20px;background:rgba(16,185,129,.12);color:var(--bp-green);">Completed</span>
                        </td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include_once("layouts/footer.php"); ?>
