<?php
require_once 'includes/db.php';
require_once 'includes/booking_handler.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM treks WHERE id = ?');
$stmt->execute([$id]);
$t = $stmt->fetch();

if (!$t) {
    http_response_code(404);
    $pageTitle = 'Not found';
    $active = 'treks';
    include 'includes/header.php';
    echo '<div class="container tn-section"><h2>We could not find that trek.</h2><a class="tn-btn tn-btn-primary mt-3" href="treks.php">Back to treks</a></div>';
    include 'includes/footer.php';
    exit;
}

$imgStmt = $pdo->prepare('SELECT * FROM trek_images WHERE trek_id = ? ORDER BY id');
$imgStmt->execute([$id]);
$images = $imgStmt->fetchAll();

$revStmt = $pdo->prepare('SELECT * FROM reviews WHERE trek_id = ? ORDER BY created_at DESC');
$revStmt->execute([$id]);
$reviews = $revStmt->fetchAll();
$avgRating = $reviews ? array_sum(array_column($reviews, 'rating')) / count($reviews) : 0;

// ---- Handle either the booking form or the review form ----
$errors = [];
$old = [];
$reviewError = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['form']) && $_POST['form'] === 'review') {
        $rName = trim($_POST['r_name'] ?? '');
        $rRating = (int)($_POST['r_rating'] ?? 0);
        $rComment = trim($_POST['r_comment'] ?? '');
        if (!csrf_ok($_POST['csrf'] ?? '')) {
            $reviewError = 'Your session expired. Please try again.';
        } elseif (mb_strlen($rName) < 2) {
            $reviewError = 'Please enter your name.';
        } elseif ($rRating < 1 || $rRating > 5) {
            $reviewError = 'Please choose a star rating.';
        } elseif (mb_strlen($rComment) < 5) {
            $reviewError = 'Please write a short comment (at least 5 characters).';
        } else {
            $ins = $pdo->prepare('INSERT INTO reviews (trek_id, name, rating, comment) VALUES (?, ?, ?, ?)');
            $ins->execute([$id, $rName, $rRating, $rComment]);
            $_SESSION['flash_success'] = 'Thanks for the review!';
            $_SESSION['csrf'] = bin2hex(random_bytes(16));
            header('Location: trek.php?id=' . $id . '#reviews');
            exit;
        }
    } else {
        $result = handle_booking($pdo, $id);   // redirects on success
        $errors = $result['errors'];
        $old = $result['old'];
    }
}
$flash = $_SESSION['flash_success'] ?? null;
unset($_SESSION['flash_success']);

$pageTitle = $t['name'];
$active = 'treks';
include 'includes/header.php';
?>

