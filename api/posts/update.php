<?php
header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: PUT');
include '../require/connection.php';

$response = array();

$conn->begin_transaction();

try {
    if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        $putData = file_get_contents("php://input");
        $requestData = json_decode($putData, true);
    
        $id = $requestData['id'];
        $title = $requestData['title'];
        $content = $requestData['content'];

        $query = "UPDATE posts SET title=?, content=? WHERE id = ?";
        $sql=$conn->prepare($query);
        $sql->bind_param("ssi",$title,$content, $id);
        
        if ($sql->execute()) {
            $response['success'] = true;
            $response['message'] = 'Post updated';
        }
    }
    $conn->commit();
} catch (\Exception $e) {
    $response['error']='Failed to fetch data';
}
echo json_encode($response);