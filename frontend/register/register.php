<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $city = $_POST['city'];
    $country = $_POST['country'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Validate passwords
    if ($password !== $confirmPassword) {
        echo 'Passwords do not match.';
        exit;
    }

    // Prepare data to send to the API
    $postData = [
        'first_name' => $firstName,
        'last_name' => $lastName,
        'email' => $email,
        'city' => $city,
        'country' => $country,
        'password' => $password
    ];

    // Convert data to JSON
    $jsonData = json_encode($postData);

    // Initialize cURL
    $ch = curl_init('http://localhost/news/backend/api/v1/create_user.php');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

    // Execute cURL request
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // echo $response;
    // echo $httpCode;
    // Close cURL
    curl_close($ch);

    // Handle response
    if ($httpCode === 201) {
        echo 'User registered successfully.';
        header("Location: ../dist/homepage-v1.php");
        exit();
    } else {
        echo "HTTP STATUS: ".$httpCode."<br>";
        echo 'Failed to register user: '.'<BR>'.$response;
    }
} else {
    // If not a POST request, show the registration form
    header('Location: register.html');
    exit;
}
?>