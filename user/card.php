<?php
$pageName = "Virtual Card";
include_once("layouts/header.php");
$breadcrumbs = [['Home','./dashboard.php'],['Services','#'],['Virtual Card',null]];
include_once('layouts/breadcrumb.php');
require_once("../include/cardFunction.php");

if ($acct_stat != 'active') {
    header("Location:./error.php");
    exit();
}

// Fetch card — check existence BEFORE accessing card data
$sql2 = "SELECT * FROM card WHERE user_id=:user_id";
$stmt = $conn->prepare($sql2);
$stmt->execute(['user_id' => $user_id]);
$cardCheck = $stmt->fetch(PDO::FETCH_ASSOC);
$hasCard = ($cardCheck !== false);

if ($hasCard) {
    $card_number_parts = explode(' ', $cardCheck['card_number']);
    $card_type   = cardTypeName($card_number_parts);
    $cardStatus  = getCardStatus($cardCheck);

    // Card request check
    $sql3 = "SELECT * FROM card_request WHERE user_id=:user_id";
    $stmt3 = $conn->prepare($sql3);
    $stmt3->execute(['user_id' => $user_id]);
    $hasCardRequest = ($stmt3->rowCount() > 0);
}

// Handle form actions
if ($hasCard && isset($_POST['pause_card'])) {
    $conn->prepare("UPDATE card SET card_status=4 WHERE user_id=:uid")->execute(['uid'=>$user_id]);
    header("Location:./card.php"); exit();
}
if ($hasCard && isset($_POST['active_card'])) {
    $conn->prepare("UPDATE card SET card_status=1 WHERE user_id=:uid")->execute(['uid'=>$user_id]);
    header("Location:./card.php"); exit();
}
if (!$hasCard && isset($_POST['card_generate'])) {
    require_once("../include/cardFunction.php");
    header("Location:./card.php"); exit();
}
if (isset($_POST['card_request'])) {
    $card_type_req  = $_POST['card_type'] ?? '';
    $card_reason    = $_POST['card_reason'] ?? '';
    $ref_id = uniqid('CARD', true);
    $conn->prepare("INSERT INTO card_request (reference_id,user_id,card_type,card_reason) VALUES(:ref,:uid,:type,:reason)")
         ->execute(['ref'=>$ref_id,'uid'=>$user_id,'type'=>$card_type_req,'reason'=>$card_reason]);
    header("Location:./card.php"); exit();
}
?>

