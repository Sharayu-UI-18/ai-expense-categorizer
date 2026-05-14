<?php
// components/history_section.php
// Dynamic Transaction history list populated from the `expenses` table.
// This component keeps the existing UI and styling but renders real DB rows.

require_once __DIR__ . '/../db.php';

// Fetch latest 10 expenses (newest first)
$limit = 10;
$items = [];
$sql = "SELECT expense_text, amount, category, created_at FROM expenses ORDER BY created_at DESC LIMIT ?";
$stmt = mysqli_prepare($conn, $sql);
if ($stmt) {
    mysqli_stmt_bind_param($stmt, 'i', $limit);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $items[] = $row;
        }
    }
    mysqli_stmt_close($stmt);
}

function esc($s) {
    return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
}

function truncate($s, $len = 80) {
    if (mb_strlen($s) <= $len) return $s;
    return mb_substr($s, 0, $len - 1) . '…';
}

// Map categories to Font Awesome icons
$categoryIcons = [
    'Food' => 'fa-utensils',
    'Transport' => 'fa-car-side',
    'Shopping' => 'fa-bag-shopping',
    'Entertainment' => 'fa-film',
    'Education' => 'fa-graduation-cap',
    'Utilities' => 'fa-bolt',
    'Healthcare' => 'fa-heart-pulse',
    'Personal' => 'fa-user'
];

function getCategoryIcon($category) {
    global $categoryIcons;
    // Handle null/empty categories
    if (empty($category)) {
        return 'fa-tag';
    }
    // Try direct match first
    if (isset($categoryIcons[$category])) {
        return $categoryIcons[$category];
    }
    // Try case-insensitive match by capitalizing the first letter
    $capitalizedCategory = ucfirst(strtolower($category));
    if (isset($categoryIcons[$capitalizedCategory])) {
        return $categoryIcons[$capitalizedCategory];
    }
    // Default fallback
    return 'fa-tag';
}
?>

<aside id="history" class="history card glass">
  <div class="history-header">
    <h3><i class="fa-solid fa-clock-rotate-left"></i> Transaction History</h3>
  </div>
  <div id="history-list" class="history-list">
    <?php if (empty($items)): ?>
      <div class="empty-state">
        <div class="empty-icon"><i class="fa-solid fa-inbox"></i></div>
        <h3>No expenses added yet.</h3>
        <p class="muted">Your saved expenses will appear here once you add them.</p>
      </div>
    <?php else: ?>
      <?php foreach ($items as $it): ?>
        <?php
          $text = esc($it['expense_text'] ?? '');
          $amount = number_format((float)($it['amount'] ?? 0), 2);
          $category = esc($it['category'] ?? 'Uncategorized');
          $categoryIcon = getCategoryIcon($it['category'] ?? '');
          $dt = $it['created_at'] ?? null;
          $dateDisplay = $dt ? date('M j, Y • H:i', strtotime($dt)) : '';
        ?>
        <div class="history-item">
          <div class="history-icon"><i class="fa-solid <?php echo htmlspecialchars($categoryIcon); ?>"></i></div>
          <div class="left">
            <div class="title"><?php echo esc(truncate($text, 64)); ?></div>
            <div class="meta"><?php echo esc($dateDisplay); ?> • <span class="category-badge"><?php echo $category; ?></span></div>
          </div>
          <div class="right">₹<?php echo $amount; ?></div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</aside>

