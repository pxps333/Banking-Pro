<?php include_once("./layout/header.php"); ?>

<div id="content" class="main-content">
<div class="layout-px-spacing">

<div class="adm-page-header">
  <div>
    <h1 class="adm-page-title">Domestic Transactions</h1>
    <nav class="adm-breadcrumb"><a href="./dashboard.php">Dashboard</a> <span>/</span> <span>Domestic Transfers</span></nav>
  </div>
</div>

<div class="adm-card">
  <div class="adm-card-header">
    <h2 class="adm-card-title"><i class="ri-exchange-line"></i> All Domestic Transfer Records</h2>
  </div>
  <div class="adm-card-body">
    <div class="adm-table-wrap">
      <table id="default-ordering" class="table table-hover" style="width:100%">
        <thead>
          <tr>
            <th>S/N</th>
            <th>Amount</th>
            <th>Bank Name</th>
            <th>Account Name</th>
            <th>Account No</th>
            <th>Account Type</th>
            <th>Trans Type</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
        <?php
          $sql = "SELECT * FROM domestic_transfer LEFT JOIN users ON domestic_transfer.acct_id = users.id ORDER BY domestic_transfer.dom_id DESC";
          $stmt = $conn->prepare($sql); $stmt->execute();
          $sn = 1;
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
            $currency = currency($row);
            $ds = $row['dom_status'];
            if ($ds == '0') { $badge = '<span class="adm-badge adm-badge-warning">Processing</span>'; }
            elseif ($ds == '1') { $badge = '<span class="adm-badge adm-badge-success">Approved</span>'; }
            elseif ($ds == '2') { $badge = '<span class="adm-badge adm-badge-info">On Hold</span>'; }
            else { $badge = '<span class="adm-badge adm-badge-danger">Cancelled</span>'; }
        ?>
        <tr>
          <td><?= $sn++ ?></td>
          <td style="font-weight:700"><?= htmlspecialchars($currency.$row['amount']) ?></td>
          <td style="font-size:.83rem"><?= htmlspecialchars($row['bank_name']) ?></td>
          <td style="font-size:.83rem"><?= htmlspecialchars($row['acct_name']) ?></td>
          <td><code style="font-size:.78rem;background:var(--adm-surface2);padding:2px 6px;border-radius:5px;border:1px solid var(--adm-border)"><?= htmlspecialchars($row['acct_number']) ?></code></td>
          <td style="font-size:.83rem"><?= htmlspecialchars($row['acct_type']) ?></td>
          <td style="font-size:.83rem"><?= htmlspecialchars(ucwords($row['trans_type'])) ?></td>
          <td><?= $badge ?></td>
          <td><a href="./view-domtrans.php?id=<?= htmlspecialchars($row['refrence_id']) ?>" class="adm-btn adm-btn-sm adm-btn-primary"><i class="ri-eye-line"></i> View</a></td>
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
