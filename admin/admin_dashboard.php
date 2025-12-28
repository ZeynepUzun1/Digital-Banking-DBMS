<!DOCTYPE html>
<html>
<head>
    <title>Admin Portal - Digital Banking</title>
    <link rel="stylesheet" href="admin_layout.css">
    <style>
        /* Admin Paneli olduğunu belli etmek için Kırmızı Menü */
        .navbar {
            background: linear-gradient(135deg, #e74c3c, #c0392b) !important;
        }
        .admin-btn {
            background-color: #c0392b !important;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-top: 10px;
        }
        .admin-btn:hover {
            background-color: #a93226 !important;
        }
    </style>
</head>

<body>

<div class="navbar">
    <div>ADMIN PANEL</div>
    <div>
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="admin_tickets.php">Manage Tickets</a>
        <a href="index.php" style="font-size:14px; opacity:0.8;">(Go to User App)</a>
    </div>
</div>

<div class="container">

    <div class="card">
        <h2>Welcome, Administrator</h2>
        <p>
            You are logged into the administrative area. Here you can review support requests
            submitted by users via the NoSQL (MongoDB) system.
        </p>
    </div>

    <div class="card">
        <h2>Pending Actions</h2>
        <ul>
            <li>
                <strong>Review Support Tickets:</strong> Check incoming messages from MongoDB and reply to users.
                <br><br>
                <a href="admin_tickets.php" class="admin-btn">Go to Ticket Manager</a>
            </li>
        </ul>
    </div>

</div>

</body>
</html>
