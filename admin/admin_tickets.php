<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'db_mongo.php';

$message = "";

if (isset($_POST['update_ticket'])) {

    $ticket_id   = $_POST['ticket_id'];
    $admin_reply = trim($_POST['admin_reply']);
    $action      = $_POST['status']; // Keep / Closed

    try {
        $mongoID = new MongoDB\BSON\ObjectId($ticket_id);

        // --- BASE UPDATE ---
        $update = [
            '$set' => [
                'updated_at' => new MongoDB\BSON\UTCDateTime()
            ]
        ];

        // 🔴 Eğer admin bir şey yazdıysa -> ARRAY'E PUSH
        if ($admin_reply !== "") {
            $update['$push'] = [
                'admin_reply' => "admin: " . $admin_reply
            ];
        }

        // 🔴 Eğer çözüldüyse -> status = false
        if ($action === "Closed") {
            $update['$set']['status'] = false;
        }

        $collection->updateOne(
            ['_id' => $mongoID],
            $update
        );

        header("Location: admin_tickets.php?msg=updated");
        exit();

    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
    }
}

if (isset($_GET['msg']) && $_GET['msg'] === 'updated') {
    $message = "Ticket updated successfully!";
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Manage Tickets - Admin</title>
<link rel="stylesheet" href="admin_layout.css">
</head>

<body>

<div class="navbar">
    <div>ADMIN PANEL</div>
    <div>
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="admin_tickets.php">Manage Tickets</a>
    </div>
</div>

<div class="container">

<div class="card">
    <h2>Incoming Support Tickets (MongoDB)</h2>

    <?php if ($message): ?>
        <div style="padding:10px; background:#e8f5e9; color:green;">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>
</div>

<?php
// SADECE status = true olanlar
$cursor = $collection->find(
    ['status' => true],
    ['sort' => ['created_at' => -1]]
);

foreach ($cursor as $doc):

    $doc_id = (string)$doc['_id'];
?>
<div class="ticket-box">

    <h3><?php echo $doc['subject']; ?></h3>

    <p><b>Customer:</b> <?php echo $doc['customer_id']; ?></p>

    <p><?php echo nl2br($doc['message']); ?></p>

    <!-- ADMIN REPLY HISTORY -->
    <div style="margin:10px 0; padding:10px; background:#fafafa;">
        <b>Admin Replies:</b><br>

        <?php
        if (!empty($doc['admin_reply'])) {
            foreach ($doc['admin_reply'] as $rep) {
                echo "<div>• " . htmlspecialchars($rep) . "</div>";
            }
        } else {
            echo "<i>No reply yet.</i>";
        }
        ?>
    </div>

    <form method="POST">

        <input type="hidden" name="ticket_id" value="<?php echo $doc_id; ?>">

        <textarea name="admin_reply" rows="3" style="width:100%;" placeholder="Write a reply..."></textarea>

        <select name="status" style="margin-top:10px;">
            <option value="Keep">Keep Open</option>
            <option value="Closed">Mark as Solved</option>
        </select>

        <button type="submit" name="update_ticket" style="margin-top:10px;">
            Update Ticket
        </button>

    </form>

</div>

<?php endforeach; ?>

</div>
</body>
</html>
