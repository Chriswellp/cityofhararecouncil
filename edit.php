<?php
$conn = new mysqli("localhost", "root", "", "zrpt_db");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// 1. Handle the Update Logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $id > 0) {
    // Prepare the update statement for ALL fields
    $sql = "UPDATE registrations SET 
            tracking_code = ?, council_list_no = ?, receipt_no = ?, 
            app_name = ?, app_surname = ?, app_id = ?, app_dob = ?, app_address = ?, app_contact = ?, 
            app_employer = ?, app_city_harare = ?, app_dept = ?, app_emp_no = ?,
            kin_name = ?, kin_id = ?, kin_cell = ?, 
            spouse_name = ?, spouse_id = ?, spouse_dob = ?, 
            who_will_take_over = ?, mou_signer_name = ?
            WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssssssssssssssi", 
        $_POST['tracking_code'], $_POST['council_list_no'], $_POST['receipt_no'],
        $_POST['app_name'], $_POST['app_surname'], $_POST['app_id'], $_POST['app_dob'], $_POST['app_address'], $_POST['app_contact'],
        $_POST['app_employer'], $_POST['app_city_harare'], $_POST['app_dept'], $_POST['app_emp_no'],
        $_POST['kin_name'], $_POST['kin_id'], $_POST['kin_cell'],
        $_POST['spouse_name'], $_POST['spouse_id'], $_POST['spouse_dob'],
        $_POST['who_will_take_over'], $_POST['mou_signer_name'],
        $id
    );

    if ($stmt->execute()) {
        header("Location: view.php?id=$id&msg=updated");
        exit();
    } else {
        $error = "Error updating record: " . $conn->error;
    }
}

// 2. Fetch existing data
$res = $conn->query("SELECT * FROM registrations WHERE id = $id");
$data = $res->fetch_assoc();
if (!$data) die("Record not found.");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ZRPT | Edit Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --brand-blue: #1a365d; --brand-gold: #c5a059; }
        body { background-color: #f4f7f9; font-family: 'Inter', sans-serif; padding: 40px 0; }
        .edit-card { background: white; border-radius: 12px; border: 1px solid #cbd5e1; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
        .section-title { color: var(--brand-blue); font-weight: 800; border-bottom: 2px solid var(--brand-gold); padding-bottom: 5px; margin-bottom: 20px; font-size: 0.9rem; text-transform: uppercase; }
        label { font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; }
        .form-control:focus { border-color: var(--brand-gold); box-shadow: 0 0 0 0.25 row rgba(197, 160, 89, 0.25); }
    </style>
</head>
<body>

<div class="container" style="max-width: 900px;">
    <div class="edit-card p-4 p-md-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold m-0" style="color: var(--brand-blue);">Edit Record</h2>
            <a href="view.php?id=<?php echo $id; ?>" class="btn btn-outline-secondary btn-sm">Cancel</a>
        </div>

        <?php if(isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="section-title">Administrative Info</div>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label>Account Number</label>
                    <input type="text" name="tracking_code" class="form-control" value="<?php echo $data['tracking_code']; ?>" required>
                </div>
                <div class="col-md-4">
                    <label>Council List No</label>
                    <input type="text" name="council_list_no" class="form-control" value="<?php echo $data['council_list_no']; ?>">
                </div>
                <div class="col-md-4">
                    <label>Receipt No</label>
                    <input type="text" name="receipt_no" class="form-control" value="<?php echo $data['receipt_no']; ?>">
                </div>
            </div>

            <div class="section-title">1. Applicant Details</div>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label>First Name</label>
                    <input type="text" name="app_name" class="form-control" value="<?php echo $data['app_name']; ?>">
                </div>
                <div class="col-md-6">
                    <label>Surname</label>
                    <input type="text" name="app_surname" class="form-control" value="<?php echo $data['app_surname']; ?>">
                </div>
                <div class="col-md-8">
                    <label>ID Number</label>
                    <input type="text" name="app_id" class="form-control" value="<?php echo $data['app_id']; ?>">
                </div>
                <div class="col-md-4">
                    <label>Date of Birth</label>
                    <input type="date" name="app_dob" class="form-control" value="<?php echo $data['app_dob']; ?>">
                </div>
                <div class="col-12">
                    <label>Residential Address</label>
                    <input type="text" name="app_address" class="form-control" value="<?php echo $data['app_address']; ?>">
                </div>
                <div class="col-md-4">
                    <label>Contact Number</label>
                    <input type="text" name="app_contact" class="form-control" value="<?php echo $data['app_contact']; ?>">
                </div>
                <div class="col-md-4">
                    <label>Employer</label>
                    <input type="text" name="app_employer" class="form-control" value="<?php echo $data['app_employer']; ?>">
                </div>
                <div class="col-md-4">
                    <label>City/Council</label>
                    <input type="text" name="app_city_harare" class="form-control" value="<?php echo $data['app_city_harare']; ?>">
                </div>
                <div class="col-md-6">
                    <label>Department</label>
                    <input type="text" name="app_dept" class="form-control" value="<?php echo $data['app_dept']; ?>">
                </div>
                <div class="col-md-6">
                    <label>Employee No</label>
                    <input type="text" name="app_emp_no" class="form-control" value="<?php echo $data['app_emp_no']; ?>">
                </div>
            </div>

            <div class="section-title">2 & 3. Kin & Spouse</div>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label>Next of Kin Name</label>
                    <input type="text" name="kin_name" class="form-control" value="<?php echo $data['kin_name']; ?>">
                </div>
                <div class="col-md-4">
                    <label>Kin ID</label>
                    <input type="text" name="kin_id" class="form-control" value="<?php echo $data['kin_id']; ?>">
                </div>
                <div class="col-md-4">
                    <label>Kin Contact</label>
                    <input type="text" name="kin_cell" class="form-control" value="<?php echo $data['kin_cell']; ?>">
                </div>
                <div class="col-md-4">
                    <label>Spouse Name</label>
                    <input type="text" name="spouse_name" class="form-control" value="<?php echo $data['spouse_name']; ?>">
                </div>
                <div class="col-md-4">
                    <label>Spouse ID</label>
                    <input type="text" name="spouse_id" class="form-control" value="<?php echo $data['spouse_id']; ?>">
                </div>
                <div class="col-md-4">
                    <label>Spouse DOB</label>
                    <input type="date" name="spouse_dob" class="form-control" value="<?php echo $data['spouse_dob']; ?>">
                </div>
            </div>

            <div class="section-title">4. Legal & Succession</div>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label>Who will take over property?</label>
                    <input type="text" name="who_will_take_over" class="form-control" value="<?php echo $data['who_will_take_over']; ?>">
                </div>
                <div class="col-md-6">
                    <label>MOU Signed By</label>
                    <input type="text" name="mou_signer_name" class="form-control" value="<?php echo $data['mou_signer_name']; ?>">
                </div>
            </div>

            <div class="text-end border-top pt-4">
                <button type="submit" class="btn btn-primary px-5 fw-bold">Update Registration</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>