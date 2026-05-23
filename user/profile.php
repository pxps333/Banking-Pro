<?php
$pageName = "My Profile";
include_once("layouts/header.php");
$breadcrumbs = [['Home','./dashboard.php'],['Account','#'],['My Profile',null]];
include_once('layouts/breadcrumb.php');

if (!$_SESSION['acct_no']) {
    header("location:../login.php");
    die;
}
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
                <div style="height:1px;background:var(--bp-border);margin:0 8px;"></div>
                <a href="./account-manager.php" style="display:flex;align-items:center;gap:12px;padding:12px 8px;border-radius:10px;transition:background .2s;text-decoration:none;" onmouseover="this.style.background='var(--bp-surface2)'" onmouseout="this.style.background='transparent'">
                    <div style="width:36px;height:36px;border-radius:10px;background:rgba(245,158,11,0.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="ri-user-star-line" style="color:var(--bp-orange);font-size:16px;"></i>
                    </div>
                    <div style="flex:1;">
                        <div style="font-size:.85rem;font-weight:600;color:var(--bp-text);">Account Manager</div>
                        <div style="font-size:.75rem;color:var(--bp-text3);">View your dedicated account manager</div>
                    </div>
                    <i class="ri-arrow-right-s-line" style="color:var(--bp-text3);"></i>
                </a>
            </div>
        </div>

    </div>
</div>

<?php include_once("layouts/footer.php"); ?>
