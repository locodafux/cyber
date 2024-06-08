<?php
// Establish a connection to the MySQL database
$servername = "localhost";
$username = "root";
$password = "";
$database = "cybervlog";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data based on the condition (where id = 1)
$id = $_GET['id']; // Change this to the desired ID
$sql = "SELECT fullname, designation, rank, unit, contact_no, purpose_visit, `message` FROM `tb_vip` WHERE vip_id = $id;"; // Replace "your_table" with your actual table name
$result = $conn->query($sql);

// Fetch data and convert it to JSON
$data = null;
if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
}

// Close connection
$conn->close();

// Return data as JSON
header('Content-Type: application/json');
echo json_encode($data);
?>
