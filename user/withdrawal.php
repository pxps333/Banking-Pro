<?php

$pageName = "Withdrawal";
include("../include/vendor/autoload.php");
include_once("layouts/header.php");
$breadcrumbs = [['Home','./dashboard.php'],['Banking','#'],['Withdrawal',null]];
include_once('layouts/breadcrumb.php');

$email = $row['acct_email'];

if(isset($_POST['withdraw'])){
    $user_id        = userDetails('id');
    $amount         = $_POST['amount'];
    $withdraw_method = $_POST['withdraw_method'];
    $wallet_address = $_POST['wallet_address'];
    $trans_type     = 2;

    $checkUser = $conn->query("SELECT * FROM users WHERE id='$user_id'");
    $result    = $checkUser->fetch(PDO::FETCH_ASSOC);

    if($amount > $result['acct_balance']){
        toast_alert('error','Insufficient Balance');
    } else {
        $available_balance = ($result['acct_balance'] - $amount);
        $sql    = "UPDATE users SET acct_balance=:available_balance WHERE id=:user_id";
        $addUp  = $conn->prepare($sql);
        $addUp->execute(['available_balance'=>$available_balance,'user_id'=>$user_id]);

        $trans_id = uniqid();
        $sql = "INSERT INTO withdrawal (user_id,amount,withdraw_method,wallet_address,reference_id,trans_type) VALUES(:user_id,:amount,:withdraw_method,:wallet_address,:reference_id,:trans_type)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'user_id'         => $user_id,
            'amount'          => $amount,
            'withdraw_method' => $withdraw_method,
            'wallet_address'  => $wallet_address,
            'reference_id'    => $trans_id,
            'trans_type'      => $trans_type,
        ]);

        $full_name  = $user['firstname'] . " " . $user['lastname'];
        $APP_NAME   = WEB_TITLE;
        $APP_URL    = WEB_URL;
        $user_email = $user['acct_email'];
        $message    = $sendMail->WithdrawMsg($currency, $full_name, $amount, $withdraw_method, $wallet_address, $APP_NAME);
        $subject    = "Withdrawal Notification - $APP_NAME";
        $email_message->send_mail($user_email, $message, $subject);
        $email_message->send_mail(WEB_EMAIL, $message, $subject);

        toast_alert('success', 'Your Withdrawal request has been submitted', 'Pending');
    }
}
?>

<div class="bp-grid-2" style="gap:24px;align-items:start;">

    <!-- Crypto Withdrawal Form -->
    <div class="bp-card">
        <div class="bp-card-header">
            <h5 class="bp-card-title"><i class="ri-hand-coin-line" style="color:var(--bp-primary);margin-right:6px;"></i>Crypto Withdrawal</h5>
        </div>
        <div class="bp-card-body">
            <?php if($acct_stat === 'active'): ?>
            <form method="POST" enctype="multipart/form-data">
                <div style="display:flex;flex-direction:column;gap:18px;">

                    <div>
                        <label class="bp-form-label">Amount (<?= htmlspecialchars($currency) ?>)</label>
                        <div class="bp-input-group">
                            <span class="bp-input-prefix"><i class="ri-money-dollar-circle-line"></i></span>
                            <input type="number" class="bp-form-input" name="amount" placeholder="Enter amount" required>
                        </div>
                        <div style="font-size:.75rem;color:var(--bp-text3);margin-top:4px;">
                            Available: <strong style="color:var(--bp-green);"><?= $currency . number_format($avail_balance, 2) ?></strong>
                        </div>
                    </div>

                    <div>
                        <label class="bp-form-label">Withdrawal Method</label>
                        <select name="withdraw_method" class="bp-form-input" required>
                            <option value="">Select crypto type</option>
                            <?php
                            $sql = $conn->query("SELECT * FROM crypto_currency ORDER BY crypto_name");
                            while($rs = $sql->fetch(PDO::FETCH_ASSOC)){
                                $data[] = ['id'=>$rs['id'],'wallet_address'=>$rs['wallet_address']];
                                echo '<option value="'.htmlspecialchars($rs['id']).'">'.htmlspecialchars(ucwords($rs['crypto_name'])).'</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div>
                        <label class="bp-form-label">Your Wallet Address</label>
                        <input type="text" class="bp-form-input" name="wallet_address" placeholder="Enter your wallet address" required>
                    </div>

                    <button type="submit" name="withdraw" class="bp-btn-primary" style="width:100%;justify-content:center;padding:12px;">
                        <i class="ri-hand-coin-line"></i> Submit Withdrawal
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

    <!-- Bank Withdrawal + Info -->
    <div style="display:flex;flex-direction:column;gap:20px;">

        <!-- Bank Withdrawal Link -->
        <div class="bp-card">
            <div class="bp-card-header">
                <h5 class="bp-card-title"><i class="ri-bank-line" style="color:var(--bp-green);margin-right:6px;"></i>Bank Withdrawal</h5>
            </div>
            <div class="bp-card-body">
                <p style="font-size:.83rem;color:var(--bp-text3);margin-bottom:16px;">Prefer to withdraw directly to your bank account? Use our bank withdrawal option.</p>
                <a href="./bank-withdraw.php" class="bp-btn-outline" style="width:100%;justify-content:center;padding:12px;">
                    <i class="ri-bank-line"></i> Use Bank Withdrawal
                </a>
            </div>
        </div>

        <!-- Withdrawal Info -->
        <div class="bp-card">
            <div class="bp-card-header">
                <h5 class="bp-card-title"><i class="ri-information-line" style="color:var(--bp-cyan);margin-right:6px;"></i>Withdrawal Info</h5>
            </div>
            <div class="bp-card-body">
                <div style="display:flex;flex-direction:column;gap:10px;">
                    <?php
                    $tips = [
                        ['icon'=>'ri-time-line','color'=>'var(--bp-orange)','text'=>'Withdrawals are processed within 1-3 business days'],
                        ['icon'=>'ri-shield-check-line','color'=>'var(--bp-green)','text'=>'Only withdraw to wallets you own and control'],
                        ['icon'=>'ri-wallet-3-line','color'=>'var(--bp-primary)','text'=>'Ensure your wallet address is correct before submitting'],
                        ['icon'=>'ri-alarm-warning-line','color'=>'var(--bp-red)','text'=>'Withdrawals cannot be reversed once processed'],
                    ];
                    foreach($tips as $t): ?>
                    <div style="display:flex;align-items:flex-start;gap:10px;">
                        <i class="<?= $t['icon'] ?>" style="color:<?= $t['color'] ?>;font-size:1rem;margin-top:2px;flex-shrink:0;"></i>
                        <span style="font-size:.8rem;color:var(--bp-text2);"><?= $t['text'] ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div style="margin-top:16px;background:rgba(67,97,238,0.06);border:1px solid rgba(67,97,238,0.15);border-radius:10px;padding:14px;">
                    <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--bp-primary);margin-bottom:8px;">Current Balance</div>
                    <div style="font-size:1.4rem;font-weight:800;color:var(--bp-text);"><?= $currency . number_format($acct_balance, 2) ?></div>
                    <div style="font-size:.78rem;color:var(--bp-green);margin-top:2px;"><i class="ri-checkbox-circle-line"></i> Available: <?= $currency . number_format($avail_balance, 2) ?></div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include_once('layouts/footer.php'); ?>
