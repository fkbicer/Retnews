<?php 
require '../../db/database.class.php';
$db0 = new Database();
$token = '138bbe94bc482f2e13ad83fd5cdaa9b3';
$getQuery = 'SELECT expired_at FROM tokens WHERE token = ?';
$rowData = $db0->getRow($getQuery,array($token));
$expiredAt = new DateTime($rowData['expired_at']);
$currentDateTime = new DateTime();
$currentDateTime->add(new DateInterval('PT60M'));

print_r($expiredAt);
print_r($currentDateTime);

if ($expiredAt < $currentDateTime) {
    http_response_code(401);
    echo json_encode(['message' => 'Token expired, Unauthorized.']);
    exit;
}


?>