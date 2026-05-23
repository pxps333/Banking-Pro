<?php
header('Content-Type: application/json');
require_once('../../session.php');
require_once('../../include/config.php');

if (!isset($_SESSION['acct_no'])) {
    echo json_encode(['error' => 'unauthenticated']);
    exit;
}

$conn = dbConnect();

$sql = "SELECT * FROM users WHERE acct_no = :acct_no";
$stmt = $conn->prepare($sql);
$stmt->execute([':acct_no' => $_SESSION['acct_no']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo json_encode(['error' => 'user_not_found']);
    exit;
}

$uid = $user['id'];
$since = isset($_GET['since']) ? (int)$_GET['since'] : 0;

$notifications = [];

// Approved transactions (trans_status = 1 = approved, trans_type = 1 = credit)
$sql = "SELECT trans_id AS id, 'transaction' AS type, amount, sender_name AS label,
               description, trans_type, EXTRACT(EPOCH FROM NOW())::int AS ts
        FROM transactions
        WHERE user_id = :uid AND trans_id > :since
        ORDER BY trans_id DESC LIMIT 10";
$stmt = $conn->prepare($sql);
$stmt->execute(['uid' => $uid, 'since' => $since]);
$txns = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($txns as $t) {
    $typeLabel = ($t['trans_type'] == 1) ? 'Credit' : 'Debit';
    $notifications[] = [
        'id'      => (int)$t['id'],
        'type'    => $typeLabel,
        'amount'  => (float)$t['amount'],
        'label'   => $t['label'] ?: $t['description'],
        'message' => $t['description'],
        'ts'      => (int)$t['ts'],
    ];
}

// Approved deposits (crypto_status = 1)
$sql = "SELECT d_id AS id, 'Deposit' AS type, amount,
               'Crypto Deposit' AS label, '' AS message,
               EXTRACT(EPOCH FROM created_at)::int AS ts
        FROM deposit
        WHERE user_id = :uid AND crypto_status = 1
              AND EXTRACT(EPOCH FROM created_at)::int > :since
        ORDER BY d_id DESC LIMIT 5";
$stmt = $conn->prepare($sql);
$stmt->execute(['uid' => $uid, 'since' => $since]);
$deps = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($deps as $d) {
    $notifications[] = [
        'id'      => (int)$d['id'] + 90000,
        'type'    => 'Deposit',
        'amount'  => (float)$d['amount'],
        'label'   => 'Crypto Deposit Approved',
        'message' => 'Your deposit has been approved',
        'ts'      => (int)$d['ts'],
    ];
}

// Approved wire transfers (wire_status = 1)
$sql = "SELECT wire_id AS id, 'Wire Transfer' AS type, amount,
               bank_name AS label, acct_name AS message,
               EXTRACT(EPOCH FROM \"createdAt\")::int AS ts
        FROM wire_transfer
        WHERE acct_id = :uid AND wire_status = 1
              AND EXTRACT(EPOCH FROM \"createdAt\")::int > :since
        ORDER BY wire_id DESC LIMIT 5";
$stmt = $conn->prepare($sql);
$stmt->execute(['uid' => $uid, 'since' => $since]);
$wires = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($wires as $w) {
    $notifications[] = [
        'id'      => (int)$w['id'] + 80000,
        'type'    => 'Wire Transfer',
        'amount'  => (float)$w['amount'],
        'label'   => 'Wire Transfer Approved',
        'message' => 'Transfer to ' . ($w['label'] ?: 'beneficiary') . ' was approved',
        'ts'      => (int)$w['ts'],
    ];
}

// Approved domestic transfers (dom_status = 1)
$sql = "SELECT dom_id AS id, 'Domestic Transfer' AS type, amount,
               bank_name AS label, acct_name AS message,
               EXTRACT(EPOCH FROM created_at)::int AS ts
        FROM domestic_transfer
        WHERE acct_id = :uid AND dom_status = 1
              AND EXTRACT(EPOCH FROM created_at)::int > :since
        ORDER BY dom_id DESC LIMIT 5";
$stmt = $conn->prepare($sql);
$stmt->execute(['uid' => $uid, 'since' => $since]);
$doms = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($doms as $d) {
    $notifications[] = [
        'id'      => (int)$d['id'] + 70000,
        'type'    => 'Domestic Transfer',
        'amount'  => (float)$d['amount'],
        'label'   => 'Domestic Transfer Approved',
        'message' => 'Transfer to ' . ($d['label'] ?: 'beneficiary') . ' was approved',
        'ts'      => (int)$d['ts'],
    ];
}

echo json_encode([
    'notifications' => $notifications,
    'count'         => count($notifications),
    'timestamp'     => time(),
]);
