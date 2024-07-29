<?php 
require 'db/database.class.php';

$db = new Database();


function sendCurlRequest($url, $data) {
    $ch = curl_init($url);
    
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json'
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    curl_close($ch);
    
    return [
        'response' => $response,
        'httpCode' => $httpCode
    ];
}

// Example data for user creation
$userData = [
    'username' => 'newuser',
    'email' => 'newuser@example.com',
    'password' => 'securepassword'
];

// URL of the create_user API endpoint
$apiUrl = 'http://localhost/myapi/create_user.php';

// Send the cURL request
$result = sendCurlRequest($apiUrl, $userData);

// Output the result
echo '<pre>';
print_r($result);
echo '</pre>';






?>