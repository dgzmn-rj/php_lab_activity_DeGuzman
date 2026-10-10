<?php
include 'dbcon.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name']);
    $email = trim($_POST['email']);

    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
    $stmt->bind_param("ssi", $name, $email, $id);
    $stmt->execute();
    header("Location: index.php?msg=updated");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5" style="max-width: 600px;">
    <div class="card shadow-sm">
        <div class="card-header">Edit User</div>
        <div class="card-body">
            <form method="POST" action="edit.php?id=<?= $user['id'] ?>">
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control"
                           value="<?= htmlspecialchars($user['name']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control"
                           value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Update User</button>
                <a href="index.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>