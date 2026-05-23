<?php
include_once("./layout/header.php");
require_once('./include/adminloginFunction.php');

// ── BANK / v_bank handlers ────────────────────────────────────────────────────

if (isset($_POST['add_bank'])) {
    $conn->prepare("INSERT INTO v_bank (bank_name,routine_no,acct_no,swift_code) VALUES(:bn,:rn,:an,:sw)")
         ->execute([
             'bn' => trim($_POST['bank_name']),
             'rn' => trim($_POST['routine_no']),
             'an' => trim($_POST['acct_no']),
             'sw' => trim($_POST['swift_code']),
         ]);
    toast_alert('success', 'Bank details added successfully.', 'Added');
}

if (isset($_POST['edit_bank'])) {
    $conn->prepare("UPDATE v_bank SET bank_name=:bn, routine_no=:rn, acct_no=:an, swift_code=:sw WHERE id=:id")
         ->execute([
             'bn' => trim($_POST['bank_name']),
             'rn' => trim($_POST['routine_no']),
             'an' => trim($_POST['acct_no']),
             'sw' => trim($_POST['swift_code']),
             'id' => (int)$_POST['bank_id'],
         ]);
    toast_alert('success', 'Bank details updated.', 'Saved');
}

if (isset($_POST['delete_bank'])) {
    $conn->prepare("DELETE FROM v_bank WHERE id=:id")->execute(['id' => (int)$_POST['bank_id']]);
    toast_alert('success', 'Bank entry removed.', 'Deleted');
}

// ── CRYPTO / crypto_currency handlers ────────────────────────────────────────

if (isset($_POST['add_crypto'])) {
    $conn->prepare("INSERT INTO crypto_currency (crypto_name,wallet_address) VALUES(:cn,:wa)")
         ->execute([
             'cn' => trim($_POST['crypto_name']),
             'wa' => trim($_POST['wallet_address']),
         ]);
    toast_alert('success', 'Crypto wallet added.', 'Added');
}

if (isset($_POST['edit_crypto'])) {
    $conn->prepare("UPDATE crypto_currency SET crypto_name=:cn, wallet_address=:wa WHERE id=:id")
         ->execute([
             'cn' => trim($_POST['crypto_name']),
             'wa' => trim($_POST['wallet_address']),
             'id' => (int)$_POST['crypto_id'],
         ]);
    toast_alert('success', 'Crypto wallet updated.', 'Saved');
}

if (isset($_POST['delete_crypto'])) {
    $conn->prepare("DELETE FROM crypto_currency WHERE id=:id")->execute(['id' => (int)$_POST['crypto_id']]);
    toast_alert('success', 'Crypto wallet removed.', 'Deleted');
}

