<?php
header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
include '../require/connection.php';

$response = array();

$conn->begin_transaction();

try {
    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        $putData = file_get_contents("php://input");
        $requestData = parse_multipart_formdata($putData);
    
        $id = $requestData['id'];

        $sql = $conn->prepare("SELECT image FROM posts WHERE id = ?");
        $sql->bind_param("i", $id);
        $sql->execute();
        $result = $sql->get_result();
        $row = $result->fetch_assoc();
        $image = $row["image"] ?? '';
      
        $oldImagePath = "../../files/" . $image;
        if (is_file($oldImagePath)) {
          unlink($oldImagePath);
        }

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