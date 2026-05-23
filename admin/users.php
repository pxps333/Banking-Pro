<?php include_once("./layout/header.php"); ?>

<div id="content" class="main-content">
<div class="layout-px-spacing">

<div class="adm-page-header">
  <div>
    <h1 class="adm-page-title">All Users</h1>
    <nav class="adm-breadcrumb"><a href="./dashboard.php">Dashboard</a> <span>/</span> <span>Users</span></nav>
  </div>
  <a href="./reguser.php" class="adm-btn adm-btn-primary"><i class="ri-user-add-line"></i> New Account</a>
</div>

<div class="adm-card">
  <div class="adm-card-header">
    <h2 class="adm-card-title"><i class="ri-group-line"></i> User Accounts</h2>
  </div>
  <div class="adm-card-body">
    <div class="adm-table-wrap">
      <table id="default-ordering" class="table table-hover" style="width:100%">
        <thead>
          <tr>
            <th>S/N</th>
            <th>Name</th>
            <th>Account No</th>
            <th>Currency</th>
            <th>Type</th>
            <th>Status</th>
            <th>Email</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
        <?php
          $sql = "SELECT * FROM users ORDER BY id ASC";
          $stmt = $conn->prepare($sql); $stmt->execute();
          $sn = 1;
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
            $fullName = ucwords($row['firstname'].' '.$row['lastname']);
            $isActive = ($row['acct_status'] == '1' || strtolower($row['acct_status']) === 'active');
        ?>
        <tr>
          <td><?= $sn++ ?></td>
          <td><span style="font-weight:600"><?= htmlspecialchars($fullName) ?></span></td>
          <td><code style="font-size:.82rem;background:var(--adm-surface2);padding:2px 7px;border-radius:5px;border:1px solid var(--adm-border)"><?= htmlspecialchars($row['acct_no']) ?></code></td>
          <td><?= htmlspecialchars($row['acct_currency']) ?></td>
          <td><span class="adm-badge adm-badge-info"><?= htmlspecialchars($row['acct_type']) ?></span></td>
          <td><span class="adm-badge <?= $isActive ? 'adm-badge-success' : 'adm-badge-neutral' ?>"><?= $isActive ? 'Active' : htmlspecialchars($row['acct_status']) ?></span></td>
          <td style="font-size:.83rem;color:var(--adm-text2)"><?= htmlspecialchars($row['acct_email']) ?></td>
          <td><a href="./view_users.php?id=<?= $row['id'] ?>" class="adm-btn adm-btn-sm adm-btn-primary"><i class="ri-eye-line"></i> View</a></td>
        </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

</div>
</div>
<?php include_once("./layout/footer.php"); ?>
