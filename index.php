<?php
include 'dbcon.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name']);
    $email = trim($_POST['email']);

    if ($name !== '' && $email !== '') {
        $stmt = $conn->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $email);
        $stmt->execute();
        header("Location: index.php?msg=added");
        exit;
    }
}

$result = $conn->query("SELECT * FROM users ORDER BY id DESC");
$users = $result->fetch_all(MYSQLI_ASSOC);

$msg = $_GET['msg'] ?? '';
$alerts = [
    'added'   => ['success', 'User added successfully.'],
    'updated' => ['info',    'User updated successfully.'],
    'deleted' => ['danger',  'User deleted successfully.'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Simple Pure PHP MySQL App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
    <h1 class="text-center mb-4">User Management System</h1>

    <?php if (isset($alerts[$msg])): ?>
        <div class="alert alert-<?= $alerts[$msg][0] ?> alert-dismissible fade show">
            <?= $alerts[$msg][1] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Left -->
    <div class="row g-4">
        <div class="col-md-5">
            <h5 class="mb-3">Add New User</h5>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control"
                           placeholder="Enter Full Name" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control"
                           placeholder="Enter Email Address" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Submit</button>
            </form>
        </div>

        <!-- Right -->
        <div class="col-md-7">
            <h5 class="mb-3">Registered Users</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Joined</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (count($users) > 0): ?>
                        <?php foreach ($users as $row): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= date('M d, Y', strtotime($row['created_at'])) ?></td>
                            <td>
                                <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger"
                                   onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center text-muted">No data found</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>