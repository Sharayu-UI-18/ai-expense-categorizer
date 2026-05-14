<?php
// category.php
// Dynamic category dashboard page for viewing filtered expenses.

$allowedCategories = ['Food', 'Transport', 'Shopping', 'Entertainment', 'Education', 'Utilities', 'Healthcare', 'Personal'];
$categoryType = isset($_GET['type']) ? trim($_GET['type']) : 'Food';
$categoryType = in_array($categoryType, $allowedCategories, true) ? $categoryType : 'Food';

$categoryMeta = [
  'Food' => ['icon' => 'fa-utensils', 'label' => 'Food Expenses', 'accent' => 'rgba(6, 182, 212, 0.18)'],
  'Transport' => ['icon' => 'fa-car-side', 'label' => 'Transport Expenses', 'accent' => 'rgba(59, 130, 246, 0.18)'],
  'Shopping' => ['icon' => 'fa-bag-shopping', 'label' => 'Shopping Expenses', 'accent' => 'rgba(139, 92, 246, 0.18)'],
  'Entertainment' => ['icon' => 'fa-film', 'label' => 'Entertainment Expenses', 'accent' => 'rgba(6, 182, 212, 0.18)'],
  'Education' => ['icon' => 'fa-graduation-cap', 'label' => 'Education Expenses', 'accent' => 'rgba(59, 130, 246, 0.18)'],
  'Utilities' => ['icon' => 'fa-bolt', 'label' => 'Utilities Expenses', 'accent' => 'rgba(139, 92, 246, 0.18)'],
  'Healthcare' => ['icon' => 'fa-heart-pulse', 'label' => 'Healthcare Expenses', 'accent' => 'rgba(6, 182, 212, 0.18)'],
  'Personal' => ['icon' => 'fa-user', 'label' => 'Personal Expenses', 'accent' => 'rgba(59, 130, 246, 0.18)'],
];

$pageMeta = $categoryMeta[$categoryType];

// Placeholder data only. The future backend flow can load this list from fetch_history.php
// with a category filter such as: SELECT * FROM expenses WHERE category = 'Food'
// Fetch real items from the database for this category.
require_once __DIR__ . '/db.php';

$items = [];
// Use a prepared statement to securely fetch expenses for the chosen category.
$sql = "SELECT expense_text, amount, category, created_at FROM expenses WHERE category = ? ORDER BY created_at DESC";
$stmt = mysqli_prepare($conn, $sql);
if ($stmt) {
    mysqli_stmt_bind_param($stmt, 's', $categoryType);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $items[] = $row;
        }
    }
    mysqli_stmt_close($stmt);
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo htmlspecialchars($categoryType); ?> Expenses | AI Expense Categorizer</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="style.css">
</head>
<body class="app-bg category-page">
  <?php require_once __DIR__ . '/components/navbar.php'; ?>

  <main class="container page-stack category-page-shell">
    <section class="category-hero card glass" style="--category-glow: <?php echo htmlspecialchars($pageMeta['accent']); ?>;">
      <div class="category-hero-badge"><i class="fa-solid <?php echo htmlspecialchars($pageMeta['icon']); ?>"></i></div>
      <div class="category-hero-content">
        <p class="section-kicker">Category Dashboard</p>
        <h1><?php echo htmlspecialchars($pageMeta['label']); ?></h1>
        <p class="category-hero-text">Review all expenses tagged under <?php echo htmlspecialchars($categoryType); ?>. Search and filter through the transaction history below.</p>
      </div>
      <div class="category-hero-actions">
        <a class="btn btn-ghost" href="index.php"><i class="fa-solid fa-arrow-left"></i> Back to Home</a>
      </div>
    </section>

    <section class="card glass category-toolbar">
      <div class="search-group">
        <label for="category-search">Search transactions</label>
        <div class="search-input-wrap">
          <i class="fa-solid fa-magnifying-glass"></i>
          <input id="category-search" type="search" placeholder="Search in <?php echo htmlspecialchars($categoryType); ?>...">
        </div>
      </div>
      <div class="search-group">
        <label>Active category</label>
        <div class="active-category-chip"><i class="fa-solid <?php echo htmlspecialchars($pageMeta['icon']); ?>"></i> <?php echo htmlspecialchars($categoryType); ?></div>
      </div>
    </section>

    <?php $currentCategory = $categoryType; require_once __DIR__ . '/components/category_cards.php'; ?>

    <section class="card glass category-history-card">
      <div class="section-head">
        <div>
          <p class="section-kicker">Filtered History</p>
          <h2><?php echo htmlspecialchars($categoryType); ?> Expenses</h2>
        </div>
        <p class="section-note"><?php echo count($items); ?> placeholder transactions ready for future database filtering.</p>
      </div>

      <div class="category-history-list" id="category-history-list">
        <?php if (!empty($items)): ?>
            <?php foreach ($items as $item): ?>
              <?php
                // Prepare display values and escape
                $text = htmlspecialchars($item['expense_text'] ?? '', ENT_QUOTES, 'UTF-8');
                $amount = number_format((float)($item['amount'] ?? 0), 2);
                $cat = htmlspecialchars($item['category'] ?? '', ENT_QUOTES, 'UTF-8');
                $dt = $item['created_at'] ?? null;
                $dateDisplay = $dt ? date('M j, Y • H:i', strtotime($dt)) : '';
                $searchAttr = htmlspecialchars(strtolower(($item['expense_text'] ?? '') . ' ' . ($item['category'] ?? '') . ' ' . ($item['created_at'] ?? '')));
              ?>
              <article class="category-history-row" data-search="<?php echo $searchAttr; ?>">
                <div class="row-left">
                  <div class="row-title"><?php echo mb_strlen($text) > 64 ? mb_substr($text, 0, 61) . '…' : $text; ?></div>
                  <div class="row-meta"><?php echo $dateDisplay; ?> • <?php echo $cat; ?></div>
                </div>
                <div class="row-center">
                  <span class="category-badge"><?php echo $cat; ?></span>
                </div>
                <div class="row-right">₹<?php echo $amount; ?></div>
              </article>
            <?php endforeach; ?>
        <?php else: ?>
          <div class="empty-state">
            <div class="empty-icon"><i class="fa-solid fa-inbox"></i></div>
            <h3>No <?php echo htmlspecialchars($categoryType); ?> expenses yet</h3>
            <p>Once your database is connected, filtered transactions will appear here automatically.</p>
          </div>
        <?php endif; ?>
        <div id="category-filter-empty" class="empty-state filter-empty" style="display:none;">
          <div class="empty-icon"><i class="fa-solid fa-search"></i></div>
          <h3>No matching transactions</h3>
          <p>Try a different search term or clear the filter to see all <?php echo htmlspecialchars($categoryType); ?> expenses.</p>
        </div>
      </div>
    </section>
  </main>

  <script src="script.js" defer></script>
</body>
</html>
