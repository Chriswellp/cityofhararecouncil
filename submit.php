<?php
header("Content-Type: application/json");
$data = json_decode(file_get_contents("php://input"), true);

$conn = new mysqli("localhost", "root", "", "zrpt_db");

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed"]));
}

$sql = "INSERT INTO registrations (
    tracking_code, council_list_no, receipt_no, 
    app_name, app_surname, app_id, app_dob, app_address, app_contact, app_employer, app_city_harare, app_dept, app_emp_no,
    kin_name, kin_surname, kin_nee, kin_id, kin_dob, kin_rel, kin_prof, kin_address, kin_cell,
    spouse_name, spouse_surname, spouse_nee, spouse_id, spouse_dob,
    mou_signer_name, sig_app, sig_cons
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

$stmt->bind_param("ssssssssssssssssssssssssssssss", 
    $data['tracking_code'], $data['council_list_no'], $data['receipt_no'],
    $data['app_name'], $data['app_surname'], $data['app_id'], $data['app_dob'], $data['app_address'], $data['app_contact'], $data['app_employer'], $data['app_city_harare'], $data['app_dept'], $data['app_emp_no'],
    $data['kin_name'], $data['kin_surname'], $data['kin_nee'], $data['kin_id'], $data['kin_dob'], $data['kin_rel'], $data['kin_prof'], $data['kin_address'], $data['kin_cell'],
    $data['spouse_name'], $data['spouse_surname'], $data['spouse_nee'], $data['spouse_id'], $data['spouse_dob'],
    $data['mou_signer_name'], $data['sig_app'], $data['sig_cons']
);

if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => $conn->error]);
}

$stmt->close();
$conn->close();
?>