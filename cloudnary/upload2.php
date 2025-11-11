<?php
 $allowed_roles=[];
require 'vendor/autoload.php';
include('../configs/database.php');
include('../configs/Session.php');

use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;
   
Configuration::instance([
    'cloud' => [
        'cloud_name' => 'dggldmj6z',
        'api_key'    => '597569858316692',
        'api_secret' => 'nzOwOOVFsPNNGqB56R8SiQbcJ2M'
    ],
    'url' => ['secure' => true]
]);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['video'])) {
    $title = htmlspecialchars(trim($_POST['title']));
    $file = $_FILES['video'];

    if ($file['error'] === UPLOAD_ERR_OK) {
        try {
            $upload = new UploadApi();
            $result = $upload->upload($file['tmp_name'], [
                'resource_type' => 'video',
                // 'folder' => 'awareness_videos'
                // 'folder' => 'videos'
            ]);

            $videoUrl = $result['secure_url'];
            $publicId = $result['public_id'];
            $description=$_POST['description'];
            $user_id=$_SESSION['user_id'];
           

if ($videoUrl) {
    $stmt = $conn->prepare("INSERT INTO videos (title, description, video_url, public_id, user_id)
                            VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $title, $description, $videoUrl, $publicId, $user_id);

    if ($stmt->execute()) {
        echo "<script>alert('✅ Upload Successful!'); window.location='upload2.php';</script>";
    } else {
        echo " Database Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "⚠️ Something went wrong — video URL missing.";
}      
        } catch (Exception $e) {
            echo " Error: " . $e->getMessage();
        }
    }
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Upload Video</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light py-5">
  <div class="container">
    <div class="card mx-auto p-4 shadow" style="max-width: 500px;">
      <h3 class="text-center mb-4">📤 Upload New Video</h3>
      <form action="" method="post" enctype="multipart/form-data">
        <div class="mb-3">
          <label class="form-label">Video Title</label>
          <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Video description</label>
          <input type="text" name="description" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Select Video</label>
          <input type="file" name="video" class="form-control" accept="video/*" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Upload</button>
      </form>
    </div>
  </div>
</body>
</html>
<?php } ?>
