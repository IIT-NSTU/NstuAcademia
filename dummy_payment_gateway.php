<?php
$amount = $_GET['amount'] ?? 0;
$studentId = $_GET['studentId'] ?? null;

if (!$amount || !$studentId) {
    die("Invalid payment details.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dummy Payment Gateway</title>
</head>
<body>
    <h1>Dummy Payment Gateway</h1>
    <p>Student ID: <?= htmlspecialchars($studentId) ?></p>
    <p>Total Amount: <?= htmlspecialchars($amount) ?> BDT</p>

    <form action="payment_success.php" method="POST">
        <input type="hidden" name="studentId" value="<?= htmlspecialchars($studentId) ?>">
        <input type="hidden" name="amount" value="<?= htmlspecialchars($amount) ?>">
        <input type="hidden" name="status" value="success">
        <button type="submit">Simulate Payment Success</button>
    </form>

    <form action="payment_fail.php" method="POST">
        <input type="hidden" name="status" value="failure">
        <button type="submit">Simulate Payment Failure</button>
    </form>
</body>
</html>
