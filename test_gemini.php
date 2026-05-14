<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

if (empty($openRouterApiKey)) {
    die("API key missing");
}

$endpoint = "https://openrouter.ai/api/v1/chat/completions";

$data = [
    "model" => "openai/gpt-3.5-turbo",
    "messages" => [
        [
            "role" => "user",
            "content" => "Say hello from AI"
        ]
    ]
];

$ch = curl_init();

curl_setopt_array($ch, [
    CURLOPT_URL => $endpoint,
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
    die("cURL Error: " . curl_error($ch));
}

curl_close($ch);

$result = json_decode($response, true);

echo "<h2>AI Test</h2>";

if (isset($result['choices'][0]['message']['content'])) {

    echo "<pre>";
    echo htmlspecialchars($result['choices'][0]['message']['content']);
    echo "</pre>";

} else {

    echo "<h3>Error Response</h3>";
    echo "<pre>";
    print_r($result);
    echo "</pre>";
}
?>