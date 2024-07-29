<?php
session_start();
require '../../backend/functions/routing.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $content_id = $_POST['id'];
    $title = $_POST['title'];
    $topic = $_POST['topic'];
    $content = $_POST['content'];
    $is_admin = $_SESSION['is_admin'];
    $token = $_SESSION['token'];

    // Prepare data to send to the API
    $postData = [
        'content_id' => $content_id,
        'title' => $title,
        'topic' => $topic,
        'content' => $content
    ];

    // Convert data to JSON
    $jsonData = json_encode($postData);

    // Initialize cURL
    $ch = curl_init('http://localhost/news/backend/api/v1/update_content.php');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json','Authorization: Bearer ' . $token]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Handle response
    if ($httpcode === 201) {
        echo "Content updated succesfully";
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