<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$database = "cybervlog";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitors</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/1.5.3/signature_pad.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
     <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
     <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
     <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
     <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" />

    <style>
       #alertSuccess {
        display:none;
       }
       .popup-edit-reg {
        display:none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 9999; /* Adjust z-index as needed */
    background-color: white; /* Set background color */
    border: 1px solid black; /* Add border */
    padding: 20px; /* Add padding */
    border-radius: 5px; /* Add border radius */
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Add box shadow */
}

.popup-edit-vip {
        display:none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 9999; /* Adjust z-index as needed */
    background-color: white; /* Set background color */
    border: 1px solid black; /* Add border */
    padding: 20px; /* Add padding */
    border-radius: 5px; /* Add border radius */
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Add box shadow */
}


.dropdown-container-vip {
    position: relative; /* or position: absolute; */
    display: inline-block; /* or display: block; */
}

.dropdown-content {
    display: none;
    position: absolute;
    background-color: #f9f9f9;
    min-width: 160px;
    box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
    z-index: 1;
    /* Adjust alignment as needed */
    top: 100%; /* or top: auto; bottom: 100%; */
    left: 0; /* or right: 0; */
}

/* Show dropdown content on hover */
.dropdown-container-vip:hover .dropdown-content {
    display: block;
}
    </style>
</head>
<body>
    <?php 
        include 'include/header.php';
        include 'include/sidebar.php';
    ?>    

    <div class="visitors-frame">
        <div class="first">
            <div class="visitors-title"> VISITORS </div>
            <div class="addGuest-btn" id="addGuest">
                <span class="addGuest-btn-text"> Add Visitor </span>
            </div>
        </div>

        <div class="table1">
            <div class="guestType">
                <span class="table1-title"> Type of Guest </span>
                <div class="guest-dropdown-container">
                    <div class="guestType-button"> 
                        <span class="guest-box-text"> Regular </span>
                        <img src="img/downward-arrow.png" alt="arrow" class="guest-box-arrow">
                    </div>
                    <div class="guest-dropdown-content" style="display: none;">
                        <span class="VIP-button">VIP</span>
                    </div>
                </div>
            </div>   
            
           
            <div class="container9">
            <div class="addGuest-btn" onclick="printReg()">
                <span class="addGuest-btn-text"> Print Regular</span>
            </div>
            </div>     
            <div class="container9">
            <div class="addGuest-btn" onclick="printVIP()">
                <span class="addGuest-btn-text"> Print VIP</span>
            </div>
            </div>   
           
             
        </div>

        <div class="reg-table-container p-3" style="display: block;">
        <?php
    // SQL query
    $sql = "SELECT reg_id, fullname, designation, rank, unit, contact_no, purpose_visit, DATE(created_at) AS date, TIME(time_in) AS time_in, TIME(time_out) AS time_out FROM tb_regular";

    // Execute SQL query
    $result = $conn->query($sql);

    // Check if there are any results
    if ($result->num_rows > 0) {
        // Fetching results as associative array
        $results = array();
        while ($row = $result->fetch_assoc()) {
            $results[] = $row;
        }
        // HTML table start
        echo "<table class='table' id='regTable' border='1'>";
        echo "<thead class='thead-dark'><tr><th>Reg ID</th><th>Full Name</th><th>Designation/Position</th><th>Rank</th><th>Unit</th><th>Contact No</th><th>Purpose of Visit</th><th>Date</th><th>Time In</th><th>Time Out</th><th>Action</th></tr></thead><tbody>";
        
        // Loop through the results array to populate the table rows
        foreach ($results as $row) {
            echo "<tr>";
            echo "<td>" . $row['reg_id'] . "</td>";
            echo "<td>" . $row['fullname'] . "</td>";
            echo "<td>" . $row['designation'] . "</td>";
            echo "<td>" . $row['rank'] . "</td>";
            echo "<td>" . $row['unit'] . "</td>";
            echo "<td>" . $row['contact_no'] . "</td>";
            echo "<td>" . $row['purpose_visit'] . "</td>";
            echo "<td>" . $row['date'] . "</td>";
            echo "<td>" . $row['time_in'] . "</td>";
            $time_out_display = ($row['time_out'] == '00:00:00') ? 'TBD' : $row['time_out'];
            echo "<td>" . $time_out_display . "</td>";
            echo '  <td>
                        <div class="dropdown-container">
                            <button class="dropdown-button">Action</button>
                            <div class="dropdown-content" style="display: none;">
                                <button data-regid="' . $row['reg_id'] . '" class="edit-button">Edit</button>
                                <button class="reg-time-out-button" data-regid="' . $row['reg_id'] . '">Time Out</button>
                                <button class="reg-delete-button" delete-data-regid="' . $row['reg_id'] . '">Delete</button>
                            </div>
                        </div>
                    </td>';
            echo "</tr>";
        }
        // HTML table end
        echo "</tbody></table>";
    } else {
        echo "0 results";
    }

    // Close connection
    $conn->close();
