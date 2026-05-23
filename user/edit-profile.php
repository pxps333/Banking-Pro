<?php
$pageName = "Edit Profile";
include_once("layouts/header.php");

if (!$_SESSION['acct_no']) {
    header("location:../login.php");
    die;
}

$acct_id = userDetails('id');

/* ── Upload profile picture ── */
if (isset($_POST['upload_picture'])) {
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $file      = $_FILES['image'];
        $ext       = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed   = ['jpg', 'png', 'jpeg', 'webp'];
        if (in_array($ext, $allowed) && $file['size'] <= 2 * 1024 * 1024) {
            $n           = $row['firstname'] . '_' . time() . '.' . $ext;
            $destination = '../assets/profile/' . $n;
            if (move_uploaded_file($file['tmp_name'], $destination)) {
                $conn->prepare("UPDATE users SET image=:image WHERE id=:id")
                     ->execute(['image' => $n, 'id' => $user_id]);
                toast_alert("success", "Profile photo updated!", "Done");
            }
        } else {
            toast_alert("error", "Invalid file. Use JPG/PNG/WebP under 2 MB.");
        }
    }
}

/* ── Change password ── */
if (isset($_POST['change_password'])) {
    $old_password     = inputValidation($_POST['old_password']);
    $new_password     = inputValidation($_POST['new_password']);
    $confirm_password = inputValidation($_POST['confirm_password']);

    if (empty($old_password)) {
        toast_alert("error", "Please enter your current password.");
    } elseif (empty($new_password) || empty($confirm_password)) {
        toast_alert("error", "Please fill in all password fields.");
    } elseif (!password_verify($old_password, $row['acct_password'])) {
        toast_alert("error", "Current password is incorrect.");
    } elseif ($new_password !== $confirm_password) {
        toast_alert("error", "New password and confirmation don't match.");
    } elseif ($new_password === $old_password) {
        toast_alert("error", "New password must be different from the current one.");
    } else {
        $conn->prepare("UPDATE users SET acct_password=:pw WHERE id=:id")
             ->execute(['pw' => password_hash($new_password, PASSWORD_BCRYPT), 'id' => $user_id]);

        $APP_NAME   = WEB_TITLE;
        $APP_EMAIL  = WEB_EMAIL;
        $full_name  = $row['firstname'] . ' ' . $row['lastname'];
        $msg        = $sendMail->PassChange($full_name, $APP_EMAIL, $APP_NAME);
        $subj       = "Password Change Notification — $APP_NAME";
        $email_message->send_mail($row['acct_email'], $msg, $subj);

        toast_alert("success", "Password updated successfully!");
    }
}

/* ── Change PIN ── */
if (isset($_POST['change_pin'])) {
    $current_pin    = $_POST['current_pin'] ?? '';
    $new_pin        = $_POST['new_pin'] ?? '';
    $confirm_pin    = $_POST['confirm_pin'] ?? '';

    if ($current_pin !== $row['acct_pin']) {
        toast_alert("error", "Current PIN is incorrect.");
    } elseif (empty($new_pin) || empty($confirm_pin)) {
        toast_alert("error", "Please fill in all PIN fields.");
    } elseif ($new_pin !== $confirm_pin) {
        toast_alert("error", "New PIN and confirmation don't match.");
    } elseif ($new_pin === $current_pin) {
        toast_alert("error", "New PIN must be different from the current one.");
    } else {
        $conn->prepare("UPDATE users SET acct_pin=:pin WHERE id=:id")
             ->execute(['pin' => $new_pin, 'id' => $user_id]);
        toast_alert("success", "PIN updated successfully!");
    }
}

/* ── Toggle 2FA ── */
if (isset($_POST['toggle_2fa'])) {
    $new_val = empty($row['two_fa_enabled']) ? 1 : 0;
    $conn->prepare("UPDATE users SET two_fa_enabled=:val WHERE id=:id")
         ->execute(['val' => $new_val, 'id' => $user_id]);
    /* Reload row after update */
    $stmt2 = $conn->prepare("SELECT * FROM users WHERE id=:id");
    $stmt2->execute(['id' => $user_id]);
    $row = $stmt2->fetch(PDO::FETCH_ASSOC);
    $state = $new_val ? 'enabled' : 'disabled';
    toast_alert($new_val ? 'success' : 'warning', "Two-factor authentication $state.");
}

