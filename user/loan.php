<?php
$pageName = "Loans & Mortgages";
include_once("layouts/header.php");
$breadcrumbs = [['Home','./dashboard.php'],['Services','#'],['Loans & Mortgages',null]];
include_once('layouts/breadcrumb.php');
$acct_id = userDetails('id');

if(isset($_POST['loan-submit'])){
    $amount       = $_POST['amount'];
    $loan_remarks = $_POST['loan_remarks'];
    $reference_id = uniqid();

    if(empty($amount)){
        notify_alert('Amount Required','info','3000','Close');
    } elseif($amount <= 0){
        notify_alert('Invalid Amount','info','3000','Close');
    } elseif(empty($loan_remarks)){
        notify_alert('Loan Description Required!','info','3000','Close');
    } else {
        $sql2 = "INSERT INTO loan (loan_reference_id,acct_id,amount,loan_remarks) VALUES (:loan_reference_id,:acct_id,:amount,:loan_remarks)";
        $stmt = $conn->prepare($sql2);
        $stmt->execute([
            'loan_reference_id' => $reference_id,
            'acct_id'           => $acct_id,
            'amount'            => $amount,
            'loan_remarks'      => $loan_remarks
        ]);

        $email       = $acct_email;
        $APP_NAME    = $pageTitle;
        $APP_URL     = APP_URL;
        $message     = $sendMail->LoanMsg($currency, $amount, $loan_remarks, $fullName, $APP_NAME, $APP_URL);
        $subject     = "Loan Status - $APP_NAME";
        $email_message->send_mail($email, $message, $subject);
        $email_message->send_mail(WEB_EMAIL, $message, $subject);

        toast_alert('success', 'Your loan application has been submitted. Await approval.', 'Success');
    }
}