?>
</div>


        <div class="vip-table-container p-3" >
        <table class="table" id="vipTable" border="1">
    <thead class="thead-dark">
        <tr>
            <th>Vip_id</th>
            <th>Fullname</th>
            <th>Designation/Position</th>
            <th>Rank</th>
            <th>Unit</th>
            <th>Contact_no</th>
            <th>Purpose_visit</th>
            <th>Message</th>
            <th>Signature</th>
            <th>Image</th>
            <th>Date</th>
            <th>Time in</th>
            <th>Time out</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Connect to your database
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "cybervlog";

        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Fetch data from your database table
        $sql = "SELECT vip_id, fullname, designation, rank, unit, contact_no, purpose_visit, message, signature, images, DATE_FORMAT(created_at, '%Y-%m-%d') AS date, TIME_FORMAT(time_in, '%H:%i:%s') AS time_in, TIME_FORMAT(time_out, '%H:%i:%s') AS time_out FROM tb_vip";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".$row["vip_id"]."</td>";
                echo "<td>".$row["fullname"]."</td>";
                echo "<td>".$row["designation"]."</td>";
                echo "<td>".$row["rank"]."</td>";
                echo "<td>".$row["unit"]."</td>";
                echo "<td>".$row["contact_no"]."</td>";
                echo "<td>".$row["purpose_visit"]."</td>";
                echo "<td>".$row["message"]."</td>";
                echo "<td><img src='data:image/jpeg;base64,".base64_encode($row["signature"])."' style='max-width:100px'></td>";
                // Displaying the image as a link
                echo "<td><img src='data:image/jpeg;base64,".base64_encode($row["images"])."' style='max-width:100px'></td>";
                echo "<td>".$row["date"]."</td>";
                echo "<td>".$row["time_in"]."</td>";
                $time_out_display = ($row['time_out'] == '00:00:00') ? 'TBD' : $row['time_out'];
                echo "<td>" . $time_out_display . "</td>";
                echo '  <td>
                <div class="dropdown-container-vip">
                    <button class="dropdown-button">Action</button>
                    <div class="dropdown-content" style="display: none;">
                        <button data-vipid="' . $row['vip_id'] . '" class="edit-vip-button">Edit</button>
                        <button class="vip-time-out-button" data-vipid="' . $row['vip_id'] . '">Time Out</button>
                        <button class="vip-delete-button" delete-data-vipid="' . $row['vip_id'] . '">Delete</button>
                    </div>
                </div>
            </td>';
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='14'>0 results</td></tr>";
        }
        $conn->close();
        ?>
    </tbody>
</table>
</div>
    </div>

<script>

document.querySelectorAll('.reg-time-out-button').forEach(button => {
        button.addEventListener('click', function() {
            const regId = this.getAttribute('data-regid');
            // Send AJAX request
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'update_time_out.php');
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                   
                    const response = xhr.responseText;
                    if (response.startsWith('Error')) {
                        
                        alert(response);
                    } else {
                       
                        alert(response);
                        
                        window.location.reload();
                    }
                } else {
                    
                    alert('Error: ' + xhr.statusText);
                }
            };
            xhr.send('reg_id=' + encodeURIComponent(regId));
        });
    });

document.querySelectorAll('.vip-time-out-button').forEach(button => {
    button.addEventListener('click', function() {
        const vipId = this.getAttribute('data-vipid');
        // Send AJAX request
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'update_time_out.php');
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                const response = xhr.responseText;
                if (response.startsWith('Error')) {
                    alert(response);
                } else {
                    alert(response);
                    window.location.reload();
                }
            } else {
                alert('Error: ' + xhr.statusText);
            }
        };
        xhr.send('vip_id=' + encodeURIComponent(vipId));
    });
});



