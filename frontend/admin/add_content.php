<?php
session_start();
require '../../backend/functions/routing.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $title = $_POST['title'];
    $content = $_POST['content'];
    $topic = $_POST['topic'];
    $token = $_SESSION['token'];
    $is_admin = $_SESSION['is_admin'];
    $email = $_SESSION['email'];

    // Prepare data to send to the API
    $postData = [
        'title' => $title,
        'content' => $content,
        'topic' => $topic,
        'is_admin' => $is_admin,
        'email' => $email
    ];

    // Convert data to JSON
    $jsonData = json_encode($postData);

    // Initialize cURL
    $ch = curl_init('http://localhost/news/backend/api/v1/create_content.php');
    define("COOKIE_FILE", "cookie.txt");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt ($ch, CURLOPT_COOKIEJAR, COOKIE_FILE); 
    curl_setopt ($ch, CURLOPT_COOKIEFILE, COOKIE_FILE); 
    curl_setopt($ch, CURLOPT_COOKIESESSION, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Handle response
    if ($httpcode === 201) {
        echo "Content creation done succesfully";
        go("admin-panel.php", 3);
    } else {
        echo "HTTP STATUS: ".$httpcode."<br>";
        echo 'Failed to register user: '.'<BR>'.$response;
    }
} else {
    // If not a POST request, show the registration form
    header("Location: admin-panel.php");
    exit;
}
?>