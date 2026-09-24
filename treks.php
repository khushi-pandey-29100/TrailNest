<?php
require_once 'includes/db.php';
$pageTitle = 'Treks';
$active = 'treks';

$allowed = ['Easy', 'Moderate', 'Difficult'];
$difficulty = $_GET['difficulty'] ?? '';
if (!in_array($difficulty, $allowed, true)) $difficulty = '';

$sql = "SELECT t.*, AVG(r.rating) AS avg_rating, COUNT(r.id) AS review_count
        FROM treks t LEFT JOIN reviews r ON r.trek_id = t.id";
$params = [];
if ($difficulty !== '') {
    $sql .= " WHERE t.difficulty = ?";
    $params[] = $difficulty;
}
$sql .= " GROUP BY t.id ORDER BY t.name";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$treks = $stmt->fetchAll();

include 'includes/header.php';
?>

<section class="tn-page-head">
  <div class="container">
    <p class="tn-eyebrow">ALL ROUTES</p>
    <h1>Pick a trail</h1>
    <p class="tn-lead-sm">Filter by difficulty, or jump to your saved list.</p>
  </div>
</section>

<section class="tn-section pt-4">
  <div class="container">
    <div class="tn-filters">
      <a href="treks.php" class="<?= $difficulty === '' ? 'active' : '' ?>">All</a>
      <a href="treks.php?difficulty=Easy" class="<?= $difficulty === 'Easy' ? 'active' : '' ?>">Easy</a>
      <a href="treks.php?difficulty=Moderate" class="<?= $difficulty === 'Moderate' ? 'active' : '' ?>">Moderate</a>
      <a href="treks.php?difficulty=Difficult" class="<?= $difficulty === 'Difficult' ? 'active' : '' ?>">Difficult</a>
    </div>

    <?php if (!$treks): ?>
      <p class="text-muted mt-4">No treks match that filter yet.</p>
    <?php endif; ?>

    <div class="tn-grid mt-2">
      <?php foreach ($treks as $t): ?>
        <?php include 'includes/trek_card.php'; ?>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="tn-section tn-section-dark" id="wishlist">
  <div class="container">
    <p class="tn-eyebrow">YOUR LIST</p>
    <h2 class="mb-4">Saved for later</h2>
    <p id="wishlistEmpty" class="tn-lead-sm">Nothing saved yet. Tap "Save" on any trek card above, it's stored in this browser.</p>
    <div class="tn-grid">
      <?php
      $all = $pdo->query(
          "SELECT t.*, AVG(r.rating) AS avg_rating, COUNT(r.id) AS review_count
           FROM treks t LEFT JOIN reviews r ON r.trek_id = t.id GROUP BY t.id ORDER BY t.name"
      )->fetchAll();
      foreach ($all as $t): ?>
        <?php include 'includes/trek_card.php'; ?>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<script>
  // Cards inside #wishlist start hidden until wishlist.js reveals the saved ones.
  document.querySelectorAll('#wishlist [data-wishlist-card]').forEach(c => c.style.display = 'none');
</script>

<?php include 'includes/footer.php'; ?>
