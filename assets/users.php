<?php 
include 'include/header.php';
include 'include/sidebar.php';
include 'database/conns.php';
?>

<?php


if (isset($_SESSION['alert_message'])) {
    echo '<script>alert("' . $_SESSION['alert_message'] . '");</script>';
    unset($_SESSION['alert_message']); 
}


include 'database/conns.php';
$roleType = "";


$sql = "SELECT user_role FROM tb_users WHERE username = ?";
$stmt = $conn->prepare($sql);


$stmt->bind_param("s", $_SESSION['username']);


$stmt->execute();


$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $roleType = $row['user_role'];
    echo '<script>console.log("Role: ' . $roleType . '");</script>';
} else {
    echo '<script>console.log("User not found.");</script>';
}

$stmt->close();


if($roleType == "User"){
    header("Location: dashboard.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/1.5.3/signature_pad.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" />


</head>



<body>
    <div class="visitors-frame">
        <div class="first">
            <div class="visitors-title"> USERS </div>
            <div class="addUser-btn" id="addUser">
                <span class="addUser-btn-text"> Add User </span>
            </div>
        </div>
        

        <div class="table1">
            <div class="guestType">
                <span class="table1-title"> Type of Guest </span>
                <div class="guest-dropdown-container">
                    <div class="guestType-button"> 
                        <span class="guest-box-text"> User </span>
                    </div>
                </div>
            </div>   
            <div class="container9">
                <div class="addGuest-btn" onclick="printReg()">
                    <span class="addGuest-btn-text"> Print</span>
                </div>
            </div>     
        </div>

        <div class="users-table-container p-3" style="display: block;">
            <?php 
                $sql = "SELECT * FROM tb_users";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    $results = array();
                    while ($row = $result->fetch_assoc()) {
                        $results[] = $row;
                    }
                    echo "<table class='table' id='regTable' border='1'>";
                    echo "<thead class='thead-dark'><tr><th>User ID</th><th>Username</th><th>Fullname</th><th>Serial No.</th><th>Rank</th><th>Unit</th><th>Role Type</th><th>Contact No.</th><th>Action</th></tr></thead><tbody>";

                    foreach ($results as $row) {
                        echo "<tr>";
                        echo "<td>" . $row['user_id'] . "</td>";
                        echo "<td>" . $row['username'] . "</td>";
                        echo "<td>" . $row['fullname'] . "</td>";
                        echo "<td>" . $row['serial_no'] . "</td>";
                        echo "<td>" . $row['rank'] . "</td>";
                        echo "<td>" . $row['unit'] . "</td>";
                        echo "<td>" . $row['user_role'] . "</td>";
                        echo "<td>" . $row['contact'] . "</td>";
                        echo '  <td>
                                    <div class="dropdown-container">
                                        <button class="dropdown-button">Action</button>
                                        <div class="dropdown-content" style="display: none;">
                                            <button data-usergid="' . $row['user_id'] . '" class="edit-button">Edit</button>
                                            <button class="reg-time-out-button" data-usergid="' . $row['user_id'] . '">Time Out</button>
                                            <button class="reg-delete-button" delete-data-usergid="' . $row['user_id'] . '">Delete</button>
                                        </div>
                                    </div>
                                </td>';
                        echo "</tr>";
                    }
                    echo "</tbody></table>";
                } else {
                    echo "0 results";
                }
                $conn->close();
            ?>
        </div>
    </div>


    
    <div class="popup" id="popup">
        <div class="popup-content">
            <div class="popup-title">Add User</div>
            <div id="alert-container"></div>
                <div id="user_input_container" class="input-container">
                    <form class="input-container" id="addUserForm" method="POST" action="add_user.php" enctype="multipart/form-data">
                    <div class="text-input-container" >
                        <div class="inputs">
                            <div>
                                <div class="label"> Personnel Name </div>
                                <input type="textbox" class="textbox" name="fullname">
                            </div>
                        </div>

                        <div class="inputs">
                            <div>
                                <div class="label"> Serial No. </div>
                                <input type="textbox" class="textbox" name="serial_no">
                            </div>
                        </div>

                    


                        <div class="selects">
                            <label for="role_type">Role Type<br> </label>
                            <select id="role_type" name="role_type">
                                <option value="User">User</option>
                            </select>
                        </div>


                        <div class="inputs">
                            <div>
                                <div class="label"> Username </div>
                                <input type="textbox" class="textbox" name="username">
                            </div>
                        </div>
                    
                    
                </div>

                <div class="text-input-container">

                    <div class="inputs">
                        <div>
                            <div class="label"> Contact no: </div>
                            <input type="number" class="textbox" name="user_contact">
                        </div>
                    </div>

                    <div class="inputs">
                        <div>
                            <div class="label"> Rank ID </div>
                            <input type="textbox" class="textbox" name="rank">
                        </div>
                    </div>

                    <div class="inputs">
                        <div>
                            <div class="label"> Unit </div>
                            <input type="textbox" class="textbox" name="unit">
                        </div>
                    </div>

                    <div class="inputs">
                        <div>
                            <div class="label"> Password </div>
                            <input type="textbox" class="textbox" name="password">
                        </div>
                    </div>

                </div>

                
           
            </div>
        
            <div class="popup-button-container">
                <button type="submit" name="add" id="add-visitor-btn"> Add User</button>
                <div data-dismiss="popup" id="cancel"> Cancel</div>
            </div>
            </form>
        </div>
    </div>
   

    <script>
       
         document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('addUser').addEventListener('click', function() {
            document.getElementById('popup').classList.add('show');
            });
         });

         document.getElementById('add-visitor-btn').addEventListener('click', function() {
   
        var inputs = document.querySelectorAll('#addUserForm input[type=text], #addUserForm input[type=number], #addUserForm select');

    
        var isEmpty = false;

    
        inputs.forEach(function(input) {
            if (input.value.trim() === '') {
                isEmpty = true;
            }
        });

    
        if (isEmpty) {
            var alertMessage = '<div class="alert alert-danger" role="alert">Please fill in all fields.</div>';
            document.getElementById('alert-container').innerHTML = alertMessage;
            event.preventDefault(); 

            setTimeout(function() {
            document.getElementById('alert-container').innerHTML = '';
        }, 2000);
        }else {
        document.getElementById('alert-container').innerHTML = ''; // Clear previous alert

        
    }
    });










        document.getElementById('cancel').addEventListener('click', function() {
            document.getElementById('popup').classList.remove('show');
        });

       
        document.getElementById('popup').addEventListener('click', function(event) {
            if (event.target.id === 'popup') {
                document.getElementById('popup').classList.remove('show');
            }
        });

    
    </script>



</body>
</html>
