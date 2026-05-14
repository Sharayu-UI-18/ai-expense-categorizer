<?php
// components/category_cards.php
// Reusable category explorer grid for the homepage and category pages.

$categories = [
  ['type' => 'Food', 'icon' => 'fa-utensils', 'title' => 'Food', 'description' => 'Meals, snacks, cafes, and restaurants.', 'count' => '24 transactions'],
  ['type' => 'Transport', 'icon' => 'fa-car-side', 'title' => 'Transport', 'description' => 'Ride shares, fuel, parking, and transit.', 'count' => '18 transactions'],
  ['type' => 'Shopping', 'icon' => 'fa-bag-shopping', 'title' => 'Shopping', 'description' => 'Retail purchases and online orders.', 'count' => '31 transactions'],
  ['type' => 'Entertainment', 'icon' => 'fa-film', 'title' => 'Entertainment', 'description' => 'Movies, streaming, events, and hobbies.', 'count' => '12 transactions'],
  ['type' => 'Education', 'icon' => 'fa-graduation-cap', 'title' => 'Education', 'description' => 'Courses, books, and learning platforms.', 'count' => '9 transactions'],
  ['type' => 'Utilities', 'icon' => 'fa-bolt', 'title' => 'Utilities', 'description' => 'Power, water, internet, and subscriptions.', 'count' => '15 transactions'],
  ['type' => 'Healthcare', 'icon' => 'fa-heart-pulse', 'title' => 'Healthcare', 'description' => 'Medical, pharmacy, and wellness spending.', 'count' => '7 transactions'],
  ['type' => 'Personal', 'icon' => 'fa-user', 'title' => 'Personal', 'description' => 'Daily personal expenses and self-care.', 'count' => '20 transactions'],
];

$currentCategory = $currentCategory ?? null;
?>

<section class="category-explorer card glass">
  <div class="section-head">
    <div>
      <p class="section-kicker">Explore</p>
      <h2>Expense Categories</h2>
    </div>
    <p class="section-note">Tap a category to open its dedicated dashboard view.</p>
  </div>

  <div class="category-grid">
    <?php foreach ($categories as $category): ?>
      <?php $isActive = $currentCategory && strcasecmp($currentCategory, $category['type']) === 0; ?>
      <a class="category-card<?php echo $isActive ? ' active' : ''; ?>" href="category.php?type=<?php echo urlencode($category['type']); ?>" aria-label="Open <?php echo htmlspecialchars($category['title']); ?> expenses">
        <div class="category-card-top">
          <div class="category-icon"><i class="fa-solid <?php echo htmlspecialchars($category['icon']); ?>"></i></div>
          <span class="category-pill"><?php echo htmlspecialchars($category['count']); ?></span>
        </div>
        <div class="category-card-body">
          <h3><?php echo htmlspecialchars($category['title']); ?></h3>
          <p><?php echo htmlspecialchars($category['description']); ?></p>
        </div>
        <div class="category-card-footer">
          <span>View dashboard</span>
          <i class="fa-solid fa-arrow-right"></i>
        </div>
      </a>
    <?php endforeach; ?>
  </div>
</section>
