<?php

$pageName = "Funding";
include("../include/vendor/autoload.php");
include_once("layouts/header.php");
$breadcrumbs = [['Home','./dashboard.php'],['Banking','#'],['Deposit',null]];
include_once('layouts/breadcrumb.php');

$sql7 = "SELECT * FROM v_bank ORDER BY id DESC LIMIT 1";
$stmt7 = $conn->prepare($sql7);
$stmt7->execute();
$deposit = $stmt7->fetch(PDO::FETCH_ASSOC);
if (!$deposit) { $deposit = ['routine_no'=>'','bank_name'=>'','swift_code'=>'','acct_no'=>'']; }

$routine_no = $deposit['routine_no'];
$bank_name  = $deposit['bank_name'];
$swift_code = $deposit['swift_code'];
$dep_acct_no = $deposit['acct_no'];
$email      = $row['acct_email'];

if(isset($_POST['deposit'])) {
    $amount         = $_POST['amount'];
    $crypto_name    = $_POST['crypto_name'];
    $wallet_address = $_POST['wallet_address'];
    $acct_id        = userDetails('id');

    if (empty($amount) || empty($crypto_name) || empty($wallet_address)) {
        notify_alert('Fill Required Form', 'danger', '3000', 'Close');
    } else if(empty($_FILES['image'])){
        notify_alert('Upload Payment Screenshot', 'danger', '3000', 'Close');
    } else {
        if (isset($_FILES['image'])) {
            $file   = $_FILES['image'];
            $name   = $file['name'];
            $path   = pathinfo($name, PATHINFO_EXTENSION);
            $allowed = array('jpg', 'png', 'jpeg');
            $folder = "../assets/deposit/";
            $n      = time() . $name;
            $destination = $folder . $n;
        }
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            if ($acct_stat === 'hold') {
                toast_alert('error', 'Account on Hold Contact Support for more info');
            } elseif ($amount < 0) {
                toast_alert('error', 'Invalid amount entered');
            } elseif ($amount < $trans_limit_min) {
                toast_alert('error', 'Amount Less than Deposit Limit');
            } elseif ($amount > $trans_limit_max) {
                toast_alert('error', 'Amount greater than Deposit Limit');
            } else {
                $reference_id = uniqid();
                $deposited = "INSERT INTO deposit (amount,user_id,wallet_address,crypto_id,image,refrence_id)VALUES(:amount,:user_id,:wallet_address,:crypto_id,:image,:refrence_id)";
                $stmt = $conn->prepare($deposited);
                $stmt->execute([
                    'amount'         => $amount,
                    'user_id'        => $acct_id,
                    'wallet_address' => $wallet_address,
                    'crypto_id'      => $crypto_name,
                    'image'          => $n,
                    'refrence_id'    => $reference_id
                ]);
                if (true) {
                    $sql  = "SELECT d.*, c.crypto_name FROM deposit d INNER JOIN crypto_currency c ON d.crypto_id = c.id WHERE d.user_id =:acct_id ORDER BY d.d_id DESC LIMIT 1";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute(['acct_id' => $acct_id]);
                    $result      = $stmt->fetch(PDO::FETCH_ASSOC);
                    $trans_id    = $result['refrence_id'];
                    $crypto_name = $result['crypto_name'];
                    $APP_NAME    = $pageTitle;
                    $message     = $sendMail->depositMsg($currency, $amount, $crypto_name, $fullName, $trans_id, $APP_NAME);
                    $subject     = "[DEPOSIT] - $APP_NAME";
                    $email_message->send_mail($email, $message, $subject);
                    $subject     = "Pending Deposit Notification - $APP_NAME";
                    $email_message->send_mail(WEB_EMAIL, $message, $subject);
                    toast_alert("success", "Your Deposit is being Processed", "Thanks!");
                }
            }
        }
    }
}
?>

