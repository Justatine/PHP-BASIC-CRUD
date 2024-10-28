<?php
header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
include '../require/connection.php';

$response = array();

$conn->begin_transaction();

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = $_POST['title'];
        $content = $_POST['content'];

        $query = "INSERT INTO posts(title,content) VALUES(?, ?)";
        $sql=$conn->prepare($query);
        $sql->bind_param("ss",$title,$content);
        
        if ($sql->execute()) {
            $response['success'] = true;
            $response['message'] = 'Post added';
        }
    }
    $conn->commit();
} catch (\Exception $e) {
    $response['error']='Failed to fetch data';
}
echo json_encode($response);