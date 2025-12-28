<?php
include("db.php");

$message = "";
$ticketInfo = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $customer_id = $_POST['customer_id'];
    $topic       = $_POST['topic'];
    $message_text= $_POST['message'];
    $type        = $_POST['type'];
    $extra       = $_POST['extra'];

    /*
    ----------------------------------
    STEP 1 — CHECK CUSTOMER EXISTS
    ----------------------------------
    */
    $checkCustomer = "
        SELECT customer_id
        FROM Customer
        WHERE customer_id = '$customer_id'
    ";

    $checkResult = mysqli_query($connection, $checkCustomer);

    if (!$checkResult) {
        $message = "Error: " . mysqli_error($connection);
    }
    elseif (mysqli_num_rows($checkResult) == 0) {
        $message = "Customer ID not found.";
    }
    else {

        /*
        ----------------------------------
        STEP 2 — GENERATE TICKET ID
        ----------------------------------
        */
        $getMax = "
            SELECT MAX(ticket_id) AS max_ticket
            FROM Customer_Ticket
        ";

        $maxResult = mysqli_query($connection, $getMax);
        $row = mysqli_fetch_assoc($maxResult);

        if ($row['max_ticket']) {
            $num = intval(substr($row['max_ticket'], 1)) + 1;
            $ticket_id = 'T' . str_pad($num, 3, '0', STR_PAD_LEFT);
        } else {
            $ticket_id = 'T001';
        }

        /*
        ----------------------------------
        STEP 3 — CALL STORED PROCEDURE
        ----------------------------------
        */
        $query = "
            CALL CreateTicket(
                '$ticket_id',
                '$customer_id',
                '$topic',
                '$message_text',
                '$type',
                '$extra'
            )
        ";

        if (mysqli_query($connection, $query)) {
            $message = "Ticket created successfully using stored procedure!";

            $ticketInfo = [
                'ticket_id' => $ticket_id,
                'customer_id' => $customer_id,
                'topic' => $topic,
                'message' => $message_text,
                'type' => $type,
                'extra' => $extra
            ];
        } else {
            $message = "Error: " . mysqli_error($connection);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Ticket</title>
    <link rel="stylesheet" href="layout.css">
</head>

<body>

<div class="navbar">
    <div>Digital Banking — User Portal</div>

    <div>
       <a href="index.php">Home</a>
       <a href="create_ticket.php">Create Ticket</a>
       <a href="add_card.php">Add Card</a>
       <a href="pay_bill.php">Pay Bill</a>
    </div>
</div>

<div class="container">

    <div class="card">
        <h2>Create Support Ticket</h2>

        <form method="POST">

            <label>Customer ID</label>
            <input type="text" name="customer_id" required>

            <label>Topic</label>
            <input type="text" name="topic" required>

            <label>Message</label>
            <input type="text" name="message" required>

            <label>Type</label>
            <select name="type">
                <option value="IT">IT</option>
                <option value="Consultation">Consultation</option>
            </select>

            <label>Extra info (device / advisor)</label>
            <input type="text" name="extra">

            <button type="submit">Create Ticket</button>

        </form>

        <br>

        <?php if($message != ""): ?>
            <p class="<?php echo (str_contains($message,'not') || str_contains($message,'Error')) ? 'error' : 'success'; ?>">
                <?php echo $message; ?>
            </p>
        <?php endif; ?>

    </div>

    <?php if ($ticketInfo): ?>
    <div class="card">
        <h2>Ticket Details</h2>

        <p>
            <strong>Ticket ID:</strong> <?php echo $ticketInfo['ticket_id']; ?><br>
            <strong>Customer ID:</strong> <?php echo $ticketInfo['customer_id']; ?><br>
            <strong>Topic:</strong> <?php echo $ticketInfo['topic']; ?><br>
            <strong>Message:</strong> <?php echo $ticketInfo['message']; ?><br>
            <strong>Type:</strong> <?php echo $ticketInfo['type']; ?><br>
            <strong>Extra Info:</strong> <?php echo ($ticketInfo['extra'] ?: '-'); ?>
        </p>
    </div>
    <?php endif; ?>

</div>

</body>
</html>
