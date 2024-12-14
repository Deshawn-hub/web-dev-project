<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once 'database.php';

// Get list of users
$users_query = "SELECT id, firstname, lastname, email, role, created_at FROM Users ORDER BY created_at DESC";
$users = $conn->query($users_query);

// Handle form submission for new user
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = sanitizeInput($conn, $_POST['firstname']);
    $lastname = sanitizeInput($conn, $_POST['lastname']);
    $email = sanitizeInput($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = sanitizeInput($conn, $_POST['role']);
    
    $insert_query = "INSERT INTO Users (firstname, lastname, email, password, role) 
                    VALUES (?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("sssss", $firstname, $lastname, $email, $password, $role);
    
    if ($stmt->execute()) {
        header('Location: users.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users - Dolphin CRM</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f3f4f6;
        }

        .header {
            background-color: #1a1f36;
            color: white;
            padding: 15px;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 100;
            display: flex;
            align-items: center;
            gap: 10px;
            box-sizing: border-box;
        }

        .header img {
            height: 20px;
            width: 20px;
            vertical-align: middle;
            object-fit: contain;
        }

        .header-text {
            font-size: 16px;
            font-weight: 500;
            line-height: 20px;
        }

        .sidebar {
            width: 200px;
            background-color: white;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            border-right: 1px solid #e5e7eb;
            padding-top: 60px;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            color: #1a1f36;
            text-decoration: none;
            font-size: 14px;
        }

        .sidebar a:hover, 
        .sidebar a.active {
            background-color: #f3f4f6;
        }

        .content {
            margin-left: 200px;
            padding: 80px 20px 20px;
        }

        .users-wrapper {
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .users-title {
            font-size: 18px;
            font-weight: bold;
            color: #1a1f36;
            margin-bottom: 15px;
        }

        .users-container {
            background: white;
            border-radius: 8px;
            padding: 20px;
            width: 100%;
        }

        .users-header {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }

        .add-user-button {
            background-color: #4f46e5;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
        }

        .add-user-button:hover {
            background-color: #4338ca;
        }

        .users-table {
            width: 100%;
            border-collapse: collapse;
        }

        .users-table th,
        .users-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
        }

        .users-table th {
            background-color: #f9fafb;
            font-weight: 500;
            color: #6b7280;
        }

        .users-table tr:hover {
            background-color: #f9fafb;
        }

        .role-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }

        .role-admin {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .role-member {
            background-color: #e0e7ff;
            color: #3730a3;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="dolphin-icon.png" alt="Dolphin Logo">
        <span class="header-text">Dolphin CRM</span>
    </div>

    <div class="sidebar">
        <a href="dashboard.php">🏠 Home</a>
        <a href="new-contact.php">➕ New Contact</a>
        <a href="users.php" class="active">👥 Users</a>
        <a href="logout.php">🚪 Logout</a>
    </div>

    <div class="content">
        <div class="users-wrapper">
            <div class="users-title">Users</div>
            <div class="users-container">
                <div class="users-header">
                    <button class="add-user-button">+ Add User</button>
                </div>
                <table class="users-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($user = $users->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user['firstname'] . ' ' . $user['lastname']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td>
                                    <span class="role-badge <?php echo strtolower($user['role']) === 'admin' ? 'role-admin' : 'role-member'; ?>">
                                        <?php echo htmlspecialchars($user['role']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('Y-m-d H:i', strtotime($user['created_at'])); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>