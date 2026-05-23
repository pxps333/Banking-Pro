<?php
$pageName = "My Profile";
include_once("layouts/header.php");
$breadcrumbs = [['Home','./dashboard.php'],['Account','#'],['My Profile',null]];
include_once('layouts/breadcrumb.php');

if (!$_SESSION['acct_no']) {
    header("location:../login.php");
    die;
}

// Handle flag/unflag suspicious login
if (isset($_POST['flag_log']) && is_numeric($_POST['log_id'])) {
    $log_id = (int)$_POST['log_id'];
    $flagVal = $_POST['flag_val'] === '1' ? true : false;
    $conn->prepare("UPDATE audit_logs SET flagged=:f WHERE id=:id AND user_id=:uid")
         ->execute(['f' => $flagVal ? 't' : 'f', 'id' => $log_id, 'uid' => $user_id]);
    header("Location:./profile.php");
    exit();
}

// Fetch login history for current user
$sqlLogs = "SELECT * FROM audit_logs WHERE user_id = :uid ORDER BY datenow DESC LIMIT 10";
$stmtLogs = $conn->prepare($sqlLogs);
$stmtLogs->execute(['uid' => $user_id]);
$loginLogs = $stmtLogs->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="bp-grid-2" style="gap:24px;align-items:start;">

    <!-- Left: Profile Card -->
    <div class="bp-card" style="text-align:center;padding:32px 24px;">
        <div style="position:relative;display:inline-block;margin-bottom:16px;">
            <img src="../assets/profile/<?= htmlspecialchars($row['image'] ?? 'default.png') ?>"
                 alt="Profile Photo"
                 style="width:96px;height:96px;border-radius:50%;object-fit:cover;border:3px solid var(--bp-primary);box-shadow:0 0 0 6px rgba(67,97,238,0.1);">
            <span style="position:absolute;bottom:4px;right:4px;width:18px;height:18px;border-radius:50%;background:<?= $acct_stat==='active' ? 'var(--bp-green)' : 'var(--bp-orange)' ?>;border:2px solid var(--bp-surface);"></span>
        </div>
        <h4 style="margin:0 0 4px;font-size:1.1rem;font-weight:800;color:var(--bp-text);"><?= htmlspecialchars($fullName) ?></h4>
        <span style="display:inline-flex;align-items:center;gap:5px;font-size:.75rem;font-weight:700;padding:3px 12px;border-radius:20px;background:<?= $acct_stat==='active' ? 'rgba(16,185,129,0.1)' : 'rgba(245,158,11,0.1)' ?>;color:<?= $acct_stat==='active' ? 'var(--bp-green)' : 'var(--bp-orange)' ?>;margin-bottom:8px;">
            <i class="ri-shield-check-line"></i> <?= ucfirst(htmlspecialchars($acct_stat)) ?>
        </span>
        <div style="font-size:.82rem;color:var(--bp-text3);margin-bottom:24px;"><?= htmlspecialchars($row['acct_type'] ?? 'Checking Account') ?></div>

        <div style="background:var(--bp-surface2);border-radius:12px;padding:16px;text-align:left;border:1px solid var(--bp-border);margin-bottom:16px;">
            <div style="display:flex;flex-direction:column;gap:12px;">
                <div style="display:flex;align-items:center;gap:10px;">
                    <div style="width:32px;height:32px;border-radius:8px;background:rgba(67,97,238,0.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="ri-mail-line" style="color:var(--bp-primary);font-size:15px;"></i>
                    </div>
                    <div>
                        <div style="font-size:.68rem;color:var(--bp-text3);font-weight:600;text-transform:uppercase;letter-spacing:.06em;">Email</div>
                        <div style="font-size:.82rem;color:var(--bp-text);font-weight:600;word-break:break-all;"><?= htmlspecialchars($row['acct_email'] ?? '') ?></div>
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:10px;">
                    <div style="width:32px;height:32px;border-radius:8px;background:rgba(16,185,129,0.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="ri-phone-line" style="color:var(--bp-green);font-size:15px;"></i>
                    </div>
                    <div>
                        <div style="font-size:.68rem;color:var(--bp-text3);font-weight:600;text-transform:uppercase;letter-spacing:.06em;">Phone</div>
                        <div style="font-size:.82rem;color:var(--bp-text);font-weight:600;"><?= htmlspecialchars($row['acct_phone'] ?? '—') ?></div>
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:10px;">
                    <div style="width:32px;height:32px;border-radius:8px;background:rgba(6,182,212,0.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="ri-map-pin-line" style="color:var(--bp-cyan);font-size:15px;"></i>
                    </div>
                    <div>
                        <div style="font-size:.68rem;color:var(--bp-text3);font-weight:600;text-transform:uppercase;letter-spacing:.06em;">Location</div>
                        <div style="font-size:.82rem;color:var(--bp-text);font-weight:600;"><?= htmlspecialchars(($row['state'] ?? '') . ', ' . ($row['country'] ?? '')) ?></div>
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:10px;">
                    <div style="width:32px;height:32px;border-radius:8px;background:rgba(245,158,11,0.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="ri-calendar-line" style="color:var(--bp-orange);font-size:15px;"></i>
                    </div>
                    <div>
                        <div style="font-size:.68rem;color:var(--bp-text3);font-weight:600;text-transform:uppercase;letter-spacing:.06em;">Date of Birth</div>
                        <div style="font-size:.82rem;color:var(--bp-text);font-weight:600;"><?= htmlspecialchars($row['acct_dob'] ?? '—') ?></div>
                    </div>
                </div>
            </div>
        </div>

        <a href="./edit-profile.php" class="bp-btn-primary" style="width:100%;justify-content:center;">
            <i class="ri-edit-line"></i> Edit Profile
        </a>
    </div>

    <!-- Right: Account Details + Quick Actions -->
    <div style="display:flex;flex-direction:column;gap:20px;">

        <!-- Account Info -->
        <div class="bp-card">
            <div class="bp-card-header">
                <h5 class="bp-card-title"><i class="ri-bank-line" style="color:var(--bp-primary);margin-right:6px;"></i>Account Information</h5>
            </div>
            <div class="bp-card-body">
                <div class="bp-acct-info">
                    <div class="bp-acct-row">
                        <span class="bp-acct-row-label">Account Number</span>
                        <span class="bp-acct-row-val" style="font-family:monospace;"><?= htmlspecialchars($row['acct_no'] ?? '—') ?></span>
                    </div>
                    <div style="height:1px;background:var(--bp-border);"></div>
                    <div class="bp-acct-row">
                        <span class="bp-acct-row-label">Account Type</span>
                        <span class="bp-acct-row-val"><?= htmlspecialchars($row['acct_type'] ?? '—') ?></span>
                    </div>
                    <div style="height:1px;background:var(--bp-border);"></div>
                    <div class="bp-acct-row">
                        <span class="bp-acct-row-label">Currency</span>
                        <span class="bp-acct-row-val"><?= htmlspecialchars($row['acct_currency'] ?? '—') ?></span>
                    </div>
                    <div style="height:1px;background:var(--bp-border);"></div>
                    <div class="bp-acct-row">
                        <span class="bp-acct-row-label">Total Balance</span>
                        <span class="bp-acct-row-val bp-txt-primary"><?= $currency . number_format($acct_balance, 2) ?></span>
                    </div>
                    <div style="height:1px;background:var(--bp-border);"></div>
                    <div class="bp-acct-row">
                        <span class="bp-acct-row-label">Available Balance</span>
                        <span class="bp-acct-row-val bp-txt-green"><?= $currency . number_format($avail_balance, 2) ?></span>
                    </div>
                    <div style="height:1px;background:var(--bp-border);"></div>
                    <div class="bp-acct-row">
                        <span class="bp-acct-row-label">Account Status</span>
                        <span style="font-size:.75rem;font-weight:700;padding:3px 10px;border-radius:20px;background:<?= $acct_stat==='active' ? 'rgba(16,185,129,0.1)' : 'rgba(245,158,11,0.1)' ?>;color:<?= $acct_stat==='active' ? 'var(--bp-green)' : 'var(--bp-orange)' ?>;">
                            <?= ucfirst(htmlspecialchars($acct_stat)) ?>
                        </span>
                    </div>
                    <div style="height:1px;background:var(--bp-border);"></div>
                    <div class="bp-acct-row">
                        <span class="bp-acct-row-label">Gender</span>
                        <span class="bp-acct-row-val"><?= htmlspecialchars($row['acct_gender'] ?? '—') ?></span>
                    </div>
                    <div style="height:1px;background:var(--bp-border);"></div>
                    <div class="bp-acct-row">
                        <span class="bp-acct-row-label">Occupation</span>
                        <span class="bp-acct-row-val"><?= htmlspecialchars($row['acct_occupation'] ?? '—') ?></span>
                    </div>
                    <div style="height:1px;background:var(--bp-border);"></div>
                    <div class="bp-acct-row">
                        <span class="bp-acct-row-label">Address</span>
                        <span class="bp-acct-row-val" style="text-align:right;max-width:60%;"><?= htmlspecialchars($row['acct_address'] ?? '—') ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Security & Settings -->
        <div class="bp-card">
            <div class="bp-card-header">
                <h5 class="bp-card-title"><i class="ri-shield-keyhole-line" style="color:var(--bp-primary);margin-right:6px;"></i>Security & Settings</h5>
            </div>
            <div class="bp-card-body" style="padding:12px 16px;">
                <a href="./pin.php" style="display:flex;align-items:center;gap:12px;padding:12px 8px;border-radius:10px;transition:background .2s;text-decoration:none;" onmouseover="this.style.background='var(--bp-surface2)'" onmouseout="this.style.background='transparent'">
                    <div style="width:36px;height:36px;border-radius:10px;background:rgba(67,97,238,0.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="ri-lock-password-line" style="color:var(--bp-primary);font-size:16px;"></i>
                    </div>
                    <div style="flex:1;">
                        <div style="font-size:.85rem;font-weight:600;color:var(--bp-text);">Change PIN</div>
                        <div style="font-size:.75rem;color:var(--bp-text3);">Update your transaction PIN</div>
                    </div>
                    <i class="ri-arrow-right-s-line" style="color:var(--bp-text3);"></i>
                </a>
                <div style="height:1px;background:var(--bp-border);margin:0 8px;"></div>
                <a href="./imf-code.php" style="display:flex;align-items:center;gap:12px;padding:12px 8px;border-radius:10px;transition:background .2s;text-decoration:none;" onmouseover="this.style.background='var(--bp-surface2)'" onmouseout="this.style.background='transparent'">
                    <div style="width:36px;height:36px;border-radius:10px;background:rgba(6,182,212,0.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="ri-key-2-line" style="color:var(--bp-cyan);font-size:16px;"></i>
                    </div>
                    <div style="flex:1;">
                        <div style="font-size:.85rem;font-weight:600;color:var(--bp-text);">IMF Code</div>
                        <div style="font-size:.75rem;color:var(--bp-text3);">Manage your international transfer code</div>
                    </div>
                    <i class="ri-arrow-right-s-line" style="color:var(--bp-text3);"></i>
                </a>
                <div style="height:1px;background:var(--bp-border);margin:0 8px;"></div>
                <a href="./edit-profile.php" style="display:flex;align-items:center;gap:12px;padding:12px 8px;border-radius:10px;transition:background .2s;text-decoration:none;" onmouseover="this.style.background='var(--bp-surface2)'" onmouseout="this.style.background='transparent'">
                    <div style="width:36px;height:36px;border-radius:10px;background:rgba(16,185,129,0.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="ri-user-settings-line" style="color:var(--bp-green);font-size:16px;"></i>
                    </div>
                    <div style="flex:1;">
                        <div style="font-size:.85rem;font-weight:600;color:var(--bp-text);">Edit Profile</div>
                        <div style="font-size:.75rem;color:var(--bp-text3);">Update your personal information</div>
                    </div>
                    <i class="ri-arrow-right-s-line" style="color:var(--bp-text3);"></i>
                </a>
            </div>
        </div>

    </div>
