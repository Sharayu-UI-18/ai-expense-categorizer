<?php

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/config.php';


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Invalid request method.');
}

// Get form data
$expenseText = trim($_POST['expense_text'] ?? '');
$amount = trim($_POST['amount'] ?? '');

if ($expenseText === '' || $amount === '' || !is_numeric($amount)) {
    exit('Invalid input');
}

$amountValue = (float)$amount;

// Default values if AI fails
$category = "Personal";
$subcategory = "General";
$spendingType = "Unknown";
$reasoning = "AI unavailable";

// OpenRouter key from config.php
if (!isset($openRouterApiKey) || empty($openRouterApiKey)) {
    die("OpenRouter API key missing");
}

// Prompt
$prompt = "
You are an expense categorization system.

Analyze this expense:

$expenseText

Return ONLY valid JSON.

Required format:

{
\"category\":\"Food\",
\"subcategory\":\"Dining Out\",
\"spending_type\":\"Personal\",
\"reasoning\":\"Pizza is a food purchase and falls under dining expenses.\"
}

Rules:

1. ALL fields are mandatory
2. Never leave any field empty
3. Generate a subcategory
4. Generate reasoning in one sentence
5. No markdown
6. No extra text
7. No explanation outside JSON

Allowed categories:

Food
Transport
Shopping
Entertainment
Education
Utilities
Healthcare
Personal
";


// API request
$data = [
    "model" => "openai/gpt-3.5-turbo",
    "messages" => [
        [
            "role" => "user",
            "content" => $prompt
        ]
    ],
    "temperature" => 0
];

$ch = curl_init();

curl_setopt_array($ch, [
    CURLOPT_URL => "https://openrouter.ai/api/v1/chat/completions",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "Authorization: Bearer " . $openRouterApiKey,
        "HTTP-Referer: http://localhost",
        "X-Title: AI Expense Categorizer"
    ],
    CURLOPT_POSTFIELDS => json_encode($data)
]);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    curl_close($ch);
} else {

    curl_close($ch);

    $result = json_decode($response, true);

    if (isset($result['choices'][0]['message']['content'])) {

        $aiText = trim($result['choices'][0]['message']['content']);

        // remove markdown if model adds it
        $aiText = preg_replace('/```json|```/i', '', $aiText);

        // extract JSON object
        preg_match('/\{.*\}/s', $aiText, $matches);

        if (!empty($matches[0])) {

            $jsonText = trim($matches[0]);

            $aiData = json_decode($jsonText, true);

            if ($aiData !== null) {

                $category = $aiData['category'] ?? $category;
                
                $spendingType = $aiData['spending_type'] ?? $spendingType;
                $subcategory = !empty($aiData['subcategory'])
    ? $aiData['subcategory']
    : "General";

$reasoning = !empty($aiData['reasoning'])
    ? $aiData['reasoning']
    : "AI classified this expense automatically.";
            }
        }
    }
}

// Save to DB
$sql = "INSERT INTO expenses 
(expense_text, amount, category, subcategory, spending_type, reasoning)
VALUES (?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "sdssss",
    $expenseText,
    $amountValue,
    $category,
    $subcategory,
    $spendingType,
    $reasoning
);

mysqli_stmt_execute($stmt);

mysqli_stmt_close($stmt);

header("Location:index.php?saved=1");
exit;

?>