// ── Fetch data ────────────────────────────────────────────────────────────────
$banks   = $conn->query("SELECT * FROM v_bank ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
$cryptos = $conn->query("SELECT * FROM crypto_currency ORDER BY crypto_name")->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
.bd-wrap { padding: 28px 24px 60px; }
.bd-section-title {
    font-size: 1rem; font-weight: 800; color: var(--adm-text);
    display: flex; align-items: center; gap: 8px; margin-bottom: 0;
}
.bd-section-title i { font-size: 1.1rem; color: var(--adm-primary); }
.bd-copy {
    background: none; border: none; cursor: pointer;
    color: var(--adm-text3); padding: 0 0 0 6px; font-size: 13px;
    transition: color .2s;
}
.bd-copy:hover { color: var(--adm-primary); }
/* Modal tweaks */
.bd-modal { display:none; position:fixed; inset:0; z-index:9999;
    background:rgba(0,0,0,.55); align-items:center; justify-content:center; }
.bd-modal.open { display:flex; }
.bd-modal-box {
    background: var(--adm-card); border: 1px solid var(--adm-border);
    border-radius: 16px; padding: 28px 26px 24px; width: 100%; max-width: 480px;
    box-shadow: 0 20px 60px rgba(0,0,0,.35);
}
.bd-modal-title {
    font-size: .95rem; font-weight: 800; color: var(--adm-text);
    margin-bottom: 20px; display: flex; align-items: center; gap: 8px;
}
.bd-modal-title i { color: var(--adm-primary); }
.bd-form-label {
    display: block; font-size: .72rem; font-weight: 700; color: var(--adm-text3);
    text-transform: uppercase; letter-spacing: .06em; margin-bottom: 5px;
}
.bd-form-input {
    width: 100%; padding: 10px 13px; border-radius: 8px;
    border: 1px solid var(--adm-border); background: var(--adm-surface2);
    color: var(--adm-text); font-size: .85rem; outline: none;
    transition: border-color .2s;
}
.bd-form-input:focus { border-color: var(--adm-primary); }
.bd-modal-footer { display: flex; gap: 10px; justify-content: flex-end; margin-top: 22px; }
.bd-action-btn {
    background: none; border: 1px solid var(--adm-border); border-radius: 7px;
    padding: 5px 11px; font-size: .75rem; font-weight: 600; cursor: pointer;
    display: inline-flex; align-items: center; gap: 5px; color: var(--adm-text2);
    transition: all .18s;
}
.bd-action-btn:hover { background: var(--adm-surface2); }
.bd-action-btn.edit:hover { border-color: var(--adm-primary); color: var(--adm-primary); }
.bd-action-btn.del:hover  { border-color: #ef4444; color: #ef4444; }
</style>

<!--  BEGIN CONTENT AREA  -->
<div id="content" class="main-content">
<div class="bd-wrap">

    <!-- Page header -->
    <div class="adm-card-header" style="margin-bottom:24px;padding:0;">
        <div>
            <h3 style="font-size:1.25rem;font-weight:800;color:var(--adm-text);margin:0 0 4px;">
                <i class="ri-bank-line" style="color:var(--adm-primary);margin-right:6px;"></i>Payment Details Manager
            </h3>
            <p style="font-size:.78rem;color:var(--adm-text3);margin:0;">
                Manage bank deposit receiving info and crypto wallet addresses shown to customers.
            </p>
        </div>
    </div>

    <!-- ════════════════════════════════════════════
         SECTION 1 — Bank Deposit Details (v_bank)
    ═════════════════════════════════════════════ -->
    <div class="adm-card" style="margin-bottom:24px;">
        <div class="adm-card-header">
            <h5 class="bd-section-title">
                <i class="ri-building-2-line"></i> Bank Deposit Details
            </h5>
            <button class="adm-btn adm-btn-sm" onclick="openModal('addBankModal')" style="display:flex;align-items:center;gap:6px;">
                <i class="ri-add-line"></i> Add Bank
            </button>
        </div>
        <div class="adm-card-body" style="padding:0;">
            <?php if (empty($banks)): ?>
            <div style="text-align:center;padding:40px 20px;">
                <i class="ri-building-2-line" style="font-size:2.5rem;color:var(--adm-text3);opacity:.4;display:block;margin-bottom:8px;"></i>
                <div style="font-size:.85rem;color:var(--adm-text3);">No bank details yet.</div>
                <button onclick="openModal('addBankModal')" class="adm-btn adm-btn-sm" style="margin-top:12px;">
                    <i class="ri-add-line"></i> Add Bank Details
                </button>
            </div>
            <?php else: ?>
            <div style="overflow-x:auto;">
                <table class="adm-table" style="min-width:680px;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Bank Name</th>
                            <th>Routing / Sort Code</th>
                            <th>Account Number</th>
                            <th>SWIFT / BIC</th>
                            <th>Added</th>
                            <th style="text-align:right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($banks as $i => $b): ?>
                    <tr>
                        <td style="color:var(--adm-text3);font-size:.8rem;"><?= $i + 1 ?></td>
                        <td style="font-weight:700;"><?= htmlspecialchars($b['bank_name']) ?></td>
                        <td style="font-family:monospace;font-size:.85rem;">
                            <?= htmlspecialchars($b['routine_no']) ?>
                            <button class="bd-copy" title="Copy" onclick="copyText('<?= htmlspecialchars($b['routine_no']) ?>', this)"><i class="ri-file-copy-line"></i></button>
                        </td>
                        <td style="font-family:monospace;font-size:.85rem;">
                            <?= htmlspecialchars($b['acct_no']) ?>
                            <button class="bd-copy" title="Copy" onclick="copyText('<?= htmlspecialchars($b['acct_no']) ?>', this)"><i class="ri-file-copy-line"></i></button>
                        </td>
                        <td style="font-family:monospace;font-size:.85rem;">
                            <?= htmlspecialchars($b['swift_code']) ?>
                            <button class="bd-copy" title="Copy" onclick="copyText('<?= htmlspecialchars($b['swift_code']) ?>', this)"><i class="ri-file-copy-line"></i></button>
                        </td>
                        <td style="font-size:.78rem;color:var(--adm-text3);"><?= htmlspecialchars(substr($b['created_at'], 0, 10)) ?></td>
                        <td style="text-align:right;white-space:nowrap;">
                            <button class="bd-action-btn edit"
                                onclick="openEditBank(<?= $b['id'] ?>, '<?= addslashes($b['bank_name']) ?>', '<?= addslashes($b['routine_no']) ?>', '<?= addslashes($b['acct_no']) ?>', '<?= addslashes($b['swift_code']) ?>')">
                                <i class="ri-edit-line"></i> Edit
                            </button>
                            <button class="bd-action-btn del"
                                onclick="confirmDelete('deleteBank', <?= $b['id'] ?>, '<?= addslashes($b['bank_name']) ?>')">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- ════════════════════════════════════════════
         SECTION 2 — Crypto Wallets
    ═════════════════════════════════════════════ -->
    <div class="adm-card">
        <div class="adm-card-header">
            <h5 class="bd-section-title">
                <i class="ri-bit-coin-line"></i> Crypto Wallet Addresses
            </h5>
            <button class="adm-btn adm-btn-sm" onclick="openModal('addCryptoModal')" style="display:flex;align-items:center;gap:6px;">
                <i class="ri-add-line"></i> Add Wallet
            </button>
        </div>
        <div class="adm-card-body" style="padding:0;">
            <?php if (empty($cryptos)): ?>
            <div style="text-align:center;padding:40px 20px;">
                <i class="ri-bit-coin-line" style="font-size:2.5rem;color:var(--adm-text3);opacity:.4;display:block;margin-bottom:8px;"></i>
                <div style="font-size:.85rem;color:var(--adm-text3);">No crypto wallets yet.</div>
                <button onclick="openModal('addCryptoModal')" class="adm-btn adm-btn-sm" style="margin-top:12px;">
                    <i class="ri-add-line"></i> Add Wallet
                </button>
            </div>
            <?php else: ?>
            <div style="overflow-x:auto;">
                <table class="adm-table" style="min-width:500px;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Currency</th>
                            <th>Wallet Address</th>
                            <th>Added</th>
                            <th style="text-align:right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($cryptos as $i => $c): ?>
                    <tr>
                        <td style="color:var(--adm-text3);font-size:.8rem;"><?= $i + 1 ?></td>
                        <td>
                            <div style="display:flex;align-items:center;gap:8px;">
                                <div style="width:30px;height:30px;border-radius:8px;background:rgba(245,158,11,0.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="ri-bit-coin-line" style="color:var(--adm-orange);font-size:15px;"></i>
                                </div>
                                <span style="font-weight:700;"><?= htmlspecialchars($c['crypto_name']) ?></span>
                            </div>
                        </td>
                        <td style="font-family:monospace;font-size:.83rem;max-width:260px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                            <?= htmlspecialchars($c['wallet_address']) ?>
                            <button class="bd-copy" title="Copy" onclick="copyText('<?= htmlspecialchars($c['wallet_address']) ?>', this)"><i class="ri-file-copy-line"></i></button>
                        </td>
                        <td style="font-size:.78rem;color:var(--adm-text3);"><?= htmlspecialchars(substr($c['created_at'], 0, 10)) ?></td>
                        <td style="text-align:right;white-space:nowrap;">
                            <button class="bd-action-btn edit"
                                onclick="openEditCrypto(<?= $c['id'] ?>, '<?= addslashes($c['crypto_name']) ?>', '<?= addslashes($c['wallet_address']) ?>')">
                                <i class="ri-edit-line"></i> Edit
                            </button>
                            <button class="bd-action-btn del"
                                onclick="confirmDelete('deleteCrypto', <?= $c['id'] ?>, '<?= addslashes($c['crypto_name']) ?>')">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>

</div>
</div>
<!--  END CONTENT AREA  -->

<!-- ══════════════════════════════════════
     MODALS
══════════════════════════════════════ -->

<!-- Add Bank Modal -->
<div class="bd-modal" id="addBankModal">
    <div class="bd-modal-box">
        <div class="bd-modal-title"><i class="ri-building-2-line"></i> Add Bank Details</div>
        <form method="POST">
            <div style="display:flex;flex-direction:column;gap:14px;">
                <div>
                    <label class="bd-form-label">Bank Name</label>
                    <input name="bank_name" class="bd-form-input" placeholder="e.g. Chase Bank" required>
                </div>
                <div>
                    <label class="bd-form-label">Routing / Sort Code</label>
                    <input name="routine_no" class="bd-form-input" placeholder="e.g. 021000021" required>
                </div>
                <div>
                    <label class="bd-form-label">Account Number</label>
                    <input name="acct_no" class="bd-form-input" placeholder="e.g. 000123456789" required>
                </div>
                <div>
                    <label class="bd-form-label">SWIFT / BIC Code</label>
                    <input name="swift_code" class="bd-form-input" placeholder="e.g. CHASUS33" required>
                </div>
            </div>
            <div class="bd-modal-footer">
                <button type="button" onclick="closeModal('addBankModal')" class="adm-btn adm-btn-outline" style="padding:8px 16px;">Cancel</button>
                <button name="add_bank" class="adm-btn" style="padding:8px 20px;"><i class="ri-save-line"></i> Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Bank Modal -->
<div class="bd-modal" id="editBankModal">
    <div class="bd-modal-box">
        <div class="bd-modal-title"><i class="ri-edit-line"></i> Edit Bank Details</div>
        <form method="POST">
            <input type="hidden" name="bank_id" id="editBankId">
            <div style="display:flex;flex-direction:column;gap:14px;">
                <div>
                    <label class="bd-form-label">Bank Name</label>
                    <input name="bank_name" id="editBankName" class="bd-form-input" required>
                </div>
                <div>
                    <label class="bd-form-label">Routing / Sort Code</label>
                    <input name="routine_no" id="editBankRouting" class="bd-form-input" required>
                </div>
                <div>
                    <label class="bd-form-label">Account Number</label>
                    <input name="acct_no" id="editBankAcct" class="bd-form-input" required>
                </div>
                <div>
                    <label class="bd-form-label">SWIFT / BIC Code</label>
                    <input name="swift_code" id="editBankSwift" class="bd-form-input" required>
                </div>
            </div>
            <div class="bd-modal-footer">
                <button type="button" onclick="closeModal('editBankModal')" class="adm-btn adm-btn-outline" style="padding:8px 16px;">Cancel</button>
                <button name="edit_bank" class="adm-btn" style="padding:8px 20px;"><i class="ri-save-line"></i> Update</button>
            </div>
        </form>
    </div>
</div>

<!-- Add Crypto Modal -->
<div class="bd-modal" id="addCryptoModal">
    <div class="bd-modal-box">
        <div class="bd-modal-title"><i class="ri-bit-coin-line"></i> Add Crypto Wallet</div>
        <form method="POST">
            <div style="display:flex;flex-direction:column;gap:14px;">
                <div>
                    <label class="bd-form-label">Currency Name</label>
                    <input name="crypto_name" class="bd-form-input" placeholder="e.g. Bitcoin, USDT, Ethereum" required>
                </div>
                <div>
                    <label class="bd-form-label">Wallet Address</label>
                    <input name="wallet_address" class="bd-form-input" placeholder="Paste wallet address here" required>
                </div>
            </div>
            <div class="bd-modal-footer">
                <button type="button" onclick="closeModal('addCryptoModal')" class="adm-btn adm-btn-outline" style="padding:8px 16px;">Cancel</button>
                <button name="add_crypto" class="adm-btn" style="padding:8px 20px;"><i class="ri-save-line"></i> Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Crypto Modal -->
<div class="bd-modal" id="editCryptoModal">
    <div class="bd-modal-box">
        <div class="bd-modal-title"><i class="ri-edit-line"></i> Edit Crypto Wallet</div>
        <form method="POST">
            <input type="hidden" name="crypto_id" id="editCryptoId">
            <div style="display:flex;flex-direction:column;gap:14px;">
                <div>
                    <label class="bd-form-label">Currency Name</label>
                    <input name="crypto_name" id="editCryptoName" class="bd-form-input" required>
                </div>
                <div>
                    <label class="bd-form-label">Wallet Address</label>
                    <input name="wallet_address" id="editCryptoWallet" class="bd-form-input" required>
                </div>
            </div>
            <div class="bd-modal-footer">
                <button type="button" onclick="closeModal('editCryptoModal')" class="adm-btn adm-btn-outline" style="padding:8px 16px;">Cancel</button>
                <button name="edit_crypto" class="adm-btn" style="padding:8px 20px;"><i class="ri-save-line"></i> Update</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirm Modal (shared) -->
<div class="bd-modal" id="deleteConfirmModal">
    <div class="bd-modal-box" style="max-width:380px;text-align:center;">
        <div style="width:52px;height:52px;border-radius:50%;background:rgba(239,68,68,.12);display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
            <i class="ri-delete-bin-line" style="font-size:1.4rem;color:#ef4444;"></i>
        </div>
        <div style="font-size:.95rem;font-weight:700;color:var(--adm-text);margin-bottom:6px;">Delete Entry</div>
        <div id="deleteConfirmMsg" style="font-size:.82rem;color:var(--adm-text3);margin-bottom:20px;"></div>
        <form method="POST" id="deleteConfirmForm">
            <input type="hidden" name="" id="deleteIdField">
            <div style="display:flex;gap:10px;justify-content:center;">
                <button type="button" onclick="closeModal('deleteConfirmModal')" class="adm-btn adm-btn-outline" style="padding:8px 18px;">Cancel</button>
                <button type="submit" id="deleteSubmitBtn" class="adm-btn" style="padding:8px 18px;background:#ef4444;color:#fff;border-color:#ef4444;">
                    <i class="ri-delete-bin-line"></i> Delete
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(id) { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }

// Close on backdrop click
document.querySelectorAll('.bd-modal').forEach(function(m){
    m.addEventListener('click', function(e){ if(e.target === m) m.classList.remove('open'); });
});

function openEditBank(id, name, routing, acct, swift) {
    document.getElementById('editBankId').value = id;
    document.getElementById('editBankName').value = name;
    document.getElementById('editBankRouting').value = routing;
    document.getElementById('editBankAcct').value = acct;
    document.getElementById('editBankSwift').value = swift;
    openModal('editBankModal');
}

function openEditCrypto(id, name, wallet) {
    document.getElementById('editCryptoId').value = id;
    document.getElementById('editCryptoName').value = name;
    document.getElementById('editCryptoWallet').value = wallet;
    openModal('editCryptoModal');
}

function confirmDelete(type, id, label) {
    var form   = document.getElementById('deleteConfirmForm');
    var field  = document.getElementById('deleteIdField');
    var msg    = document.getElementById('deleteConfirmMsg');
    var btn    = document.getElementById('deleteSubmitBtn');

    if (type === 'deleteBank') {
        field.name = 'bank_id';
        btn.name   = 'delete_bank';
        msg.textContent = 'Remove bank entry "' + label + '"? This cannot be undone.';
    } else {
        field.name = 'crypto_id';
        btn.name   = 'delete_crypto';
        msg.textContent = 'Remove wallet "' + label + '"? This cannot be undone.';
    }
    field.value = id;
    openModal('deleteConfirmModal');
}

function copyText(text, btn) {
    navigator.clipboard.writeText(text).then(function(){
        var icon = btn.querySelector('i');
        icon.className = 'ri-check-line';
        btn.style.color = 'var(--adm-green, #10b981)';
        setTimeout(function(){
            icon.className = 'ri-file-copy-line';
            btn.style.color = '';
        }, 1800);
    });
}
</script>

<?php include_once("./layout/footer.php"); ?>
