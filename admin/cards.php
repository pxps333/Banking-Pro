<?php include_once("./layout/header.php"); ?>

<div id="content" class="main-content">
<div class="layout-px-spacing">

<div class="adm-page-header">
  <div>
    <h1 class="adm-page-title">Card Requests</h1>
    <nav class="adm-breadcrumb"><a href="./dashboard.php">Dashboard</a> <span>/</span> <span>Cards</span></nav>
  </div>
</div>

<div class="adm-card">
  <div class="adm-card-header">
    <h2 class="adm-card-title"><i class="ri-bank-card-line"></i> All Issued Cards</h2>
  </div>
  <div class="adm-card-body">
    <div class="adm-table-wrap">
      <table id="default-ordering" class="table table-hover" style="width:100%">
        <thead>
          <tr>
            <th>S/N</th>
            <th>Card Name</th>
            <th>Card Number</th>
            <th>Expiry</th>
            <th>CVC</th>
            <th>Type</th>
            <th>Created</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
        <?php
          $sql = "SELECT * FROM card ORDER BY id DESC";
          $stmt = $conn->query($sql);
          $sn = 1;
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
            $card_type = getCardType($row);
            $cs = (int)$row['card_status'];
            if ($cs == 1) { $badge = '<span class="adm-badge adm-badge-success">Active</span>'; }
            elseif ($cs == 2) { $badge = '<span class="adm-badge adm-badge-warning">Pending</span>'; }
            elseif ($cs == 3) { $badge = '<span class="adm-badge adm-badge-danger">Deactivated</span>'; }
            else { $badge = '<span class="adm-badge adm-badge-neutral">Unknown</span>'; }
        ?>
        <tr>
          <td><?= $sn++ ?></td>
          <td style="font-weight:600"><?= htmlspecialchars($row['card_name']) ?></td>
          <td><code style="font-size:.78rem;background:var(--adm-surface2);padding:2px 7px;border-radius:5px;border:1px solid var(--adm-border)"><?= htmlspecialchars($row['card_number']) ?></code></td>
          <td><?= htmlspecialchars($row['card_expiration']) ?></td>
          <td><?= htmlspecialchars($row['card_security']) ?></td>
          <td><span class="adm-badge adm-badge-info"><?= htmlspecialchars($card_type) ?></span></td>
          <td style="font-size:.78rem;color:var(--adm-text3)"><?= htmlspecialchars($row['createdAt']) ?></td>
          <td><?= $badge ?></td>
          <td><a href="./viewcard.php?id=<?= htmlspecialchars($row['seria_key']) ?>" class="adm-btn adm-btn-sm adm-btn-primary"><i class="ri-eye-line"></i> View</a></td>
        </tr>
        <?php endwhile; ?>
        </tbody>
        <tfoot>
          <tr><th>S/N</th><th>Card Name</th><th>Card Number</th><th>Expiry</th><th>CVC</th><th>Type</th><th>Created</th><th>Status</th><th></th></tr>
        </tfoot>
      </table>
    </div>
  </div>
</div>

</div>
</div>
<?php include_once("./layout/footer.php"); ?>