</div>

<!-- Login Activity History -->
<div class="bp-card" style="margin-top:24px;">
    <div class="bp-card-header">
        <h5 class="bp-card-title">
            <i class="ri-login-circle-line" style="color:var(--bp-primary);margin-right:6px;"></i>Login Activity History
        </h5>
        <span style="font-size:.75rem;color:var(--bp-text3);">Last 10 sessions</span>
    </div>

    <?php if (empty($loginLogs)): ?>
    <div class="bp-card-body" style="text-align:center;padding:40px;">
        <i class="ri-login-circle-line" style="font-size:2.5rem;color:var(--bp-text3);opacity:.5;display:block;margin-bottom:8px;"></i>
        <div style="font-size:.88rem;color:var(--bp-text3);">No login history available yet.</div>
    </div>
    <?php else: ?>
    <div class="bp-card-body" style="padding:0;">
        <?php foreach ($loginLogs as $idx => $log):
            $isFlagged = !empty($log['flagged']) && $log['flagged'] !== 'f' && $log['flagged'] !== false && $log['flagged'] !== '0';
            $isFirst   = ($idx === 0);
            // Detect device type from user agent
            $ua = strtolower($log['device'] ?? '');
            $deviceIcon = 'ri-computer-line';
            $deviceLabel = 'Desktop';
            if (str_contains($ua, 'mobile') || str_contains($ua, 'android') || str_contains($ua, 'iphone')) {
                $deviceIcon = 'ri-smartphone-line';
                $deviceLabel = 'Mobile';
            } elseif (str_contains($ua, 'tablet') || str_contains($ua, 'ipad')) {
                $deviceIcon = 'ri-tablet-line';
                $deviceLabel = 'Tablet';
            }
            // Extract browser from UA
            $browser = 'Unknown';
            if (str_contains($ua, 'chrome') && !str_contains($ua, 'edg')) $browser = 'Chrome';
            elseif (str_contains($ua, 'firefox'))  $browser = 'Firefox';
            elseif (str_contains($ua, 'safari') && !str_contains($ua, 'chrome'))  $browser = 'Safari';
            elseif (str_contains($ua, 'edg'))  $browser = 'Edge';
            elseif (str_contains($ua, 'opera') || str_contains($ua, 'opr'))  $browser = 'Opera';
        ?>
        <div style="display:flex;align-items:center;gap:14px;padding:14px 20px;border-bottom:1px solid var(--bp-border);<?= $isFlagged ? 'background:rgba(239,68,68,0.04);' : '' ?>">

            <!-- Device Icon -->
            <div style="width:40px;height:40px;border-radius:10px;background:<?= $isFlagged ? 'rgba(239,68,68,0.12)' : ($isFirst ? 'rgba(67,97,238,0.12)' : 'var(--bp-surface2)') ?>;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="<?= $deviceIcon ?>" style="font-size:18px;color:<?= $isFlagged ? 'var(--bp-red)' : ($isFirst ? 'var(--bp-primary)' : 'var(--bp-text3)') ?>;"></i>
            </div>

            <!-- Info -->
            <div style="flex:1;min-width:0;">
                <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:3px;">
                    <span style="font-size:.84rem;font-weight:600;color:var(--bp-text);"><?= htmlspecialchars($deviceLabel) ?> · <?= htmlspecialchars($browser) ?></span>
                    <?php if ($isFirst && !$isFlagged): ?>
                    <span style="font-size:.7rem;font-weight:700;padding:2px 8px;border-radius:10px;background:rgba(16,185,129,.12);color:var(--bp-green);">Current Session</span>
                    <?php endif; ?>
                    <?php if ($isFlagged): ?>
                    <span style="font-size:.7rem;font-weight:700;padding:2px 8px;border-radius:10px;background:rgba(239,68,68,.12);color:var(--bp-red);">
                        <i class="ri-flag-2-fill" style="font-size:11px;"></i> Flagged Suspicious
                    </span>
                    <?php endif; ?>
                </div>
                <div style="display:flex;gap:14px;flex-wrap:wrap;">
                    <span style="font-size:.76rem;color:var(--bp-text3);display:flex;align-items:center;gap:4px;">
                        <i class="ri-map-pin-line" style="font-size:12px;"></i>
                        IP: <?= htmlspecialchars($log['ipAddress'] ?? '—') ?>
                    </span>
                    <span style="font-size:.76rem;color:var(--bp-text3);display:flex;align-items:center;gap:4px;">
                        <i class="ri-time-line" style="font-size:12px;"></i>
                        <?= htmlspecialchars($log['datenow'] ? date('M j, Y · g:i a', strtotime($log['datenow'])) : '—') ?>
                    </span>
                </div>
            </div>

            <!-- Flag action -->
            <form method="POST" style="flex-shrink:0;">
                <input type="hidden" name="log_id" value="<?= (int)$log['id'] ?>">
                <input type="hidden" name="flag_log" value="1">
                <?php if ($isFlagged): ?>
                <input type="hidden" name="flag_val" value="0">
                <button type="submit" title="Remove flag" style="background:none;border:1px solid var(--bp-border);border-radius:8px;padding:5px 10px;cursor:pointer;color:var(--bp-text3);font-size:.75rem;display:flex;align-items:center;gap:4px;">
                    <i class="ri-flag-2-line" style="font-size:13px;color:var(--bp-red);"></i> Unflag
                </button>
                <?php else: ?>
                <input type="hidden" name="flag_val" value="1">
                <button type="submit" title="Flag as suspicious" style="background:none;border:1px solid var(--bp-border);border-radius:8px;padding:5px 10px;cursor:pointer;color:var(--bp-text3);font-size:.75rem;display:flex;align-items:center;gap:4px;" onmouseover="this.style.borderColor='var(--bp-red)';this.style.color='var(--bp-red)'" onmouseout="this.style.borderColor='var(--bp-border)';this.style.color='var(--bp-text3)'">
                    <i class="ri-flag-2-line" style="font-size:13px;"></i> Flag
                </button>
                <?php endif; ?>
            </form>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Security tip -->
    <div style="padding:14px 20px;border-top:1px solid var(--bp-border);background:rgba(67,97,238,0.04);border-radius:0 0 var(--bp-radius) var(--bp-radius);">
        <div style="display:flex;align-items:flex-start;gap:10px;">
            <i class="ri-information-line" style="color:var(--bp-primary);font-size:1rem;margin-top:1px;flex-shrink:0;"></i>
            <span style="font-size:.78rem;color:var(--bp-text3);">If you notice a session you don't recognise, flag it as suspicious and <a href="./pin.php" style="color:var(--bp-primary);font-weight:600;">change your PIN</a> immediately. Contact support if you believe your account has been compromised.</span>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include_once("layouts/footer.php"); ?>