// JavaScript for regular delete button
document.querySelectorAll('.reg-delete-button').forEach(button => {
    button.addEventListener('click', function() {
        const regId = this.getAttribute('delete-data-regid');
        // Send AJAX request
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'delete_entry.php');
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                const response = xhr.responseText;
                if (response.startsWith('Error')) {
                    alert(response);
                } else {
                    alert(response);
                    window.location.reload();
                }
            } else {
                alert('Error: ' + xhr.statusText);
            }
        };
        xhr.send('reg_id=' + encodeURIComponent(regId));
    });
});

// JavaScript for VIP delete button
document.querySelectorAll('.vip-delete-button').forEach(button => {
    button.addEventListener('click', function() {
        const vipId = this.getAttribute('delete-data-vipid');
        // Send AJAX request
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'delete_entry.php');
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                const response = xhr.responseText;
                if (response.startsWith('Error')) {
                    alert(response);
                } else {
                    alert(response);
                    window.location.reload();
                }
            } else {
                alert('Error: ' + xhr.statusText);
            }
        };
        xhr.send('vip_id=' + encodeURIComponent(vipId));
    });
});


</script>


    <div class="popup-camera" style="display: none;">
        <div class="popup-camera-content">
            <video id="video" autoplay></video>
            <canvas id="canvas" style="display:none;"></canvas>
            <div class="camera-button-container">
                <img src="img/camera.png" alt="" id="take-image" >
                <img src="img/retake.png" alt="" id="retake" style="display: none;">
                <img src="img/check.png" alt="" id="done" style="display: none;">
            </div>
            <input type="hidden" name="image" id="image">
        </div>
    </div>

    <div class="popup">
        <div class="popup-content">
            <div class="popup-title">Add Visitor</div>
            <div class="popup-dropdown-container">
                <div class="popup-guestType-button"> 
                    <span class="popup-RegularVis-btn"> Regular </span>
                    <img src="img/downward-arrow.png" alt="arrow" class="guest-box-arrow">
                </div>
                <div class="popup-dropdown-content" style="display: none;">
                    <span class="popup-VIPVis-btn">VIP</span>
                </div>
            </div>

            <div id="alertSuccess" class="alert alert-success">
                <strong>Success!</strong> <span id="data"></span>
            </div>

            <!-- This input container is for regular visitors -->
            <div id="reg_input_container" class="input-container">
            <div class="text-input-container" >
                    <div class="inputs">
                        <div>
                            <div class="label"> Name: </div>
                            <input type="textbox" class="textbox" name="reg-name">
                        </div>
                    </div>

                    <div class="inputs">
                        <div>
                            <div class="label"> Designation/Position </div>
                            <input type="textbox" class="textbox" name="reg-designation">
                        </div>
                    </div>

                    


                    <div class="selects">
                    <label for="reg-rank">Rank:<br> </label>
                    <select id="reg-rank" name="reg-rank">
                        <option value="Private">Mr.</option>
                        <option value="Private">Ms.</option>
                        <option value="Private">Private</option>
                        <option value="Private first class">Private first class</option>
                        <option value="Corporal">Corporal</option>
                        <option value="Sergeant">Sergeant</option>
                        <option value="Staff sergeant">Staff sergeant</option>
                        <option value="Technical sergeant">Technical sergeant</option>
                        <option value="Master sergeant">Master sergeant</option>
                        <option value="Senior master sergeant">Senior master sergeant</option>
                        <option value="Chief master sergeant">Chief master sergeant</option>
                        <option value="Second lieutenant">Second lieutenant</option>
                        <option value="First lieutenant">First lieutenant</option>
                        <option value="Captain">Captain</option>
                        <option value="Major">Major</option>
                        <option value="Lieutenant Colonel">Lieutenant Colonel</option>
                        <option value="Colonel">Colonel</option>
                        <option value="Brigadier general">Brigadier general</option>
                        <option value="Major general">Major general</option>
                        <option value="Lieutenant general">Lieutenant general</option>
                        <option value="General">General</option>
                    </select>
                </div>
                    
                    
                </div>

                <div class="text-input-container">

                    <div class="inputs">
                        <div>
                            <div class="label"> Contact no: </div>
                            <input type="number" class="textbox" name="reg-contact">
                        </div>
                    </div>

                    <div class="inputs">
                        <div>
                            <div class="label"> Purpose of Visit: </div>
                            <input type="textbox" class="textbox" name="reg-purpose">
                        </div>
                    </div>

                    <div class="inputs">
                        <div>
                            <div class="label"> Unit/Organization: </div>
                            <input type="textbox" class="textbox" name="reg-unit">
                        </div>
                    </div>


                </div>
            </div>

            <!-- this input container for VIP Visitors -->
            <div id="vip_input_container" class="input-container" style="display: none;3">
                <div class="image-container">
                    <div id="image_holder" class="image-holder" ></div>
                    <div class="image-button-container">
                        <div id="uploadButton">Upload</div>
                        <div id="captureButton">Capture</div>
                    </div>
                    <input type="file" id="fileInput" accept="image/*" style="display: none;">
                </div>

                <div class="text-input-container">
                    <div class="inputs">
                        <div>
                            <div class="label"> Name: </div>
                            <input type="textbox" class="textbox" name="vip-name">
                        </div>
                    </div>

                    <div class="inputs">
                        <div>
                            <div class="label"> Designation/Position: </div>
                            <input type="textbox" class="textbox" name="vip-designation">
                        </div>
                    </div>

                   

                    <div class="selects">
                    <label for="vip-rank">Rank:<br></label>
                        <select id="vip-rank" name="vip-rank">
                            <option value="Private">Mr.</option>
                            <option value="Private">Ms.</option>
                            <option value="Private">Private</option>
                            <option value="Private first class">Private first class</option>
                            <option value="Corporal">Corporal</option>
                            <option value="Sergeant">Sergeant</option>
                            <option value="Staff sergeant">Staff sergeant	</option>
                            <option value="Technical sergeant">Technical sergeant</option>
                            <option value="Master sergeant">Master sergeant</option>
                            <option value="Senior master sergeant">Senior master sergeant</option>
                            <option value="Chief master sergeant">Chief master sergeant</option>
                            <option value="Second lieutenant">Second lieutenant</option>
                            <option value="First lieutenant">First lieutenant</option>
                            <option value="Captain">Captain</option>
                            <option value="Major">Major</option>
                            <option value="Lieutenant Colonel">Lieutenant Colonel</option>
                            <option value="Colonel">Colonel</option>
                            <option value="Brigadier general">Brigadier general</option>
                            <option value="	Major general">Major general</option>
                            <option value="Lieutenant general">Lieutenant general</option>
                            <option value="General">General</option>
                        </select>
                    </div>

                    <div class="inputs">
                        <div>
                            <div class="label"> Contact no: </div>
                            <input type="number" class="textbox" name="vip-contact">
                        </div>
                    </div>
                    
                    
                     

                </div>

                <div class="text-input-container">

                    <div class="inputs">
                        <div>
                            <div class="label"> Purpose of Visit: </div>
                            <input type="textbox" class="textbox" name="vip-purpose">
                        </div>
                    </div>

                    <div class="inputs">
                        <div>
                            <div class="label"> Message: </div>
                            <input type="textbox" class="textbox" name="vip-comment">
                        </div>
                    </div>

                    <div class="inputs">
                        <div>
                            <div class="label"> Unit/Organization: </div>
                            <input type="textbox" class="textbox" name="vip-unit">
                        </div>
                    </div>

                   

              

                </div>

                <div class="text-input-container">
                
                    <div class="sign-container" style=" height: 70%">
                        <div>
                            <div class="label">Signature:</div>
                            <canvas class="signature" id="signatureCanvas"></canvas>
                            
                        </div>
                        <img id="clearSignatureBtn" src="img/clear.png" class="clear-signature"></img>
                    </div>
                
                </div>


               
            </div>


                

            <div class="popup-button-container">
                <div id="add-visitor-btn"> Add Visitor</div>
                <div id="cancel"> Cancel</div>
            </div>
            
         </div>
         
    </div>
    
    <div class="popup-edit-reg">
    <div class="popup-content">
        <div class="popup-title">Edit Regular Visitor</div>
        <input type="hidden"id="edit-id" >
        <!-- This input container is for regular visitors -->
        <div id="edit-reg_input_container" class="input-container">
            <div class="text-input-container">
                <div class="inputs">
                    <div>
                        <div class="label"> Name: </div>
                        <input type="textbox" id="edit-reg-name" class="textbox" name="reg-name">
                    </div>
                </div>

                <div class="inputs">
                    <div>
                        <div class="label"> Designation/Position: </div>
                        <input type="textbox" id="edit-reg-designation" class="textbox" name="reg-designation">
                    </div>
                </div>

               

                <div class="selects">
                    <label for="edit-reg-rank">Rank:</label>
                    <select id="edit-reg-rank" name="reg-rank">
                        <option value="Private">Mr.</option>
                        <option value="Private">Ms.</option>
                        <option value="Private">Private</option>
                        <option value="Private first class">Private first class</option>
                        <option value="Corporal">Corporal</option>
                        <option value="Sergeant">Sergeant</option>
                        <option value="Staff sergeant">Staff sergeant</option>
                        <option value="Technical sergeant">Technical sergeant</option>
                        <option value="Master sergeant">Master sergeant</option>
                        <option value="Senior master sergeant">Senior master sergeant</option>
                        <option value="Chief master sergeant">Chief master sergeant</option>
                        <option value="Second lieutenant">Second lieutenant</option>
                        <option value="First lieutenant">First lieutenant</option>
                        <option value="Captain">Captain</option>
                        <option value="Major">Major</option>
                        <option value="Lieutenant Colonel">Lieutenant Colonel</option>
                        <option value="Colonel">Colonel</option>
                        <option value="Brigadier general">Brigadier general</option>
                        <option value="Major general">Major general</option>
                        <option value="Lieutenant general">Lieutenant general</option>
                        <option value="General">General</option>
                    </select>
                </div>

               

            </div>

            <div class="text-input-container">

                <div class="inputs">
                    <div>
                        <div class="label"> Contact no: </div>
                        <input type="number" id="edit-reg-contact" class="textbox" name="reg-contact">
                    </div>
                </div>

                <div class="inputs">
                    <div>
                        <div class="label"> Purpose of Visit: </div>
                        <input type="textbox" id="edit-reg-purpose" class="textbox" name="reg-purpose">
                    </div>
                </div>

                <div class="inputs">
                    <div>
                        <div class="label"> Unit/Organization: </div>
                        <input type="textbox" id="edit-reg-unit" class="textbox" name="reg-unit">
                    </div>
                </div>
            

            </div>
        </div>
        <div class="popup-button-container">
            <div id="edit-visitor-btn">Save Changes</div>
            <div id="cancel-edit">Cancel</div>
        </div>
    </div>
