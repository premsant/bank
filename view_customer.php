<?php
include 'db.php';

$id = intval($_GET['id']);
$customer_query = $conn->query("SELECT * FROM cust WHERE id = $id");

if ($customer_query->num_rows == 0) {
    echo "Customer not found.";
    exit;
}

$customer = $customer_query->fetch_assoc();
$customers = $conn->query("SELECT * FROM cust WHERE id != $id");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Details</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1><?= htmlspecialchars($customer['name']) ?>'s Profile</h1>
    <p>Email: <?= htmlspecialchars($customer['email']) ?></p>
    <p>Balance: $<?= htmlspecialchars($customer['balance']) ?></p>

    <h2>Transfer Money</h2>
    <form action="transfer.php" method="POST">
        <input type="hidden" name="sender_id" value="<?= htmlspecialchars($customer['id']) ?>">
        <label for="receiver_id">Transfer To:</label>
        <select name="receiver_id" required>
            <?php while ($row = $customers->fetch_assoc()): ?>
                <option value="<?= htmlspecialchars($row['id']) ?>"><?= htmlspecialchars($row['name']) ?></option>
            <?php endwhile; ?>
        </select>
        <label for="amount">Amount:</label>
        <input type="number" name="amount" step="0.01" min="0.01" required>
        <button type="submit">Transfer</button>
    </form>
</body>
</html>
