<?php
include_once("./layout/header.php");
$fullName = ucwords($row['firstname']." ".$row['lastname']);

if (isset($_POST['upload_picture'])) {
    if (isset($_FILES['image'])) {
        $destination = '../assets/images/users/';
        $file = $_FILES['image'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg','jpeg','png','webp'])) {
            $filename = time().'_'.basename($file['name']);
            if (move_uploaded_file($file['tmp_name'], $destination.$filename)) {
                $sql = "UPDATE users SET profile_pic=:pic WHERE id=:id";
                $stmt = $conn->prepare($sql);
                $stmt->execute(['pic'=>$filename,'id'=>$row['id']]);
                header("Location: ./profile.php"); exit;
            }
        }
    }
}

if (isset($_POST['change_password'])) {
    $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);
    $sql = "UPDATE users SET password=:pw WHERE id=:id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['pw'=>$new_password,'id'=>$row['id']]);
    header("Location: ./profile.php"); exit;
}
?>

<div id="content" class="main-content">
<div class="layout-px-spacing">

<div class="adm-page-header">
  <div>
    <h1 class="adm-page-title">Admin Profile</h1>
    <nav class="adm-breadcrumb"><a href="./dashboard.php">Dashboard</a> <span>/</span> <span>Profile</span></nav>
  </div>
</div>

<div style="display:grid;grid-template-columns:300px 1fr;gap:20px;align-items:start">

  <!-- Avatar card -->
  <div class="adm-card" style="text-align:center">
    <div class="adm-card-body">
      <div style="width:100px;height:100px;border-radius:50%;margin:0 auto 14px;overflow:hidden;border:3px solid var(--adm-primary);background:var(--adm-surface2)">
        <img src="/assets/images/users/<?= htmlspecialchars($row['profile_pic'] ?? 'avatar.png') ?>" alt="Avatar"
             style="width:100%;height:100%;object-fit:cover" />
      </div>
      <h3 style="font-size:1rem;font-weight:700;margin-bottom:4px"><?= htmlspecialchars($fullName) ?></h3>
      <p style="font-size:.8rem;color:var(--adm-text3);margin:0">Administrator</p>

      <form method="POST" enctype="multipart/form-data" style="margin-top:18px">
        <label for="avatar-upload" class="adm-btn adm-btn-outline adm-btn-sm" style="cursor:pointer;width:100%;justify-content:center">
          <i class="ri-upload-2-line"></i> Change Photo
        </label>
        <input type="file" id="avatar-upload" name="image" accept="image/*" style="display:none" onchange="this.form.submit()">
        <input type="hidden" name="upload_picture" value="1">
      </form>
    </div>
  </div>

  <!-- Info + password -->
  <div>
    <div class="adm-card" style="margin-bottom:20px">
      <div class="adm-card-header">
        <h2 class="adm-card-title"><i class="ri-user-settings-line"></i> Account Information</h2>
      </div>
      <div class="adm-card-body">
        <table class="adm-detail-table">
          <tr><th>Full Name</th><td><?= htmlspecialchars($fullName) ?></td></tr>
          <tr><th>Email</th><td><?= htmlspecialchars($row['acct_email'] ?? $row['email'] ?? '—') ?></td></tr>
          <tr><th>Username</th><td><?= htmlspecialchars($row['acct_username'] ?? $row['username'] ?? '—') ?></td></tr>
          <tr><th>Account Type</th><td><span class="adm-badge adm-badge-info">Administrator</span></td></tr>
          <tr><th>Status</th><td><span class="adm-badge adm-badge-success">Active</span></td></tr>
        </table>
      </div>
    </div>

    <div class="adm-card">
      <div class="adm-card-header">
        <h2 class="adm-card-title"><i class="ri-lock-password-line"></i> Change Password</h2>
      </div>
      <div class="adm-card-body">
        <form method="POST">
          <input type="hidden" name="change_password" value="1">
          <div class="adm-form-grid-2">
            <div class="adm-form-group">
              <label class="adm-label">New Password</label>
              <input class="adm-input" type="password" name="new_password" placeholder="••••••••" required />
            </div>
            <div class="adm-form-group">
              <label class="adm-label">Confirm Password</label>
              <input class="adm-input" type="password" name="confirm_password" placeholder="••••••••" required />
            </div>
          </div>
          <button type="submit" class="adm-btn adm-btn-primary"><i class="ri-save-3-line"></i> Update Password</button>
        </form>
      </div>
    </div>
  </div>

</div>

</div>
</div>
<?php include_once("./layout/footer.php"); ?>
