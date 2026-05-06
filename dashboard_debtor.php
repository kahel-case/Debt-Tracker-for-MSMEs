<!DOCTYPE html>
<html lang="en">

<?php 
    include 'validate_session.php';
    include 'db_connection.php';
    include 'head.php';

    $stmt = $conn->prepare("SELECT * FROM debtors WHERE creditor_id = ?");
    $stmt->bind_param("i", $_SESSION["user_id"]);
    $stmt->execute();
    $result = $stmt->get_result();
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
                            <a class="nav-link active" href="dashboard_debtor.php">Debt Manager <span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard_inventory.php">Inventory Manager</a>
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

    <button data-bs-toggle="modal" data-bs-target="#insertDebtor">Add Debtor</button>
    <div class="modal fade" id="insertDebtor" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Debtor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="crud/insert_debtor.php" method="post">
                    <div class="modal-body">
                        <input type="hidden" class="form-control" name="user_id" value="<?= $_SESSION['user_id'] ?>" required>
                        <div class="mb-3">
                            <label class="form-label">First Name</label>
                            <input type="text" class="form-control" name="firstName" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Middle Name</label>
                            <input type="text" class="form-control" name="middleName" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Last Name</label>
                            <input type="text" class="form-control" name="lastName" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contact Number</label>
                            <input type="text" class="form-control" name="contactNumber" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" class="form-control" name="emailAddress" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Amount Owed</label>
                            <input type="number" step="0.01" class="form-control" name="amountOwed" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" id="startDate" class="form-control" name="startDate" required>
                        </div>
                        <script>
                            const dateInputNow = document.getElementById('startDate');
                            const now = new Date();

                            const yearNow = now.getFullYear();
                            const monthNow = String(now.getMonth() + 1).padStart(2, '0');
                            const dayNow = String(now.getDate()).padStart(2, '0');

                            dateInputNow.value = `${yearNow}-${monthNow}-${dayNow}`;
                        </script>
                        <div class="mb-3">
                            <label class="form-label">Due Date</label>
                            <input type="date" id="dueDate" class="form-control" name="dueDate" required>
                        </div>
                        <script>
                            window.onload = function() {
                                const dateInputDue = document.getElementById('dueDate');
                                const now = new Date();

                                const yearDue = now.getFullYear();
                                const monthDue = String(now.getMonth() + 1).padStart(2, '0');
                                const dayDue = String(now.getDate() + 14).padStart(2, '0');

                                dateInputDue.value = `${yearDue}-${monthDue}-${dayDue}`;
                            };
                        </script>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="mx-3">
        <table id="myTable" class="table table-responsive table-striped table-hover table-bordered">
            <thead class="thead-dark text-center">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">First Name</th>
                    <th scope="col">Middle Name</th>
                    <th scope="col">Last Name</th>
                    <th scope="col">Contact Number</th>
                    <th scope="col">Email Address</th>
                    <th scope="col">Amount Owed</th>
                    <th scope="col">Start Date</th>
                    <th scope="col">Due Date</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <th scope="row"><?= $row['debtor_id'] ?></th>
                    <td><?= $row['debtor_first_name'] ?></td>
                    <td><?= $row['debtor_middle_name'] ?></td>
                    <td><?= $row['debtor_last_name'] ?></td>
                    <td><?= $row['debtor_contact_number'] ?></td>
                    <td><?= $row['debtor_email_address'] ?></td>
                    <td><?= $row['debtor_amount_owed'] ?></td>
                    <td><?= $row['debtor_start_date'] ?></td>
                    <td><?= $row['debtor_due_date'] ?></td>
                    <td><?= $row['debtor_debt_status'] ?></td>
                    <td>
                        <button data-bs-toggle="modal" data-bs-target="#edit-info-<?= $row['debtor_id'] ?>" class="btn btn-primary">Edit Info</button>
                        <div class="modal fade" id="edit-info-<?= $row['debtor_id'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Debtor</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="crud/update_debtor.php" method="post" id="update-debtor-info-<?= $row['debtor_id'] ?>">
                                        <div class="modal-body">
                                            <input type="hidden" class="form-control" name="id" value="<?= $row['debtor_id'] ?>" required>
                                            <div class="mb-3">
                                                <label class="form-label">First Name</label>
                                                <input type="text" class="form-control" name="firstName" value="<?= $row['debtor_first_name'] ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Middle Name</label>
                                                <input type="text" class="form-control" name="middleName" value="<?= $row['debtor_middle_name'] ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Last Name</label>
                                                <input type="text" class="form-control" name="lastName" value="<?= $row['debtor_last_name'] ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Contact Number</label>
                                                <input type="text" class="form-control" name="contactNumber" value="<?= $row['debtor_contact_number'] ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Email Address</label>
                                                <input type="email" class="form-control" name="emailAddress" value="<?= $row['debtor_email_address'] ?>" disabled>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Amount Owed</label>
                                                <input type="number" step="0.01" class="form-control" name="amountOwed" value="<?= $row['debtor_amount_owed'] ?>" disabled>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Start Date</label>
                                                <input type="date" id="startDate" class="form-control" name="startDate" value="<?= $row['debtor_start_date'] ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Due Date</label>
                                                <input type="date" id="dueDate" class="form-control" name="dueDate" value="<?= $row['debtor_due_date'] ?>" required>
                                            </div>
                                        </div>
                                    </form>
                                    <form action="crud/delete_debtor.php" method="post" id="delete-debtor-<?= $row['debtor_id'] ?>"><input type="hidden" class="form-control" name="id-delete" value="<?= $row['debtor_id'] ?>" required></form>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-danger" form="delete-debtor-<?= $row['debtor_id'] ?>">Delete Record</button>
                                        <button type="submit" class="btn btn-primary" form="update-debtor-info-<?= $row['debtor_id'] ?>">Save Changes</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button data-bs-toggle="modal" data-bs-target="#edit-debt-<?= $row['debtor_id'] ?>" class="btn btn-primary">Edit Debt</button>
                        <div class="modal fade" id="edit-debt-<?= $row['debtor_id'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Debt</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="crud/update_debt.php" method="post">
                                        <div class="modal-body">
                                            <input type="hidden" class="form-control" name="id" value="<?= $row['debtor_id'] ?>" required>
                                            <div class="mb-3">
                                                <label class="form-label">Amount Owed</label>
                                                <input type="number" step="0.01" class="form-control" name="amountOwed" value="<?= $row['debtor_amount_owed'] ?>" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <?php include 'scripts.php'; ?>
</body>



</html>