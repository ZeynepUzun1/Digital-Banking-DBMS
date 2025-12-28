<?php
include 'db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$message = "";
$bill = null;

if (isset($_POST['find_account'])) {

    $account_number = $_POST['account_number'];

    $sql = "
        SELECT bill_id, account_number, bill_type, bill_amount,
               bstart_date, bdue_date
        FROM Paid_Bill
        WHERE account_number = '$account_number'
        LIMIT 1
    ";

    $result = mysqli_query($connection, $sql);

    if (!$result) {
        die("FIND QUERY ERROR: " . mysqli_error($connection));
    }

    if (mysqli_num_rows($result) > 0) {
        $bill = mysqli_fetch_assoc($result);
    } else {
        $message = "Error: No bill found for this account number.";
    }
}

if (isset($_POST['pay_bill'])) {

    $bill_id        = $_POST['bill_id'];
    $account_number = $_POST['account_number'];
    $bill_type      = $_POST['bill_type'];
    $bill_amount    = $_POST['bill_amount'];
    $bstart_date    = $_POST['bstart_date'];
    $bdue_date      = $_POST['bdue_date'];

    // today
    $payment_date = date('Y-m-d');

    $delete_sql = "DELETE FROM Paid_Bill WHERE bill_id = '$bill_id'";

    if (!mysqli_query($connection, $delete_sql)) {
        die("DELETE ERROR: " . mysqli_error($connection));
    }

    /* trigger */
    $insert_sql = "
        INSERT INTO Paid_Bill
            (bill_id, account_number, bill_type, bill_amount,
             bstart_date, bdue_date, payment_date)
        VALUES
            ('$bill_id', '$account_number', '$bill_type',
             '$bill_amount', '$bstart_date', '$bdue_date', '$payment_date')
    ";

    if (!mysqli_query($connection, $insert_sql)) {
        die("INSERT ERROR: " . mysqli_error($connection));
    }

    $score_sql = "
        SELECT c.full_name, c.credit_score
        FROM Customer c
        JOIN Maintains m ON c.customer_id = m.customer_id
        WHERE m.account_number = '$account_number'
    ";

    $score_res = mysqli_query($connection, $score_sql);

    if (!$score_res) {
        die("SCORE QUERY ERROR: " . mysqli_error($connection));
    }

    if ($row = mysqli_fetch_assoc($score_res)) {

        $customer_name = $row['full_name'];
        $updated_score = $row['credit_score'];

        if ($payment_date > $bdue_date) {
            $message = "
                Payment Late! Credit Score Decreased.<br>
                Customer: <b>$customer_name</b><br>
                New Score: <b>$updated_score</b>
            ";
        }
        else {
            $message = "
                Payment On Time! Credit Score Increased.<br>
                Customer: <b>$customer_name</b><br>
                New Score: <b>$updated_score</b>
            ";
        }

    } else {
        $message = "Bill paid successfully — but customer score could not be fetched.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pay Bill</title>
    <link rel="stylesheet" href="layout.css">
    <style>
        .result-box {
            background-color: #f0f8ff;
            border: 2px solid #4b6fe4;
            padding: 15px;
            border-radius: 10px;
            margin-top: 20px;
            text-align: center;
            font-size: 18px;
        }
    </style>
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
        <h2>Find Bill to Pay</h2>

        <form method="POST">
            <label>Account Number (e.g., AC1008 or AC1005)</label>
            <input type="text" name="account_number" placeholder="Enter Account Number" required>
            <button type="submit" name="find_account">Find Bill</button>
        </form>
    </div>

    <?php if ($bill): ?>
    <div class="card">
        <h2>Bill Found</h2>

        <p>Payment date will be set to:
            <strong><?php echo date('Y-m-d'); ?> (Today)</strong>
        </p>

        <form method="POST">
            <input type="hidden" name="bill_id" value="<?php echo $bill['bill_id']; ?>">
            <input type="hidden" name="account_number" value="<?php echo $bill['account_number']; ?>">
            <input type="hidden" name="bill_type" value="<?php echo $bill['bill_type']; ?>">
            <input type="hidden" name="bill_amount" value="<?php echo $bill['bill_amount']; ?>">
            <input type="hidden" name="bstart_date" value="<?php echo $bill['bstart_date']; ?>">
            <input type="hidden" name="bdue_date" value="<?php echo $bill['bdue_date']; ?>">

            <p>
                <strong>Bill ID:</strong> <?php echo $bill['bill_id']; ?><br>
                <strong>Account:</strong> <?php echo $bill['account_number']; ?><br>
                <strong>Type:</strong> <?php echo $bill['bill_type']; ?><br>
                <strong>Amount:</strong> $<?php echo $bill['bill_amount']; ?><br>
                <strong>Due Date:</strong>
                <span style="color:red; font-weight:bold;">
                    <?php echo $bill['bdue_date']; ?>
                </span>
            </p>

            <button type="submit" name="pay_bill" style="background-color:#28a745;">
                Pay Bill Now
            </button>
        </form>
    </div>
    <?php endif; ?>

    <?php if ($message): ?>
    <div class="result-box">
        <?php echo $message; ?>
    </div>
    <?php endif; ?>

</div>

</body>
</html>
