<?php
include 'database/conns.php';
session_start();

$fullname = $_POST['fullname'];
$serial = $_POST['serial_no'];
$rank = $_POST['rank'];
$unit = $_POST['unit'];
$role = $_POST['role_type'];
$contact = $_POST['user_contact'];
$username = $_POST['username'];
$password = $_POST['password'];

if (empty($fullname) || empty($serial) || empty($rank) || empty($unit) || empty($role) || empty($contact) || empty($username) || empty($password)) {
    $_SESSION['alert_message'] = 'Failed to Add User. All fields are required.';
} else {
    $sql = "SELECT * FROM tb_users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['alert_message'] = 'Failed to Add User. Username Already Exists!';
    } else {
        $stmt = $conn->prepare("INSERT INTO tb_users (fullname, serial_no, rank, unit, user_role, contact, username, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $fullname, $serial, $rank, $unit, $role, $contact, $username, $password);

        if ($stmt->execute()) {
            $_SESSION['alert_message'] = 'User added successfully';
        } else {
            $_SESSION['alert_message'] = 'Failed to Add User. Error: ' . $stmt->error;
        }

       
    }

    $stmt->close();
}

$conn->close();
header("Location: users.php"); // Redirect to the users page or wherever appropriate
exit();
?>
