<?php
include 'database/conns.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['reg_id']) || isset($_POST['vip_id'])) {
        $id = isset($_POST['reg_id']) ? $_POST['reg_id'] : $_POST['vip_id'];

       
        $regularTable = "tb_regular";
        $vipTable = "tb_vip";
        $tableToDeleteFrom = ''; 
        $columnToDeleteFrom = ''; 

      
        if (isset($_POST['vip_id'])) {
            $tableToDeleteFrom = $vipTable;
            $columnToDeleteFrom = "vip_id";
        } else {
            $tableToDeleteFrom = $regularTable;
            $columnToDeleteFrom = "reg_id";
        }

        if ($tableToDeleteFrom != '') {
           
            $sql = "DELETE FROM $tableToDeleteFrom WHERE $columnToDeleteFrom = ?";

            
            $stmt = $conn->prepare($sql);
            
            $stmt->bind_param("s", $id); 

            
            if ($stmt->execute()) {
                
                echo "Deleted successfully";
            } else {
                echo "Error deleting: " . $conn->error;
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
