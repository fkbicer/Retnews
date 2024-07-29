<?php 
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');
$headers = apache_request_headers();
if (!isset($headers['Authorization']) || !preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)) {
    http_response_code(401);
    echo json_encode(['message' => 'Unauthorized']);
    exit;
}
$token = $matches[1];

require '../../db/database.class.php';
$db0 = new Database();
$getQuery = 'SELECT expired_at FROM tokens WHERE token = ?';
$rowData = $db0->getRow($getQuery,array($token));
$expiredAt = new DateTime($rowData['expired_at']);
$currentDateTime = new DateTime();
$currentDateTime->add(new DateInterval('PT60M'));

if ($expiredAt < $currentDateTime) {
    http_response_code(401);
    echo json_encode(['message' => 'Token expired, Unauthorized.']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if ($input['is_admin'] === 0) {
    http_response_code(401);
    echo json_encode(['message' => 'Unauthorized','is_admin' => $input['is_admin']]);
}else{
    
    
    if (isset($input['title']) && 
    isset($input['content']) && 
    isset($input['topic']) &&
    isset($input['email'])
    ) 
    {
    $title = $input['title'];
    $content = $input['content'];
    $topic = $input['topic'];
    $email = $input['email'];

    // Database connection
    $db = new Database();
    $insertQuery = 'INSERT INTO news (title, content, topic,created_by) VALUES (?, ?, ?, ?)';
    $contentId = $db->insertData($insertQuery,array($title,$content,$topic,$email));
    if($contentId) {
        http_response_code(201);
        echo json_encode(['message' => 'Content creation succesfull.']);
    }else {
        http_response_code(500);
        echo json_encode(['message' => 'User creation failed.']);
    }
} else {
    http_response_code(400);
    echo json_encode(['message' => 'Invalid input','title' => $title]);
}
}

?>