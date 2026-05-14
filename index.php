<?php
// Main entry for AI Expense Categorizer
// This page includes UI components and wires frontend assets.
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AI Expense Categorizer</title>
  <!-- Google Font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="style.css">
</head>
<body class="app-bg">

  <?php
  // Include reusable components (navbar + hero are displayed first)
  require_once __DIR__ . '/components/navbar.php';
  require_once __DIR__ . '/components/hero.php';
  ?>

  <?php if (isset($_GET['saved']) && $_GET['saved'] === '1'): ?>
    <div class="container">
      <div class="notice notice-success card glass">
        <i class="fa-solid fa-circle-check"></i>
        <span>Expense saved successfully.</span>
      </div>
    </div>
  <?php endif; ?>

  <main class="container page-stack homepage-stack">
    <?php require_once __DIR__ . '/components/expense_form.php'; ?>
    <?php require_once __DIR__ . '/components/history_section.php'; ?>
    <?php require_once __DIR__ . '/components/result_card.php'; ?>
    <?php require_once __DIR__ . '/components/category_cards.php'; ?>
  </main>

  <script src="script.js" defer></script>
</body>
</html>