</div>

<div class="popup-edit-vip">
    <div class="popup-content">
        <div class="popup-title">Edit Vip Visitor</div>
        <input type="hidden" id="edit-vip-id" >
        <!-- This input container is for regular visitors -->
        <div id="edit-vip_input_container" class="input-container">
            <div class="text-input-container">
                <div class="inputs">
                    <div>
                        <div class="label"> Name: </div>
                        <input type="textbox" id="edit-vip-name" class="textbox" name="vip-name">
                    </div>
                </div>

                <div class="inputs">
                    <div>
                        <div class="label"> Designation/Postion: </div>
                        <input type="textbox" id="edit-vip-designation" class="textbox" name="vip-designation">
                    </div>
                </div>

                <div class="inputs">
                    <div>
                        <div class="label"> Contact no: </div>
                        <input type="number" id="edit-vip-contact" class="textbox" name="vip-contact">
                    </div>
                </div>

                

                <div class="selects">
                    <label for="edit-vip-rank">Rank:</label>
                    <select id="edit-vip-rank" name="vip-rank">
                        <option value="Private">Mr.</option>
                        <option value="Private">Ms.</option>
                        <option value="Private">Private</option>
                        <option value="Private first class">Private first class</option>
                        <option value="Corporal">Corporal</option>
                        <option value="Sergeant">Sergeant</option>
                        <option value="Staff sergeant">Staff sergeant</option>
                        <option value="Technical sergeant">Technical sergeant</option>
                        <option value="Master sergeant">Master sergeant</option>
                        <option value="Senior master sergeant">Senior master sergeant</option>
                        <option value="Chief master sergeant">Chief master sergeant</option>
                        <option value="Second lieutenant">Second lieutenant</option>
                        <option value="First lieutenant">First lieutenant</option>
                        <option value="Captain">Captain</option>
                        <option value="Major">Major</option>
                        <option value="Lieutenant Colonel">Lieutenant Colonel</option>
                        <option value="Colonel">Colonel</option>
                        <option value="Brigadier general">Brigadier general</option>
                        <option value="Major general">Major general</option>
                        <option value="Lieutenant general">Lieutenant general</option>
                        <option value="General">General</option>
                    </select>
                </div>

               

            </div>

            <div class="text-input-container">

                <div class="inputs">
                    <div>
                        <div class="label"> Purpose of Visit: </div>
                        <input type="textbox" id="edit-vip-purpose" class="textbox" name="vip-purpose">
                    </div>
                </div>
                <div class="inputs">
                    <div>
                        <div class="label"> Message: </div>
                        <input type="textbox" id="edit-vip-message" class="textbox" name="vip-purpose">
                    </div>
                </div>

                <div class="inputs">
                    <div>
                        <div class="label"> Unit/Organization: </div>
                        <input type="textbox" id="edit-vip-unit" class="textbox" name="vip-unit">
                    </div>
                </div>

            

            </div>
        </div>
        <div class="popup-button-container">
            <div id="edit-vip-btn">Save Changes</div>
            <div id="cancel-edit">Cancel</div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    var editVipBtn = document.getElementById('edit-vip-btn');
  
    editVipBtn.addEventListener('click', function() {
        // Get the values from the input fields
        var vipId = document.getElementById('edit-vip-id').value;
        var name = document.getElementById('edit-vip-name').value;
        var designation = document.getElementById('edit-vip-designation').value;
        var rank = document.getElementById('edit-vip-rank').value;
        var unit = document.getElementById('edit-vip-unit').value;
        var contact = document.getElementById('edit-vip-contact').value;
        var purpose = document.getElementById('edit-vip-purpose').value;
        var message = document.getElementById('edit-vip-message').value;

        // Prepare the data to send to the server
        var data = {
            vipId: vipId,
            name: name,
            designation: designation,
            rank: rank,
            unit: unit,
            contact: contact,
            purpose: purpose,
            message: message
        };
        console.log(data);

        // Send the data to the server using fetch or XMLHttpRequest
        fetch('save-vip.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to save data');
            }
            // If the request was successful, show a success message or perform any other actions
            alert('Changes saved successfully!');
            location.reload(); 
        })
        .catch(error => {
            // Handle errors
            console.error('Error saving data:', error);
            alert('Failed to save changes. Please try again later.');
        });
    });
});

