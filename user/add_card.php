<?php
include 'db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $customer_id = $_POST['customer_id'];
    $card_type   = $_POST['card_type'];

    // Generate card details automatically
    $card_number = rand(100000000, 999999999); // 9-digit card number
    $cvv = rand(100, 999);
    $exp = date('Y-m-d', strtotime('+4 years')); // 4 years from now

    $query = "
        CALL AddNewCard(
            $card_number,
            '$customer_id',
            '$exp',
            '$card_type',
            $cvv
        )
    ";

    if (mysqli_query($connection, $query)) {
        $message = "Card added successfully using stored procedure!";
    } else {
        $message = "Error: " . mysqli_error($connection);
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Card</title>
    <link rel="stylesheet" href="layout.css">
</head>

<body>

<div class="navbar">
    <div>Digital Banking — User Portal</div>

    <div>
        <a href="index.php">Home</a>
        <a href="create_ticket.php">Create Ticket</a>
        <a href="add_card.php">Add Card</a>
    </div>
</div>

<div class="container">

    <div class="card">
        <h2>Add New Card</h2>

        <form method="POST">

            <label>Customer ID</label>
            <input type="text" name="customer_id" required>

            <label>Card Type</label>
            <input type="text" name="card_type" placeholder="Virtual / Physical" required>


            <button type="submit">Add Card</button>

        </form>

        <br>

        <?php if($message != ""): ?>
            <p class="<?php echo (str_contains($message, 'Error') ? 'error' : 'success'); ?>">
                <?php echo $message; ?>
            </p>
        <?php endif; ?>

    </div>

</div>

</body>
</html>
