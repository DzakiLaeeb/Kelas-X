<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "apirestoran";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get all customers
$sql = "SELECT * FROM pelanggans ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Customers Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    <!-- Navigation Header -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">
                <i class="bi bi-shop me-2"></i>Restaurant Dashboard
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="http://localhost:5173/">
                    <i class="bi bi-plus-circle me-1"></i>Add Customer
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="display-6 fw-bold text-dark mb-1">Customer Management</h1>
                        <p class="text-muted mb-0">Manage and view all restaurant customers</p>
                    </div>
                    <div class="text-end">
                        <div class="badge bg-success fs-6 px-3 py-2">
                            <i class="bi bi-people-fill me-1"></i>
                            <?php echo $result->num_rows; ?> Total Customers
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="text-primary mb-2">
                            <i class="bi bi-people display-4"></i>
                        </div>
                        <h5 class="card-title text-muted">Total Customers</h5>
                        <h2 class="text-primary fw-bold"><?php echo $result->num_rows; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="text-success mb-2">
                            <i class="bi bi-calendar-check display-4"></i>
                        </div>
                        <h5 class="card-title text-muted">Active Today</h5>
                        <h2 class="text-success fw-bold">
                            <?php
                            $today_count = 0;
                            $result_copy = $conn->query($sql);
                            while($row = $result_copy->fetch_assoc()) {
                                if (date('Y-m-d', strtotime($row["created_at"])) == date('Y-m-d')) {
                                    $today_count++;
                                }
                            }
                            echo $today_count;
                            ?>
                        </h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="text-warning mb-2">
                            <i class="bi bi-clock-history display-4"></i>
                        </div>
                        <h5 class="card-title text-muted">This Week</h5>
                        <h2 class="text-warning fw-bold">
                            <?php
                            $week_count = 0;
                            $result_copy2 = $conn->query($sql);
                            $week_start = date('Y-m-d', strtotime('monday this week'));
                            while($row = $result_copy2->fetch_assoc()) {
                                if (date('Y-m-d', strtotime($row["created_at"])) >= $week_start) {
                                    $week_count++;
                                }
                            }
                            echo $week_count;
                            ?>
                        </h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="text-info mb-2">
                            <i class="bi bi-graph-up display-4"></i>
                        </div>
                        <h5 class="card-title text-muted">Growth</h5>
                        <h2 class="text-info fw-bold">+12%</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Cards Grid -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0 fw-bold text-dark">
                                <i class="bi bi-person-lines-fill me-2 text-primary"></i>
                                Customer Directory
                            </h4>
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-funnel me-1"></i>Filter
                                </button>
                                <button class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-download me-1"></i>Export
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <?php if ($result->num_rows > 0): ?>
                            <?php
                            $result->data_seek(0); // Reset result pointer
                            $count = 0;
                            ?>
                            <div class="row g-0">
                                <?php while($row = $result->fetch_assoc()): ?>
                                    <?php $count++; ?>
                                    <div class="col-lg-6 col-xl-4">
                                        <div class="card border-0 border-end border-bottom h-100">
                                            <div class="card-body p-4">
                                                <div class="d-flex align-items-start">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="bg-primary bg-gradient rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                            <i class="bi bi-person-fill text-white fs-4"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                                            <h6 class="fw-bold text-dark mb-0"><?php echo htmlspecialchars($row["nama"]); ?></h6>
                                                            <span class="badge bg-light text-dark">#<?php echo $row["id"]; ?></span>
                                                        </div>

                                                        <div class="mb-2">
                                                            <small class="text-muted d-block">
                                                                <i class="bi bi-geo-alt me-1"></i>
                                                                <?php echo htmlspecialchars($row["alamat"]); ?>
                                                            </small>
                                                        </div>

                                                        <div class="mb-3">
                                                            <small class="text-muted d-block">
                                                                <i class="bi bi-telephone me-1"></i>
                                                                <?php echo htmlspecialchars($row["telp"]); ?>
                                                            </small>
                                                        </div>

                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <small class="text-muted">
                                                                <i class="bi bi-calendar3 me-1"></i>
                                                                <?php echo date('M d, Y', strtotime($row["created_at"])); ?>
                                                            </small>
                                                            <div class="btn-group btn-group-sm">
                                                                <button class="btn btn-outline-primary btn-sm">
                                                                    <i class="bi bi-eye"></i>
                                                                </button>
                                                                <button class="btn btn-outline-secondary btn-sm">
                                                                    <i class="bi bi-pencil"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <div class="mb-4">
                                    <i class="bi bi-people display-1 text-muted"></i>
                                </div>
                                <h4 class="text-muted mb-3">No Customers Found</h4>
                                <p class="text-muted mb-4">Start building your customer base by adding new customers.</p>
                                <a href="http://localhost:5173/" class="btn btn-primary btn-lg">
                                    <i class="bi bi-plus-circle me-2"></i>Add First Customer
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Footer -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm bg-dark text-white">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">Quick Actions</h6>
                                <small class="text-light opacity-75">Manage your restaurant customers efficiently</small>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="http://localhost:5173/" class="btn btn-light btn-sm">
                                    <i class="bi bi-person-plus me-1"></i>New Customer
                                </a>
                                <button class="btn btn-outline-light btn-sm">
                                    <i class="bi bi-file-earmark-spreadsheet me-1"></i>Export Data
                                </button>
                                <button class="btn btn-outline-light btn-sm">
                                    <i class="bi bi-gear me-1"></i>Settings
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Close connection
$conn->close();
?>
