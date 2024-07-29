<?php
session_start();
require '../../backend/functions/routing.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare data to send to the API
    $postData = [
        'email' => $email,
        'password' => $password
    ];

    // Convert data to JSON
    $jsonData = json_encode($postData);

    // Initialize cURL
    $ch = curl_init('http://localhost/news/backend/api/v1/login_control.php');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

    // Execute cURL request
    $response = curl_exec($ch);
    print_r($response);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Close cURL
    curl_close($ch);

    // Handle response
    if ($httpCode === 201) {
        $responseData = json_decode($response, true);
        $_SESSION['loggedIn'] = true;
        $_SESSION['email'] = $email;
        $_SESSION['is_admin'] = $responseData['is_admin'];

        // hitting create_token
        $postDataForToken = [
            'email' => $email,
            'is_admin' => $responseData['is_admin']
        ];

        $jsonDataForToken = json_encode($postDataForToken);

        $ch0 = curl_init('http://localhost/news/backend/api/v1/create_token.php');
        curl_setopt($ch0, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch0, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch0, CURLOPT_POST, true);
        curl_setopt($ch0, CURLOPT_POSTFIELDS, $jsonDataForToken);

        // Execute cURL request
        $responseT = curl_exec($ch0);
        $httpCodeT = curl_getinfo($ch0, CURLINFO_HTTP_CODE);
        $curlErrorT = curl_error($ch0); // cURL hatalarını kontrol et

        // Close cURL
        curl_close($ch0);
        
        if ($curlErrorT) {
            echo "cURL Error: " . $curlErrorT;
        } else {
            $responseToken = json_decode($responseT, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                echo "JSON decode error: " . json_last_error_msg();
                echo "Response: " . $responseT;
                exit;
            }

            if ($httpCodeT === 201 && isset($responseToken['token'])) {
                $_SESSION['token'] = $responseToken['token'];
                if ($_SESSION['is_admin'] == 0) {
                    go("../dist/homepage-v1.php", 3);
                } else {
                    go("../admin/admin-panel.php", 3);
                }
            } else {
                echo "Token creation failed with HTTP STATUS: " . $httpCodeT . "<br>";
                echo "Response: " . $responseT;
            }
        }
    } else {
        echo "HTTP STATUS: " . $httpCode . "<br>";
        echo "Username or password is wrong.";
        if ($curlError) {
            echo "cURL Error: " . $curlError;
        }
        go("login-page.php", 1);
    }
} else {
    // POST isteği değilse kayıt formunu göster
    echo 'not a valid request';
    header('Location: login-page.php');
    exit;
}
?>