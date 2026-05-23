<?php include_once("./layout/header.php"); ?>

<div id="content" class="main-content">
<div class="layout-px-spacing">

<div class="adm-page-header">
  <div>
    <h1 class="adm-page-title">Withdrawal Transactions</h1>
    <nav class="adm-breadcrumb"><a href="./dashboard.php">Dashboard</a> <span>/</span> <span>Withdrawals</span></nav>
  </div>
</div>

<div class="adm-card">
  <div class="adm-card-header">
    <h2 class="adm-card-title"><i class="ri-arrow-up-circle-line"></i> All Withdrawal Records</h2>
  </div>
  <div class="adm-card-body">
    <div class="adm-table-wrap">
      <table id="default-ordering" class="table table-hover" style="width:100%">
        <thead>
          <tr>
            <th>S/N</th>
            <th>Name</th>
            <th>Email</th>
            <th>Reference</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Date</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
        <?php
          $sql = "SELECT * FROM withdrawal w LEFT JOIN users u ON w.user_id = u.id ORDER BY w.id DESC";
          $stmt = $conn->prepare($sql); $stmt->execute();
          $sn = 1;
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
            $currency = currency($row);
            $fullName = ucwords($row['firstname'].' '.$row['lastname']);
            $ws = $row['status'];
            if ($ws == '0') { $badge = '<span class="adm-badge adm-badge-warning">Processing</span>'; }
            elseif ($ws == '1') { $badge = '<span class="adm-badge adm-badge-success">Approved</span>'; }
            elseif ($ws == '2') { $badge = '<span class="adm-badge adm-badge-info">On Hold</span>'; }
            else { $badge = '<span class="adm-badge adm-badge-danger">Cancelled</span>'; }
        ?>
        <tr>
          <td><?= $sn++ ?></td>
          <td style="font-weight:600"><?= htmlspecialchars($fullName) ?></td>
          <td style="font-size:.83rem;color:var(--adm-text2)"><?= htmlspecialchars($row['acct_email']) ?></td>
          <td><code style="font-size:.75rem;background:var(--adm-surface2);padding:2px 6px;border-radius:5px;border:1px solid var(--adm-border)"><?= htmlspecialchars($row['reference_id']) ?></code></td>
          <td style="font-weight:700"><?= htmlspecialchars($currency.$row['amount']) ?></td>
          <td><?= $badge ?></td>
          <td style="font-size:.78rem;color:var(--adm-text3)"><?= htmlspecialchars($row['createdAt']) ?></td>
          <td><a href="./viewwithdraw.php?id=<?= htmlspecialchars($row['reference_id']) ?>" class="adm-btn adm-btn-sm adm-btn-primary"><i class="ri-eye-line"></i> View</a></td>
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