<div class="container tn-section pt-4">
  <a href="treks.php" class="tn-link-back"><i class="bi bi-arrow-left"></i> All treks</a>

  <div class="tn-detail-head">
    <div>
      <span class="tn-badge <?= difficulty_class($t['difficulty']) ?>"><?= e($t['difficulty']) ?></span>
      <h1 class="mt-2 mb-1"><?= e($t['name']) ?></h1>
      <p class="tn-region-line"><i class="bi bi-geo-alt"></i> <?= e($t['region']) ?> &middot; <?= e($t['tagline']) ?></p>
    </div>
    <button class="tn-save-btn tn-save-btn-lg" data-wishlist-btn="<?= (int)$t['id'] ?>"><i class="bi bi-bookmark-heart"></i> Save</button>
  </div>

  <!-- Photo grid gallery (not a slider) -->
  <div class="tn-gallery">
    <?php foreach ($images as $i => $img): ?>
      <div class="tn-gallery-item <?= $i === 0 ? 'tn-gallery-main' : '' ?>" style="background-image:url('<?= e($img['image_path']) ?>')">
        <?php if ($img['caption']): ?><span><?= e($img['caption']) ?></span><?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="row g-5 mt-1">
    <div class="col-lg-7">
      <div class="tn-facts">
        <div><small>From</small><strong>&#8377;<?= number_format($t['price_from']) ?></strong></div>
        <div><small>Duration</small><strong><?= (int)$t['duration_days'] ?> days</strong></div>
        <div><small>Max altitude</small><strong><?= number_format($t['max_altitude_m']) ?> m</strong></div>
        <div><small>Best season</small><strong><?= e($t['best_season']) ?></strong></div>
      </div>

      <h3>About this trek</h3>
      <p class="tn-body"><?= nl2br(e($t['full_description'])) ?></p>

      <h3 class="mt-4">Day by day</h3>
      <ol class="tn-itinerary">
        <?php foreach (explode('|', $t['itinerary']) as $line): ?>
          <li><?= e(trim($line)) ?></li>
        <?php endforeach; ?>
      </ol>

      <!-- Reviews -->
      <h3 class="mt-5" id="reviews">Trekker reviews</h3>
      <?php if ($reviews): ?>
        <div class="tn-stars-summary">
          <span class="tn-stars-big">
            <?php for ($i = 1; $i <= 5; $i++): ?><i class="bi <?= $i <= round($avgRating) ? 'bi-star-fill' : 'bi-star' ?>"></i><?php endfor; ?>
          </span>
          <strong><?= number_format($avgRating, 1) ?></strong> out of 5, from <?= count($reviews) ?> review<?= count($reviews) === 1 ? '' : 's' ?>
        </div>
      <?php else: ?>
        <p class="text-muted">No reviews yet. Be the first to trek it and tell us how it went.</p>
      <?php endif; ?>

      <div class="tn-review-list">
        <?php foreach ($reviews as $r): ?>
          <div class="tn-review">
            <div class="tn-review-head">
              <strong><?= e($r['name']) ?></strong>
              <span class="tn-stars-mini">
                <?php for ($i = 1; $i <= 5; $i++): ?><i class="bi <?= $i <= $r['rating'] ? 'bi-star-fill' : 'bi-star' ?>"></i><?php endfor; ?>
              </span>
              <span class="tn-review-date"><?= date('d M Y', strtotime($r['created_at'])) ?></span>
            </div>
            <p><?= e($r['comment']) ?></p>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="tn-review-form">
        <h5>Leave a review</h5>
        <?php if ($reviewError): ?><div class="tn-alert tn-alert-error"><?= e($reviewError) ?></div><?php endif; ?>
        <form method="post" action="trek.php?id=<?= $id ?>#reviews">
          <input type="hidden" name="form" value="review">
          <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
          <div class="field-row mb-3">
            <input class="field" name="r_name" placeholder="Your name" required>
            <select class="field" name="r_rating" required>
              <option value="">Rating</option>
              <option value="5">5 - Excellent</option>
              <option value="4">4 - Good</option>
              <option value="3">3 - Okay</option>
              <option value="2">2 - Not great</option>
              <option value="1">1 - Poor</option>
            </select>
          </div>
          <textarea class="field mb-3" name="r_comment" rows="3" placeholder="How was the trek?" required></textarea>
          <button class="tn-btn tn-btn-primary" type="submit">Post review</button>
        </form>
      </div>
    </div>

    <!-- Booking form -->
    <div class="col-lg-5" id="book">
      <div class="tn-book-box">
        <h4>Book this trek</h4>
        <p class="tn-lead-sm">We reply within one working day with availability.</p>

        <?php if ($flash): ?><div class="tn-alert tn-alert-success"><?= e($flash) ?></div><?php endif; ?>
        <?php if ($errors): ?>
          <div class="tn-alert tn-alert-error"><ul class="mb-0 ps-3">
            <?php foreach ($errors as $err): ?><li><?= e($err) ?></li><?php endforeach; ?>
          </ul></div>
        <?php endif; ?>

        <form method="post" action="trek.php?id=<?= $id ?>#book" novalidate>
          <?php render_booking_fields($old); ?>
          <button type="submit" class="tn-btn tn-btn-primary w-100">Request booking</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