</script>
<script src="js/visitors1.js"></script>
<script src="js/add-visitor.js"></script>

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function () {
    $('#regTable').DataTable();
});
$(document).ready(function () {
        $('#vipTable').DataTable();
    });


    </script>


<script>

    
document.addEventListener("DOMContentLoaded", function() {
    // Get the button element
    var editVipButton = document.getElementById("edit-vip-button");

    // Get the popup element
    var editVipPopup = document.querySelector(".popup-edit-vip");

    // Add click event listener to the button
    editVipButton.addEventListener("click", function() {
        // Show the popup by adding a class that makes it visible
        console.log()
    });

    // Function to close the popup when clicking outside of it
    function closePopupOutside(event) {
        if (!editVipPopup.contains(event.target) && !editVipButton.contains(event.target)) {
            editVipPopup.classList.remove("show-popup");
        }
    }

    // Add event listener to close the popup when clicking outside of it
    document.addEventListener("click", closePopupOutside);
});

    </script>


<script>
   document.addEventListener('DOMContentLoaded', function() {
    // Event delegation to handle click events for dynamically added buttons
    document.addEventListener('click', function(event) {
        // Check if the clicked element is the edit-vip-button
        if (event.target.classList.contains('edit-vip-button')) {
            // Get the VIP ID from the data attribute
            var vipId = event.target.getAttribute('data-vipid');
            
            // Perform any actions you need with the VIP ID
            console.log('Editing VIP with ID:', vipId);
            document.getElementById('edit-vip-id').value=vipId;
             // Function to fetch VIP data by ID
    function fetchVIPData(vipId) {
        // Define the URL of the PHP script
        const phpScriptUrl = 'fetch_vip_data.php';

        // Make a fetch request to the PHP script with the VIP ID
        fetch(`${phpScriptUrl}?id=${vipId}`)
            .then(response => {
                // Check if the request was successful
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                // Parse the JSON response
                return response.json();
            })
            .then(data => {
                // Process the fetched data
                console.log('Data for VIP with ID', vipId, ':', data);
                
                // You can do further processing here, like updating HTML elements with the data
            document.getElementById('edit-vip-name').value = data.fullname;
            document.getElementById('edit-vip-designation').value = data.designation;
            document.getElementById('edit-vip-rank').value = data.rank;
            document.getElementById('edit-vip-unit').value = data.unit;
            document.getElementById('edit-vip-contact').value = data.contact_no;
            document.getElementById('edit-vip-purpose').value = data.purpose_visit;
            document.getElementById('edit-vip-message').value = data.message;
            })
            .catch(error => console.error('Error fetching data:', error));
    }

    // Example: Fetch data for VIP with ID 1
    fetchVIPData(vipId);
            
            
            // Show the popup or perform other actions here
            var editVipPopup = document.querySelector('.popup-edit-vip');
            editVipPopup.style.display = 'flex';
        }
        
        // Check if the clicked element is the cancel-edit button
        if (event.target.id === 'cancel-edit') {
            // Hide the popup
            var editVipPopup = document.querySelector('.popup-edit-vip');
            editVipPopup.style.display = 'none';
        }
    });
});


