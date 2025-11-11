<?php
include('../configs/database.php');
include('../configs/Session.php');
include('vendor/autoload.php');

use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;
use PhpParser\Node\Stmt\TryCatch;

Configuration::instance([
    'cloud'=>[
        'cloud_name'=>'dggldmj6z',
        'api_key'=>'597569858316692',
        'api_secret'=>'nzOwOOVFsPNNGqB56R8SiQbcJ2M'],
     'url'=> ['secure'=>true]   
    ]);
  
if(!isset($_GET['id'])){
echo 'invalid video';
}
$id=$_GET['id'];
$sql="SELECT  public_id  FRom videos where id= $id";
$res=$conn->query($sql);
if(mysqli_num_rows($res)){
$video=$res->fetch_assoc();
$public_id=$video['public_id'];



if(isset($_POST['submit'])){
extract($_POST);

if(!empty($_FILES['video']['name'])){

//  delete previse   
if(!empty($public_id)){

    try{
        (new UploadApi())->destroy($public_id,[ 'resource_type' => 'video']);
    }
    catch(Exception $e){
        echo 'could not delete'; }}
    
    }
}
try{
    $upload =(new UploadApi())->upload($_FILES['video']['tmp_name'],[ 'resource_type' => 'video']);
    $newvideo_url=$upload['secure_url'];
    $newpublic_id=$upload['public_url'];


}
catch(Exception $e){
die('upload faild '.$e->getMessage());
}
$updateSql="UPDATE videos   
            SET  title='$title',description='$description' ,video_url='$newvideo_url',public_id=' $newpublic_id' WHERE id=$id";
$result=$conn->query($updateSql);
if($result){
      header("Location: Edit_video.php");
}else{
    echo 'video could not upload ';
}
}



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
      <form action="update_video.php" method="post" enctype="multipart/form-data">
        <div class="mb-3">
          <label class="form-label">Video Title</label>
          <input type="text" name="title" class="form-control" >
        </div>
        <div class="mb-3">
          <label class="form-label">Video description</label>
          <input type="text" name="description" class="form-control">
        </div>
        <div class="mb-3">
          <label class="form-label">Select Video</label>
          <input type="file" name="video" class="form-control" accept="video/*" >
        </div>
        <button type="submit" class="btn btn-primary w-100">Upload</button>
      </form>
    </div>
  </div>
</body>
</html>