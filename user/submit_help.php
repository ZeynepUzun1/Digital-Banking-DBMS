<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'db_mongo.php';
include 'db.php'; // MySQL bağlantısı için

$message = "";
$selected_customer = "";

// Yeni ticket oluşturma
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
        "status"      => true,
        "admin_reply" => [],
        "created_at"  => new MongoDB\BSON\UTCDateTime(),
        "updated_at"  => null
    ];

    try {
        $sonuc = $collection->insertOne($veri_paketi);

        if ($sonuc->getInsertedCount() == 1) {
            $message = "Support request submitted successfully!";
            $selected_customer = $customer_id;
        }

    } catch (Exception $e) {
        $message = "MongoDB Error: " . $e->getMessage();
    }
}

// Dropdown için seçim
if (isset($_POST['select_customer'])) {
    $selected_customer = $_POST['customer_id_select'];
}

// MySQL'den tüm customer ID'leri al
$all_customers = [];
$query = "SELECT customer_id FROM Customer ORDER BY customer_id";
$result = mysqli_query($connection, $query);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $all_customers[] = $row['customer_id'];
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

    <!-- DROPDOWN: Customer ID Seçimi -->
    <div class="card">
        <h2>View My Active Tickets</h2>
        
        <?php if (empty($all_customers)): ?>
            <p><i>No customers found in the system.</i></p>
        <?php else: ?>
            <form method="POST">
                <label>Select Your Customer ID:</label>
                <select name="customer_id_select" required>
                    <option value="">-- Select Customer ID --</option>
                    <?php foreach ($all_customers as $cust_id): ?>
                        <option value="<?php echo $cust_id; ?>"
                            <?php echo ($selected_customer === $cust_id) ? 'selected' : ''; ?>>
                            <?php echo $cust_id; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" name="select_customer">View My Tickets</button>
            </form>
        <?php endif; ?>
    </div>

    <!-- TICKET LİSTESİ -->
    <?php if ($selected_customer !== ""): ?>
        <div class="card">
            <h2>Active Tickets for: <?php echo htmlspecialchars($selected_customer); ?></h2>
            
            <?php
            $cursor = $collection->find(
                ['customer_id' => $selected_customer, 'status' => true],
                ['sort' => ['created_at' => -1]]
            );
            
            $has_tickets = false;
            foreach ($cursor as $ticket):
                $has_tickets = true;
            ?>
                <div style="border: 1px solid #ddd; padding: 15px; margin-bottom: 15px; border-radius: 8px; background: #f9f9f9;">
                    <h3><?php echo htmlspecialchars($ticket['subject']); ?></h3>
                    <p><b>Priority:</b> <?php echo htmlspecialchars($ticket['priority']); ?></p>
                    <p><b>Created:</b> <?php echo $ticket['created_at']->toDateTime()->format('Y-m-d H:i:s'); ?></p>
                    <p><b>Message:</b><br><?php echo nl2br(htmlspecialchars($ticket['message'])); ?></p>
                    
                    <!-- Admin Replies -->
                    <div style="margin-top: 10px; padding: 10px; background: #fafafa; border-radius: 5px;">
                        <b>Admin Replies:</b><br>
                        
                        <?php
                        // MongoDB BSON Array'i kontrol et
                        $has_replies = false;
                        if (isset($ticket['admin_reply'])) {
                            // BSONArray ise count() kullan, array ise count() kullan
                            if (is_countable($ticket['admin_reply']) && count($ticket['admin_reply']) > 0) {
                                $has_replies = true;
                            }
                        }
                        
                        if ($has_replies) {
                            foreach ($ticket['admin_reply'] as $reply) {
                                echo "<div style='margin-top: 5px;'>• " . htmlspecialchars($reply) . "</div>";
                            }
                        } else {
                            echo "<i>Waiting for admin response...</i>";
                        }
                        ?>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <?php if (!$has_tickets): ?>
                <p><i>You have no active tickets. Create a new support request below!</i></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- YENİ TICKET OLUŞTURMA FORMU -->
    <div class="card">
        <h2>Create New Support Request</h2>

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
            <textarea name="message" rows="4" required></textarea>

            <button type="submit" name="gonder">Submit</button>
        </form>

        <?php if ($message): ?>
            <p style="margin-top: 15px; padding: 10px; background: #e8f5e9; color: green; border-radius: 5px;">
                <b><?php echo $message; ?></b>
            </p>
        <?php endif; ?>
    </div>

</div>

</body>
</html>
