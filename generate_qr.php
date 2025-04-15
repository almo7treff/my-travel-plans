<?php
require 'config.php';

if (!isset($_GET['data'])) {
    die("No data provided for QR code!");
}

$data = $_GET['data'];

// تحميل مكتبة QR Code
require 'phpqrcode/qrlib.php';

// تحديد مسار حفظ الصورة
$output_file = 'qrcodes/' . md5($data) . '.png';
QRcode::png($data, $output_file, QR_ECLEVEL_L, 10);

// عرض الصورة
header('Content-Type: image/png');
readfile($output_file);
?>