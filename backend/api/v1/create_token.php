<?php 
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['email']) && 
    isset($input['is_admin'])
    ) {
    $email = $input['email'];
    $is_admin = $input['is_admin'];
    $token = bin2hex(random_bytes(16));
    $expired_at = (new DateTime())->modify('+120 minutes')->format('Y-m-d H:i:s');
    // Database connection
    require '../../db/database.class.php';
    $db = new Database();
    $insertQuery = 'INSERT INTO tokens (email, token, is_admin,expired_at) VALUES (?, ?, ?,?)';
    $tokenData = $db->insertData($insertQuery,array($email,$token,$is_admin,$expired_at));
    if($tokenData) {
        http_response_code(201);
        echo json_encode([
            "message" => "Token created successful",
            "token" => $token
        ]);
    }else {
        http_response_code(500);
        echo json_encode(['message' => 'Token creation failed.']);
    }
} else {
    http_response_code(400);
    echo json_encode(['message' => 'Invalid input']);
}
?>