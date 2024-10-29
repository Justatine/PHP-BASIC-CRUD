<?php
header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: PUT, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
include '../require/connection.php';

$response = array();

$conn->begin_transaction();

try {
    if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        $putData = file_get_contents("php://input");
        $requestData = parse_multipart_formdata($putData);
    
        $id = $requestData['id'];
        $title = $requestData['title'];
        $content = $requestData['content'];
        $image = $requestData["image"];

        $sql = $conn->prepare("SELECT image FROM posts WHERE id = ?");
        $sql->bind_param("i", $id);
        $sql->execute();
        $result = $sql->get_result();
        $row = $result->fetch_assoc();
        $oldImage = $row["image"];
    
        $imageName = $oldImage;
        $imageUploaded = false;
        if ($image["name"] != null) {
          $imageUploaded = true;
          $imageName = time() . $image["name"];
      
          $allowedMimeTypes = ["image/png", "image/jpeg", "image/jpg", "image/gif"];
          $uploadedMimeType = $requestData["image"]["type"];
      
        }
        
        if ($imageUploaded) {
          $fileContentBase64 = base64_encode(file_get_contents($image["tmp_name"]));
      
          $uploadDirectory = "../../files/";
          $destination = $uploadDirectory . $imageName;
      
          $decodedContent = base64_decode($fileContentBase64);
          if (!file_put_contents($destination, $decodedContent)) {
            $response["error"]["image"] = "Failed to move uploaded file";
            echo json_encode($response);
            exit();
          }
      
          if ($oldImage != null) {
            $oldImagePath = $uploadDirectory . $oldImage;
            if (file_exists($oldImagePath)) {
              unlink($oldImagePath);
            }
          }
        }

        $query = "UPDATE posts SET title=?, content=?, image=? WHERE id = ?";
        $sql=$conn->prepare($query);
        $sql->bind_param("sssi",$title,$content, $imageName, $id);
        
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