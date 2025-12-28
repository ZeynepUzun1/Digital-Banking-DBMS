<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'db_mongo.php';

$message = "";

if (isset($_POST['gonder'])) {

    $customer_id = $_POST['customer_id'];
    $subject     = $_POST['subject'];
    $msg_text    = $_POST['message'];
    $priority    = $_POST['priority'];

    $veri_paketi = [
        "customer_id" => $customer_id,
        "subject"     => $subject,
        "message"     => $msg_text,
        "priority"    => $priority,

        // aktif ticket (BOOLEAN)
        "status"      => true,

        "admin_reply" => [],
        "created_at"  => new MongoDB\BSON\UTCDateTime(),
        "updated_at"  => null
    ];

    try {

        $sonuc = $collection->insertOne($veri_paketi);

        if ($sonuc->getInsertedCount() == 1) {
            $message = "Support request submitted successfully!";
        }

    } catch (Exception $e) {
        $message = "MongoDB Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>MongoDB Support</title>
    <link rel="stylesheet" href="layout.css">
</head>

<body>

<div class="navbar">
    <div>User Portal</div>
    <div>
        <a href="index.php">Home</a>
        <a href="submit_help.php">Support</a>
    </div>
</div>

<div class="container">

    <div class="card">
        <h2>Create Support Request</h2>

        <form method="POST">

            <label>Customer ID:</label>
            <input type="text" name="customer_id" required>

            <label>Subject:</label>
            <input type="text" name="subject" required>

            <label>Priority:</label>
            <select name="priority">
                <option value="Low">Low</option>
                <option value="Medium">Medium</option>
                <option value="High">High</option>
            </select>

            <label>Your Message:</label>
            <textarea name="message" rows="4"></textarea>

            <button type="submit" name="gonder">Submit</button>
        </form>

        <p><b><?php echo $message; ?></b></p>
    </div>

</div>

</body>
</html>
