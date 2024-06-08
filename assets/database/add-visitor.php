<?php
session_start();

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');

include_once('conn.php');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    $requestType = $_GET['type'] ?? null; // Or use a custom header
    switch ($requestType) {
        case 'reg':
            handleREGPost($pdo);
            break;
        case 'vip':
            handleVIPPost($pdo);
            break;
        default:
            echo json_encode(["message" => "Request type not supported"]);
            break;
    }
} else {
    echo json_encode(["message" => "Method not supported"]);
}

function handleREGPost($pdo) {
    try {
        // Get the data from the POST request
        $data = json_decode(file_get_contents('php://input'), true);

        // Extract fields from the input data
        $name = $data['name'] ?? null;
        $designation = $data['designation'] ?? null;
        $rank = $data['rank'] ?? null;
        $unit = $data['unit'] ?? null;
        $contact = $data['contact'] ?? null;
        $purpose = $data['purpose'] ?? null;
        $timeIn = $data['timeIn'] ?? null;
        $timeOut = $data['timeOut'] ?? null;

        // Begin a transaction
        $pdo->beginTransaction();

        $sql = "INSERT INTO tb_regular (fullname, designation, rank, unit, contact_no, purpose_visit, time_in, time_out) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);

        // Bind parameters
        $stmt->bindParam(1, $name);
        $stmt->bindParam(2, $designation);
        $stmt->bindParam(3, $rank);
        $stmt->bindParam(4, $unit);
        $stmt->bindParam(5, $contact);
        $stmt->bindParam(6, $purpose);
        $stmt->bindParam(7, $timeIn);
        $stmt->bindParam(8, $timeOut);

        // Execute the statement
        $stmt->execute();

        // Commit the transaction
        $pdo->commit();

        // Send a success response
        
        echo json_encode(["message" => "Data added successfully"]);
    } catch (PDOException $e) {
        // Rollback the transaction on error
        $pdo->rollBack();
        // Send an error response
        echo json_encode(["message" => "Error: " . $e->getMessage()]);
    }
}

function handleVIPPost($pdo) {
    try {
        // Get the data from the POST request
        $data = json_decode(file_get_contents('php://input'), true);

        // Extract fields from the input data
        $name = $data['name'] ?? null;
        $designation = $data['designation'] ?? null;
        $rank = $data['rank'] ?? null;
        $unit = $data['unit'] ?? null;
        $contact = $data['contact'] ?? null;
        $purpose = $data['purpose'] ?? null;
        $comment = $data['comment'] ?? null;
        $signature = $data['signature'] ?? null;
        $imageData = $data['image'] ?? null;
        $timeIn = $data['timeIn'] ?? null;
        $timeOut = $data['timeOut'] ?? null;

        // Begin a transaction
        $pdo->beginTransaction();

        $sql = "INSERT INTO tb_vip (fullname, designation, rank, unit, contact_no, purpose_visit, message, signature, images, time_in, time_out) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);

        // Bind parameters
        $stmt->bindParam(1, $name);
        $stmt->bindParam(2, $designation);
        $stmt->bindParam(3, $rank);
        $stmt->bindParam(4, $unit);
        $stmt->bindParam(5, $contact);
        $stmt->bindParam(6, $purpose);
        $stmt->bindParam(7, $comment);  

        $signatureBinary = base64_decode(str_replace('data:image/png;base64,', '', $signature));

        $stmt->bindParam(8, $signatureBinary);

        // Convert the base64 encoded image data to binary
        $imageBinary = base64_decode(str_replace('data:image/png;base64,', '', $imageData));
        $stmt->bindParam(9, $imageBinary, PDO::PARAM_LOB);

        $stmt->bindParam(10, $timeIn);
        $stmt->bindParam(11, $timeOut);

        // Execute the statement
        $stmt->execute();

        // Commit the transaction
        $pdo->commit();
        // Send a success response
        echo json_encode(["message" => "Data added successfully"]);
    } catch (PDOException $e) {
        // Rollback the transaction on error
        $pdo->rollBack();
        // Send an error response
        echo json_encode(["message" => "Error: " . $e->getMessage()]);
    }
}
?>


