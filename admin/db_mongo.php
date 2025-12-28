<?php
require '../vendor/autoload.php';
try {
    $mongoClient = new MongoDB\Client("mongodb://localhost:27017");
    
    $collection = $mongoClient->banking_app->support_tickets;

} catch (Exception $e) {
    die("Error: Could not connect to MongoDB. " . $e->getMessage());
}
?>
