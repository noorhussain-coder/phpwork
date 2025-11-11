<?php
$allowed_roles=['admin'];
include('../configs/auth.php');
include('../configs/database.php');


if (!isset($_GET['id'])) {
    die("Invalid user ID");
}

$id = intval($_GET['id']);

$sql = "SELECT * FROM users WHERE id = $id";
$res = $conn->query($sql);

if ($res->num_rows == 0) {
    die("User not found");
}

$user = $res->fetch_assoc();

// ✅ When submit button clicked
if (isset($_POST['submit'])) {

    $role = $_POST['role']; // ✅ FIXED

    // ✅ Update role safely
    $stmt = $conn->prepare("UPDATE users SET role=? WHERE id=?");
    $stmt->bind_param("si", $role, $id);

    if ($stmt->execute()) {
        header("Location: role.php");
        exit;
    } else {
        echo "Update failed: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit User Role</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light py-5">

<div class="container">
    <div class="card p-4 shadow" style="max-width: 500px; margin:auto;">
        <h3 class="text-center mb-4">Edit User Role</h3>

        <!-- ✅ Submit to SAME FILE -->
        <form method="post"   >

            <div class="mb-3">
                <label class="form-label">User Name</label>
                <input type="text" value="<?= $user['username'] ?>" class="form-control" disabled>
            </div>

            <div class="mb-3">
                <label class="form-label">Role</label>
                <select name="role" class="form-select">
                    <option value="user"  <?= $user['role']=='user'?'selected':'' ?>>User</option>
                    <option value="admin" <?= $user['role']=='admin'?'selected':'' ?>>Admin</option>
                </select>
            </div>

            <button type="submit" name="submit" class="btn btn-primary w-100">Update Role</button>
        </form>
    </div>
</div>

</body>
</html>
