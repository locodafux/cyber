<?php
    session_start();

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header('Content-Type: application/json');
    
    include_once('conn.php');
    
    $method = $_SERVER['REQUEST_METHOD'];
    
    switch ($method) {
        case 'GET':
            handleGet($pdo);
            break;
        // case 'PUT':
        //     handlePut($pdo);
        //     break;
        default:
            echo json_encode(["message" => "Method not supported"]);
            break;
    }
    
    // this method will get the information of regular visitors
    function handleGet($pdo) {
        try {
            $sql = "SELECT * FROM tb_vip ";
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
            echo json_encode(["message" => "Error: " . $e->getMessage()]);
        }
    } 
?>