</script>




<script>
    document.addEventListener('DOMContentLoaded', function() {
        var buttons = document.querySelectorAll('.edit-button');
        var popup = document.querySelector('.popup-edit-reg');
        var cancelBtn = document.getElementById('cancel-edit');
        var editId = document.getElementById('edit-id');
        var dropdownButtons = document.querySelectorAll('.dropdown-button');
        const editNameInput = document.getElementById('edit-reg-name');
        const editDesignationInput = document.getElementById('edit-reg-designation');
        const editRankSelect = document.getElementById('edit-reg-rank');
        const editUnitInput = document.getElementById('edit-reg-unit');
        const editContactInput = document.getElementById('edit-reg-contact');
        const editPurposeInput = document.getElementById('edit-reg-purpose');
        const editTimeInInput = document.getElementById('edit-reg-timeIn');
        const editTimeOutInput = document.getElementById('edit-reg-timeOut');

        buttons.forEach(function(button) {
        button.addEventListener('click', function() {
        var regId = this.getAttribute('data-regid');
        console.log(regId);

        fetch('fetch_reg_data.php?id='+regId)
        .then(response => response.json())
        .then(data => {
          // Process the fetched data
          console.log(data);
          editNameInput.value = data.fullname;
        editDesignationInput.value = data.designation;
        // For the rank select, find the option with the corresponding value and select it
        editRankSelect.querySelectorAll('option').forEach(function(option) {
    // Check if the option value matches the data.rank
    if (option.value === data.rank) {
        // Set the selected attribute for the matching option
        option.selected = true;
    }
});
        editUnitInput.value = data.unit; // Assuming there's no unit information provided in the data
        editContactInput.value = data.contact_no;
        editPurposeInput.value = data.purpose_visit;
        editId.value= regId;
        })
        .catch(error => console.error('Error fetching data:', error));


     

        popup.style.display = 'flex'; // Show the popup

        var dropdownContent = this.closest('.dropdown-content');
        if (dropdownContent) {
            dropdownContent.style.display = 'none'; // Hide the dropdown content
        }
    });
});
        cancelBtn.addEventListener('click', function() {
            popup.style.display = 'none'; // Hide the popup
        });

        dropdownButtons.forEach(function(dropdownButton) {
            dropdownButton.addEventListener('click', function() {
                var dropdownContent = this.nextElementSibling;
                if (dropdownContent.style.display === 'none' || dropdownContent.style.display === '') {
                    dropdownContent.style.display = 'block';
                } else {
                    dropdownContent.style.display = 'none';
                }
            });
        });
    });
</script>
</body>
</html>
