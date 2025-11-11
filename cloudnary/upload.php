<?php
require 'vendor/autoload.php';
// require 'db.php'; // ✅ include your DB connection

use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

// ✅ Cloudinary Configuration
Configuration::instance([
    'cloud' => [
        'cloud_name' => 'dggldmj6z',
        'api_key'    => '597569858316692',
        'api_secret' => 'nzOwOOVFsPNNGqB56R8SiQbcJ2M'
    ],
    'url' => ['secure' => true]
]);

// ✅ Handle upload and save to DB
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['video'])) {
    $file = $_FILES['video']['tmp_name'];
    $title = $_POST['title'];

    try {
        // Upload video to Cloudinary
        $result = (new UploadApi())->upload($file, [
            'resource_type' => 'video',
            'folder' => 'awareness_videos'
        ]);

        $videoUrl = $result['secure_url'];
        $id = $result['asset_id'];
        echo $videoUrl. "<br>";
        
        echo $id, 'id' ;
    //    if($result){
    //      echo "<pre>";
    //      print_r($result);
    //    }

        // ✅ Save video info into database
        // $stmt = $conn->prepare("INSERT INTO awareness_videos (title, video_url) VALUES (?, ?)");
        // $stmt->bind_param("ss", $title, $videoUrl);
        // $stmt->execute();

        echo "<h3>✅ Upload Successful!</h3>";
        echo "<video width='320' height='240' controls>
                <source src='{$videoUrl}' type='video/mp4'>
              </video>";
        echo "<p><strong>Title:</strong> $title</p>";
        echo "<p><a href='{$videoUrl}' target='_blank'>View on Cloudinary</a></p>";

    } catch (Exception $e) {
        echo "❌ Error: " . $e->getMessage();
    }
} else {
?>
<!-- Upload Form -->
<h2>Upload Awareness Video</h2>
<form action="upload.php" method="post" enctype="multipart/form-data">
    <label>Video Title:</label><br>
    <input type="text" name="title" required><br><br>

    <label>Select Video:</label><br>
    <input type="file" name="video" accept="video/*" required><br><br>

    <button type="submit">Upload Video</button>
</form>
<?php } ?>
