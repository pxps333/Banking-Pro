<?php
/* ── Handle AJAX save_settings ── */
if (isset($_POST['save_settings']) && isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    header('Content-Type: application/json');
    $fields = ['url_name','url_tel','url_email','about_us','livechat',
               'trans_limit_min','trans_limit_max','twillio_status',
               'transfer','billing_code','bank_deposit'];
    $set = []; $params = ['id' => 1];
    foreach ($fields as $f) {
        $set[] = "$f=:$f";
        $params[$f] = $_POST[$f] ?? '';
    }
    $sql = "UPDATE settings SET ".implode(',',$set)." WHERE id=:id";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        echo json_encode(['ok'=>true,'msg'=>'Settings saved successfully.']);
    } catch (Exception $e) {
        echo json_encode(['ok'=>false,'msg'=>'Save failed: '.$e->getMessage()]);
    }
    exit;
}

/* ── Handle AJAX logo upload ── */
if (isset($_POST['upload_picture']) && isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    header('Content-Type: application/json');
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['ok'=>false,'msg'=>'No file uploaded or upload error.']); exit;
    }
    $file = $_FILES['image'];
    $ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ['jpg','jpeg','png','svg','webp'])) {
        echo json_encode(['ok'=>false,'msg'=>'Invalid file type. Use JPG, PNG, SVG or WebP.']); exit;
    }
    if ($file['size'] > 2 * 1024 * 1024) {
        echo json_encode(['ok'=>false,'msg'=>'File too large. Max 2 MB.']); exit;
    }
    $filename = 'logo_'.time().'.'.$ext;
    $dest = __DIR__.'/../assets/images/logo/'.$filename;
    if (move_uploaded_file($file['tmp_name'], $dest)) {
        $stmt = $conn->prepare("UPDATE settings SET image=:image WHERE id=1");
        $stmt->execute(['image' => $filename]);
        echo json_encode(['ok'=>true,'msg'=>'Logo updated.','filename'=>$filename,'url'=>'/assets/images/logo/'.$filename]);
    } else {
        echo json_encode(['ok'=>false,'msg'=>'Could not save file. Check folder permissions.']);
    }
    exit;
}
?>

include_once("./layout/header.php");



