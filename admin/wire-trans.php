<?php include_once("./layout/header.php"); ?>

<div id="content" class="main-content">
<div class="layout-px-spacing">

<div class="adm-page-header">
  <div>
    <h1 class="adm-page-title">Wire Transactions</h1>
    <nav class="adm-breadcrumb"><a href="./dashboard.php">Dashboard</a> <span>/</span> <span>Wire Transfers</span></nav>
  </div>
  <a href="./transfer.php" class="adm-btn adm-btn-primary"><i class="ri-send-plane-line"></i> New Wire Transfer</a>
</div>

<div class="adm-card">
  <div class="adm-card-header">
    <h2 class="adm-card-title"><i class="ri-send-plane-line"></i> All Wire Transfer Records</h2>
  </div>
  <div class="adm-card-body">
    <div class="adm-table-wrap">
      <table id="default-ordering" class="table table-hover" style="width:100%">
        <thead>
          <tr>
            <th>S/N</th>
            <th>Sender</th>
            <th>Amount</th>
            <th>Bank Name</th>
            <th>Account Name</th>
            <th>Account No</th>
            <th>Country</th>
            <th>Status</th>
            <th>Date</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
        <?php
          $sql = "SELECT * FROM wire_transfer LEFT JOIN users ON wire_transfer.acct_id = users.id ORDER BY wire_transfer.wire_id DESC";
          $stmt = $conn->prepare($sql); $stmt->execute();
          $sn = 1;
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
            $currency = currency($row);
            $fullName = ucwords($row['firstname'].' '.$row['lastname']);
            $ws = $row['wire_status'];
            if ($ws == '0') { $badge = '<span class="adm-badge adm-badge-warning">Processing</span>'; }
            elseif ($ws == '1') { $badge = '<span class="adm-badge adm-badge-success">Approved</span>'; }
            elseif ($ws == '2') { $badge = '<span class="adm-badge adm-badge-info">On Hold</span>'; }
            else { $badge = '<span class="adm-badge adm-badge-danger">Cancelled</span>'; }
        ?>
        <tr>
          <td><?= $sn++ ?></td>
          <td style="font-weight:600"><?= htmlspecialchars($fullName) ?></td>
          <td style="font-weight:700"><?= htmlspecialchars($currency.$row['amount']) ?></td>
          <td style="font-size:.83rem"><?= htmlspecialchars($row['bank_name']) ?></td>
          <td style="font-size:.83rem"><?= htmlspecialchars($row['acct_name']) ?></td>
          <td><code style="font-size:.78rem;background:var(--adm-surface2);padding:2px 7px;border-radius:5px;border:1px solid var(--adm-border)"><?= htmlspecialchars($row['acct_number']) ?></code></td>
          <td style="font-size:.83rem"><?= htmlspecialchars($row['acct_country']) ?></td>
          <td><?= $badge ?></td>
          <td style="font-size:.78rem;color:var(--adm-text3)"><?= htmlspecialchars($row['created_at']) ?></td>
          <td><a href="./viewwire-trans.php?id=<?= htmlspecialchars($row['refrence_id']) ?>" class="adm-btn adm-btn-sm adm-btn-primary"><i class="ri-eye-line"></i> View</a></td>
        </tr>
        <?php endwhile; ?>
        </tbody>
        <tfoot>
          <tr><th>S/N</th><th>Sender</th><th>Amount</th><th>Bank</th><th>Account Name</th><th>Account No</th><th>Country</th><th>Status</th><th>Date</th><th></th></tr>
        </tfoot>
      </table>
    </div>
  </div>
</div>

</div>
</div>
<?php include_once("./layout/footer.php"); ?>
