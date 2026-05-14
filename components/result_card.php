<?php
// components/result_card.php
// Area where AI results will be displayed after categorization.
// This section shows the latest saved expense values from the database.

require_once __DIR__ . '/../db.php';

// Start with empty-state values so the UI still looks complete if there are no rows.
$latestExpense = [
  'category' => 'Not Available',
  'subcategory' => 'Not Available',
  'spending_type' => 'Not Available',
  'reasoning' => 'No AI analysis available yet',
];

// Fetch the newest expense record from the database.
$sql = 'SELECT category, subcategory, spending_type, reasoning FROM expenses ORDER BY created_at DESC LIMIT 1';
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  if ($result && ($row = mysqli_fetch_assoc($result))) {
    // Replace the empty-state defaults with real database values.
    $latestExpense['category'] = trim((string) ($row['category'] ?? '')) ?: $latestExpense['category'];
    $latestExpense['subcategory'] = trim((string) ($row['subcategory'] ?? '')) ?: $latestExpense['subcategory'];
    $latestExpense['spending_type'] = trim((string) ($row['spending_type'] ?? '')) ?: $latestExpense['spending_type'];
    $latestExpense['reasoning'] = trim((string) ($row['reasoning'] ?? '')) ?: $latestExpense['reasoning'];
  }

  mysqli_stmt_close($stmt);
}

// Small helper so all database values are escaped before showing them in HTML.
function aiEsc($value): string
{
  return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
?>
<!-- components/result_card.php
   Animated result cards displaying the latest AI output from the database
-->
<section id="ai-result" class="result">
  <h3><i class="fa-solid fa-wand-magic-sparkles"></i> AI Suggestion</h3>

  <div class="result-grid">
    <!-- Category Card -->
    <div class="card glass result-item">
      <div class="icon"><i class="fa-solid fa-tags"></i></div>
      <div class="meta">
        <div class="label">Category</div>
        <div class="value"><?php echo aiEsc($latestExpense['category']); ?> <span class="badge badge-cyan">Primary</span></div>
      </div>
    </div>

    <!-- Subcategory Card -->
    <div class="card glass result-item">
      <div class="icon"><i class="fa-solid fa-layer-group"></i></div>
      <div class="meta">
        <div class="label">Subcategory</div>
        <div class="value"><?php echo aiEsc($latestExpense['subcategory']); ?> <span class="badge badge-purple">Suggested</span></div>
      </div>
    </div>

    <!-- Spending Type -->
    <div class="card glass result-item">
      <div class="icon"><i class="fa-solid fa-arrow-right-arrow-left"></i></div>
      <div class="meta">
        <div class="label">Spending Type</div>
        <div class="value"><?php echo aiEsc($latestExpense['spending_type']); ?></div>
      </div>
    </div>

    <!-- AI Reasoning (wide) -->
    <div class="card glass result-item result-reason">
      <div class="icon"><i class="fa-solid fa-lightbulb"></i></div>
      <div class="meta">
        <div class="label">AI Reasoning</div>
        <div class="reason"><?php echo aiEsc($latestExpense['reasoning']); ?></div>
      </div>
    </div>
  </div>
</section>

