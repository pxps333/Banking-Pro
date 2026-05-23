<?php include_once("./layout/header.php"); ?>

<div id="content" class="main-content">
<div class="layout-px-spacing">

<div class="adm-page-header">
  <div>
    <h1 class="adm-page-title">Loan Requests</h1>
    <nav class="adm-breadcrumb"><a href="./dashboard.php">Dashboard</a> <span>/</span> <span>Loan Requests</span></nav>
  </div>
</div>

<div class="adm-card">
  <div class="adm-card-header">
    <h2 class="adm-card-title"><i class="ri-bank-line"></i> All Loan Requests</h2>
  </div>
  <div class="adm-card-body">
    <div class="adm-table-wrap">
      <table id="default-ordering" class="table table-hover" style="width:100%">
        <thead>
          <tr>
            <th>S/N</th>
            <th>Applicant</th>
            <th>Amount</th>
            <th>Remarks</th>
            <th>Status</th>
            <th>Date</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
        <?php
          $sql = "SELECT * FROM loan LEFT JOIN users ON loan.acct_id = users.id ORDER BY loan.loan_id DESC";
          $stmt = $conn->prepare($sql); $stmt->execute();
          $sn = 1;
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
            $currency = currency($row);
            $fullName = ucwords($row['firstname'].' '.$row['lastname']);
            $ls = $row['loan_status'];
            if ($ls == '0') { $badge = '<span class="adm-badge adm-badge-warning">Processing</span>'; }
            elseif ($ls == '1') { $badge = '<span class="adm-badge adm-badge-success">Approved</span>'; }
            elseif ($ls == '2') { $badge = '<span class="adm-badge adm-badge-info">On Hold</span>'; }
            else { $badge = '<span class="adm-badge adm-badge-danger">Declined</span>'; }
        ?>
        <tr>
          <td><?= $sn++ ?></td>
          <td style="font-weight:600"><?= htmlspecialchars($fullName) ?></td>
          <td style="font-weight:700"><?= htmlspecialchars($currency.$row['amount']) ?></td>
          <td style="font-size:.83rem;max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= htmlspecialchars($row['loan_remarks']) ?></td>
          <td><?= $badge ?></td>
          <td style="font-size:.78rem;color:var(--adm-text3)"><?= htmlspecialchars($row['created_at']) ?></td>
          <td><a href="./viewloan-trans.php?id=<?= htmlspecialchars($row['loan_reference_id']) ?>" class="adm-btn adm-btn-sm adm-btn-primary"><i class="ri-eye-line"></i> View</a></td>
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