$is2fa = !empty($row['two_fa_enabled']);
?>

<style>
/* ─── Edit Profile page ─── */
.ep-wrap { padding: 0 0 60px; }

/* page header */
.ep-header {
  display: flex; align-items: flex-start; justify-content: space-between;
  flex-wrap: wrap; gap: 12px; margin-bottom: 28px;
}
.ep-title { font-size: 1.3rem; font-weight: 800; color: var(--bp-text); letter-spacing: -.02em; margin: 0 0 4px; }
.ep-breadcrumb { font-size: .77rem; color: var(--bp-text-muted); }
.ep-breadcrumb a { color: var(--bp-primary); text-decoration: none; }
.ep-breadcrumb a:hover { text-decoration: underline; }
.ep-breadcrumb span { margin: 0 5px; }

/* cards */
.ep-card {
  background: var(--bp-surface);
  border: 1px solid var(--bp-border);
  border-radius: 16px;
  box-shadow: var(--bp-shadow-sm);
  overflow: hidden;
  margin-bottom: 20px;
}
.ep-card-header {
  display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;
  padding: 18px 22px;
  border-bottom: 1px solid var(--bp-border);
}
.ep-card-title {
  font-size: .9rem; font-weight: 700; color: var(--bp-text);
  margin: 0; display: flex; align-items: center; gap: 8px;
}
.ep-card-title i { color: var(--bp-primary); font-size: 1.05rem; }
.ep-card-body { padding: 22px; }

/* avatar section */
.ep-avatar-zone { display: flex; align-items: center; gap: 22px; flex-wrap: wrap; }
.ep-avatar-img {
  width: 90px; height: 90px; border-radius: 50%; object-fit: cover;
  border: 3px solid var(--bp-primary); flex-shrink: 0;
}
.ep-avatar-info h3 { font-size: 1rem; font-weight: 700; color: var(--bp-text); margin: 0 0 3px; }
.ep-avatar-info p  { font-size: .8rem; color: var(--bp-text-muted); margin: 0 0 12px; }
.ep-upload-btn { position: relative; }
.ep-upload-btn input[type=file] { position: absolute; inset: 0; opacity: 0; cursor: pointer; }
.ep-upload-label {
  display: inline-flex; align-items: center; gap: 7px;
  font-size: .82rem; font-weight: 700; cursor: pointer;
  background: var(--bp-primary-light); color: var(--bp-primary);
  border: 1px solid rgba(67,97,238,.25); border-radius: 9px; padding: 8px 16px;
  transition: all .2s;
}
.ep-upload-label:hover { background: rgba(67,97,238,.2); }

/* info grid */
.ep-info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 0; }
.ep-info-item { padding: 13px 0; border-bottom: 1px solid var(--bp-border); }
.ep-info-item:last-child, .ep-info-item:nth-last-child(-n+2):nth-child(odd) { border-bottom: none; }
.ep-info-label { font-size: .72rem; font-weight: 700; color: var(--bp-text-muted); text-transform: uppercase; letter-spacing: .06em; margin-bottom: 4px; }
.ep-info-value { font-size: .9rem; font-weight: 600; color: var(--bp-text); }
.ep-info-value code {
  font-size: .85rem; background: var(--bp-bg); border: 1px solid var(--bp-border);
  border-radius: 6px; padding: 2px 8px; font-family: monospace; color: var(--bp-primary);
}

/* form fields */
.ep-field { margin-bottom: 16px; }
.ep-label { display: block; font-size: .78rem; font-weight: 700; color: var(--bp-text-muted); margin-bottom: 7px; letter-spacing: .02em; }
.ep-input {
  width: 100%; background: var(--bp-bg); border: 1px solid var(--bp-border);
  border-radius: 10px; padding: 10px 14px; color: var(--bp-text); font-size: .87rem;
  font-family: inherit; outline: none; transition: border-color .2s, box-shadow .2s;
}
.ep-input:focus { border-color: var(--bp-primary); box-shadow: 0 0 0 3px rgba(67,97,238,.10); }
.ep-input::placeholder { color: var(--bp-text-muted); opacity: .6; }

.ep-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
@media (max-width: 640px) { .ep-grid-2 { grid-template-columns: 1fr; } }

