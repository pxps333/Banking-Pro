<?php
$pageName = "Domestic Transfer";
include_once("layouts/header.php");
$breadcrumbs = [['Home','./dashboard.php'],['Banking','#'],['Domestic Transfer',null]];
include_once('layouts/breadcrumb.php');
require_once("./userPinfunction.php");
?>

<div class="bp-grid-2" style="gap:24px;align-items:start;">

    <!-- Transfer Form -->
    <div class="bp-card" style="grid-column:1/2;">
        <div class="bp-card-header">
            <h5 class="bp-card-title"><i class="ri-exchange-dollar-line" style="color:var(--bp-primary);margin-right:6px;"></i>Domestic Transfer</h5>
        </div>
        <div class="bp-card-body">
            <?php if($acct_stat === 'active'): ?>
            <?php if($page['transfer'] == '1'): ?>
            <?php if($row['transfer'] == '1'): ?>
            <form method="POST" enctype="multipart/form-data">
                <div style="display:flex;flex-direction:column;gap:18px;">

                    <div class="bp-grid-2" style="gap:14px;">
                        <div>
                            <label class="bp-form-label">Amount (<?= htmlspecialchars($currency) ?>)</label>
                            <div class="bp-input-group">
                                <span class="bp-input-prefix"><i class="ri-money-dollar-circle-line"></i></span>
                                <input type="number" class="bp-form-input" name="amount" placeholder="Enter amount" style="padding-left:38px;" required>
                            </div>
                            <div style="font-size:.73rem;color:var(--bp-text3);margin-top:3px;">
                                Available: <strong style="color:var(--bp-green);"><?= $currency . number_format($avail_balance, 2) ?></strong>
                            </div>
                        </div>
                        <div>
                            <label class="bp-form-label">Beneficiary Account Name</label>
                            <input type="text" class="bp-form-input" name="acct_name" placeholder="Full name on account" required>
                        </div>
                    </div>

                    <div class="bp-grid-2" style="gap:14px;">
                        <div>
                            <label class="bp-form-label">Bank Name</label>
                            <input type="text" class="bp-form-input" name="bank_name" placeholder="Receiving bank name" required>
                        </div>
                        <div>
                            <label class="bp-form-label">Beneficiary Account No</label>
                            <input type="number" class="bp-form-input" name="acct_number" placeholder="Account number" required>
                        </div>
                    </div>

                    <div class="bp-grid-2" style="gap:14px;">
                        <div>
                            <label class="bp-form-label">Account Type</label>
                            <select name="acct_type" class="bp-form-input" required>
                                <option value="">Select Account Type</option>
                                <option value="Savings">Savings Account</option>
                                <option value="Current">Current Account</option>
                                <option value="Checking">Checking Account</option>
                                <option value="Fixed Deposit">Fixed Deposit</option>
                                <option value="Non Resident">Non Resident Account</option>
                                <option value="Online Banking">Online Banking</option>
                                <option value="Domicilary Account">Domicilary Account</option>
                                <option value="Joint Account">Joint Account</option>
                            </select>
                        </div>
                        <div>
                            <label class="bp-form-label">Narration / Purpose</label>
                            <input type="text" class="bp-form-input" name="acct_remarks" placeholder="Fund description">
                        </div>
                    </div>

                    <button type="submit" name="domestic-transfer" class="bp-btn-primary" style="width:100%;justify-content:center;padding:12px;">
                        <i class="ri-send-plane-line"></i> Send Transfer
                    </button>
                </div>
            </form>
            <?php else: ?>
            <div style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2);border-radius:12px;padding:20px;display:flex;align-items:flex-start;gap:12px;">
                <i class="ri-error-warning-line" style="color:var(--bp-red);font-size:1.3rem;flex-shrink:0;margin-top:2px;"></i>
                <div>
                    <div style="font-size:.88rem;font-weight:700;color:var(--bp-text);margin-bottom:4px;">Domestic Transfer Disabled</div>
                    <div style="font-size:.8rem;color:var(--bp-text2);">You do not have permission to make domestic transfers. Contact support.</div>
                    <a href="mailto:<?= htmlspecialchars($page['url_email']) ?>" class="bp-btn-outline" style="margin-top:12px;font-size:.8rem;padding:7px 14px;">
                        <i class="ri-mail-line"></i> Contact Us
                    </a>
                </div>
            </div>
            <?php endif; ?>
            <?php else: ?>
            <div style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2);border-radius:12px;padding:20px;display:flex;align-items:flex-start;gap:12px;">
                <i class="ri-error-warning-line" style="color:var(--bp-red);font-size:1.3rem;flex-shrink:0;margin-top:2px;"></i>
                <div>
                    <div style="font-size:.88rem;font-weight:700;color:var(--bp-text);margin-bottom:4px;">Service Unavailable</div>
                    <div style="font-size:.8rem;color:var(--bp-text2);">Transfer service is currently unavailable. Please contact support.</div>
                    <a href="mailto:<?= htmlspecialchars($page['url_email']) ?>" class="bp-btn-outline" style="margin-top:12px;font-size:.8rem;padding:7px 14px;">
                        <i class="ri-mail-line"></i> Contact Us
                    </a>
                </div>
            </div>
            <?php endif; ?>
            <?php else: ?>
            <div style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2);border-radius:12px;padding:20px;display:flex;align-items:flex-start;gap:12px;">
                <i class="ri-error-warning-line" style="color:var(--bp-red);font-size:1.3rem;flex-shrink:0;margin-top:2px;"></i>
                <div>
                    <div style="font-size:.88rem;font-weight:700;color:var(--bp-text);margin-bottom:4px;">Account on Hold</div>
                    <div style="font-size:.8rem;color:var(--bp-text2);">Your account is on hold. Contact support to restore access.</div>
                    <a href="mailto:<?= htmlspecialchars($page['url_email']) ?>" class="bp-btn-outline" style="margin-top:12px;font-size:.8rem;padding:7px 14px;">
                        <i class="ri-mail-line"></i> Contact Us
                    </a>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Transfer Info -->
    <div style="display:flex;flex-direction:column;gap:20px;">
        <div class="bp-card">
            <div class="bp-card-header">
                <h5 class="bp-card-title"><i class="ri-information-line" style="color:var(--bp-cyan);margin-right:6px;"></i>Transfer Information</h5>
            </div>
            <div class="bp-card-body">
                <div style="display:flex;flex-direction:column;gap:10px;">
                    <?php
                    $tips = [
                        ['icon'=>'ri-time-line','color'=>'var(--bp-orange)','text'=>'Domestic transfers are processed within 1-2 business days'],
                        ['icon'=>'ri-shield-check-line','color'=>'var(--bp-green)','text'=>'Always verify beneficiary details before submitting'],
                        ['icon'=>'ri-lock-line','color'=>'var(--bp-primary)','text'=>'All transfers are encrypted and secured end-to-end'],
                        ['icon'=>'ri-alarm-warning-line','color'=>'var(--bp-red)','text'=>'Transfers cannot be reversed once processed'],
                    ];
                    foreach($tips as $t): ?>
                    <div style="display:flex;align-items:flex-start;gap:10px;">
                        <i class="<?= $t['icon'] ?>" style="color:<?= $t['color'] ?>;font-size:1rem;margin-top:2px;flex-shrink:0;"></i>
                        <span style="font-size:.8rem;color:var(--bp-text2);"><?= $t['text'] ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div style="margin-top:18px;background:rgba(67,97,238,0.06);border:1px solid rgba(67,97,238,0.15);border-radius:10px;padding:14px;">
                    <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--bp-primary);margin-bottom:8px;">Account Balance</div>
                    <div style="font-size:1.4rem;font-weight:800;color:var(--bp-text);"><?= $currency . number_format($acct_balance, 2) ?></div>
                    <div style="font-size:.78rem;color:var(--bp-green);margin-top:2px;"><i class="ri-checkbox-circle-line"></i> Available: <?= $currency . number_format($avail_balance, 2) ?></div>
                </div>

                <div style="margin-top:14px;">
                    <a href="./wire-transfer.php" class="bp-btn-outline" style="width:100%;justify-content:center;padding:10px;">
                        <i class="ri-global-line"></i> Switch to Wire Transfer
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>

<?php include_once("layouts/footer.php"); ?>
