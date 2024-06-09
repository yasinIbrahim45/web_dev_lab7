<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.html");
    exit();
}

include_once 'Database.php';
include_once 'User.php';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $matric = $_POST['matric'];
    $name = $_POST['name'];
    $role = $_POST['role'];
    $result = $user->updateUser($matric, $name, $role);
    if ($result === true) {
        echo "<script>alert('User updated successfully.'); window.location.href='users.php';</script>";
    } else {
        echo $result;
    }
} elseif (isset($_GET['matric'])) {
    $matric = $_GET['matric'];
    $userData = $user->getUser($matric);
    if (!$userData) {
        echo "User not found.";
        exit();
    }
} else {
    header("Location: users.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Update User</h2>
    <form action="update.php" method="POST">
        <div class="form-group">
            <label for="matric">Matric:</label>
            <input type="text" id="matric" name="matric" value="<?php echo htmlspecialchars($userData['matric']); ?>" readonly>
        </div>
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($userData['name']); ?>" readonly>
        </div>
        <div class="form-group">
            <label for="role">Role:</label>
            <select id="role" name="role">
                <option value="lecturer" <?php if ($userData['role'] == 'lecturer') echo 'selected'; ?>>Lecturer</option>
                <option value="student" <?php if ($userData['role'] == 'student') echo 'selected'; ?>>Student</option>
            </select>
        </div>
        <button type="submit" class="btn-update">Update</button>
        <button type="button" class="btn-cancel" onclick="window.location.href='users.php'">Cancel</button>
    </form>
</body>
</html>
