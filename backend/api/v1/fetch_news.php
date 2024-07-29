<?php 
    // Database connection
    require '../../db/database.class.php';
    $db = new Database();
    $query = 'SELECT * FROM news';
    $result = $db->getRows($query);

    header('Content-Type: application/json');
    echo json_encode($result);
?>