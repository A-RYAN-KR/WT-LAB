<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
$conn = new mysqli("localhost", "root", "", "electricity_db");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $name = $data['name'];
    $units = (float)$data['units'];
    $bill = 0;

    // Slab Logic
    if ($units <= 50) {
        $bill = $units * 3.50;
    } else if ($units <= 150) {
        $bill = (50 * 3.50) + (($units - 50) * 4.00);
    } else if ($units <= 250) {
        $bill = (50 * 3.50) + (100 * 4.00) + (($units - 150) * 5.20);
    } else {
        $bill = (50 * 3.50) + (100 * 4.00) + (100 * 5.20) + (($units - 250) * 6.50);
    }

    $sql = "INSERT INTO bills (customer_name, units, total_amount) VALUES ('$name', $units, $bill)";
    
    if ($conn->query($sql)) {
        echo json_encode(["status" => "success", "amount" => $bill]);
    }
}
?>