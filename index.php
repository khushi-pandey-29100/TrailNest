<?php
require_once 'includes/db.php';
$pageTitle = 'Home';
$active = 'home';
$featured = $pdo->query(
    "SELECT t.*, AVG(r.rating) AS avg_rating, COUNT(r.id) AS review_count
     FROM treks t LEFT JOIN reviews r ON r.trek_id = t.id
     GROUP BY t.id ORDER BY t.id LIMIT 3"
)->fetchAll();
$count = (int)$pdo->query('SELECT COUNT(*) FROM treks')->fetchColumn();
include 'includes/header.php';
?>

<section class="tn-hero">
  <div class="tn-hero-bg" style="background-image:url('assets/images/everest-1.jpg')"></div>
  <div class="container tn-hero-inner">
    <p class="tn-eyebrow">HIMALAYA &middot; SMALL GROUPS &middot; LOCAL GUIDES</p>
    <h1>The trail doesn't<br>care about your excuses.</h1>
    <p class="tn-lead">Neither do we, gently. <?= $count ?> routes across India and Nepal, real trekker reviews on every page, and a booking form that takes two minutes.</p>
    <div class="d-flex gap-3 flex-wrap">
      <a href="treks.php" class="tn-btn tn-btn-primary">See all treks</a>
      <a href="treks.php?difficulty=Easy" class="tn-btn tn-btn-ghost">I'm a beginner</a>
    </div>
  </div>
</section>

<section class="tn-section">
  <div class="container">
    <div class="tn-section-head">
      <div>
        <p class="tn-eyebrow">FEATURED</p>
        <h2>Where people are headed this season</h2>
      </div>
      <a href="treks.php" class="tn-link">All treks <i class="bi bi-arrow-right"></i></a>
    </div>
    <div class="tn-grid">
      <?php foreach ($featured as $t): ?>
        <?php include 'includes/trek_card.php'; ?>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="tn-section tn-section-dark">
  <div class="container">
    <div class="row g-5 align-items-center">
      <div class="col-lg-5">
        <p class="tn-eyebrow">WHY TRAILNEST</p>
        <h2>We only run trips we'd take ourselves</h2>
        <p class="tn-lead-sm">Every route on this site has a lead guide who has walked it more times than they can count, a hard cap on group size, and real reviews left by past trekkers, not curated ones.</p>
      </div>
      <div class="col-lg-7">
        <div class="tn-stat-grid">
          <div class="tn-stat"><strong>12</strong><span>years running Himalayan treks</span></div>
          <div class="tn-stat"><strong>1:8</strong><span>guide-to-trekker ratio, max</span></div>
          <div class="tn-stat"><strong>4.7</strong><span>average trip rating</span></div>
          <div class="tn-stat"><strong>24 hr</strong><span>booking reply time</span></div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
