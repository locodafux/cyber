<?php
include 'database/conns.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['reg_id']) || isset($_POST['vip_id'])) {
        $id = isset($_POST['reg_id']) ? $_POST['reg_id'] : $_POST['vip_id'];

        $regularTable = "tb_regular";
        $vipTable = "tb_vip";
        $tableToUpdate = '';
        $columnToUpdate = ''; 

        
        if (isset($_POST['vip_id'])) {
            $tableToUpdate = $vipTable;
            $columnToUpdate = "vip_id";
        } else {
            $tableToUpdate = $regularTable;
            $columnToUpdate = "reg_id";
        }

        if ($tableToUpdate != '') {
           
            $sql = "UPDATE $tableToUpdate SET time_out = NOW() WHERE $columnToUpdate = ?";

            
            $stmt = $conn->prepare($sql);
           
            $stmt->bind_param("s", $id); 

          
            if ($stmt->execute()) {
              
                echo "Time out updated successfully";
            } else {
               
                echo "Error updating time out: " . $conn->error;
            }

          
            $stmt->close();
        } else {
            
            echo "Invalid ID format";
        }
    } else {
        
        echo "Missing ID parameter";
    }
} else {
   
    echo "Invalid request method";
}
?>