// Fetch existing loans
$sqlLoans = "SELECT * FROM loan WHERE acct_id=:acct_id ORDER BY loan_id DESC";
$stmtLoans = $conn->prepare($sqlLoans);
$stmtLoans->execute(['acct_id' => $acct_id]);
$myLoans = $stmtLoans->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="bp-grid-2" style="gap:24px;align-items:start;">

    <!-- Loan Application Form -->
    <div class="bp-card">
        <div class="bp-card-header">
            <h5 class="bp-card-title"><i class="ri-money-dollar-circle-line" style="color:var(--bp-primary);margin-right:6px;"></i>Loan / Mortgage Application</h5>
        </div>
        <div class="bp-card-body">
            <?php if($acct_stat === 'active'): ?>
            <form method="POST">
                <div style="display:flex;flex-direction:column;gap:18px;">

                    <div class="bp-grid-2" style="gap:14px;">
                        <div>
                            <label class="bp-form-label">Loan Amount (<?= htmlspecialchars($currency) ?>)</label>
                            <div class="bp-input-group">
                                <span class="bp-input-prefix"><i class="ri-money-dollar-circle-line"></i></span>
                                <input type="number" class="bp-form-input" name="amount" value="<?= htmlspecialchars($_POST['amount'] ?? '') ?>" placeholder="Enter amount" required>
                            </div>
                        </div>
                        <div>
                            <label class="bp-form-label">Recipient</label>
                            <input type="text" class="bp-form-input" value="Customer Service" readonly style="background:var(--bp-surface2);">
                        </div>
                    </div>

                    <div>
                        <label class="bp-form-label">Purpose / Narration</label>
                        <textarea class="bp-form-input" name="loan_remarks" rows="4" placeholder="Describe the purpose of this loan..."><?= htmlspecialchars($_POST['loan_remarks'] ?? '') ?></textarea>
                    </div>

                    <div style="background:rgba(67,97,238,0.06);border:1px solid rgba(67,97,238,0.15);border-radius:10px;padding:14px;">
                        <div style="font-size:.8rem;color:var(--bp-text3);line-height:1.7;">
                            <i class="ri-information-line" style="color:var(--bp-primary);"></i>
                            Loan applications are reviewed within 2-5 business days. You will be notified via email once a decision is made.
                        </div>
                    </div>

                    <button type="submit" name="loan-submit" class="bp-btn-primary" style="width:100%;justify-content:center;padding:12px;">
                        <i class="ri-send-plane-line"></i> Submit Application
                    </button>
                </div>
            </form>
            <?php else: ?>
            <div style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2);border-radius:12px;padding:20px;display:flex;align-items:flex-start;gap:12px;">
                <i class="ri-error-warning-line" style="color:var(--bp-red);font-size:1.3rem;flex-shrink:0;margin-top:2px;"></i>
                <div>
                    <div style="font-size:.88rem;font-weight:700;color:var(--bp-text);margin-bottom:4px;">Account on Hold</div>
                    <div style="font-size:.8rem;color:var(--bp-text2);">Your account is currently on hold. Contact support to continue.</div>
                    <a href="mailto:<?= htmlspecialchars($url_email) ?>" class="bp-btn-outline" style="margin-top:12px;font-size:.8rem;padding:7px 14px;">
                        <i class="ri-mail-line"></i> Contact Support
                    </a>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Loan Info + My Loans -->
    <div style="display:flex;flex-direction:column;gap:20px;">

        <!-- Loan Types Info -->
        <div class="bp-card">
            <div class="bp-card-header">
                <h5 class="bp-card-title"><i class="ri-home-4-line" style="color:var(--bp-orange);margin-right:6px;"></i>Loan Types Available</h5>
            </div>
            <div class="bp-card-body">
                <div style="display:flex;flex-direction:column;gap:12px;">
                    <?php
                    $types = [
                        ['icon'=>'ri-home-2-line','color'=>'var(--bp-primary)','name'=>'Home Mortgage','desc'=>'Purchase or refinance residential property'],
                        ['icon'=>'ri-user-heart-line','color'=>'var(--bp-green)','name'=>'Personal Loan','desc'=>'Flexible loans for personal expenses'],
                        ['icon'=>'ri-building-line','color'=>'var(--bp-orange)','name'=>'Business Loan','desc'=>'Capital for business growth and operations'],
                        ['icon'=>'ri-car-line','color'=>'var(--bp-cyan)','name'=>'Auto Loan','desc'=>'Finance your vehicle purchase'],
                    ];
                    foreach($types as $lt): ?>
                    <div style="display:flex;align-items:center;gap:12px;padding:10px;background:var(--bp-surface2);border-radius:10px;">
                        <div style="width:36px;height:36px;border-radius:9px;background:rgba(67,97,238,0.08);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="<?= $lt['icon'] ?>" style="color:<?= $lt['color'] ?>;font-size:1rem;"></i>
                        </div>
                        <div>
                            <div style="font-size:.84rem;font-weight:700;color:var(--bp-text);"><?= $lt['name'] ?></div>
                            <div style="font-size:.74rem;color:var(--bp-text3);"><?= $lt['desc'] ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- My Recent Loans -->
        <?php if(!empty($myLoans)): ?>
        <div class="bp-card">
            <div class="bp-card-header">
                <h5 class="bp-card-title"><i class="ri-receipt-line" style="color:var(--bp-primary);margin-right:6px;"></i>My Loan Applications</h5>
                <a href="./loan-transaction.php" class="bp-card-badge" style="text-decoration:none;">View All</a>
            </div>
            <div class="bp-card-body" style="padding:0;">
                <?php foreach(array_slice($myLoans, 0, 4) as $ln):
                    $lnStatus = loanStatus($ln);
                    $statusColor = 'var(--bp-orange)';
                    $statusBg    = 'rgba(245,158,11,0.1)';
                    if(strpos(strtolower($lnStatus),'approv')!==false){ $statusColor='var(--bp-green)'; $statusBg='rgba(16,185,129,0.1)'; }
                    if(strpos(strtolower($lnStatus),'reject')!==false){ $statusColor='var(--bp-red)'; $statusBg='rgba(239,68,68,0.1)'; }
                ?>
                <div style="display:flex;align-items:center;gap:12px;padding:14px 20px;border-bottom:1px solid var(--bp-border);">
                    <div style="width:36px;height:36px;border-radius:9px;background:rgba(245,158,11,0.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="ri-money-dollar-circle-line" style="color:var(--bp-orange);"></i>
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div style="font-size:.82rem;font-weight:600;color:var(--bp-text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= htmlspecialchars(substr($ln['loan_remarks'],0,30)) ?>...</div>
                        <div style="font-size:.73rem;color:var(--bp-text3);"><?= htmlspecialchars($currency.number_format($ln['amount'],2)) ?></div>
                    </div>
                    <span style="font-size:.7rem;font-weight:700;padding:2px 9px;border-radius:20px;background:<?= $statusBg ?>;color:<?= $statusColor ?>;white-space:nowrap;"><?= $lnStatus ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>

<?php include_once("layouts/footer.php"); ?>
