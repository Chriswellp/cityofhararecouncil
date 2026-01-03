<?php
$conn = new mysqli("localhost", "root", "", "zrpt_db");
if ($conn->connect_error) die("Connection failed");

// Set headers to force download as Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=ZRPT_Registrations_" . date('Y-m-d') . ".xls");
header("Pragma: no-cache");
header("Expires: 0");

$sql = "SELECT * FROM registrations ORDER BY id DESC";
$result = $conn->query($sql);

echo '<table border="1">';
// Create Excel Table Headers
echo '<tr>
        <th style="background-color: #1a365d; color: white;">ID</th>
        <th style="background-color: #1a365d; color: white;">Account No</th>
        <th style="background-color: #1a365d; color: white;">Council No</th>
        <th style="background-color: #1a365d; color: white;">First Name</th>
        <th style="background-color: #1a365d; color: white;">Surname</th>
        <th style="background-color: #1a365d; color: white;">ID Number</th>
        <th style="background-color: #1a365d; color: white;">DOB</th>
        <th style="background-color: #1a365d; color: white;">Address</th>
        <th style="background-color: #1a365d; color: white;">Contact</th>
        <th style="background-color: #1a365d; color: white;">Employer</th>
        <th style="background-color: #1a365d; color: white;">Kin Name</th>
        <th style="background-color: #1a365d; color: white;">Successor</th>
        <th style="background-color: #1a365d; color: white;">MOU Signer</th>
      </tr>';

// Loop through database and create rows
while($row = $result->fetch_assoc()) {
    echo '<tr>';
    echo '<td>' . $row['id'] . '</td>';
    echo '<td>' . $row['tracking_code'] . '</td>';
    echo '<td>' . $row['council_list_no'] . '</td>';
    echo '<td>' . $row['app_name'] . '</td>';
    echo '<td>' . $row['app_surname'] . '</td>';
    echo '<td>' . $row['app_id'] . '</td>';
    echo '<td>' . $row['app_dob'] . '</td>';
    echo '<td>' . $row['app_address'] . '</td>';
    echo '<td>' . $row['app_contact'] . '</td>';
    echo '<td>' . $row['app_employer'] . '</td>';
    echo '<td>' . $row['kin_name'] . '</td>';
    echo '<td>' . $row['who_will_take_over'] . '</td>';
    echo '<td>' . $row['mou_signer_name'] . '</td>';
    echo '</tr>';
}
echo '</table>';
exit;
?>