<?php if (!$hasCard): ?>
<!-- ── No Card: Request Form ── -->
<div class="bp-grid-2" style="gap:24px;align-items:start;">

    <!-- Left: Card Preview -->
    <div class="bp-card" style="padding:28px 24px;text-align:center;">
        <div style="background:linear-gradient(135deg,var(--bp-primary) 0%,#7c3aed 100%);border-radius:18px;padding:28px 24px;color:#fff;position:relative;overflow:hidden;margin-bottom:20px;">
            <div style="position:absolute;top:-20px;right:-20px;width:120px;height:120px;border-radius:50%;background:rgba(255,255,255,0.08);"></div>
            <div style="position:absolute;bottom:-30px;left:-20px;width:100px;height:100px;border-radius:50%;background:rgba(255,255,255,0.06);"></div>
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
                <div style="font-size:.75rem;font-weight:700;letter-spacing:.12em;opacity:.8;">VIRTUAL CARD</div>
                <div style="width:40px;height:28px;border-radius:4px;background:rgba(255,255,255,0.25);display:flex;align-items:center;justify-content:center;">
                    <i class="ri-bank-card-line" style="font-size:14px;color:#fff;"></i>
                </div>
            </div>
            <div style="font-size:1.4rem;letter-spacing:.18em;font-weight:700;margin-bottom:20px;font-family:monospace;">**** **** **** ****</div>
            <div style="display:flex;justify-content:space-between;align-items:flex-end;">
                <div>
                    <div style="font-size:.65rem;opacity:.7;text-transform:uppercase;letter-spacing:.08em;margin-bottom:2px;">Cardholder</div>
                    <div style="font-size:.88rem;font-weight:700;"><?= htmlspecialchars(strtoupper($fullName)) ?></div>
                </div>
                <div style="text-align:right;">
                    <div style="font-size:.65rem;opacity:.7;text-transform:uppercase;letter-spacing:.08em;margin-bottom:2px;">Expires</div>
                    <div style="font-size:.88rem;font-weight:700;">MM/YY</div>
                </div>
            </div>
        </div>
        <div style="text-align:left;">
            <h5 style="font-size:.9rem;font-weight:700;color:var(--bp-text);margin-bottom:8px;">Generate Your Virtual Card</h5>
            <p style="font-size:.8rem;color:var(--bp-text3);line-height:1.6;">Request a virtual card to make online purchases securely. Your card will be issued after admin review.</p>
        </div>
    </div>

    <!-- Right: Generate Form -->
    <div class="bp-card">
        <div class="bp-card-header">
            <h5 class="bp-card-title"><i class="ri-bank-card-2-line" style="color:var(--bp-primary);margin-right:6px;"></i>Card Request Form</h5>
        </div>
        <div class="bp-card-body">
            <form method="POST">
                <div style="display:flex;flex-direction:column;gap:18px;">
                    <div>
                        <label class="bp-form-label">Cardholder Name</label>
                        <input type="text" class="bp-form-input" name="card_name" value="<?= htmlspecialchars($fullName) ?>" readonly>
                    </div>
                    <div>
                        <label class="bp-form-label">Card Number</label>
                        <div class="bp-input-group">
                            <input type="text" class="bp-form-input" id="cardnumber" name="card_number" placeholder="Click Generate" readonly required>
                            <button type="button" class="bp-input-suffix" id="generatecard" style="padding:0 14px;white-space:nowrap;font-size:.78rem;font-weight:700;color:var(--bp-primary);">Generate</button>
                        </div>
                    </div>
                    <div class="bp-grid-2" style="gap:14px;">
                        <div>
                            <label class="bp-form-label">Expiration (MM/YY)</label>
                            <input type="text" class="bp-form-input" id="expirationdate" name="card_expiration" value="07/26" readonly required>
                        </div>
                        <div>
                            <label class="bp-form-label">Security Code</label>
                            <input type="text" class="bp-form-input" id="securitycode" name="security" value="897" readonly required>
                        </div>
                    </div>
                    <button type="submit" name="card_generate" class="bp-btn-primary" style="width:100%;justify-content:center;padding:12px;">
                        <i class="ri-bank-card-2-line"></i> Request Card
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
<script>
document.getElementById('generatecard').addEventListener('click', function(){
    var chars = '0123456789';
    var groups = [];
    for (var g = 0; g < 4; g++) {
        var group = '';
        for (var i = 0; i < 4; i++) group += chars.charAt(Math.floor(Math.random()*chars.length));
        groups.push(group);
    }
    document.getElementById('cardnumber').value = groups.join(' ');
});
</script>

