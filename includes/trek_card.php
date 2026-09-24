<?php /* expects $t (a row from treks, optionally with avg_rating and review_count) */ ?>
<div class="tn-card" data-wishlist-card="<?= (int)$t['id'] ?>">
  <a href="trek.php?id=<?= (int)$t['id'] ?>" class="tn-card-img" style="background-image:url('<?= e($t['cover_image']) ?>')">
    <span class="tn-badge <?= difficulty_class($t['difficulty']) ?>"><?= e($t['difficulty']) ?></span>
  </a>
  <div class="tn-card-body">
    <div class="tn-card-region"><i class="bi bi-geo-alt"></i> <?= e($t['region']) ?></div>
    <a href="trek.php?id=<?= (int)$t['id'] ?>" class="tn-card-title"><?= e($t['name']) ?></a>
    <p class="tn-card-tag"><?= e($t['tagline']) ?></p>
    <?php if (!empty($t['review_count'])): ?>
      <div class="tn-stars-mini">
        <?php for ($i = 1; $i <= 5; $i++): ?><i class="bi <?= $i <= round($t['avg_rating']) ? 'bi-star-fill' : 'bi-star' ?>"></i><?php endfor; ?>
        <span><?= number_format($t['avg_rating'], 1) ?> (<?= (int)$t['review_count'] ?>)</span>
      </div>
    <?php else: ?>
      <div class="tn-stars-mini text-muted-small">No reviews yet</div>
    <?php endif; ?>
    <div class="tn-card-meta">
      <span><i class="bi bi-arrow-up-right-circle"></i> <?= number_format($t['max_altitude_m']) ?> m</span>
      <span><i class="bi bi-calendar3"></i> <?= (int)$t['duration_days'] ?> days</span>
    </div>
    <div class="tn-card-foot">
      <span class="tn-price">&#8377;<?= number_format($t['price_from']) ?><small>/person</small></span>
      <button class="tn-save-btn" data-wishlist-btn="<?= (int)$t['id'] ?>"><i class="bi bi-bookmark-heart"></i> Save</button>
    </div>
  </div>
</div>
