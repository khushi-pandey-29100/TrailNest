<?php
/**
 * Shared booking logic used by trek.php and contact.php.
 * Call handle_booking($pdo, $trekId) when a POST arrives.
 */
function handle_booking(PDO $pdo, $trekId = null) {
    $old = [
        'name' => trim($_POST['name'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'phone' => trim($_POST['phone'] ?? ''),
        'participants' => (int)($_POST['participants'] ?? 1),
        'start_date' => trim($_POST['start_date'] ?? ''),
        'pickup_point' => trim($_POST['pickup_point'] ?? ''),
        'message' => trim($_POST['message'] ?? ''),
    ];
    $errors = [];

    if (!csrf_ok($_POST['csrf'] ?? '')) {
        $errors[] = 'Your session expired. Please try again.';
    }
    if (mb_strlen($old['name']) < 2) {
        $errors[] = 'Please enter your full name.';
    }
    if (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }
    if (!preg_match('/^[0-9+\-\s]{7,15}$/', $old['phone'])) {
        $errors[] = 'Please enter a valid phone number.';
    }
    if ($old['participants'] < 1 || $old['participants'] > 30) {
        $errors[] = 'Group size must be between 1 and 30.';
    }
    $date = null;
    if ($old['start_date'] !== '') {
        $d = DateTime::createFromFormat('Y-m-d', $old['start_date']);
        if (!$d || $d->format('Y-m-d') !== $old['start_date']) {
            $errors[] = 'Please choose a valid start date.';
        } else {
            $date = $old['start_date'];
        }
    }

    if (!$errors) {
        $stmt = $pdo->prepare(
            'INSERT INTO bookings (trek_id, name, email, phone, participants, start_date, pickup_point, message)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $trekId, $old['name'], $old['email'], $old['phone'],
            $old['participants'], $date, $old['pickup_point'], $old['message'],
        ]);
        $_SESSION['flash_success'] = 'Thanks, ' . $old['name'] . '. Your booking request is in, we reply within one working day.';
        $_SESSION['csrf'] = bin2hex(random_bytes(16));
        header('Location: ' . $_SERVER['REQUEST_URI'] . '#book');
        exit;
    }
    return ['errors' => $errors, 'old' => $old];
}

function render_booking_fields($old = []) {
    $v = function ($k, $default = '') use ($old) { return e($old[$k] ?? $default); };
    ?>
    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
    <div class="mb-3">
      <label class="field-label" for="name">Full name</label>
      <input class="field" id="name" name="name" required value="<?= $v('name') ?>">
    </div>
    <div class="mb-3">
      <label class="field-label" for="email">Email</label>
      <input class="field" type="email" id="email" name="email" required value="<?= $v('email') ?>">
    </div>
    <div class="mb-3">
      <label class="field-label" for="phone">Phone</label>
      <input class="field" type="tel" id="phone" name="phone" required value="<?= $v('phone') ?>">
    </div>
    <div class="field-row mb-3">
      <div>
        <label class="field-label" for="participants">Group size</label>
        <input class="field" type="number" min="1" max="30" id="participants" name="participants" value="<?= $v('participants', '2') ?>">
      </div>
      <div>
        <label class="field-label" for="start_date">Start date</label>
        <input class="field" type="date" id="start_date" name="start_date" min="<?= date('Y-m-d') ?>" value="<?= $v('start_date') ?>">
      </div>
    </div>
    <div class="mb-3">
      <label class="field-label" for="pickup_point">Pickup point</label>
      <input class="field" id="pickup_point" name="pickup_point" placeholder="e.g. Dehradun railway station" value="<?= $v('pickup_point') ?>">
    </div>
    <div class="mb-3">
      <label class="field-label" for="message">Fitness level, gear questions, anything else?</label>
      <textarea class="field" id="message" name="message" rows="3"><?= $v('message') ?></textarea>
    </div>
    <?php
}
