<?php
// Test script to validate communication between PHP and Node.js server
require_once '../models/Validator.php';

echo "<h2>🔍 Test Communication PHP → Node.js</h2>";
echo "<div style='font-family: monospace; background: #f5f5f5; padding: 15px; border-radius: 5px;'>";

// Test 1: Check if Node.js server is running
echo "<h3>📡 Test 1: Node.js Server Status</h3>";
$ch = curl_init("http://localhost:8080/");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    echo "✅ Node.js server is running on port 8080<br>";
} else {
    echo "❌ Node.js server is NOT running on port 8080 (HTTP Code: $httpCode)<br>";
    echo "💡 Please start the Node.js server first:<br>";
    echo "<code>cd notification-server && node server.js</code><br>";
    echo "</div></div>";
    exit;
}

// Test 2: Direct test of the notification function
echo "<h3>🔔 Test 2: Direct Notification Function Test</h3>";
echo "Calling Validator::notifyNodeNewOrder('TEST-123')...<br>";

try {
    $result = Validator::notifyNodeNewOrder('TEST-123');
    echo "Function executed. Result: " . ($result ?: 'null') . "<br>";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
}

// Test 3: Manual cURL test
echo "<h3>🌐 Test 3: Manual cURL Test</h3>";
$payload = json_encode(["order_id" => "MANUAL-TEST-456"]);
$ch = curl_init("http://localhost:8080/resto/commandes/createFromCart");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Content-Length: " . strlen($payload)
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

echo "HTTP Response Code: $httpCode<br>";
echo "cURL Error: " . ($curlError ?: "None") . "<br>";
echo "Response: $response<br>";

if ($httpCode === 200) {
    echo "✅ Manual test successful!<br>";
} else {
    echo "❌ Manual test failed<br>";
}

echo "</div>";
echo "<p><strong>Next steps:</strong></p>";
echo "<ol>";
echo "<li>Check the Node.js server console for incoming requests</li>";
echo "<li>Check PHP error logs for any issues</li>";
echo "<li>Make sure the admin interface is listening for 'nouvelle_commande' events</li>";
echo "</ol>";
?>