<?php
require_once __DIR__ . '/../includes/db.php';

if (isset($_GET['logout'])) {
    unset($_SESSION['admin']);
    header('Location: index.php');
    exit;
}

$loginError = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
    if (hash_equals(ADMIN_PASSWORD, $_POST['password'])) {
        $_SESSION['admin'] = true;
        header('Location: index.php');
        exit;
    }
    $loginError = 'Wrong password.';
}
$loggedIn = !empty($_SESSION['admin']);

$rows = [];
if ($loggedIn) {
    $rows = $pdo->query(
        'SELECT b.*, t.name AS trek
         FROM bookings b LEFT JOIN treks t ON t.id = b.trek_id
         ORDER BY b.created_at DESC'
    )->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Bookings | <?= e(SITE_NAME) ?> staff</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>body{background:#12181b}</style>
</head>
<body class="bg-dark text-light">
<div class="container py-5">
<?php if (!$loggedIn): ?>
  <div class="mx-auto" style="max-width:360px">
    <h3>Staff login</h3>
    <?php if ($loginError): ?><div class="alert alert-danger"><?= e($loginError) ?></div><?php endif; ?>
    <form method="post">
      <input type="password" name="password" class="form-control mb-3" placeholder="Password" required autofocus>
      <button class="btn btn-warning w-100">Log in</button>
    </form>
    <a href="../index.php" class="d-block mt-3 text-light">Back to site</a>
  </div>
<?php else: ?>
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Bookings (<?= count($rows) ?>)</h3>
    <div>
      <a href="../index.php" class="btn btn-outline-light btn-sm">View site</a>
      <a href="?logout=1" class="btn btn-warning btn-sm">Log out</a>
    </div>
  </div>
  <div class="table-responsive bg-black border border-secondary rounded">
    <table class="table table-dark table-hover align-middle mb-0">
      <thead><tr><th>#</th><th>Received</th><th>Trek</th><th>Name</th><th>Email</th><th>Phone</th><th>Group</th><th>Start date</th><th>Pickup</th><th>Message</th></tr></thead>
      <tbody>
      <?php foreach ($rows as $r): ?>
        <tr>
          <td><?= (int)$r['id'] ?></td>
          <td><?= e($r['created_at']) ?></td>
          <td><?= e($r['trek'] ?? 'General') ?></td>
          <td><?= e($r['name']) ?></td>
          <td><a href="mailto:<?= e($r['email']) ?>" class="text-warning"><?= e($r['email']) ?></a></td>
          <td><?= e($r['phone']) ?></td>
          <td><?= (int)$r['participants'] ?></td>
          <td><?= e($r['start_date'] ?? '-') ?></td>
          <td><?= e($r['pickup_point'] ?: '-') ?></td>
          <td style="max-width:260px"><?= e($r['message']) ?></td>
        </tr>
      <?php endforeach; ?>
      <?php if (!$rows): ?><tr><td colspan="10" class="text-center text-muted py-4">No bookings yet.</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>
</div>
</body>
</html>
