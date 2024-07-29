<?php 
session_start();
require '../../backend/functions/routing.php';
$token = $_SESSION['token'];

$postData = [
    'token' => $token
];

// Convert data to JSON
$jsonData = json_encode($postData);

// Initialize cURL
$ch = curl_init('http://localhost/news/backend/api/v1/token_terminate.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpcode === 201) {
    session_unset();
    session_destroy();
    echo 'Logged out successfully.';
    go("../login/login-page.php",2);
    exit();
} else {
    echo "HTTP STATUS: ".$httpcode."<br>";
    echo 'Failed to register user: '.'<BR>'.$response;
}



?>