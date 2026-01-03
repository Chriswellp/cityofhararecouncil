<?php
$conn = new mysqli("localhost", "root", "", "zrpt_db");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// 1. Handle Deletion Logic
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $conn->query("DELETE FROM registrations WHERE id = $delete_id");
    header("Location: view.php?msg=deleted");
    exit();
}

$registration = null;
$all_records = null;
$search_query = "";

// 2. Dashboard Stats Logic
$total_res = $conn->query("SELECT COUNT(*) as total FROM registrations")->fetch_assoc()['total'];

// 3. Handle Search or Specific Record Selection (FULLY RESTORED)
if (isset($_GET['search']) || isset($_GET['id'])) {
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $search_val = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : "";
    
    if ($id > 0) {
        $sql = "SELECT * FROM registrations WHERE id = $id LIMIT 1";
    } else {
        $sql = "SELECT * FROM registrations WHERE tracking_code = '$search_val' OR app_id = '$search_val' LIMIT 1";
    }
    
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $registration = $result->fetch_assoc();
    }
} 

// 4. Fetch All Records for the "One after the other" display (FULLY RESTORED)
if (!$registration) {
    $all_records = $conn->query("SELECT * FROM registrations ORDER BY id DESC");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZRPT | Database System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <style>
        :root { --brand-blue: #1a365d; --brand-gold: #c5a059; --bg-body: #f4f7f9; }
        body { 
            font-family: 'Inter', sans-serif; 
            background-color: var(--bg-body); 
            background-image: radial-gradient(circle at 2px 2px, #cbd5e1 1px, transparent 0);
            background-size: 30px 30px;
            color: #1e293b; 
        }
        
        /* Modernized Form Wrapper */
        .form-wrapper { 
            background: rgba(255, 255, 255, 0.95); 
            backdrop-filter: blur(10px);
            border-radius: 15px; 
            padding: 45px; 
            margin: 20px auto; 
            max-width: 1000px; 
            border: 1px solid #cbd5e1; 
            box-shadow: 0 15px 35px rgba(0,0,0,0.08); 
            position: relative; 
        }
        .form-wrapper::before {
            content: ""; position: absolute; top: 0; left: 0; right: 0; height: 5px;
            background: linear-gradient(90deg, var(--brand-blue), var(--brand-gold));
            border-radius: 15px 15px 0 0;
        }

        .section-header { 
            background-color: rgba(197, 160, 89, 0.08); 
            padding: 12px 18px; 
            margin: 25px 0 15px; 
            border-left: 5px solid var(--brand-gold); 
            border-radius: 0 5px 5px 0;
        }
        .section-header h3 { font-size: 0.85rem; font-weight: 800; color: var(--brand-blue); margin: 0; text-transform: uppercase; letter-spacing: 0.5px; }
        
        .data-label { font-weight: 700; color: #64748b; font-size: 0.65rem; text-transform: uppercase; margin-bottom: 2px; }
        .data-value { border-bottom: 1px solid #e2e8f0; padding: 5px 0; font-weight: 600; min-height: 30px; margin-bottom: 15px; color: #000; }
        
        /* Stats Cards */
        .stat-card { background: white; border-radius: 12px; border: 1px solid #e2e8f0; transition: transform 0.2s; }
        .stat-card:hover { transform: translateY(-3px); }

        /* Table Styling */
        .table-card { background: white; border-radius: 12px; border: 1px solid #cbd5e1; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .table thead { background-color: #f8fafc; }
        
        .sig-img { max-height: 100px; border: 1px dashed #cbd5e1; padding: 5px; background: #fdfdfd; border-radius: 4px; }
        
        @media print { 
            .no-print { display: none !important; } 
            body { background: white; }
            .form-wrapper { border: none; box-shadow: none; margin: 0; padding: 0; width: 100%; }
            .form-wrapper::before { display: none; }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-dark bg-dark no-print mb-4 p-3 shadow-sm">
    <div class="container">
        <div class="d-flex align-items-center">
            <a href="index.html" class="btn btn-outline-light btn-sm me-3">
                <i class="fas fa-home me-1"></i> HOME
            </a>
            <a class="navbar-brand fw-bold" href="view.php" style="font-family: 'Playfair Display', serif; letter-spacing: 1px;">
                <i class="fas fa-database me-2 text-warning"></i>ZRPT DATABASE
            </a>
        </div>
        <form class="d-flex" action="view.php" method="GET">
            <input class="form-control form-control-sm me-2 bg-dark text-white border-secondary" type="search" name="search" placeholder="Search ID or Account #">
            <button class="btn btn-warning btn-sm fw-bold" type="submit">Search</button>
        </form>
    </div>
</nav>

<div class="container pb-5">

    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
        <div class="alert alert-danger no-print shadow-sm"><i class="fas fa-check-circle me-2"></i> Record deleted successfully.</div>
    <?php endif; ?>

    <?php if ($registration): ?>
        <div class="d-flex justify-content-between align-items-center no-print mb-3">
            <div>
                <a href="view.php" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i> Back to Database</a>
                <a href="index.html" class="btn btn-outline-secondary btn-sm ms-2" title="Go to Home"><i class="fas fa-home"></i></a>
            </div>
            <div>
                <a href="edit.php?id=<?php echo $registration['id']; ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit me-1"></i> Edit Record</a>
                <button onclick="window.print()" class="btn btn-primary btn-sm px-3 shadow-sm"><i class="fas fa-print me-1"></i> Print Registration</button>
            </div>
        </div>

        <div class="form-wrapper">
            <div class="row mb-4">
                <div class="col-md-7">
                    <h1 class="fw-800" style="color: var(--brand-blue); font-size: 1.8rem; font-family: 'Playfair Display', serif;">ZIMBABWE ROYAL PROJECTS TRUST</h1>
                    <p class="fw-bold text-muted small">OFFICIAL REGISTRATION RECORD</p>
                    <div class="mt-4">
                        <label class="data-label">Account Number</label>
                        <div class="fs-4 fw-bold text-primary"><?php echo $registration['tracking_code']; ?></div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="p-3 bg-light rounded border">
                        <label class="data-label">Council Waiting List No.</label>
                        <div class="data-value"><?php echo $registration['council_list_no']; ?></div>
                        <label class="data-label">Receipt No.</label>
                        <div class="data-value"><?php echo $registration['receipt_no']; ?></div>
                    </div>
                </div>
            </div>

            <div class="section-header"><h3>SECTION 1: APPLICANT DETAILS</h3></div>
            <div class="row">
                <div class="col-md-6"><label class="data-label">First Name</label><div class="data-value"><?php echo $registration['app_name']; ?></div></div>
                <div class="col-md-6"><label class="data-label">Surname</label><div class="data-value"><?php echo $registration['app_surname']; ?></div></div>
                <div class="col-md-8"><label class="data-label">ID Number</label><div class="data-value"><?php echo $registration['app_id']; ?></div></div>
                <div class="col-md-4"><label class="data-label">Date of Birth</label><div class="data-value"><?php echo $registration['app_dob']; ?></div></div>
                <div class="col-12"><label class="data-label">Residential Address</label><div class="data-value"><?php echo $registration['app_address']; ?></div></div>
                <div class="col-md-4"><label class="data-label">Contact</label><div class="data-value"><?php echo $registration['app_contact']; ?></div></div>
                <div class="col-md-4"><label class="data-label">Employer</label><div class="data-value"><?php echo $registration['app_employer']; ?></div></div>
                <div class="col-md-4"><label class="data-label">Dept / Emp No</label><div class="data-value"><?php echo $registration['app_dept'] . " / " . $registration['app_emp_no']; ?></div></div>
            </div>

            <div class="section-header"><h3>SECTION 2 & 3: KIN & SPOUSE</h3></div>
            <div class="row">
                <div class="col-md-6"><label class="data-label">Next of Kin Name</label><div class="data-value"><?php echo $registration['kin_name']; ?></div></div>
                <div class="col-md-6"><label class="data-label">Kin ID / Contact</label><div class="data-value"><?php echo $registration['kin_id'] . " / " . $registration['kin_cell']; ?></div></div>
                <div class="col-md-6"><label class="data-label">Spouse Name</label><div class="data-value"><?php echo $registration['spouse_name'] . " " . $registration['spouse_surname']; ?></div></div>
                <div class="col-md-6"><label class="data-label">Spouse ID / DOB</label><div class="data-value"><?php echo $registration['spouse_id'] . " / " . $registration['spouse_dob']; ?></div></div>
            </div>

            <div class="section-header"><h3>SECTION 4: SUCCESSION</h3></div>
            <p class="small fw-bold">In the event of loss or death who is going to take over the property?</p>
            <div class="data-value text-primary fw-bold text-uppercase"><?php echo $registration['who_will_take_over']; ?></div>

            <div class="row mt-5">
                <div class="col-md-6 text-center">
                    <label class="data-label d-block">Applicant Signature</label>
                    <img src="<?php echo $registration['sig_app']; ?>" class="sig-img w-75">
                </div>
                <div class="col-md-6 text-center">
                    <label class="data-label d-block">Consultant Signature</label>
                    <img src="<?php echo $registration['sig_cons']; ?>" class="sig-img w-75">
                </div>
            </div>
            <div class="mt-4"><label class="data-label">MOU Signed By</label><div class="data-value"><?php echo $registration['mou_signer_name']; ?></div></div>
        </div>

    <?php else: ?>
        <div class="row g-4 mb-4 no-print">
            <div class="col-md-4">
                <div class="stat-card p-3 shadow-sm border-start border-4 border-primary">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3"><i class="fas fa-users text-primary"></i></div>
                        <div>
                            <div class="text-muted small fw-bold">TOTAL REGISTRATIONS</div>
                            <div class="h4 fw-bold mb-0"><?php echo number_format($total_res); ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card p-3 shadow-sm border-start border-4 border-warning">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning bg-opacity-10 p-3 rounded-circle me-3"><i class="fas fa-file-invoice text-warning"></i></div>
                        <div>
                            <div class="text-muted small fw-bold">SYSTEM STATUS</div>
                            <div class="h4 fw-bold mb-0 text-success">ONLINE</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card p-3 shadow-sm border-start border-4 border-dark">
                    <div class="d-flex align-items-center">
                        <div class="bg-dark bg-opacity-10 p-3 rounded-circle me-3"><i class="fas fa-shield-alt text-dark"></i></div>
                        <div>
                            <div class="text-muted small fw-bold">SECURE DATABASE</div>
                            <div class="h4 fw-bold mb-0">ACTIVE</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4 no-print">
            <h3 class="fw-bold m-0 text-dark">All Registered Persons</h3>
            <div class="btn-group shadow-sm">
                <a href="export.php" class="btn btn-success"><i class="fas fa-file-excel me-1"></i> Excel Export</a>
                <a href="registration.html" class="btn btn-primary px-3"><i class="fas fa-plus me-1"></i> New Registration</a>
            </div>
        </div>

        <div class="table-card shadow-sm">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="small fw-bold ps-3">ACCOUNT</th>
                        <th class="small fw-bold">FULL NAME</th>
                        <th class="small fw-bold">ID NUMBER</th>
                        <th class="small fw-bold">CONTACT</th>
                        <th class="small fw-bold text-end pe-3">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($all_records && $all_records->num_rows > 0): ?>
                        <?php while($row = $all_records->fetch_assoc()): ?>
                        <tr>
                            <td class="ps-3"><span class="badge bg-primary px-3 py-2"><?php echo $row['tracking_code']; ?></span></td>
                            <td class="fw-bold text-dark"><?php echo $row['app_name'] . " " . $row['app_surname']; ?></td>
                            <td><?php echo $row['app_id']; ?></td>
                            <td><?php echo $row['app_contact']; ?></td>
                            <td class="text-end pe-3">
                                <div class="btn-group">
                                    <a href="view.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-dark btn-sm" title="View Detail"><i class="fas fa-eye"></i></a>
                                    <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-warning btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                                    <a href="view.php?delete_id=<?php echo $row['id']; ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Permanently delete this person?')" title="Delete"><i class="fas fa-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center p-5 text-muted">The database is currently empty.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>