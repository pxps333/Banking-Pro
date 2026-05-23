<?php
$pageName = "Loan History";
include_once("layouts/header.php");
$breadcrumbs = [['Home','./dashboard.php'],['Transactions','#'],['Loan History',null]];
include_once('layouts/breadcrumb.php');
$acct_id = userDetails('id');

if (!$_SESSION['acct_no']) {
    header("location:../login.php");
    die;
}

$sql2 = "SELECT * FROM loan WHERE acct_id = :acct_id ORDER BY loan_id DESC";
$wire = $conn->prepare($sql2);
$wire->execute(['acct_id' => $acct_id]);
$loans = $wire->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="bp-card">
    <div class="bp-card-header" style="flex-wrap:wrap;gap:10px;">
        <h5 class="bp-card-title"><i class="ri-money-dollar-circle-line" style="color:var(--bp-primary);margin-right:6px;"></i>Loan History &amp; Inbox</h5>
        <div style="display:flex;gap:8px;">
            <a href="./loan.php" class="bp-btn-primary" style="font-size:.78rem;padding:6px 14px;">
                <i class="ri-add-line"></i> Apply for Loan
            </a>
            <button onclick="window.print()" class="bp-btn-outline" style="font-size:.78rem;padding:6px 14px;">
                <i class="ri-printer-line"></i> Print
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
                        <th style="font-size:.75rem;font-weight:700;color:var(--bp-text3);text-transform:uppercase;letter-spacing:.06em;padding:12px 16px;border-bottom:1px solid var(--bp-border);">Reason</th>
                        <th style="font-size:.75rem;font-weight:700;color:var(--bp-text3);text-transform:uppercase;letter-spacing:.06em;padding:12px 16px;border-bottom:1px solid var(--bp-border);">Status</th>
                        <th style="font-size:.75rem;font-weight:700;color:var(--bp-text3);text-transform:uppercase;letter-spacing:.06em;padding:12px 16px;border-bottom:1px solid var(--bp-border);">Date</th>
                        <th style="font-size:.75rem;font-weight:700;color:var(--bp-text3);text-transform:uppercase;letter-spacing:.06em;padding:12px 16px;border-bottom:1px solid var(--bp-border);text-align:center;">Details</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($loans)): ?>
                    <tr>
                        <td colspan="6" style="text-align:center;padding:48px 16px;">
                            <div style="display:flex;flex-direction:column;align-items:center;gap:10px;">
                                <i class="ri-money-dollar-circle-line" style="font-size:2.5rem;color:var(--bp-text3);opacity:.5;"></i>
                                <div style="font-size:.88rem;color:var(--bp-text3);">No loan applications yet</div>
                                <a href="./loan.php" class="bp-btn-primary" style="font-size:.78rem;padding:7px 16px;margin-top:4px;">Apply for a Loan</a>
                            </div>
                        </td>
                    </tr>
                <?php else: $sn = 1; foreach ($loans as $result):
                    $lStatus = loanModalStatus($result);
                    $statusColor = $result['loan_status'] == '1' ? 'var(--bp-green)' : ($result['loan_status'] == '2' ? 'var(--bp-red)' : 'var(--bp-orange)');
                    $statusBg    = $result['loan_status'] == '1' ? 'rgba(16,185,129,.12)' : ($result['loan_status'] == '2' ? 'rgba(239,68,68,.12)' : 'rgba(245,158,11,.12)');
                    ?>
                    <tr style="border-bottom:1px solid var(--bp-border);">
                        <td style="padding:12px 16px;font-size:.82rem;color:var(--bp-text3);"><?= $sn++ ?></td>
                        <td style="padding:12px 16px;font-weight:700;font-size:.9rem;color:var(--bp-primary);"><?= $currency . number_format((float)$result['amount'], 2) ?></td>
                        <td style="padding:12px 16px;font-size:.84rem;color:var(--bp-text2);max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="<?= htmlspecialchars($result['loan_remarks']) ?>"><?= htmlspecialchars($result['loan_remarks']) ?></td>
                        <td style="padding:12px 16px;">
                            <span style="font-size:.75rem;font-weight:700;padding:3px 10px;border-radius:20px;background:<?= $statusBg ?>;color:<?= $statusColor ?>;">
                                <?= $lStatus ?>
                            </span>
                        </td>
                        <td style="padding:12px 16px;font-size:.8rem;color:var(--bp-text3);"><?= htmlspecialchars(substr($result['created_at'] ?? '—', 0, 10)) ?></td>
                        <td style="padding:12px 16px;text-align:center;">
                            <a href="./viewloantrans.php?id=<?= htmlspecialchars($result['loan_reference_id']) ?>" class="bp-btn-outline" style="font-size:.75rem;padding:5px 12px;">
                                <i class="ri-eye-line"></i> View
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include_once("layouts/footer.php"); ?>
