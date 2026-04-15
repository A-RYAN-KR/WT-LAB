<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
$conn = new mysqli("localhost", "root", "", "bookstore");

$action = $_GET['action'] ?? '';
$data = json_decode(file_get_contents('php://input'), true);

if ($action == 'register') {
    $sql = "INSERT INTO users (name, email, password) VALUES ('{$data['name']}', '{$data['email']}', '{$data['pass']}')";
    echo json_encode(["success" => $conn->query($sql)]);
} 

elseif ($action == 'login') {
    $res = $conn->query("SELECT * FROM users WHERE email='{$data['email']}' AND password='{$data['pass']}'");
    echo json_encode(["success" => $res->num_rows > 0]);
} 

elseif ($action == 'catalog') {
    $res = $conn->query("SELECT * FROM books");
    echo json_encode($res->fetch_all(MYSQLI_ASSOC));
}
?>