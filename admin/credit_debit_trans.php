<?php include_once("./layout/header.php"); ?>

<div id="content" class="main-content">
<div class="layout-px-spacing">

<div class="adm-page-header">
  <div>
    <h1 class="adm-page-title">Credit / Debit Transactions</h1>
    <nav class="adm-breadcrumb"><a href="./dashboard.php">Dashboard</a> <span>/</span> <span>Transactions</span></nav>
  </div>
  <a href="./funduser.php" class="adm-btn adm-btn-primary"><i class="ri-funds-line"></i> Fund User</a>
</div>

<div class="adm-card">
  <div class="adm-card-header">
    <h2 class="adm-card-title"><i class="ri-exchange-funds-line"></i> All Credit / Debit Records</h2>
  </div>
  <div class="adm-card-body">
    <div class="adm-table-wrap">
      <table id="default-ordering" class="table table-hover" style="width:100%">
        <thead>
          <tr>
            <th>S/N</th>
            <th>Name</th>
            <th>Amount</th>
            <th>Type</th>
            <th>Sender</th>
            <th>Date</th>
            <th>Time</th>
            <th>Edit</th>
            <th>View</th>
          </tr>
        </thead>
        <tbody>
        <?php
          $sql = "SELECT * FROM transactions LEFT JOIN users ON transactions.user_id = users.id ORDER BY transactions.trans_id DESC";
          $stmt = $conn->prepare($sql); $stmt->execute();
          $sn = 1;
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
            $currency = currency($row);
            $fullName = ucwords($row['firstname'].' '.$row['lastname']);
            $isCredit = ($row['trans_type'] === '1');
        ?>
        <tr>
          <td><?= $sn++ ?></td>
          <td style="font-weight:600"><?= htmlspecialchars($fullName) ?></td>
          <td style="font-weight:700"><?= htmlspecialchars($currency.$row['amount']) ?></td>
          <td>
            <?php if ($isCredit): ?>
              <span class="adm-badge adm-badge-success"><i class="ri-arrow-down-line"></i> Credit</span>
            <?php else: ?>
              <span class="adm-badge adm-badge-danger"><i class="ri-arrow-up-line"></i> Debit</span>
            <?php endif; ?>
          </td>
          <td style="font-size:.83rem"><?= htmlspecialchars($row['sender_name']) ?></td>
          <td style="font-size:.83rem;color:var(--adm-text2)"><?= htmlspecialchars($row['created_at']) ?></td>
          <td style="font-size:.83rem;color:var(--adm-text2)"><?= htmlspecialchars($row['time_created']) ?></td>
          <td><a href="./edit-trans.php?id=<?= htmlspecialchars($row['trans_id']) ?>" class="adm-btn adm-btn-sm adm-btn-outline"><i class="ri-edit-line"></i> Edit</a></td>
          <td><a href="./view-trans.php?id=<?= htmlspecialchars($row['trans_id']) ?>" class="adm-btn adm-btn-sm adm-btn-primary"><i class="ri-eye-line"></i> View</a></td>
        </tr>
        <?php endwhile; ?>
        </tbody>
        <tfoot>
          <tr><th>S/N</th><th>Name</th><th>Amount</th><th>Type</th><th>Sender</th><th>Date</th><th>Time</th><th>Edit</th><th>View</th></tr>
        </tfoot>
      </table>
    </div>
  </div>
</div>

</div>
</div>
<?php include_once("./layout/footer.php"); ?>
