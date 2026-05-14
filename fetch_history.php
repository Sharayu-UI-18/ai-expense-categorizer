<?php
// fetch_history.php
// Returns JSON list of recent transactions from the `expenses` table.
// Beginner-friendly implementation using the existing MySQLi connection in db.php

header('Content-Type: application/json');
require_once __DIR__ . '/db.php';

// Optional category filter (not required by the task, kept simple)
$category = isset($_GET['category']) ? trim($_GET['category']) : '';

// Limit the number of results returned for the dashboard
$limit = 10;

// Build and execute the SQL query with prepared statements
if ($category !== '') {
    $sql = "SELECT expense_text, amount, category, created_at FROM expenses WHERE category LIKE ? ORDER BY created_at DESC LIMIT ?";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        $like = "%" . $category . "%";
        mysqli_stmt_bind_param($stmt, 'si', $like, $limit);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'DB prepare failed']);
        exit;
    }
} else {
    $sql = "SELECT expense_text, amount, category, created_at FROM expenses ORDER BY created_at DESC LIMIT ?";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $limit);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'DB prepare failed']);
        exit;
    }
}

$items = [];
if (!empty($res)) {
    while ($row = mysqli_fetch_assoc($res)) {
        $items[] = [
            'expense_text' => $row['expense_text'],
            'amount' => $row['amount'],
            'category' => $row['category'],
            'created_at' => $row['created_at'],
        ];
    }
}

echo json_encode(['status' => 'ok', 'items' => $items]);