/* submit button */
.ep-btn {
  display: inline-flex; align-items: center; gap: 7px;
  font-size: .85rem; font-weight: 700; padding: 11px 22px; border-radius: 10px;
  border: none; cursor: pointer; font-family: inherit; transition: all .2s;
}
.ep-btn-primary { background: var(--bp-primary); color: #fff; box-shadow: 0 4px 14px rgba(67,97,238,.3); }
.ep-btn-primary:hover { background: #3451d1; box-shadow: 0 6px 20px rgba(67,97,238,.45); transform: translateY(-1px); }
.ep-btn-danger  { background: #ef4444; color: #fff; box-shadow: 0 4px 14px rgba(239,68,68,.2); }
.ep-btn-danger:hover { background: #dc2626; transform: translateY(-1px); }

/* ─── 2FA card ─── */
.ep-2fa-banner {
  display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;
  padding: 20px 22px;
  background: var(--bp-surface);
}
.ep-2fa-left { display: flex; align-items: center; gap: 16px; }
.ep-2fa-icon {
  width: 48px; height: 48px; border-radius: 13px; flex-shrink: 0;
  display: flex; align-items: center; justify-content: center; font-size: 1.25rem;
}
.ep-2fa-icon.on  { background: rgba(16,185,129,.12); color: #10b981; }
.ep-2fa-icon.off { background: rgba(100,116,139,.10); color: #64748b; }
.ep-2fa-info h4 { font-size: .93rem; font-weight: 700; color: var(--bp-text); margin: 0 0 3px; }
.ep-2fa-info p  { font-size: .8rem; color: var(--bp-text-muted); margin: 0; }

/* pill toggle */
.ep-toggle-pill { position: relative; display: inline-block; width: 52px; height: 28px; }
.ep-toggle-pill input { opacity: 0; width: 0; height: 0; }
.ep-toggle-track {
  position: absolute; inset: 0; cursor: pointer; border-radius: 100px;
  background: rgba(100,116,139,.25); border: 1px solid var(--bp-border); transition: .25s;
}
.ep-toggle-track::before {
  content: ''; position: absolute; left: 3px; top: 50%; transform: translateY(-50%);
  width: 20px; height: 20px; border-radius: 50%; background: #fff;
  box-shadow: 0 1px 4px rgba(0,0,0,.2); transition: .25s;
}
.ep-toggle-pill input:checked + .ep-toggle-track { background: #10b981; border-color: #10b981; }
.ep-toggle-pill input:checked + .ep-toggle-track::before { left: 27px; }

/* 2FA status badge */
.ep-2fa-badge {
  display: inline-flex; align-items: center; gap: 5px;
  padding: 4px 10px; border-radius: 100px; font-size: .72rem; font-weight: 700;
}
.ep-2fa-badge.on  { background: rgba(16,185,129,.12); color: #10b981; }
.ep-2fa-badge.off { background: rgba(100,116,139,.10); color: #64748b; }

/* 2FA info row */
.ep-2fa-features {
  display: flex; flex-wrap: wrap; gap: 10px;
  padding: 14px 22px;
  border-top: 1px solid var(--bp-border);
  background: var(--bp-bg);
}
.ep-2fa-feature {
  display: flex; align-items: center; gap: 7px;
  font-size: .77rem; color: var(--bp-text-muted);
}
.ep-2fa-feature i { font-size: .9rem; color: var(--bp-primary); }

/* security tip */
.ep-security-tip {
  display: flex; align-items: flex-start; gap: 12px;
  background: rgba(67,97,238,.06); border: 1px solid rgba(67,97,238,.15);
  border-radius: 12px; padding: 14px 16px; font-size: .8rem; color: var(--bp-text-muted);
  margin-bottom: 20px;
}
.ep-security-tip i { color: var(--bp-primary); font-size: 1rem; flex-shrink: 0; margin-top: 1px; }
.ep-security-tip strong { color: var(--bp-text); }
</style>

<div id="content" class="main-content">
<div class="layout-px-spacing ep-wrap">

  <!-- Page header -->
  <div class="ep-header">
    <div>
      <h1 class="ep-title">Edit Profile</h1>
      <nav class="ep-breadcrumb">
        <a href="./dashboard.php">Home</a>
        <span>/</span> <a href="#">Account</a>
        <span>/</span> <span>Edit Profile</span>
      </nav>
    </div>
  </div>

  <!-- ── Avatar + account info ── -->
  <div class="ep-card">
    <div class="ep-card-header">
      <h2 class="ep-card-title"><i class="ri-user-line"></i> Account Overview</h2>
      <span class="ep-2fa-badge <?= $is2fa ? 'on' : 'off' ?>">
        <i class="ri-<?= $is2fa ? 'shield-check' : 'shield-line' ?>-line"></i>
        2FA <?= $is2fa ? 'Active' : 'Off' ?>
      </span>
    </div>
    <div class="ep-card-body">

      <!-- Avatar row -->
      <form method="POST" enctype="multipart/form-data">
        <div class="ep-avatar-zone" style="margin-bottom:28px;padding-bottom:22px;border-bottom:1px solid var(--bp-border)">
          <img
            class="ep-avatar-img"
            src="../assets/profile/<?= htmlspecialchars($row['image'] ?: 'default.png') ?>"
            alt="Profile photo"
            id="ep-avatar-preview"
          />
          <div class="ep-avatar-info">
            <h3><?= htmlspecialchars(ucwords($row['firstname'].' '.$row['lastname'])) ?></h3>
            <p><?= htmlspecialchars($row['acct_type']) ?> Account &nbsp;·&nbsp; <?= htmlspecialchars($row['acct_no']) ?></p>
            <div class="ep-upload-btn">
              <span class="ep-upload-label">
                <i class="ri-camera-line"></i> Change Photo
                <input type="file" name="image" accept="image/*" onchange="previewAvatar(this)" />
              </span>
            </div>
          </div>
          <button type="submit" name="upload_picture" class="ep-btn ep-btn-primary" style="align-self:flex-end">
            <i class="ri-upload-2-line"></i> Save Photo
          </button>
        </div>
      </form>

      <!-- Info grid -->
      <div class="ep-info-grid">
        <div class="ep-info-item">
          <div class="ep-info-label">Account Number</div>
          <div class="ep-info-value"><code><?= htmlspecialchars($row['acct_no']) ?></code></div>
        </div>
        <div class="ep-info-item">
          <div class="ep-info-label">Account Type</div>
          <div class="ep-info-value"><?= htmlspecialchars($row['acct_type']) ?></div>
        </div>
        <div class="ep-info-item">
          <div class="ep-info-label">Email</div>
          <div class="ep-info-value"><?= htmlspecialchars($row['acct_email']) ?></div>
        </div>
        <div class="ep-info-item">
          <div class="ep-info-label">Date of Birth</div>
          <div class="ep-info-value"><?= htmlspecialchars($row['acct_dob']) ?></div>
        </div>
        <div class="ep-info-item">
          <div class="ep-info-label">Phone</div>
          <div class="ep-info-value"><?= htmlspecialchars($row['acct_phone']) ?></div>
        </div>
        <div class="ep-info-item">
          <div class="ep-info-label">Occupation</div>
          <div class="ep-info-value"><?= htmlspecialchars($row['acct_occupation']) ?></div>
        </div>
        <div class="ep-info-item">
          <div class="ep-info-label">Gender</div>
          <div class="ep-info-value"><?= htmlspecialchars(ucfirst($row['acct_gender'])) ?></div>
        </div>
        <div class="ep-info-item">
          <div class="ep-info-label">Marital Status</div>
          <div class="ep-info-value"><?= htmlspecialchars(ucfirst($row['marital_status'])) ?></div>
        </div>
        <div class="ep-info-item" style="grid-column:1/-1">
          <div class="ep-info-label">Contact Address</div>
          <div class="ep-info-value"><?= htmlspecialchars($row['acct_address']) ?></div>
        </div>
      </div>

    </div>
  </div>

  <!-- ── 2FA Card ── -->
  <div class="ep-card">
    <div class="ep-card-header">
      <h2 class="ep-card-title"><i class="ri-shield-keyhole-line"></i> Two-Factor Authentication</h2>
      <span class="ep-2fa-badge <?= $is2fa ? 'on' : 'off' ?>">
        <?= $is2fa ? '✓ Enabled' : '✗ Disabled' ?>
      </span>
    </div>

    <div class="ep-2fa-banner">
      <div class="ep-2fa-left">
        <div class="ep-2fa-icon <?= $is2fa ? 'on' : 'off' ?>">
          <i class="ri-<?= $is2fa ? 'shield-check' : 'shield-line' ?>-line"></i>
        </div>
        <div class="ep-2fa-info">
          <h4><?= $is2fa ? 'Your account is protected by 2FA' : 'Add an extra layer of security' ?></h4>
          <p>
            <?php if ($is2fa): ?>
              Every login requires a 6-digit email code in addition to your password and PIN.
            <?php else: ?>
              When enabled, a one-time code is sent to your email at every login attempt.
            <?php endif; ?>
          </p>
        </div>
      </div>

      <form method="POST" id="ep-2fa-form">
        <label class="ep-toggle-pill" title="<?= $is2fa ? 'Disable 2FA' : 'Enable 2FA' ?>">
          <input type="checkbox" id="ep-2fa-toggle" <?= $is2fa ? 'checked' : '' ?> onchange="confirm2faToggle(this)" />
          <span class="ep-toggle-track"></span>
        </label>
        <input type="hidden" name="toggle_2fa" value="1" />
      </form>
    </div>

    <div class="ep-2fa-features">
      <div class="ep-2fa-feature"><i class="ri-mail-lock-line"></i> Email OTP delivery</div>
      <div class="ep-2fa-feature"><i class="ri-time-line"></i> Code expires in 10 minutes</div>
      <div class="ep-2fa-feature"><i class="ri-lock-2-line"></i> Prevents unauthorised logins</div>
      <div class="ep-2fa-feature"><i class="ri-refresh-line"></i> Resend code if needed</div>
    </div>
  </div>

  <!-- ── Password + PIN side-by-side ── -->
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">

    <!-- Change Password -->
    <div class="ep-card" style="margin-bottom:0">
      <div class="ep-card-header">
        <h2 class="ep-card-title"><i class="ri-lock-password-line"></i> Change Password</h2>
      </div>
      <div class="ep-card-body">
        <div class="ep-security-tip">
          <i class="ri-information-line"></i>
          <div><strong>Tips:</strong> Use at least 8 characters, mix uppercase, lowercase, numbers and symbols.</div>
        </div>
        <form method="POST" autocomplete="off">
          <div class="ep-field">
            <label class="ep-label">Current Password</label>
            <div style="position:relative">
              <input class="ep-input" type="password" name="old_password" id="pwd-old" placeholder="••••••••" />
              <button type="button" class="ep-eye-btn" onclick="togglePwd('pwd-old',this)"><i class="ri-eye-line"></i></button>
            </div>
          </div>
          <div class="ep-field">
            <label class="ep-label">New Password</label>
            <div style="position:relative">
              <input class="ep-input" type="password" name="new_password" id="pwd-new" placeholder="••••••••" />
              <button type="button" class="ep-eye-btn" onclick="togglePwd('pwd-new',this)"><i class="ri-eye-line"></i></button>
            </div>
          </div>
          <div class="ep-field">
            <label class="ep-label">Confirm New Password</label>
            <div style="position:relative">
              <input class="ep-input" type="password" name="confirm_password" id="pwd-confirm" placeholder="••••••••" />
              <button type="button" class="ep-eye-btn" onclick="togglePwd('pwd-confirm',this)"><i class="ri-eye-line"></i></button>
            </div>
          </div>
          <div id="pwd-strength" style="margin-bottom:14px;display:none">
            <div style="height:4px;border-radius:2px;overflow:hidden;background:var(--bp-border)">
              <div id="pwd-bar" style="height:100%;width:0;transition:width .3s,background .3s;border-radius:2px"></div>
            </div>
            <div id="pwd-strength-label" style="font-size:.72rem;margin-top:5px;color:var(--bp-text-muted)"></div>
          </div>
          <button type="submit" name="change_password" class="ep-btn ep-btn-primary" style="width:100%;justify-content:center">
            <i class="ri-save-3-line"></i> Update Password
          </button>
        </form>
      </div>
    </div>

    <!-- Change PIN -->
    <div class="ep-card" style="margin-bottom:0">
      <div class="ep-card-header">
        <h2 class="ep-card-title"><i class="ri-num-line"></i> Change PIN</h2>
      </div>
      <div class="ep-card-body">
        <div class="ep-security-tip">
          <i class="ri-information-line"></i>
          <div><strong>Note:</strong> Your PIN is used as a secondary login verification step. Never share it.</div>
        </div>
        <form method="POST" autocomplete="off">
          <div class="ep-field">
            <label class="ep-label">Current PIN</label>
            <input class="ep-input" type="password" name="current_pin" placeholder="Current PIN" inputmode="numeric" />
          </div>
          <div class="ep-field">
            <label class="ep-label">New PIN</label>
            <input class="ep-input" type="password" name="new_pin" placeholder="New PIN" inputmode="numeric" />
          </div>
          <div class="ep-field">
            <label class="ep-label">Confirm New PIN</label>
            <input class="ep-input" type="password" name="confirm_pin" placeholder="Confirm PIN" inputmode="numeric" />
          </div>
          <div style="height:67px"></div>
          <button type="submit" name="change_pin" class="ep-btn ep-btn-primary" style="width:100%;justify-content:center">
            <i class="ri-save-3-line"></i> Update PIN
          </button>
        </form>
      </div>
    </div>

  </div>
  <!-- end password+PIN grid -->

</div>
</div>

<style>
.ep-eye-btn {
  position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
  background: none; border: none; cursor: pointer; color: var(--bp-text-muted);
  font-size: 1rem; padding: 0; line-height: 1; transition: color .15s;
}
.ep-eye-btn:hover { color: var(--bp-primary); }
@media (max-width:767px) {
  .ep-grid-2 { grid-template-columns: 1fr; }
  div[style*="grid-template-columns:1fr 1fr"] { grid-template-columns: 1fr !important; }
}
</style>

<script>
/* ── Avatar preview ── */
function previewAvatar(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function(e) {
      document.getElementById('ep-avatar-preview').src = e.target.result;
    };
    reader.readAsDataURL(input.files[0]);
  }
}

/* ── Toggle password visibility ── */
function togglePwd(id, btn) {
  var inp = document.getElementById(id);
  if (!inp) return;
  inp.type = inp.type === 'password' ? 'text' : 'password';
  btn.innerHTML = inp.type === 'password' ? '<i class="ri-eye-line"></i>' : '<i class="ri-eye-off-line"></i>';
}

/* ── Password strength meter ── */
document.getElementById('pwd-new')?.addEventListener('input', function () {
  var val = this.value;
  var bar = document.getElementById('pwd-bar');
  var label = document.getElementById('pwd-strength-label');
  var wrap = document.getElementById('pwd-strength');
  if (!bar) return;
  if (!val) { wrap.style.display = 'none'; return; }
  wrap.style.display = 'block';
  var score = 0;
  if (val.length >= 8) score++;
  if (/[A-Z]/.test(val)) score++;
  if (/[0-9]/.test(val)) score++;
  if (/[^A-Za-z0-9]/.test(val)) score++;
  if (val.length >= 14) score++;
  var colors = ['#ef4444','#f97316','#f59e0b','#10b981','#10b981'];
  var labels = ['Very Weak','Weak','Fair','Strong','Very Strong'];
  var pct = Math.min(100, score * 25);
  bar.style.width = pct + '%';
  bar.style.background = colors[score - 1] || '#ef4444';
  label.textContent = (labels[score - 1] || 'Very Weak') + ' password';
  label.style.color = colors[score - 1] || '#ef4444';
});

/* ── 2FA toggle confirmation ── */
function confirm2faToggle(checkbox) {
  var enabling = checkbox.checked;
  var msg = enabling
    ? 'Enable Two-Factor Authentication? A verification code will be required at every login.'
    : 'Disable Two-Factor Authentication? Your account will be less secure.';
  if (window.Swal) {
    checkbox.checked = !enabling; // revert while confirming
    Swal.fire({
      title: enabling ? 'Enable 2FA?' : 'Disable 2FA?',
      text: msg,
      icon: enabling ? 'question' : 'warning',
      showCancelButton: true,
      confirmButtonText: enabling ? 'Yes, Enable' : 'Yes, Disable',
      confirmButtonColor: enabling ? '#10b981' : '#ef4444',
      cancelButtonText: 'Cancel',
      background: '#0d1117',
      color: '#e2e8f0'
    }).then(function(result) {
      if (result.isConfirmed) {
        checkbox.checked = enabling;
        document.getElementById('ep-2fa-form').submit();
      }
    });
  } else {
    if (confirm(msg)) {
      document.getElementById('ep-2fa-form').submit();
    } else {
      checkbox.checked = !enabling;
    }
  }
}
</script>

<?php include_once("layouts/footer.php"); ?>
