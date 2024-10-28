<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$conn = new mysqli("localhost", "root", "justine123", "practiceapp4");
if (!$conn) {
    echo "Not connected";
}

mysqli_set_charset($conn, 'utf8');
date_default_timezone_set('Asia/Manila');
$currentDate = date('Y-m-d H:i:s');