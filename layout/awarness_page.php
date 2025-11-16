<?php
// session_start();
$allowed_roles = ['user']; 
include('../configs/auth.php'); 
include('../configs/database.php'); // Your DB connection
// Your DB connection


// Fetch videos from database
$sql = "SELECT * FROM videos ORDER BY created_at DESC";
$result = $conn->query($sql);
$videos = [];
if ($result && $result->num_rows > 0) {
    $videos = $result->fetch_all(MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Awareness Videos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
      <div>
    <a href="../index.php"><h2>Home</h2></a>
  </div>
<div class="container py-5">
    <h2 class="text-center mb-4">📹 Awareness Videos</h2>

    <?php if (empty($videos)): ?>
        <p class="text-center">No videos available at the moment.</p>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($videos as $video): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card shadow-sm">
                        <video class="w-100" controls>
                            <source src="<?= htmlspecialchars($video['video_url']) ?>" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($video['title']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($video['description']) ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
