<?php
include 'db.php';

$result = $conn->query("SELECT * FROM cust");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customers</title>
    <link rel="stylesheet" href="styles.css">

</head>
<body>
    <h1>All Customers</h1>
    <ul>
        <?php while ($row = $result->fetch_assoc()): ?>
            <li>
                <a href="view_customer.php?id=<?= $row['id'] ?>">
                    <?= $row['name'] ?> (Balance: $<?= $row['balance'] ?>)
                </a>
            </li>
        <?php endwhile; ?>
    </ul>
</body>
</html>
