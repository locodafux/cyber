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
            $sql = "SELECT reg_id, fullname, designation, rank, unit, contact_no, purpose_visit, DATE(created_at) AS date, TIME(time_in) AS time_in, TIME(time_out) AS time_out FROM tb_regular";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode($data);
        } catch (PDOException $e) {
            echo json_encode(["message" => "Error: " . $e->getMessage()]);
        }
    }    
?>