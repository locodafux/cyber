<?php
session_start();

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');

include_once('conn.php');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        handleGet($pdo);
        break;
    default:
        http_response_code(405); // Method Not Allowed
        echo json_encode(["message" => "Method not supported"]);
        break;
}

// This method will get the information of regular visitors
// Modify the handleGet function in your PHP file
// This method will get the information of regular visitors
function handleGet($pdo)
{
    try {
        $sql = "SELECT vip_id, fullname, designation, rank, unit, contact_no, purpose_visit, message, signature, images, DATE_FORMAT(created_at, '%Y-%m-%d') AS date, TIME_FORMAT(time_in, '%H:%i:%s') AS time_in, TIME_FORMAT(time_out, '%H:%i:%s') AS time_out FROM tb_vip";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Encode image data as base64 if needed
        foreach ($data as &$row) {
            if (isset($row['images'])) {
                $row['image_base64'] = base64_encode($row['images']);
                unset($row['images']);
            }
            // Encode signature data as base64 if needed
            if (isset($row['signature'])) {
                $row['signature_base64'] = base64_encode($row['signature']);
                unset($row['signature']);
            }
        }

        

        echo json_encode($data);
    } catch (PDOException $e) {
        http_response_code(500); // Internal Server Error
        echo json_encode(["message" => "Error: " . $e->getMessage()]);
    }
}


?>
