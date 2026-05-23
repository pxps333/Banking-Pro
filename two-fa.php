<?php
include_once("layout/header.php");

/* ── Guard: must have login session + 2fa_pending flag ── */
if (empty($_SESSION['login']) || empty($_SESSION['2fa_pending'])) {
    header("Location:./login.php");
    exit;
}
if (!empty($_SESSION['acct_no'])) {
    header("Location:./user/dashboard.php");
    exit;
}

/* ── Load the pending user ── */
$conn = dbConnect();
$stmt = $conn->prepare("SELECT * FROM users WHERE acct_no=:acct_no");
$stmt->execute(['acct_no' => $_SESSION['login']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    session_destroy();
    header("Location:./login.php");
    exit;
}

$fullName  = ucwords($user['firstname'].' '.$user['lastname']);
$maskedEmail = preg_replace('/(?<=.{3}).(?=.*@)/u', '*', $user['acct_email']);

/* ── Resend OTP ── */
if (isset($_POST['resend_otp'])) {
    $otp    = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    $expiry = time() + 600; // 10 minutes
    $conn->prepare("UPDATE users SET two_fa_otp=:otp, two_fa_otp_expiry=:exp WHERE id=:id")
         ->execute(['otp' => $otp, 'exp' => $expiry, 'id' => $user['id']]);

    $APP_NAME = $pageTitle;
    $subject  = "Your 2FA Code — $APP_NAME";
    $message  = "
    <div style='font-family:Inter,sans-serif;max-width:500px;margin:0 auto;padding:32px;background:#0d1117;border-radius:16px;color:#e2e8f0'>
      <h2 style='font-size:1.2rem;font-weight:800;margin-bottom:8px'>Two-Factor Authentication</h2>
      <p style='color:#94a3b8;margin-bottom:24px'>Hi $fullName, here is your one-time verification code:</p>
      <div style='background:#161b2e;border:1px solid rgba(255,255,255,0.08);border-radius:12px;padding:24px;text-align:center;margin-bottom:24px'>
        <span style='font-size:2.5rem;font-weight:800;letter-spacing:0.2em;color:#4361ee'>$otp</span>
      </div>
      <p style='color:#64748b;font-size:.82rem'>This code expires in <strong style='color:#e2e8f0'>10 minutes</strong>. Do not share it with anyone.</p>
      <p style='color:#64748b;font-size:.78rem;margin-top:16px'>If you did not request this, please contact support immediately.</p>
    </div>";
    $email_message->send_mail($user['acct_email'], $message, $subject);
    $_SESSION['2fa_resent'] = true;
    header("Location:./two-fa.php");
    exit;
}

/* ── Verify OTP ── */
$error = '';
if (isset($_POST['verify_otp'])) {
    $entered = trim($_POST['otp_code'] ?? '');

    if (empty($entered)) {
        $error = 'Please enter the 6-digit code.';
    } elseif (time() > (int)$user['two_fa_otp_expiry']) {
        $error = 'Your code has expired. Please request a new one.';
    } elseif ($entered !== $user['two_fa_otp']) {
        $error = 'Incorrect code. Please check your email and try again.';
    } else {
        /* ── Success: clear OTP, keep login session, go to PIN ── */
        $conn->prepare("UPDATE users SET two_fa_otp=NULL, two_fa_otp_expiry=NULL WHERE id=:id")
             ->execute(['id' => $user['id']]);
        unset($_SESSION['2fa_pending']);
        header("Location:./pin.php");
        exit;
    }
}
?>

<style>
  /* ── Page override for 2FA: full-page centred card ── */
  body { background: #060a12 !important; }

  .tfa-outer {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 24px 16px;
  }

  .tfa-card {
    width: 100%;
    max-width: 440px;
    background: #0d1117;
    border: 1px solid rgba(255,255,255,0.07);
    border-radius: 20px;
    box-shadow: 0 24px 80px rgba(0,0,0,0.6);
    overflow: hidden;
  }

  /* brand bar */
  .tfa-brand {
    background: linear-gradient(135deg, #0f172a, #1a2035);
    padding: 28px 32px 24px;
    text-align: center;
    border-bottom: 1px solid rgba(255,255,255,0.06);
  }
  .tfa-brand img { height: 44px; width: auto; object-fit: contain; margin-bottom: 10px; }
  .tfa-brand-name { font-size: .88rem; font-weight: 700; color: #4361ee; letter-spacing: .01em; }

  /* body */
  .tfa-body { padding: 32px; }

  /* shield icon */
  .tfa-shield {
    width: 64px; height: 64px;
    background: rgba(67,97,238,.12);
    border: 2px solid rgba(67,97,238,.25);
    border-radius: 18px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.75rem; color: #4361ee;
    margin: 0 auto 20px;
  }

  .tfa-title {
    text-align: center;
    font-size: 1.25rem; font-weight: 800; color: #e2e8f0;
    margin-bottom: 6px; letter-spacing: -.02em;
  }
  .tfa-subtitle {
    text-align: center;
    font-size: .82rem; color: #64748b;
    line-height: 1.55; margin-bottom: 28px;
  }
  .tfa-subtitle strong { color: #94a3b8; }

  /* Alert banner */
  .tfa-alert {
    border-radius: 10px; padding: 12px 16px;
    font-size: .82rem; font-weight: 600;
    margin-bottom: 20px;
    display: flex; align-items: center; gap: 9px;
  }
  .tfa-alert.error { background: rgba(239,68,68,.1); border: 1px solid rgba(239,68,68,.2); color: #f87171; }
  .tfa-alert.success { background: rgba(16,185,129,.1); border: 1px solid rgba(16,185,129,.2); color: #34d399; }

  /* OTP input group */
  .tfa-otp-label {
    display: block; font-size: .77rem; font-weight: 700;
    color: #64748b; text-transform: uppercase; letter-spacing: .07em;
    margin-bottom: 10px;
  }
  .tfa-otp-input {
    width: 100%;
    background: rgba(255,255,255,.04);
    border: 2px solid rgba(255,255,255,.08);
    border-radius: 12px;
    padding: 16px 18px;
    color: #e2e8f0;
    font-size: 1.6rem;
    font-weight: 800;
    letter-spacing: 0.35em;
    text-align: center;
    outline: none;
    font-family: 'Inter', monospace;
    transition: border-color .2s, box-shadow .2s;
    -moz-appearance: textfield;
  }
  .tfa-otp-input::-webkit-inner-spin-button,
  .tfa-otp-input::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
  .tfa-otp-input:focus {
    border-color: #4361ee;
    box-shadow: 0 0 0 4px rgba(67,97,238,.15);
    background: rgba(67,97,238,.04);
  }
  .tfa-otp-input.error-state { border-color: #ef4444; }

  /* Expiry countdown */
  .tfa-expiry {
    display: flex; align-items: center; justify-content: center; gap: 6px;
    font-size: .77rem; color: #64748b; margin-top: 10px;
  }
  .tfa-expiry #tfa-countdown { color: #f59e0b; font-weight: 700; font-variant-numeric: tabular-nums; }

  /* Submit button */
  .tfa-btn {
    width: 100%; margin-top: 20px;
    background: #4361ee;
    border: none; border-radius: 12px;
    color: #fff; font-size: .9rem; font-weight: 700;
    padding: 15px; cursor: pointer;
    transition: all .2s;
    box-shadow: 0 6px 24px rgba(67,97,238,.3);
    display: flex; align-items: center; justify-content: center; gap: 9px;
    font-family: inherit;
  }
  .tfa-btn:hover { background: #3451d1; box-shadow: 0 8px 30px rgba(67,97,238,.45); transform: translateY(-1px); }
  .tfa-btn:active { transform: translateY(0); }
  .tfa-btn:disabled { opacity: .6; cursor: not-allowed; transform: none; }

  /* Divider */
  .tfa-divider {
    display: flex; align-items: center; gap: 12px;
    margin: 22px 0;
    font-size: .75rem; color: #334155;
  }
  .tfa-divider::before, .tfa-divider::after {
    content: ''; flex: 1; height: 1px; background: rgba(255,255,255,.06);
  }

  /* Resend / back links */
  .tfa-actions { display: flex; align-items: center; justify-content: center; gap: 20px; }
  .tfa-link {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: .8rem; font-weight: 600; color: #4361ee; text-decoration: none;
    background: none; border: none; cursor: pointer; font-family: inherit;
    transition: color .15s;
  }
  .tfa-link:hover { color: #7b9cff; }
  .tfa-link.muted { color: #475569; }
  .tfa-link.muted:hover { color: #64748b; }

  /* Trust badges */
  .tfa-trust {
    display: flex; align-items: center; justify-content: center; gap: 16px;
    flex-wrap: wrap;
    border-top: 1px solid rgba(255,255,255,.05);
    padding: 16px 32px;
    background: #060a12;
  }
  .tfa-trust-item {
    display: flex; align-items: center; gap: 5px;
    font-size: .7rem; color: #334155; font-weight: 600;
  }
  .tfa-trust-item i { font-size: .85rem; color: #10b981; }
</style>

<div class="tfa-outer">
  <div class="tfa-card">

    <!-- Brand bar -->
    <div class="tfa-brand">
      <img src="./assets/images/logo/<?= htmlspecialchars($page['image'] ?? 'logo.png') ?>" alt="<?= htmlspecialchars($pageTitle) ?>">
      <div class="tfa-brand-name"><?= htmlspecialchars($pageTitle) ?></div>
    </div>

    <!-- Body -->
    <div class="tfa-body">

      <div class="tfa-shield"><i class="ri-shield-keyhole-line"></i></div>

      <h1 class="tfa-title">Two-Factor Verification</h1>
      <p class="tfa-subtitle">
        A 6-digit code was sent to<br>
        <strong><?= htmlspecialchars($maskedEmail) ?></strong>
      </p>

      <?php if ($error): ?>
      <div class="tfa-alert error">
        <i class="ri-error-warning-line"></i> <?= htmlspecialchars($error) ?>
      </div>
      <?php endif; ?>

      <?php if (!empty($_SESSION['2fa_resent'])): unset($_SESSION['2fa_resent']); ?>
      <div class="tfa-alert success">
        <i class="ri-check-line"></i> A fresh code was sent to your email.
      </div>
      <?php endif; ?>

      <form method="POST" id="tfa-form" autocomplete="off">
        <label class="tfa-otp-label" for="otp-code">Verification Code</label>
        <input
          class="tfa-otp-input <?= $error ? 'error-state' : '' ?>"
          type="number"
          id="otp-code"
          name="otp_code"
          placeholder="· · · · · ·"
          maxlength="6"
          inputmode="numeric"
          autofocus
          required
        />
        <div class="tfa-expiry">
          <i class="ri-time-line"></i>
          Code expires in <span id="tfa-countdown">10:00</span>
        </div>

        <button class="tfa-btn" type="submit" name="verify_otp" id="tfa-submit">
          <i class="ri-shield-check-line"></i> Verify &amp; Continue
        </button>
      </form>

      <div class="tfa-divider">or</div>

      <div class="tfa-actions">
        <form method="POST" style="margin:0">
          <button type="submit" name="resend_otp" class="tfa-link">
            <i class="ri-refresh-line"></i> Resend Code
          </button>
        </form>
        <a href="./login.php" class="tfa-link muted">
          <i class="ri-arrow-left-line"></i> Back to Login
        </a>
      </div>

    </div>

    <!-- Trust bar -->
    <div class="tfa-trust">
      <div class="tfa-trust-item"><i class="ri-lock-line"></i> End-to-end Encrypted</div>
      <div class="tfa-trust-item"><i class="ri-shield-check-line"></i> Secure Session</div>
      <div class="tfa-trust-item"><i class="ri-time-line"></i> 10-min Expiry</div>
    </div>

  </div>
</div>

<script>
/* ── OTP digit limiter ── */
var otpInput = document.getElementById('otp-code');
otpInput.addEventListener('input', function () {
  if (this.value.length > 6) this.value = this.value.slice(0, 6);
  this.classList.remove('error-state');
});

/* ── Countdown timer (10 min from page load) ── */
(function () {
  var seconds = 600;
  var el = document.getElementById('tfa-countdown');
  var btn = document.getElementById('tfa-submit');
  if (!el) return;
  var t = setInterval(function () {
    seconds--;
    if (seconds <= 0) {
      clearInterval(t);
      el.textContent = '0:00';
      el.style.color = '#ef4444';
      btn.disabled = true;
      btn.innerHTML = '<i class="ri-close-circle-line"></i> Code Expired — Request a new one';
      return;
    }
    var m = Math.floor(seconds / 60);
    var s = seconds % 60;
    el.textContent = m + ':' + (s < 10 ? '0' : '') + s;
    if (seconds <= 60) el.style.color = '#ef4444';
  }, 1000);
})();
</script>

<?php include_once("layout/footer.php"); ?>
