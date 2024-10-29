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
        $image = $_FILES["image"];

        $imageName = null;
        $imageUploaded = false;
        if (is_uploaded_file($image["tmp_name"])) {
          $imageUploaded = true;
          $imageName = time() . $image["name"];
      
          $allowedMimeTypes = ["image/png", "image/jpeg", "image/jpg", "image/gif"];
          $uploadedMimeType = $_FILES["image"]["type"];
      
          if (!in_array($uploadedMimeType, $allowedMimeTypes)) {
            $response["error"]["image"] = "Invalid image extension";
          }
        }
  
        if ($imageUploaded) {
          $targetPath = "../../files/" . $imageName;
      
          if (!move_uploaded_file($image["tmp_name"], $targetPath)) {
            $response["error"]["image"] = "Failed to move uploaded file";
            echo json_encode($response);
            exit();
          }
        }
    
        $query = "INSERT INTO posts(title,content,image) VALUES(?, ?, ?)";
        $sql=$conn->prepare($query);
        $sql->bind_param("sss",$title,$content, $imageName);
        
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