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
    if (isset($_POST['delete'])) {
        $matric = $_POST['matric'];
        $result = $user->deleteUser($matric);
        if ($result === true) {
            echo "<script>alert('User deleted successfully.');</script>";
        } else {
            echo $result;
        }
    }
}

$result = $user->getUsers();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users List</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="header">
        <h2>Hello, <?php echo htmlspecialchars($_SESSION['user']['name']); ?></h2>
        <button class="btn-logout" onclick="window.location.href='logout.php'">Logout</button>
    </div>
    <h2>Users List</h2>
    <table>
        <thead>
            <tr>
                <th>Matric</th>
                <th>Name</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <form action="users.php" method="POST">
                    <td><?php echo htmlspecialchars($row['matric']); ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['role']); ?></td>
                    <td>
                        <button type="button" onclick="window.location.href='update.php?matric=<?php echo htmlspecialchars($row['matric']); ?>'" class="btn-update">Update</button>
                        <input type="hidden" name="matric" value="<?php echo htmlspecialchars($row['matric']); ?>">
                        <button type="submit" name="delete" class="btn-delete">Delete</button>
                    </td>
                </form>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