<!-- Deposit Form -->
<div class="bp-grid-2" style="gap:24px;align-items:start;">

    <!-- Crypto Deposit -->
    <div class="bp-card">
        <div class="bp-card-header">
            <h5 class="bp-card-title"><i class="ri-bit-coin-line" style="color:var(--bp-primary);margin-right:6px;"></i>Crypto Deposit</h5>
            <span class="bp-card-badge">Recommended</span>
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
                    </div>

                    <div class="bp-grid-2" style="gap:14px;">
                        <div>
                            <label class="bp-form-label">Crypto Type</label>
                            <select name="crypto_name" class="bp-form-input" onchange="crypto_type(this.value)" required>
                                <option value="">Select crypto</option>
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
                            <label class="bp-form-label">Wallet Address</label>
                            <div class="bp-input-group">
                                <input type="text" class="bp-form-input" name="wallet_address" id="wallet_address" placeholder="Auto-filled" readonly>
                                <button type="button" class="bp-input-suffix" data-clipboard-action="copy" data-clipboard-target="#wallet_address" title="Copy">
                                    <i class="ri-file-copy-line"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="bp-form-label">Payment Screenshot</label>
                        <div class="custom-file-container" data-upload-id="myFirstImage" style="background:var(--bp-surface2);border:2px dashed var(--bp-border);border-radius:12px;padding:20px;text-align:center;">
                            <label style="font-size:.82rem;color:var(--bp-text3);cursor:pointer;display:block;">
                                <i class="ri-upload-cloud-line" style="font-size:2rem;display:block;margin-bottom:8px;color:var(--bp-primary);"></i>
                                Upload Payment Receipt
                                <a href="javascript:void(0)" class="custom-file-container__image-clear" title="Clear Image" style="margin-left:8px;color:var(--bp-red);text-decoration:none;">&times; Clear</a>
                            </label>
                            <label class="custom-file-container__custom-file">
                                <input type="file" class="custom-file-container__custom-file__custom-file-input" name="image" accept="image/*">
                                <input type="hidden" name="MAX_FILE_SIZE" value="10485760">
                                <span class="custom-file-container__custom-file__custom-file-control"></span>
                            </label>
                            <div class="custom-file-container__image-preview"></div>
                        </div>
                    </div>

                    <button type="submit" name="deposit" class="bp-btn-primary" style="width:100%;justify-content:center;padding:12px;">
                        <i class="ri-send-plane-line"></i> Submit Crypto Deposit
                    </button>
                </div>
            </form>
            <?php else: ?>
            <div style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2);border-radius:12px;padding:20px;display:flex;align-items:flex-start;gap:12px;">
                <i class="ri-error-warning-line" style="color:var(--bp-red);font-size:1.3rem;flex-shrink:0;margin-top:2px;"></i>
                <div>
                    <div style="font-size:.88rem;font-weight:700;color:var(--bp-text);margin-bottom:4px;">Account on Hold</div>
                    <div style="font-size:.8rem;color:var(--bp-text2);">Your account is currently on hold. Please contact support to continue.</div>
                    <a href="mailto:<?= htmlspecialchars($url_email) ?>" class="bp-btn-outline" style="margin-top:12px;font-size:.8rem;padding:7px 14px;">
                        <i class="ri-mail-line"></i> Contact Support
                    </a>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bank Deposit + Deposit Tips -->
    <div style="display:flex;flex-direction:column;gap:20px;">

        <!-- Bank Deposit -->
        <?php if($page['bank_deposit']==='1'): ?>
        <div class="bp-card">
            <div class="bp-card-header">
                <h5 class="bp-card-title"><i class="ri-bank-line" style="color:var(--bp-green);margin-right:6px;"></i>Bank Transfer Deposit</h5>
            </div>
            <div class="bp-card-body">
                <p style="font-size:.82rem;color:var(--bp-text3);margin-bottom:16px;">Send funds directly to our bank account. Use the details below.</p>
                <div style="display:flex;flex-direction:column;gap:12px;">
                    <?php
                    $bankFields = [
                        ['label'=>'Bank Name','id'=>'bank_name','val'=>$deposit['bank_name']],
                        ['label'=>'Account Number','id'=>'acct_no_bank','val'=>$deposit['acct_no']],
                        ['label'=>'Routing Number','id'=>'routine_no','val'=>$deposit['routine_no']],
                        ['label'=>'SWIFT Code','id'=>'swift_code','val'=>$deposit['swift_code']],
                    ];
                    foreach($bankFields as $bf): ?>
                    <div>
                        <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--bp-text3);margin-bottom:4px;"><?= $bf['label'] ?></div>
                        <div class="bp-input-group">
                            <input type="text" class="bp-form-input" id="<?= $bf['id'] ?>" value="<?= htmlspecialchars($bf['val'] ?? '') ?>" readonly>
                            <button type="button" class="bp-input-suffix" data-clipboard-action="copy" data-clipboard-target="#<?= $bf['id'] ?>" title="Copy">
                                <i class="ri-file-copy-line"></i>
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Deposit Tips -->
        <div class="bp-card">
            <div class="bp-card-header">
                <h5 class="bp-card-title"><i class="ri-information-line" style="color:var(--bp-cyan);margin-right:6px;"></i>Deposit Guidelines</h5>
            </div>
            <div class="bp-card-body">
                <div style="display:flex;flex-direction:column;gap:10px;">
                    <?php
                    $tips = [
                        ['icon'=>'ri-time-line','color'=>'var(--bp-orange)','text'=>'Processing takes 1-24 hours after confirmation'],
                        ['icon'=>'ri-shield-check-line','color'=>'var(--bp-green)','text'=>'Always send from your own verified wallet'],
                        ['icon'=>'ri-file-image-line','color'=>'var(--bp-primary)','text'=>'Upload a clear screenshot of your payment proof'],
                        ['icon'=>'ri-money-dollar-circle-line','color'=>'var(--bp-cyan)','text'=>"Min: {$currency}{$trans_limit_min} · Max: {$currency}{$trans_limit_max}"],
                    ];
                    foreach($tips as $t): ?>
                    <div style="display:flex;align-items:flex-start;gap:10px;">
                        <i class="<?= $t['icon'] ?>" style="color:<?= $t['color'] ?>;font-size:1rem;margin-top:2px;flex-shrink:0;"></i>
                        <span style="font-size:.8rem;color:var(--bp-text2);"><?= $t['text'] ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include_once('layouts/footer.php'); ?>
