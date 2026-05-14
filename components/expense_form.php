<?php
// components/expense_form.php
// Form used to submit a new expense for categorization
?>
<!-- components/expense_form.php
     Centered glassmorphism input card with textarea, amount input and analyze button
-->
<section class="card glass expense-card">
  <h2><i class="fa-solid fa-receipt"></i> Analyze an Expense</h2>
  <p class="muted"><i class="fa-solid fa-circle-info"></i> Try an example: "Spent ₹300 on pizza and coffee"</p>

  <form id="expense-form" class="form-grid" method="POST" action="save_expense.php">
    <!-- Textarea for freeform expense input -->
    <label for="expense_text" class="sr-only">Expense details</label>
    <div class="form-group">
      <div class="form-group-label">
        <i class="fa-solid fa-pencil"></i> Expense Details
      </div>
      <textarea id="expense_text" name="expense_text" rows="4" placeholder="e.g. Spent ₹500 on pizza and coffee" required></textarea>
    </div>

    <div class="row">
      <div class="col">
        <label for="amount"><i class="fa-solid fa-tag"></i> Amount</label>
        <div class="currency-input">
          <span class="currency-symbol">₹</span>
          <input type="number" step="0.01" id="amount" name="amount" placeholder="500" required>
        </div>
      </div>
      <div class="col">
        <label for="currency"><i class="fa-solid fa-globe"></i> Currency</label>
        <select id="currency" name="currency">
          <option value="INR" selected>INR - ₹</option>
        </select>
      </div>
    </div>

    <div class="form-actions">
      <button id="analyze-btn" class="btn btn-primary" type="submit"><i class="fa-solid fa-wand-magic-sparkles"></i> Analyze</button>
      <div id="loading-spinner" class="spinner" aria-hidden="true"></div>
    </div>
  </form>
</section>

