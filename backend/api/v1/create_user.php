<?php 
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['email']) && 
    isset($input['password']) && 
    isset($input['first_name']) && 
    isset($input['last_name']) && 
    isset($input['city']) &&
    isset($input['country'])
    ) {
    $password = $input['password'];
    $first_name = $input['first_name'];
    $last_name = $input['last_name'];
    $city =$input['city'];
    $country =$input['country'];
    $email= $input['email'];

    // Database connection
    require '../../db/database.class.php';
    $db = new Database();
    $insertQuery = 'INSERT INTO users (email, password, first_name, last_name, city, country) VALUES (?, ?, ?, ?, ?, ?)';
    $userId = $db->insertData($insertQuery,array($email,$password,$first_name,$last_name,$city,$country));
    if($userId) {
        http_response_code(201);
        echo json_encode(['message' => 'User creation succesfull.']);
    }else {
        http_response_code(500);
        echo json_encode(['message' => 'User creation failed.']);
    }
} else {
    http_response_code(400);
    echo json_encode(['message' => 'Invalid input']);
}
?>