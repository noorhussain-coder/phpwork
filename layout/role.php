<?php
$allowed_roles=['admin'];
include('../configs/auth.php');
include('../configs/database.php');


// Fetch all users
$sql = "SELECT * FROM users ORDER BY id DESC";
$result = $conn->query($sql);

$users = ($result && $result->num_rows > 0)
         ? $result->fetch_all(MYSQLI_ASSOC)
         : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light py-5">
<div class="container">
          <div>
    <a href="../index.php">Home</a>
  </div>
    <h2 class="text-center mb-4">👥 All Users</h2>

    <div class="card shadow p-3">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="6" class="text-center">No users found.</td>
                    </tr>

                <?php else: ?>
                    <?php foreach ($users as $index => $user): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($user['username']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td>
                                <span class="badge bg-<?= $user['role'] == 'admin' ? 'primary' : 'secondary' ?>">
                                    <?= $user['role'] ?>
                                </span>
                            </td>
                            <td><?= $user['created_at'] ?></td>
                            <td>
                                <a href="update_role.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-warning">
                                    Edit Role
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
