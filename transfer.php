<?php
include 'db.php';

if (isset($_POST['sender_id'], $_POST['receiver_id'], $_POST['amount'])) {
    $sender_id = intval($_POST['sender_id']);
    $receiver_id = intval($_POST['receiver_id']);
    $amount = floatval($_POST['amount']);

    if ($sender_id > 0 && $receiver_id > 0 && $amount > 0) {
        // Start the transaction
        $conn->autocommit(FALSE);

        // Lock the rows for the sender and receiver to avoid race conditions
        $conn->query("SELECT balance FROM cust WHERE id IN ($sender_id, $receiver_id) FOR UPDATE");

        $sender_result = $conn->query("SELECT balance FROM cust WHERE id = $sender_id");
        $receiver_result = $conn->query("SELECT balance FROM cust WHERE id = $receiver_id");

        if ($sender_result->num_rows > 0 && $receiver_result->num_rows > 0) {
            $sender = $sender_result->fetch_assoc();
            $receiver = $receiver_result->fetch_assoc();

            if ($sender['balance'] >= $amount) {
                // Perform the transfer
                $update_sender = $conn->query("UPDATE cust SET balance = balance - $amount WHERE id = $sender_id");
                $update_receiver = $conn->query("UPDATE cust SET balance = balance + $amount WHERE id = $receiver_id");
                $insert_transfer = $conn->query("INSERT INTO trans (sender_id, receiver_id, anount) VALUES ($sender_id, $receiver_id, $amount)");

                if ($update_sender && $update_receiver && $insert_transfer) {
                    $conn->commit();
                    echo "Transfer successful!";
                } else {
                    $conn->rollback();
                    echo "Transfer failed. Please try again.";
                }
            } else {
                echo "Insufficient balance.";
                $conn->rollback();
            }
        } else {
            echo "Invalid sender or receiver ID.";
            $conn->rollback();
        }

        // End the transaction
        $conn->autocommit(TRUE);
    } else {
        echo "Invalid input. Ensure all fields are positive numbers.";
    }
} else {
    echo "Invalid input.";
}
?>

<html>
<head>
    <title>Customers</title>
    <link rel="stylesheet" href="styles.css">

</head>
<a href="index.php">Go Back</a>
</html>