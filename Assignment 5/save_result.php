<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
$conn = new mysqli("localhost", "root", "", "vit_results");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $calculate = function($mse, $ese) { return ($mse * 0.3) + ($ese * 0.7); };

    $dsa = $calculate($data['dsa_mse'], $data['dsa_ese']);
    $os = $calculate($data['os_mse'], $data['os_ese']);
    $dbms = $calculate($data['dbms_mse'], $data['dbms_ese']);
    $toc = $calculate($data['toc_mse'], $data['toc_ese']);
    $avg = ($dsa + $os + $dbms + $toc) / 4;

    $sql = "INSERT INTO results (name, prn, dsa_total, os_total, dbms_total, toc_total, final_percentage) 
            VALUES ('{$data['name']}', '{$data['prn']}', $dsa, $os, $dbms, $toc, $avg)";

    if ($conn->query($sql)) {
        echo json_encode(["status" => "success", "percentage" => $avg]);
    }
}
?>