<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once 'database.php';

// Get list of users for the Assigned To dropdown
$users_query = "SELECT id, firstname, lastname FROM Users ORDER BY firstname, lastname";
$users = $conn->query($users_query);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Form processing code here (same as before)
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact - Dolphin CRM</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f3f4f6;
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

        .sidebar a:hover {
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
            gap: 10px;  /* Adds space between logo and text */
        }

        .header img {
            height: 20px;
            width: auto;
            vertical-align: middle;
        }

        .header-text {
            font-size: 16px;
            font-weight: 500;
        }
        .content {
            margin-left: 200px;
            padding: 80px 20px 20px;
        }

        .new-contact-form {
            background: white;
            border-radius: 8px;
            padding: 40px;  /* Increased padding from 20px to 40px */
            max-width: 1000px;  /* Increased from 800px to 1000px */
            margin: 0 auto;
            width: 90%;  /* Added to ensure responsiveness */
        }

        .form-title {
            font-size: 18px;
            font-weight: 500;
            margin-bottom: 30px;  /* Increased from 20px */
            color: #1a1f36;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;  /* Increased from 20px */
            margin-bottom: 30px;  /* Increased from 20px */
        }

        .form-group {
            margin-bottom: 30px;  /* Increased from 20px */
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;  /* Increased from 5px */
            color: #6b7280;
            font-size: 14px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;  /* Added to ensure padding doesn't affect width */
        }

        .full-width {
            grid-column: 1 / -1;
        }

        .save-button {
            background-color: #4f46e5;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            margin-top: 20px;
        }

        .save-button:hover {
            background-color: #4338ca;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="dolphin-icon.png" alt="" style="height: 20px; vertical-align: middle;">
        Dolphin CRM
    </div>

    <div class="sidebar">
        <a href="dashboard.php">🏠 Home</a>
        <a href="new-contact.php" style="background-color: #f3f4f6;">➕ New Contact</a>
        <a href="users.php">👥 Users</a>
        <a href="logout.php">🚪 Logout</a>
    </div>

    <div class="content">
        <div class="new-contact-form">
            <div class="form-title">New Contact</div>
            <form method="POST" action="">
                <!-- Title field -->
                <div class="form-group">
                    <label for="title">Title</label>
                    <select id="title" name="title" style="width: 100px;">
                        <option value="Mr">Mr</option>
                        <option value="Ms">Ms</option>
                        <option value="Mrs">Mrs</option>
                        <option value="Dr">Dr</option>
                    </select>
                </div>

                <!-- Name fields -->
                <div class="form-grid">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="firstname">First Name</label>
                        <input type="text" id="firstname" name="firstname" placeholder="Jane">
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="lastname">Last Name</label>
                        <input type="text" id="lastname" name="lastname" placeholder="Doe">
                    </div>
                </div>

                <!-- Contact fields -->
                <div class="form-grid">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="something@example.com">
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="telephone">Telephone</label>
                        <input type="tel" id="telephone" name="telephone">
                    </div>
                </div>

                <!-- Company field -->
                <div class="form-group">
                    <label for="company">Company</label>
                    <input type="text" id="company" name="company">
                </div>

                <!-- Type and Assigned To fields -->
                <div class="form-grid">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="type">Type</label>
                        <select id="type" name="type">
                            <option value="Sales Lead">Sales Lead</option>
                            <option value="Support">Support</option>
                        </select>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="assigned_to">Assigned To</label>
                        <select id="assigned_to" name="assigned_to">
                            <?php while ($user = $users->fetch_assoc()): ?>
                                <option value="<?php echo $user['id']; ?>">
                                    <?php echo htmlspecialchars($user['firstname'] . ' ' . $user['lastname']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <!-- Save button -->
                <div style="text-align: right;">
                    <button type="submit" class="save-button">Save</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>