<?php else: ?>
<!-- ── Has Card: Card Display ── -->
<div class="bp-grid-2" style="gap:24px;align-items:start;">

    <!-- Left: Card Visual -->
    <div class="bp-card" style="padding:28px 24px;">
        <?php
        $statusColor = $cardCheck['card_status'] == '1' ? '#10b981' : ($cardCheck['card_status'] == '4' ? '#f59e0b' : '#ef4444');
        $statusLabel = $cardCheck['card_status'] == '1' ? 'Active' : ($cardCheck['card_status'] == '4' ? 'Paused' : 'Blocked');
        ?>
        <!-- Card Visual -->
        <div style="background:linear-gradient(135deg,<?= $cardCheck['card_status']=='1' ? '#4361ee 0%,#7c3aed 100%' : ($cardCheck['card_status']=='4' ? '#b45309 0%,#92400e 100%' : '#374151 0%,#1f2937 100%') ?>);border-radius:18px;padding:28px 24px;color:#fff;position:relative;overflow:hidden;margin-bottom:20px;">
            <div style="position:absolute;top:-20px;right:-20px;width:120px;height:120px;border-radius:50%;background:rgba(255,255,255,0.08);"></div>
            <div style="position:absolute;bottom:-30px;left:-20px;width:100px;height:100px;border-radius:50%;background:rgba(255,255,255,0.06);"></div>
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                <div style="font-size:.75rem;font-weight:700;letter-spacing:.12em;opacity:.8;">DEBIT CARD</div>
                <span style="font-size:.72rem;font-weight:700;padding:3px 10px;border-radius:20px;background:rgba(255,255,255,0.2);"><?= htmlspecialchars($statusLabel) ?></span>
            </div>
            <div style="margin-bottom:20px;margin-top:18px;">
                <!-- chip -->
                <div style="width:36px;height:28px;border-radius:5px;background:rgba(255,215,0,0.7);margin-bottom:14px;"></div>
                <?php
                $parts = $card_number_parts;
                $masked = (count($parts)===4) ? $parts[0].' **** **** '.$parts[3] : implode(' ', $parts);
                ?>
                <div style="font-size:1.3rem;letter-spacing:.18em;font-weight:700;font-family:monospace;"><?= htmlspecialchars($masked) ?></div>
            </div>
            <div style="display:flex;justify-content:space-between;align-items:flex-end;">
                <div>
                    <div style="font-size:.65rem;opacity:.7;text-transform:uppercase;letter-spacing:.08em;margin-bottom:2px;">Cardholder</div>
                    <div style="font-size:.88rem;font-weight:700;"><?= htmlspecialchars(strtoupper($cardCheck['card_name'])) ?></div>
                </div>
                <div style="text-align:right;">
                    <div style="font-size:.65rem;opacity:.7;text-transform:uppercase;letter-spacing:.08em;margin-bottom:2px;">Expires</div>
                    <div style="font-size:.88rem;font-weight:700;"><?= htmlspecialchars($cardCheck['card_expiration']) ?></div>
                    <div style="font-size:.78rem;font-weight:700;margin-top:4px;opacity:.9;"><?= htmlspecialchars(strtoupper($card_type)) ?></div>
                </div>
            </div>
        </div>

        <!-- Limit info -->
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:20px;">
            <div style="background:var(--bp-surface2);border-radius:12px;padding:14px;border:1px solid var(--bp-border);">
                <div style="font-size:.68rem;color:var(--bp-text3);font-weight:700;text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px;">Card Limit</div>
                <div style="font-size:1rem;font-weight:800;color:var(--bp-text);"><?= $currency . number_format($cardCheck['card_limit'], 2) ?></div>
            </div>
            <div style="background:var(--bp-surface2);border-radius:12px;padding:14px;border:1px solid var(--bp-border);">
                <div style="font-size:.68rem;color:var(--bp-text3);font-weight:700;text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px;">Remaining</div>
                <div style="font-size:1rem;font-weight:800;color:var(--bp-red);"><?= $currency . number_format($cardCheck['card_limit_remain'], 2) ?></div>
            </div>
        </div>

        <!-- Actions -->
        <div style="display:flex;flex-direction:column;gap:10px;">
            <?php if ($cardCheck['card_status'] == '1'): ?>
            <form method="POST">
                <button name="pause_card" class="bp-btn-outline" style="width:100%;justify-content:center;color:var(--bp-orange);border-color:var(--bp-orange);">
                    <i class="ri-pause-circle-line"></i> Pause Card
                </button>
            </form>
            <?php elseif ($cardCheck['card_status'] == '4'): ?>
            <form method="POST">
                <button name="active_card" class="bp-btn-primary" style="width:100%;justify-content:center;">
                    <i class="ri-play-circle-line"></i> Activate Card
                </button>
            </form>
            <?php elseif ($cardCheck['card_status'] == '3'): ?>
            <a href="mailto:<?= htmlspecialchars($page['url_email']) ?>" class="bp-btn-outline" style="width:100%;justify-content:center;color:var(--bp-red);border-color:var(--bp-red);">
                <i class="ri-customer-service-line"></i> Contact Support
            </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Right: Card Details + New Card Request -->
    <div style="display:flex;flex-direction:column;gap:20px;">

        <div class="bp-card">
            <div class="bp-card-header">
                <h5 class="bp-card-title"><i class="ri-shield-check-line" style="color:var(--bp-primary);margin-right:6px;"></i>Card Details</h5>
            </div>
            <div class="bp-card-body">
                <div style="display:flex;flex-direction:column;gap:0;">
                    <div class="bp-acct-row">
                        <span class="bp-acct-row-label">Card Type</span>
                        <span class="bp-acct-row-val"><?= htmlspecialchars(ucwords($card_type)) ?></span>
                    </div>
                    <div style="height:1px;background:var(--bp-border);"></div>
                    <div class="bp-acct-row">
                        <span class="bp-acct-row-label">Card Status</span>
                        <span style="font-size:.75rem;font-weight:700;padding:3px 10px;border-radius:20px;background:<?= $cardCheck['card_status']=='1' ? 'rgba(16,185,129,.12)' : 'rgba(245,158,11,.12)' ?>;color:<?= $cardCheck['card_status']=='1' ? 'var(--bp-green)' : 'var(--bp-orange)' ?>;"><?= htmlspecialchars($statusLabel) ?></span>
                    </div>
                    <div style="height:1px;background:var(--bp-border);"></div>
                    <div class="bp-acct-row">
                        <span class="bp-acct-row-label">Card Number</span>
                        <span class="bp-acct-row-val" style="font-family:monospace;"><?= htmlspecialchars($masked) ?></span>
                    </div>
                    <div style="height:1px;background:var(--bp-border);"></div>
                    <div class="bp-acct-row">
                        <span class="bp-acct-row-label">Expiration</span>
                        <span class="bp-acct-row-val"><?= htmlspecialchars($cardCheck['card_expiration']) ?></span>
                    </div>
                    <div style="height:1px;background:var(--bp-border);"></div>
                    <div class="bp-acct-row">
                        <span class="bp-acct-row-label">Cardholder</span>
                        <span class="bp-acct-row-val"><?= htmlspecialchars($cardCheck['card_name']) ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- New card request -->
        <div class="bp-card">
            <div class="bp-card-header">
                <h5 class="bp-card-title"><i class="ri-add-circle-line" style="color:var(--bp-cyan);margin-right:6px;"></i>Request New Card</h5>
            </div>
            <div class="bp-card-body">
                <?php if ($hasCardRequest): ?>
                <div style="background:rgba(245,158,11,0.08);border:1px solid rgba(245,158,11,0.2);border-radius:10px;padding:14px;display:flex;align-items:center;gap:10px;">
                    <i class="ri-time-line" style="color:var(--bp-orange);font-size:1.2rem;"></i>
                    <span style="font-size:.82rem;color:var(--bp-text2);">A new card request is already in progress. We'll notify you once it's processed.</span>
                </div>
                <?php else: ?>
                <p style="font-size:.8rem;color:var(--bp-text3);margin-bottom:16px;">Need a replacement or new card type? Submit a request below.</p>
                <form method="POST">
                    <div style="display:flex;flex-direction:column;gap:14px;">
                        <div>
                            <label class="bp-form-label">Card Type</label>
                            <select name="card_type" class="bp-form-input" required>
                                <option value="">Select type</option>
                                <option value="mastercard">Mastercard</option>
                                <option value="visa">Visa</option>
                                <option value="american express">American Express</option>
                                <option value="discover">Discover</option>
                            </select>
                        </div>
                        <div>
                            <label class="bp-form-label">Reason for Request</label>
                            <textarea class="bp-form-input" name="card_reason" rows="3" placeholder="Briefly describe why you need a new card..." required></textarea>
                        </div>
                        <button type="submit" name="card_request" class="bp-btn-primary" style="justify-content:center;">
                            <i class="ri-send-plane-line"></i> Submit Request
                        </button>
                    </div>
                </form>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>
<?php endif; ?>

<?php include_once("layouts/footer.php"); ?>
