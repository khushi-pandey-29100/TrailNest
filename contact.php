<?php
require_once 'includes/db.php';
require_once 'includes/booking_handler.php';

$errors = [];
$old = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = handle_booking($pdo, null);   // general enquiry, no trek
    $errors = $result['errors'];
    $old = $result['old'];
}
$flash = $_SESSION['flash_success'] ?? null;
unset($_SESSION['flash_success']);

$pageTitle = 'Contact';
$active = 'contact';
include 'includes/header.php';
?>

<section class="tn-page-head">
  <div class="container">
    <p class="tn-eyebrow">GET IN TOUCH</p>
    <h1>Not sure which trek yet?</h1>
    <p class="tn-lead-sm">Tell us your dates and fitness level, we'll point you at the right route.</p>
  </div>
</section>

<section class="tn-section pt-4">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-5">
        <h4>Talk to the booking desk</h4>
        <ul class="list-unstyled tn-contact-list">
          <li><i class="bi bi-telephone"></i> +91 91234 56780</li>
          <li><i class="bi bi-envelope"></i> trip@trailnest.example</li>
          <li><i class="bi bi-geo-alt"></i> Dehradun, Uttarakhand</li>
          <li><i class="bi bi-clock"></i> Mon to Sat, 9 am to 6 pm</li>
        </ul>
      </div>
      <div class="col-lg-7">
        <div class="tn-book-box" id="book">
          <h4>Send a message</h4>
          <?php if ($flash): ?><div class="tn-alert tn-alert-success"><?= e($flash) ?></div><?php endif; ?>
          <?php if ($errors): ?>
            <div class="tn-alert tn-alert-error"><ul class="mb-0 ps-3">
              <?php foreach ($errors as $err): ?><li><?= e($err) ?></li><?php endforeach; ?>
            </ul></div>
          <?php endif; ?>
          <form method="post" action="contact.php#book" novalidate>
            <?php render_booking_fields($old); ?>
            <button type="submit" class="tn-btn tn-btn-primary w-100">Send message</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
