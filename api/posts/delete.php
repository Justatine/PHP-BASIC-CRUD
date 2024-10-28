<?php
header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: DELETE');
include '../require/connection.php';

$response = array();

$conn->begin_transaction();

try {
    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        $deleteData = file_get_contents("php://input");
        $requestData = json_decode($deleteData, true);
    
        $id = $requestData['id'];

        $query = "DELETE FROM posts WHERE id=?";
        $sql=$conn->prepare($query);
        $sql->bind_param("i", $id);
        
        if ($sql->execute()) {
            $response['success'] = true;
            $response['message'] = 'Post deleted';
        }
    }
    $conn->commit();
} catch (\Exception $e) {
    $response['error']='Failed to fetch data';
}
echo json_encode($response);