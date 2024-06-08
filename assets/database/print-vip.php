<?php
	$host = 'localhost'; // Assuming your database is hosted locally
    $dbname = 'cybervlog';
    $username = 'root';
    $password = '';
    
    // Attempt to connect to the database
    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        // Set PDO to throw exceptions on errors
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        // If connection fails, display error message
        echo "Connection failed: " . $e->getMessage();
        exit;
    }
    

	function generateRow($conn){
		$contents = '';
        $sql = "SELECT vip_id, fullname, designation, rank, unit, contact_no, purpose_visit, message, signature, images, DATE_FORMAT(created_at, '%Y-%m-%d') AS date, TIME_FORMAT(time_in, '%H:%i:%s') AS time_in, TIME_FORMAT(time_out, '%H:%i:%s') AS time_out FROM tb_vip";

		$query = $conn->query($sql);
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
        
			$contents .= "
            <tr>
             <td>" . $row['vip_id'] . "</td>
             <td>" . $row['fullname'] . "</td>
             <td>" . $row['designation'] . "</td>
             <td>" . $row['rank'] . "</td>
             <td>" . $row['unit'] . "</td>
             <td>" . $row['contact_no'] . "</td>
             <td>" . $row['purpose_visit'] . "</td>
             <td>" . $row['date'] . "</td>
             <td>" . $row['time_in'] . "</td>
             <td>" . $row['time_out'] . "</td>
        </tr>

			";
		}

		return $contents;
	}

	require_once('../tcpdf/tcpdf.php');  
    $pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);  
    $pdf->SetCreator(PDF_CREATOR);  
    $pdf->SetTitle('CYBER VISITOR LOG - VIP Visitors');  
    $pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);  
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));  
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));  
    $pdf->SetDefaultMonospacedFont('helvetica');  
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);  
    $pdf->SetMargins(PDF_MARGIN_LEFT, '10', PDF_MARGIN_RIGHT);  
    $pdf->setPrintHeader(false);  
    $pdf->setPrintFooter(false);  
    $pdf->SetAutoPageBreak(TRUE, 10);  
    $pdf->SetFont('helvetica', '', 11);  
    $pdf->AddPage();  
    $content = '';  
    $content .= '
      	<h2 align="center">CYBER VISITOR LOG</h2>
        <h5 align="center"> (VIP Visitors)</h5>
      	<table border="1" cellspacing="0" cellpadding="3">  
           <tr>  
                  <th>I.D</th>
                        <th >Name</th>
                        <th >Designation/Position</th>
                        <th >Rank</th>
                        <th >Unit/Organization</th>
                        <th >Contact Number</th>
                        <th >Purpose</th>
                        <th  >Date</th>
                        <th >Time-in</th>
                        <th >Time-out</th>
                
           </tr>  
      ';  
    $content .= generateRow($conn); 
    $content .= '</table>';  
    $pdf->writeHTML($content);  
    $pdf->Output('AttendanceRecords.pdf', 'I');

?>