<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>

<head>
    <title>Digital Banking — User Portal</title>
    <link rel="stylesheet" href="layout.css">
</head>

<body>

<div class="navbar">
    <div>Digital Banking — User Portal</div>

    <div>
        <a href="index.php">Home</a>
        <a href="create_ticket.php">Official Ticket (SQL)</a>
        <a href="submit_help.php">Questions & Help (NoSQL)</a>
        
        <a href="add_card.php">Add Card</a>
        <a href="pay_bill.php">Pay Bill</a>
    </div>
</div>

<div class="container">

    <div class="card">
        <h2>Welcome</h2>

        <p>
            From this main page, you can access all banking features implemented
            in Phase-2 and Phase-3.
        </p>
    </div>

    <div class="card">
        <h2>Available Operations</h2>

        <ul>
            <li>
                <strong>Official Support Ticket:</strong> Create formal requests using Stored Procedures
                <span style="color: #666; font-size: 0.9em;">(MySQL)</span>.
            </li>
            <li>
                <strong>Questions & Help Center:</strong> Submit general inquiries and feedback
                <span style="color: #d35400; font-weight: bold; font-size: 0.9em;">(MongoDB / NoSQL Integration)</span>.
            </li>
            <li>
                <strong>Card Management:</strong> Add new bank cards securely.
            </li>
            <li>
                <strong>Payments:</strong> Pay bills and automatically trigger balance & score updates
                <span style="color: #666; font-size: 0.9em;">(SQL Triggers)</span>.
            </li>
        </ul>

        <p>
            Please select one of the actions from the navigation bar above.
        </p>
    </div>

</div>

</body>
</html>
