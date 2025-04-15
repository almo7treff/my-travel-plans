<?php
require 'config.php';

if (!isset($_GET['serial_number'])) {
    die("Serial number is required.");
}

$serial_number = $_GET['serial_number'];

$stmt = $conn->prepare("SELECT * FROM inventory WHERE serial_number = ?");
$stmt->bind_param("s", $serial_number);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("No record found for this serial number.");
}

$item = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate</title>
    <style>
        .certificate {
            width: 80%;
            margin: auto;
            border: 1px solid #000;
            padding: 20px;
            font-family: Arial, sans-serif;
        }
        .qr-code {
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <h1>Gold Certificate</h1>
        <p><strong>Metal:</strong> <?= $item['metal'] ?></p>
        <p><strong>Weight (g):</strong> <?= $item['weight'] ?></p>
        <p><strong>Fineness (%):</strong> <?= $item['fineness'] ?></p>
        <p><strong>Serial Number:</strong> <?= $item['serial_number'] ?></p>
        <p><strong>Date:</strong> <?= $item['added_at'] ?></p>
        <div class="qr-code">
            <img src="generate_qr.php?data=<?= urlencode($item['serial_number']) ?>" alt="QR Code">
        </div>
    </div>
</body>
</html>