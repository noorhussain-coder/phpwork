<?php
include('../configs/database.php');
include('../configs/Session.php');
require 'vendor/autoload.php';

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

// ✅ Must have ID
if (!isset($_GET['id'])) {
    die("Invalid video ID");
}

$id = intval($_GET['id']);

// ✅ Fetch old video data
$sql = "SELECT * FROM videos WHERE id = $id";
$res = $conn->query($sql);

if (!$res || $res->num_rows == 0) {
    die("Video not found");
}

$old = $res->fetch_assoc();


// ✅ When form submitted
if (isset($_POST['submit'])) {

    $title = $_POST['title'];
    $description = $_POST['description'];

    $new_url = $old['video_url'];      // default old
    $new_public_id = $old['public_id'];

    // ✅ If new video is uploaded
    if (!empty($_FILES['video']['name'])) {

        // ✅ delete the old video
        if (!empty($old['public_id'])) {
            try {
                (new UploadApi())->destroy($old['public_id'], [
                    'resource_type' => 'video'
                ]);
            } catch (Exception $e) {
                // ignore cloudinary delete error
            }
        }

        // ✅ Upload new video
        try {
            $upload = (new UploadApi())->upload($_FILES['video']['tmp_name'], [
                'resource_type' => 'video',
                'folder' => 'videos'
            ]);

            $new_url = $upload['secure_url'];
            $new_public_id = $upload['public_id'];

        } catch (Exception $e) {
            die("Upload failed: " . $e->getMessage());
        }
    }

    // ✅ Update DB
    $updateSql = "UPDATE videos 
                    SET title='$title',
                        description='$description',
                        video_url='$new_url',
                        public_id='$new_public_id'
                  WHERE id = $id";

    if ($conn->query($updateSql)) {
        header("Location: Edit_Video.php");
        exit;
    } else {
        die("Database update error: " . $conn->error);
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Video</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light py-5">
  <div class="container">
    <div class="card mx-auto p-4 shadow" style="max-width: 500px;">
      <h3 class="text-center mb-4">✏️ Edit Video</h3>

      <form method="post" enctype="multipart/form-data">

        <div class="mb-3">
          <label class="form-label">Video Title</label>
          <input type="text" name="title" class="form-control" value="<?= $old['title'] ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Video Description</label>
          <input type="text" name="description" class="form-control" value="<?= $old['description'] ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Current Video</label>
          <video width="100%" controls>
            <source src="<?= $old['video_url'] ?>" type="video/mp4">
          </video>
        </div>

        <div class="mb-3">
          <label class="form-label">Upload New Video (optional)</label>
          <input type="file" name="video" class="form-control" accept="video/*">
        </div>

        <button type="submit" name="submit" class="btn btn-primary w-100">Update Video</button>
      </form>

    </div>
  </div>
</body>
</html>
