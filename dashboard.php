<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once 'database.php';

// Get contacts query
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$user_id = $_SESSION['user_id'];

$contacts_query = "SELECT c.*, u.firstname as assigned_firstname, u.lastname as assigned_lastname 
                  FROM Contacts c 
                  LEFT JOIN Users u ON c.assigned_to = u.id 
                  WHERE 1=1";

// Apply filters
switch($filter) {
    case 'sales':
        $contacts_query .= " AND c.type = 'Sales Lead'";
        break;
    case 'support':
        $contacts_query .= " AND c.type = 'Support'";
        break;
    case 'assigned':
        $contacts_query .= " AND c.assigned_to = $user_id";
        break;
}

$contacts_query .= " ORDER BY c.created_at DESC";
$contacts = $conn->query($contacts_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Dolphin CRM</title>
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

        .dashboard-wrapper {
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .dashboard-title {
            font-size: 18px;
            font-weight: bold;
            color: #1a1f36;
            margin-bottom: 15px;
        }

        .dashboard-container {
            background: white;
            border-radius: 8px;
            padding: 20px;
            width: 100%;
        }

        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .filter-section {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-bottom: 20px;
        }

        .filter-label {
            font-size: 14px;
            color: #6b7280;
        }

        .filter-button {
            padding: 6px 12px;
            border: 1px solid #e5e7eb;
            background: none;
            border-radius: 4px;
            font-size: 13px;
            color: #6b7280;
            cursor: pointer;
        }

        .filter-button.active {
            background-color: #f3f4f6;
            color: #1a1f36;
            font-weight: 500;
        }

        .add-contact-button {
            background-color: #4f46e5;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
        }

        .contacts-table {
            width: 100%;
            border-collapse: collapse;
        }

        .contacts-table th,
        .contacts-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
        }

        .contacts-table th {
            font-weight: 500;
            color: #6b7280;
        }

        .type-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }

        .type-sales {
            background-color: #fef3c7;
            color: #92400e;
        }

        .type-support {
            background-color: #e0e7ff;
            color: #3730a3;
        }

        .view-link {
            color: #4f46e5;
            text-decoration: none;
            font-size: 14px;
        }

        .view-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="dolphin-icon.png" alt="Dolphin Logo">
        <span class="header-text">Dolphin CRM</span>
    </div>

    <div class="sidebar">
        <a href="dashboard.php" class="active">🏠 Home</a>
        <a href="new-contact.php">➕ New Contact</a>
        <a href="users.php">👥 Users</a>
        <a href="logout.php">🚪 Logout</a>
    </div>

    <div class="content">
        <div class="dashboard-wrapper">
            <div class="dashboard-title">Dashboard</div>
            <div class="dashboard-container">
                <div class="dashboard-header">
                    <div class="filter-section">
                        <span class="filter-label">Filter By:</span>
                        <a href="?filter=all" class="filter-button <?php echo $filter === 'all' ? 'active' : ''; ?>">All</a>
                        <a href="?filter=sales" class="filter-button <?php echo $filter === 'sales' ? 'active' : ''; ?>">Sales Leads</a>
                        <a href="?filter=support" class="filter-button <?php echo $filter === 'support' ? 'active' : ''; ?>">Support</a>
                        <a href="?filter=assigned" class="filter-button <?php echo $filter === 'assigned' ? 'active' : ''; ?>">Assigned to me</a>
                    </div>
                    <a href="new-contact.php" class="add-contact-button">+ Add Contact</a>
                </div>
                <table class="contacts-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Company</th>
                            <th>Type</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($contact = $contacts->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($contact['title'] . ' ' . $contact['firstname'] . ' ' . $contact['lastname']); ?></td>
                                <td><?php echo htmlspecialchars($contact['email']); ?></td>
                                <td><?php echo htmlspecialchars($contact['company']); ?></td>
                                <td>
                                    <span class="type-badge <?php echo $contact['type'] === 'Sales Lead' ? 'type-sales' : 'type-support'; ?>">
                                        <?php echo htmlspecialchars($contact['type']); ?>
                                    </span>
                                </td>
                                <td><a href="view-contact.php?id=<?php echo $contact['id']; ?>" class="view-link">View</a></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>