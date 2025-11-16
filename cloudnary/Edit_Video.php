<?php
 $allowed_roles=[];
include('../configs/database.php');
include('../configs/Session.php');

require 'vendor/autoload.php';
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;
use Illuminate\Console\View\Components\Alert;

// ------------------------------
// ✅ Delete video (Cloudinary + DB)
// ------------------------------
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);

    // Get public_id
    $sql = "SELECT public_id FROM videos WHERE id = $delete_id";
    $res = $conn->query($sql);

    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $publicId = $row['public_id'];

        Configuration::instance([
            'cloud' => [
                'cloud_name' => 'dggldmj6z',
                'api_key'    => '597569858316692',
                'api_secret' => 'nzOwOOVFsPNNGqB56R8SiQbcJ2M'
            ],
            'url' => ['secure' => true]
        ]);

        try {
            (new UploadApi())->destroy($publicId, ['resource_type' => 'video']);
        } catch (Exception $e) {}
    }

    $conn->query("DELETE FROM videos WHERE id = $delete_id");
    header("Location: Edit_Video.php");
    
    exit;
}

// ------------------------------
// ✅ Fetch only videos table
// ------------------------------
$sql = "SELECT * FROM videos ORDER BY created_at DESC";
$result = $conn->query($sql);

$videos = ($result && $result->num_rows > 0)
            ? $result->fetch_all(MYSQLI_ASSOC)
            : [];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Videos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light py-5">

<div class="container">
       <div>
    <a href="../index.php">Home</a>
  </div>
    <h2 class="text-center mb-4">🎬 Manage Videos</h2>

    <div class="text-end mb-3">
        <a href="upload2.php" class="btn btn-success">Upload New Video</a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Video</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php if (empty($videos)): ?>
                    <tr>
                        <td colspan="6" class="text-center">No videos found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($videos as $index => $video): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($video['title']) ?></td>
                            <td><?= htmlspecialchars($video['description']) ?></td>

                            <td>
                                <video width="200" controls>
                                    <source src="<?= htmlspecialchars($video['video_url']) ?>" type="video/mp4">
                                </video>
                            </td>

                            <td><?= $video['created_at'] ?></td>

                            <td>
                                <!-- <a href="update_video.php?id=<?= $video['id'] ?>" class="btn btn-primary btn-sm">Edit</a> -->
                                <a href="upate_video2.php?id=<?= $video['id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                               <?php echo $video['id']?>
                                <a href=" Edit_Video.php?delete_id=<?= $video['id'] ?>"
                                   onclick="return confirm('Are you sure you want to delete this video?');"
                                   class="btn btn-danger btn-sm">
                                   Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>

        </table>
    </div>
</div>

</body>
</html>