<!-- ── Custom styles scoped to this page ── -->
<style>
:root {
  --sp-bg:        #0d1117;
  --sp-surface:   #161b2e;
  --sp-card:      #1a2035;
  --sp-border:    rgba(255,255,255,0.08);
  --sp-border-h:  rgba(99,102,241,0.4);
  --sp-text:      #e8eaf6;
  --sp-muted:     rgba(232,234,246,0.45);
  --sp-accent:    #6366f1;
  --sp-green:     #10b981;
  --sp-red:       #f87171;
  --sp-grad:      linear-gradient(135deg,#6366f1,#8b5cf6);
}

#sp-wrap { font-family: 'Inter','Quicksand',sans-serif; color: var(--sp-text); padding: 28px 20px 60px; }

/* ── Page header ── */
.sp-page-header { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px; margin-bottom:32px; }
.sp-page-title { font-size:1.4rem; font-weight:800; letter-spacing:-0.02em; }
.sp-page-title span { background:var(--sp-grad); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.sp-breadcrumb { font-size:.75rem; color:var(--sp-muted); }
.sp-breadcrumb a { color:var(--sp-accent); text-decoration:none; }

/* ── Layout: sidebar tabs + content + preview ── */
.sp-layout { display:grid; grid-template-columns:200px 1fr 340px; gap:20px; align-items:start; }

/* ── Tab sidebar ── */
.sp-tabs { background:var(--sp-card); border:1px solid var(--sp-border); border-radius:16px; padding:10px; }
.sp-tab-btn {
  width:100%; background:none; border:none; cursor:pointer; text-align:left;
  display:flex; align-items:center; gap:10px; padding:11px 14px; border-radius:10px;
  font-size:.83rem; font-weight:600; color:var(--sp-muted); transition:all .2s;
  margin-bottom:2px;
}
.sp-tab-btn i { font-size:1rem; flex-shrink:0; }
.sp-tab-btn:hover { background:rgba(255,255,255,.04); color:var(--sp-text); }
.sp-tab-btn.active { background:rgba(99,102,241,.12); color:var(--sp-accent); border:1px solid rgba(99,102,241,.2); }

/* ── Content panels ── */
.sp-panels { display:flex; flex-direction:column; gap:16px; }
.sp-panel { display:none; flex-direction:column; gap:16px; }
.sp-panel.active { display:flex; }

/* ── Cards ── */
.sp-card { background:var(--sp-card); border:1px solid var(--sp-border); border-radius:16px; padding:24px; }
.sp-card-title { font-size:.82rem; font-weight:700; text-transform:uppercase; letter-spacing:.1em; color:var(--sp-muted); margin-bottom:20px; display:flex; align-items:center; gap:8px; }
.sp-card-title i { font-size:1rem; color:var(--sp-accent); }

/* ── Form fields ── */
.sp-field { margin-bottom:18px; }
.sp-label { display:block; font-size:.78rem; font-weight:600; color:var(--sp-muted); margin-bottom:7px; letter-spacing:.02em; }
.sp-label span.req { color:var(--sp-accent); margin-left:2px; }
.sp-input, .sp-textarea, .sp-select {
  width:100%; background:rgba(255,255,255,.04); border:1px solid var(--sp-border);
  border-radius:10px; padding:10px 14px; color:var(--sp-text); font-size:.88rem;
  transition:border-color .2s, box-shadow .2s; outline:none; font-family:inherit;
}
.sp-input:focus, .sp-textarea:focus, .sp-select:focus {
  border-color:var(--sp-border-h); box-shadow:0 0 0 3px rgba(99,102,241,.12);
}
.sp-input::placeholder, .sp-textarea::placeholder { color:rgba(255,255,255,.2); }
.sp-textarea { resize:vertical; min-height:80px; }
.sp-hint { font-size:.72rem; color:var(--sp-muted); margin-top:5px; }

/* ── Toggle switch ── */
.sp-toggle-row { display:flex; align-items:center; justify-content:space-between; padding:12px 0; border-bottom:1px solid var(--sp-border); }
.sp-toggle-row:last-child { border-bottom:none; }
.sp-toggle-info strong { display:block; font-size:.85rem; font-weight:600; margin-bottom:2px; }
.sp-toggle-info span { font-size:.75rem; color:var(--sp-muted); }
.sp-toggle { position:relative; display:inline-block; width:42px; height:24px; flex-shrink:0; }
.sp-toggle input { opacity:0; width:0; height:0; }
.sp-toggle-slider {
  position:absolute; inset:0; cursor:pointer; border-radius:100px;
  background:rgba(255,255,255,.12); border:1px solid var(--sp-border); transition:.25s;
}
.sp-toggle-slider::before {
  content:''; position:absolute; left:3px; bottom:3px;
  width:16px; height:16px; border-radius:50%; background:#fff; transition:.25s;
}
.sp-toggle input:checked + .sp-toggle-slider { background:var(--sp-accent); border-color:var(--sp-accent); }
.sp-toggle input:checked + .sp-toggle-slider::before { transform:translateX(18px); }
.sp-toggle-value { display:none; }

/* ── Logo upload zone ── */
#sp-logo-zone {
  border:2px dashed var(--sp-border); border-radius:14px; padding:28px 20px;
  text-align:center; cursor:pointer; transition:all .25s; position:relative;
  background:rgba(255,255,255,.02);
}
#sp-logo-zone:hover, #sp-logo-zone.drag-over { border-color:var(--sp-accent); background:rgba(99,102,241,.06); }
#sp-logo-zone input { position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; height:100%; }
#sp-logo-preview { width:80px; height:80px; border-radius:12px; object-fit:contain; margin:0 auto 12px; display:block; background:rgba(255,255,255,.05); padding:6px; }
.sp-logo-upload-label { font-size:.82rem; color:var(--sp-muted); }
.sp-logo-upload-label strong { color:var(--sp-accent); }
#sp-logo-progress { display:none; width:100%; height:4px; background:rgba(255,255,255,.08); border-radius:2px; margin-top:12px; overflow:hidden; }
#sp-logo-progress-bar { height:100%; width:0%; background:var(--sp-grad); transition:width .3s; border-radius:2px; }
#sp-logo-filename { font-size:.72rem; color:var(--sp-green); margin-top:8px; display:none; }

/* ── Save button ── */
.sp-save-btn {
  display:inline-flex; align-items:center; gap:8px;
  background:var(--sp-grad); color:#fff; border:none; cursor:pointer;
  font-size:.88rem; font-weight:700; padding:12px 28px; border-radius:10px;
  transition:all .25s; box-shadow:0 4px 20px rgba(99,102,241,.35);
}
.sp-save-btn:hover { transform:translateY(-1px); box-shadow:0 8px 28px rgba(99,102,241,.5); }
.sp-save-btn:disabled { opacity:.6; cursor:not-allowed; transform:none; }
.sp-save-btn .sp-spinner { width:14px; height:14px; border:2px solid rgba(255,255,255,.3); border-top-color:#fff; border-radius:50%; animation:sp-spin .6s linear infinite; display:none; }
@keyframes sp-spin { to { transform:rotate(360deg); } }

/* ── Toast notification ── */
#sp-toast {
  position:fixed; bottom:24px; right:24px; z-index:9999;
  background:var(--sp-card); border:1px solid var(--sp-border);
  border-radius:14px; padding:14px 18px; min-width:280px; max-width:380px;
  box-shadow:0 20px 60px rgba(0,0,0,.5); display:flex; align-items:flex-start; gap:12px;
  transform:translateY(20px) scale(.96); opacity:0; pointer-events:none;
  transition:all .3s cubic-bezier(.4,0,.2,1);
}
#sp-toast.show { transform:translateY(0) scale(1); opacity:1; pointer-events:auto; }
.sp-toast-icon { width:32px; height:32px; border-radius:9px; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:1rem; }
.sp-toast-icon.success { background:rgba(16,185,129,.15); color:var(--sp-green); }
.sp-toast-icon.error { background:rgba(248,113,113,.15); color:var(--sp-red); }
.sp-toast-body strong { display:block; font-size:.85rem; font-weight:700; margin-bottom:3px; }
.sp-toast-body span { font-size:.78rem; color:var(--sp-muted); }

/* ── Live preview ── */
.sp-preview { position:sticky; top:80px; }
.sp-preview-card { background:var(--sp-card); border:1px solid var(--sp-border); border-radius:16px; overflow:hidden; }
.sp-preview-label { padding:14px 18px; border-bottom:1px solid var(--sp-border); display:flex; align-items:center; justify-content:space-between; }
.sp-preview-label span { font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.1em; color:var(--sp-muted); }
.sp-preview-dot { width:8px; height:8px; border-radius:50%; background:var(--sp-green); box-shadow:0 0 6px var(--sp-green); animation:sp-pulse 2s infinite; }
@keyframes sp-pulse { 0%,100%{opacity:1} 50%{opacity:.4} }

/* Simulated browser chrome */
.sp-browser { background:#0a0e1a; }
.sp-browser-bar { background:#1a1f2e; padding:8px 12px; display:flex; align-items:center; gap:8px; border-bottom:1px solid rgba(255,255,255,.05); }
.sp-browser-dots { display:flex; gap:5px; }
.sp-browser-dots span { width:9px; height:9px; border-radius:50%; }
.sp-browser-url { flex:1; background:rgba(255,255,255,.05); border-radius:6px; padding:4px 10px; font-size:.65rem; color:rgba(255,255,255,.3); font-family:monospace; margin-left:4px; }

/* Simulated mini navbar */
.sp-mini-nav {
  height:46px; display:flex; align-items:center; justify-content:space-between;
  padding:0 16px; background:rgba(6,9,18,.9); backdrop-filter:blur(10px);
  border-bottom:1px solid rgba(255,255,255,.06);
}
.sp-mini-logo { display:flex; align-items:center; gap:8px; }
.sp-mini-logo img { height:22px; width:auto; object-fit:contain; }
.sp-mini-logo-text { font-size:.72rem; font-weight:800; background:linear-gradient(135deg,#6366f1,#8b5cf6); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.sp-mini-links { display:flex; gap:10px; }
.sp-mini-links span { font-size:.55rem; color:rgba(255,255,255,.35); font-weight:500; }
.sp-mini-btn { font-size:.6rem; background:linear-gradient(135deg,#6366f1,#8b5cf6); color:#fff; border:none; border-radius:6px; padding:4px 10px; font-weight:700; cursor:default; }

/* Simulated hero snippet */
.sp-mini-hero { padding:20px 16px; }
.sp-mini-hero-badge { display:flex; align-items:center; gap:5px; font-size:.55rem; color:rgba(255,255,255,.4); margin-bottom:8px; }
.sp-mini-hero-badge span { width:5px; height:5px; border-radius:50%; background:var(--sp-green); }
.sp-mini-hero h3 { font-size:.85rem; font-weight:800; line-height:1.2; margin-bottom:6px; }
.sp-mini-hero p { font-size:.6rem; color:rgba(255,255,255,.45); line-height:1.5; margin-bottom:10px; max-width:200px; }
.sp-mini-hero-btns { display:flex; gap:6px; }
.sp-mini-hero-btns a { font-size:.6rem; font-weight:700; padding:5px 10px; border-radius:6px; text-decoration:none; }
.sp-mini-hero-btns a:first-child { background:linear-gradient(135deg,#6366f1,#8b5cf6); color:#fff; }
.sp-mini-hero-btns a:last-child { background:rgba(255,255,255,.06); color:rgba(255,255,255,.6); border:1px solid rgba(255,255,255,.1); }

/* Contact info strip */
.sp-mini-contact { background:rgba(255,255,255,.03); border-top:1px solid rgba(255,255,255,.05); padding:10px 16px; display:flex; gap:16px; }
.sp-mini-contact-item { display:flex; align-items:center; gap:5px; font-size:.58rem; color:rgba(255,255,255,.4); }
.sp-mini-contact-item i { font-size:.7rem; color:var(--sp-accent); }
.sp-mini-contact-item strong { color:rgba(255,255,255,.65); }

/* ── Responsive ── */
@media (max-width:1100px) { .sp-layout { grid-template-columns:160px 1fr; } .sp-preview { display:none; } }
@media (max-width:768px)  { .sp-layout { grid-template-columns:1fr; } }
</style>

<!-- ── Remix Icons ── -->
<link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet" />

<div id="sp-wrap">

  <!-- Page header -->
  <div class="sp-page-header">
    <div>
      <div class="sp-page-title">Bank <span>Settings</span></div>
      <div class="sp-breadcrumb"><a href="./dashboard.php">Dashboard</a> / Settings</div>
    </div>
    <button class="sp-save-btn" id="sp-global-save" onclick="spSave()">
      <span class="sp-spinner" id="sp-spinner"></span>
      <i class="ri-save-3-line"></i> Save All Changes
    </button>
  </div>

  <!-- 3-column layout -->
  <div class="sp-layout">

    <!-- ── Tab sidebar ── -->
    <nav class="sp-tabs">
      <button class="sp-tab-btn active" onclick="spTab('identity',this)"><i class="ri-bank-line"></i> Identity</button>
      <button class="sp-tab-btn" onclick="spTab('contact',this)"><i class="ri-phone-line"></i> Contact</button>
      <button class="sp-tab-btn" onclick="spTab('operations',this)"><i class="ri-settings-3-line"></i> Operations</button>
      <button class="sp-tab-btn" onclick="spTab('integrations',this)"><i class="ri-code-box-line"></i> Integrations</button>
    </nav>

    <!-- ── Settings panels ── -->
    <div class="sp-panels">

      <!-- IDENTITY -->
      <div class="sp-panel active" id="sp-panel-identity">

        <div class="sp-card">
          <div class="sp-card-title"><i class="ri-image-line"></i> Bank Logo</div>

          <div id="sp-logo-zone">
            <input type="file" id="sp-logo-file" accept="image/*" />
            <img id="sp-logo-preview" src="/assets/images/logo/<?= htmlspecialchars($page['image'] ?? 'logo.png') ?>" alt="Logo preview" />
            <div class="sp-logo-upload-label">
              <strong>Click to upload</strong> or drag &amp; drop<br>
              JPG, PNG, SVG, WebP — max 2 MB
            </div>
            <div id="sp-logo-progress"><div id="sp-logo-progress-bar"></div></div>
            <div id="sp-logo-filename"></div>
          </div>
        </div>

        <div class="sp-card">
          <div class="sp-card-title"><i class="ri-bank-line"></i> Bank Identity</div>

          <div class="sp-field">
            <label class="sp-label" for="sp-url-name">Bank Name <span class="req">*</span></label>
            <input class="sp-input" id="sp-url-name" name="url_name" type="text"
                   value="<?= htmlspecialchars($page['url_name'] ?? '') ?>"
                   placeholder="e.g. Capital Trust Bank"
                   oninput="spPreviewUpdate()" />
            <div class="sp-hint">This name appears on the navbar, emails, footer, and all pages.</div>
          </div>

          <div class="sp-field">
            <label class="sp-label" for="sp-about">About the Bank</label>
            <textarea class="sp-textarea" id="sp-about" name="about_us"
                      placeholder="A short description about your bank..."><?= htmlspecialchars($page['about_us'] ?? '') ?></textarea>
          </div>
        </div>

      </div>

      <!-- CONTACT -->
      <div class="sp-panel" id="sp-panel-contact">
        <div class="sp-card">
          <div class="sp-card-title"><i class="ri-contacts-line"></i> Contact Information</div>

          <div class="sp-field">
            <label class="sp-label" for="sp-url-tel">Phone Number <span class="req">*</span></label>
            <input class="sp-input" id="sp-url-tel" name="url_tel" type="tel"
                   value="<?= htmlspecialchars($page['url_tel'] ?? '') ?>"
                   placeholder="e.g. 18005551234"
                   oninput="spPreviewUpdate()" />
            <div class="sp-hint">Include country code without spaces or dashes.</div>
          </div>

          <div class="sp-field">
            <label class="sp-label" for="sp-url-email">Support Email <span class="req">*</span></label>
            <input class="sp-input" id="sp-url-email" name="url_email" type="email"
                   value="<?= htmlspecialchars($page['url_email'] ?? '') ?>"
                   placeholder="e.g. support@yourbank.com"
                   oninput="spPreviewUpdate()" />
            <div class="sp-hint">Displayed in the footer and used for outgoing notifications.</div>
          </div>
        </div>
      </div>

      <!-- OPERATIONS -->
      <div class="sp-panel" id="sp-panel-operations">
        <div class="sp-card">
          <div class="sp-card-title"><i class="ri-money-dollar-circle-line"></i> Deposit Limits</div>

          <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
            <div class="sp-field" style="margin:0">
              <label class="sp-label" for="sp-limit-min">Minimum Deposit ($)</label>
              <input class="sp-input" id="sp-limit-min" name="trans_limit_min" type="number" min="0"
                     value="<?= htmlspecialchars($page['trans_limit_min'] ?? '0') ?>" />
            </div>
            <div class="sp-field" style="margin:0">
              <label class="sp-label" for="sp-limit-max">Maximum Deposit ($)</label>
              <input class="sp-input" id="sp-limit-max" name="trans_limit_max" type="number" min="0"
                     value="<?= htmlspecialchars($page['trans_limit_max'] ?? '0') ?>" />
            </div>
          </div>
        </div>

        <div class="sp-card">
          <div class="sp-card-title"><i class="ri-toggle-line"></i> Feature Toggles</div>

          <div class="sp-toggle-row">
            <div class="sp-toggle-info">
              <strong>Wire Transfers</strong>
              <span>Allow users to send and receive wire transfers</span>
            </div>
            <label class="sp-toggle">
              <input type="checkbox" id="sp-toggle-transfer" <?= ($page['transfer'] ?? 0) == 1 ? 'checked' : '' ?> />
              <span class="sp-toggle-slider"></span>
            </label>
            <input type="hidden" name="transfer" id="sp-val-transfer" value="<?= ($page['transfer'] ?? 0) ?>" />
          </div>

          <div class="sp-toggle-row">
            <div class="sp-toggle-info">
              <strong>Bank Deposit</strong>
              <span>Allow users to make bank deposits</span>
            </div>
            <label class="sp-toggle">
              <input type="checkbox" id="sp-toggle-deposit" <?= ($page['bank_deposit'] ?? 0) == 1 ? 'checked' : '' ?> />
              <span class="sp-toggle-slider"></span>
            </label>
            <input type="hidden" name="bank_deposit" id="sp-val-deposit" value="<?= ($page['bank_deposit'] ?? 0) ?>" />
          </div>

          <div class="sp-toggle-row">
            <div class="sp-toggle-info">
              <strong>Billing Codes</strong>
              <span>Require billing codes for certain transactions</span>
            </div>
            <label class="sp-toggle">
              <input type="checkbox" id="sp-toggle-billing" <?= ($page['billing_code'] ?? 0) == 1 ? 'checked' : '' ?> />
              <span class="sp-toggle-slider"></span>
            </label>
            <input type="hidden" name="billing_code" id="sp-val-billing" value="<?= ($page['billing_code'] ?? 0) ?>" />
          </div>

          <div class="sp-toggle-row">
            <div class="sp-toggle-info">
              <strong>Twilio SMS Alerts</strong>
              <span>Send SMS notifications via Twilio</span>
            </div>
            <label class="sp-toggle">
              <input type="checkbox" id="sp-toggle-twilio" <?= ($page['twillio_status'] ?? 0) == 1 ? 'checked' : '' ?> />
              <span class="sp-toggle-slider"></span>
            </label>
            <input type="hidden" name="twillio_status" id="sp-val-twilio" value="<?= ($page['twillio_status'] ?? 0) ?>" />
          </div>
        </div>
      </div>

      <!-- INTEGRATIONS -->
      <div class="sp-panel" id="sp-panel-integrations">
        <div class="sp-card">
          <div class="sp-card-title"><i class="ri-chat-3-line"></i> Live Chat</div>

          <div class="sp-field">
            <label class="sp-label" for="sp-livechat">Live Chat Embed Script</label>
            <textarea class="sp-textarea" id="sp-livechat" name="livechat"
                      placeholder="Paste your Tawk.to or other live chat embed script here..."
                      style="min-height:120px;font-family:monospace;font-size:.8rem"><?= htmlspecialchars($page['livechat'] ?? '') ?></textarea>
            <div class="sp-hint">Paste the full script tag from Tawk.to, Intercom, or any live chat provider. Leave blank to disable.</div>
          </div>
        </div>
      </div>

    </div>
    <!-- end panels -->

    <!-- ── Live Preview ── -->
    <aside class="sp-preview">
      <div class="sp-preview-card">
        <div class="sp-preview-label">
          <span>Live Preview</span>
          <div class="sp-preview-dot"></div>
        </div>

        <!-- Browser chrome -->
        <div class="sp-browser">
          <div class="sp-browser-bar">
            <div class="sp-browser-dots">
              <span style="background:#f87171"></span>
              <span style="background:#fbbf24"></span>
              <span style="background:#34d399"></span>
            </div>
            <div class="sp-browser-url">yourbank.com</div>
          </div>

          <!-- Mini navbar preview -->
          <div class="sp-mini-nav">
            <div class="sp-mini-logo">
              <img id="sp-prev-logo" src="/assets/images/logo/<?= htmlspecialchars($page['image'] ?? 'logo.png') ?>" alt="logo" />
              <span class="sp-mini-logo-text" id="sp-prev-name"><?= htmlspecialchars($page['url_name'] ?? 'Bank Name') ?></span>
            </div>
            <div class="sp-mini-links">
              <span>Home</span><span>Personal</span><span>Business</span>
            </div>
            <button class="sp-mini-btn">Open Account</button>
          </div>

          <!-- Mini hero preview -->
          <div class="sp-mini-hero">
            <div class="sp-mini-hero-badge"><span></span> Trusted Banking Infrastructure</div>
            <h3>Banking Designed<br>for the <span style="background:linear-gradient(135deg,#6366f1,#8b5cf6);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">Modern World</span></h3>
            <p id="sp-prev-about"><?= htmlspecialchars(mb_substr($page['about_us'] ?? 'Your trusted financial partner.', 0, 90)) ?></p>
            <div class="sp-mini-hero-btns">
              <a href="#">Open Account</a>
              <a href="#">Sign In</a>
            </div>
          </div>

          <!-- Contact strip -->
          <div class="sp-mini-contact">
            <div class="sp-mini-contact-item">
              <i class="ri-phone-line"></i>
              <strong id="sp-prev-tel">+<?= htmlspecialchars($page['url_tel'] ?? '') ?></strong>
            </div>
            <div class="sp-mini-contact-item">
              <i class="ri-mail-line"></i>
              <strong id="sp-prev-email"><?= htmlspecialchars($page['url_email'] ?? '') ?></strong>
            </div>
          </div>
        </div>
      </div>

      <!-- Quick stats -->
      <div class="sp-card" style="margin-top:16px">
        <div class="sp-card-title"><i class="ri-information-line"></i> Current Settings</div>
        <div style="display:flex;flex-direction:column;gap:10px">
          <?php
          $statItems = [
            ['Bank Name', $page['url_name'] ?? '-'],
            ['Phone', '+'.$page['url_tel'] ?? '-'],
            ['Email', $page['url_email'] ?? '-'],
            ['Min Deposit', '$'.number_format($page['trans_limit_min'] ?? 0)],
            ['Max Deposit', '$'.number_format($page['trans_limit_max'] ?? 0)],
          ];
          foreach ($statItems as $s): ?>
          <div style="display:flex;justify-content:space-between;align-items:center;font-size:.78rem;padding:6px 0;border-bottom:1px solid var(--sp-border)">
            <span style="color:var(--sp-muted)"><?= $s[0] ?></span>
            <span style="font-weight:600;color:var(--sp-text);max-width:160px;text-align:right;word-break:break-all"><?= htmlspecialchars($s[1]) ?></span>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </aside>

  </div>
</div>

<!-- Toast notification -->
<div id="sp-toast">
  <div class="sp-toast-icon success" id="sp-toast-icon"><i class="ri-checkbox-circle-line"></i></div>
  <div class="sp-toast-body">
    <strong id="sp-toast-title">Saved!</strong>
    <span id="sp-toast-msg">Settings updated successfully.</span>
  </div>
</div>

<script>
(function () {
  "use strict";

  /* ─── Tab switching ─── */
  window.spTab = function (id, btn) {
    document.querySelectorAll('.sp-panel').forEach(function(p){ p.classList.remove('active'); });
    document.querySelectorAll('.sp-tab-btn').forEach(function(b){ b.classList.remove('active'); });
    document.getElementById('sp-panel-' + id).classList.add('active');
    btn.classList.add('active');
  };

  /* ─── Live preview update ─── */
  window.spPreviewUpdate = function () {
    var name  = document.getElementById('sp-url-name').value || 'Bank Name';
    var tel   = document.getElementById('sp-url-tel').value || '';
    var email = document.getElementById('sp-url-email').value || '';
    var about = document.getElementById('sp-about').value || '';
    document.getElementById('sp-prev-name').textContent  = name;
    document.getElementById('sp-prev-tel').textContent   = tel ? '+' + tel : '';
    document.getElementById('sp-prev-email').textContent = email;
    document.getElementById('sp-prev-about').textContent = about.substring(0,90) + (about.length > 90 ? '…' : '');
  };

  /* ─── Toggle → hidden input sync ─── */
  var toggleMap = {
    'sp-toggle-transfer': 'sp-val-transfer',
    'sp-toggle-deposit':  'sp-val-deposit',
    'sp-toggle-billing':  'sp-val-billing',
    'sp-toggle-twilio':   'sp-val-twilio'
  };
  Object.keys(toggleMap).forEach(function (tId) {
    document.getElementById(tId).addEventListener('change', function () {
      document.getElementById(toggleMap[tId]).value = this.checked ? '1' : '0';
    });
  });

  /* ─── Logo drag-and-drop ─── */
  var zone     = document.getElementById('sp-logo-zone');
  var fileIn   = document.getElementById('sp-logo-file');
  var preview  = document.getElementById('sp-logo-preview');
  var progress = document.getElementById('sp-logo-progress');
  var progBar  = document.getElementById('sp-logo-progress-bar');
  var fnLabel  = document.getElementById('sp-logo-filename');

  zone.addEventListener('dragover', function(e){ e.preventDefault(); zone.classList.add('drag-over'); });
  zone.addEventListener('dragleave', function(){ zone.classList.remove('drag-over'); });
  zone.addEventListener('drop', function(e){
    e.preventDefault(); zone.classList.remove('drag-over');
    if (e.dataTransfer.files.length) uploadLogo(e.dataTransfer.files[0]);
  });
  fileIn.addEventListener('change', function(){
    if (this.files.length) uploadLogo(this.files[0]);
  });

  function uploadLogo(file) {
    if (!file.type.match(/image.*/)) { spToast('error','Invalid File','Please upload an image file (JPG, PNG, SVG, WebP).'); return; }
    if (file.size > 2 * 1024 * 1024)  { spToast('error','File Too Large','Maximum file size is 2 MB.'); return; }

    /* Instant local preview */
    var reader = new FileReader();
    reader.onload = function(e) {
      preview.src = e.target.result;
      document.getElementById('sp-prev-logo').src = e.target.result;
    };
    reader.readAsDataURL(file);

    /* Upload via AJAX */
    var fd = new FormData();
    fd.append('upload_picture', '1');
    fd.append('image', file);

    progress.style.display = 'block';
    progBar.style.width = '0%';

    var xhr = new XMLHttpRequest();
    xhr.upload.onprogress = function(e) {
      if (e.lengthComputable) progBar.style.width = Math.round(e.loaded/e.total*100)+'%';
    };
    xhr.onload = function() {
      progress.style.display = 'none';
      try {
        var res = JSON.parse(xhr.responseText);
        if (res.ok) {
          fnLabel.textContent = '✓ ' + file.name + ' uploaded';
          fnLabel.style.display = 'block';
          spToast('success','Logo Updated',res.msg);
        } else {
          spToast('error','Upload Failed', res.msg);
        }
      } catch(err) { spToast('error','Upload Error','Unexpected server response.'); }
    };
    xhr.onerror = function() { progress.style.display='none'; spToast('error','Upload Failed','Network error.'); };
    xhr.open('POST', './settings.php');
    xhr.setRequestHeader('X-Requested-With','XMLHttpRequest');
    xhr.send(fd);
  }

  /* ─── Save all settings via AJAX ─── */
  window.spSave = function () {
    var btn     = document.getElementById('sp-global-save');
    var spinner = document.getElementById('sp-spinner');
    btn.disabled = true;
    spinner.style.display = 'block';

    var data = new FormData();
    data.append('save_settings','1');
    var names = ['url_name','url_tel','url_email','about_us','livechat',
                 'trans_limit_min','trans_limit_max',
                 'transfer','billing_code','bank_deposit','twillio_status'];

    var fieldMap = {
      url_name:       'sp-url-name',
      url_tel:        'sp-url-tel',
      url_email:      'sp-url-email',
      about_us:       'sp-about',
      livechat:       'sp-livechat',
      trans_limit_min:'sp-limit-min',
      trans_limit_max:'sp-limit-max',
      transfer:       'sp-val-transfer',
      billing_code:   'sp-val-billing',
      bank_deposit:   'sp-val-deposit',
      twillio_status: 'sp-val-twilio'
    };

    names.forEach(function(n){
      var el = document.getElementById(fieldMap[n]);
      data.append(n, el ? el.value : '');
    });

    var xhr = new XMLHttpRequest();
    xhr.onload = function() {
      btn.disabled = false;
      spinner.style.display = 'none';
      try {
        var res = JSON.parse(xhr.responseText);
        if (res.ok) {
          spToast('success','Saved!', res.msg);
          spPreviewUpdate();
        } else {
          spToast('error','Error', res.msg);
        }
      } catch(e) { spToast('error','Error','Unexpected server response.'); }
    };
    xhr.onerror = function(){ btn.disabled=false; spinner.style.display='none'; spToast('error','Network Error','Could not reach server.'); };
    xhr.open('POST','./settings.php');
    xhr.setRequestHeader('X-Requested-With','XMLHttpRequest');
    xhr.send(data);
  };

  /* ─── Toast helper ─── */
  var toastTimer = null;
  function spToast(type, title, msg) {
    var toast   = document.getElementById('sp-toast');
    var icon    = document.getElementById('sp-toast-icon');
    var ttitle  = document.getElementById('sp-toast-title');
    var tmsg    = document.getElementById('sp-toast-msg');
    var icons   = { success: 'ri-checkbox-circle-line', error: 'ri-close-circle-line' };

    icon.className = 'sp-toast-icon ' + type;
    icon.innerHTML = '<i class="' + (icons[type]||icons.success) + '"></i>';
    ttitle.textContent = title;
    tmsg.textContent   = msg || '';

    toast.classList.add('show');
    if (toastTimer) clearTimeout(toastTimer);
    toastTimer = setTimeout(function(){ toast.classList.remove('show'); }, 4000);
  }

  /* ─── Wire about textarea to preview update too ─── */
  document.getElementById('sp-about').addEventListener('input', spPreviewUpdate);
  document.getElementById('sp-url-tel').addEventListener('input', spPreviewUpdate);
  document.getElementById('sp-url-email').addEventListener('input', spPreviewUpdate);

})();
</script>

<?php include_once("./layout/footer.php"); ?>
