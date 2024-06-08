<?php
// Check if the request is a POST request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve the JSON data from the request body
    $jsonData = file_get_contents("php://input");

    // If JSON data is received
    if ($jsonData) {
        // Decode the JSON data into an associative array
        $data = json_decode($jsonData, true);

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

        // Prepare and execute an SQL UPDATE statement to update the record in the database
        $sql = "UPDATE tb_regular SET 
                fullname = '" . $data['name'] . "', 
                designation = '" . $data['designation'] . "', 
                rank = '" . $data['rank'] . "', 
                unit = '" . $data['unit'] . "', 
                contact_no = '" . $data['contact'] . "', 
                purpose_visit = '" . $data['purpose'] . "' 
                WHERE reg_id = " . $data['regId'];

        if ($conn->query($sql) === TRUE) {
            // If the record is successfully updated
            http_response_code(200); // Send a success response code
        } else {
            // If there is an error in the SQL query
            http_response_code(500); // Send a server error response code
            echo json_encode(array("error" => "Error updating record: " . $conn->error));
        }

        // Close connection
        $conn->close();
    } else {
        // If JSON data is not received
        http_response_code(400); // Send a bad request response code
        echo "No data received";
    }
} else {
    // If the request is not a POST request
    http_response_code(405); // Send a method not allowed response code
    echo "Method Not Allowed";
}
?>
