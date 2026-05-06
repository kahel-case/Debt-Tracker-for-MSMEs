<!DOCTYPE html>
<html lang="en">

<?php 
    include 'validate_session.php';
    include 'head.php';
?>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container py-2">
                <h1 class="navbar-brand text-light"><strong>Debt Tracker</strong></h1>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard_debtor.php">Debt Manager</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="dashboard_inventory.php">Inventory Manager <span class="sr-only">(current)</span></a>
                        </li>
                    </ul>
                    <ul class="navbar-nav ms-auto align-items-lg-center">
                        <li class="nav-item">
                            <span class="nav-link text-light">
                                User: <?php echo $_SESSION['username']; ?>
                            </span>
                        </li>
                        <li class="nav-item">
                            <button onclick="window.location.href='logout.php'" class="btn btn-danger ms-lg-2">
                                Logout
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Debtors</h5>
        <button class="btn btn-primary btn-sm">Add Debtor</button>
    </div>

    <div class="card-body">
        <table id="myTable" class="table table-striped table-hover align-middle table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Column 1</th>
                    <th>Column 2</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Row 1 Data 1</td>
                    <td>Row 1 Data 2</td>
                </tr>
                <tr>
                    <td>Row 2 Data 1</td>
                    <td>Row 2 Data 2</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

    <?php include 'scripts.php'; ?>
    <script src="lib/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>



</html>