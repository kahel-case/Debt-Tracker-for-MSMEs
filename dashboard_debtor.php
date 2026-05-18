<!DOCTYPE html>
<html lang="en">

<?php 
    include 'validate_session.php';
    include 'db_connection.php';
    include 'head.php';

    $sql = "
    UPDATE debtors
    SET debtor_debt_status =
        CASE
            WHEN debtor_amount_owed = 0 THEN 'PAID'
            WHEN debtor_due_date > CURDATE() THEN 'PENDING'
            WHEN debtor_due_date = CURDATE() THEN 'DUE'
            WHEN debtor_due_date < CURDATE() THEN 'OVERDUE'
        END
    ";

    $conn->query($sql);

    $stmt = $conn->prepare("SELECT * FROM debtors WHERE user_id = ?");
    $stmt->bind_param("i", $_SESSION["user_id"]);
    $stmt->execute();
    $result = $stmt->get_result();
?>

<style>
    .sidebar-sticky {
    position: sticky;
    top: 70px;
    display: flex;
    flex-direction: column;
    align-self: flex-start;
}
.sticky-note {
    background: #fff9a6;
    padding: 14px;
    border-radius: 8px;
    box-shadow: 2px 4px 10px rgba(0,0,0,0.15);
    position: relative;
}

</style>

<body>
    <header class="sticky-top">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark ">
            <div class="container py-2">
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
                            <span class="nav-link text-light">User: <?= $_SESSION['username']; ?></span>
                        </li>
                        <li class="nav-item">
                            <button onclick="window.location.href='logout.php'" class="btn btn-danger rounded-pill px-4">Logout</button>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <div class="container-fluid mt-4">
        <div class="row g-4">
            <div class="col-lg-2 flex-column sidebar-sticky">
                <?php include 'misc/sidebar_nav.php'; ?>
            </div>

            <div class="col-lg-10 flex-column">
                <div class="content-card">
                    <h2 class="mb-4">Debtor Records</h2>
                    <table id="myTable" class="table table-striped table-hover table-bordered align-middle">
                        <thead class="table-dark text-center">
                            <tr>
                                <th>#</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Contact Number</th>
                                <th>Email Address</th>
                                <th>Amount Owed</th>
                                <th>Start Date</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $result->fetch_assoc()): ?>

                            <tr class="<?php
                                            if($row['debtor_debt_status']=='OVERDUE') echo 'status-overdue';
                                            elseif($row['debtor_debt_status']=='DUE') echo 'status-due';
                                            elseif($row['debtor_debt_status']=='PAID') echo 'status-paid';
                                            else echo 'status-pending';
                                ?>">

                                <td><?= $row['debtor_id'] ?></td>
                                <td><?= $row['debtor_first_name'] ?></td>
                                <td><?= $row['debtor_last_name'] ?></td>
                                <td><?= $row['debtor_contact_number'] ?></td>
                                <td><?= $row['debtor_email_address'] ?></td>
                                <td>₱<?= number_format($row['debtor_amount_owed'],2) ?></td>
                                <td><?= $row['debtor_start_date'] ?></td>
                                <td><?= $row['debtor_due_date'] ?></td>
                                <td><span class="badge-status"><?= $row['debtor_debt_status'] ?></span></td>

                                <td>
                                    <!-- Action Buttons -->
                                    <div class="d-flex w-100">
                                        <button data-bs-toggle="modal" data-bs-target="#edit-info-<?= $row['debtor_id'] ?>" class="btn btn-sm btn-outline-primary flex-fill me-1">Edit Info</button>
                                        <button data-bs-toggle="modal" data-bs-target="#edit-debt-<?= $row['debtor_id'] ?>" class="btn btn-sm btn-success flex-fill ms-1">Edit Debt</button>
                                    </div>

                                    <!-- Edit Info Modal -->
                                    <div class="modal fade" id="edit-info-<?= $row['debtor_id'] ?>">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary text-white">
                                                    <h5 class="modal-title">Edit Debtor</h5>
                                                    <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="post" id="edit_debtor_form">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="id" value="<?= $row['debtor_id'] ?>">
                                                        <div class="mb-3">
                                                            <label>First Name</label>
                                                            <input class="form-control" type="text" name="firstName" value="<?= $row['debtor_first_name'] ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label>Last Name</label>
                                                            <input class="form-control" type="text" name="lastName" value="<?= $row['debtor_last_name'] ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label>Contact Number</label>
                                                            <input class="form-control" type="text" name="contactNumber" value="<?= $row['debtor_contact_number'] ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label>Start Date</label>
                                                            <input class="form-control" type="date" name="startDate" value="<?= $row['debtor_start_date'] ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label>Due Date</label>
                                                            <input class="form-control" type="date" name="dueDate" value="<?= $row['debtor_due_date'] ?>" required>
                                                        </div>
                                                    </div>
                                                </form>
                                                <div class="modal-footer">
                                                    <button class="btn btn-danger rounded-pill px-4" type="submit" form="edit_debtor_form" formaction="crud/delete_debtor.php">Delete Record</button>
                                                    <button class="btn btn-primary rounded-pill px-4" type="submit" form="edit_debtor_form" formaction="crud/update_debtor.php">Save Changes</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Edit Debt Modal -->
                                    <div class="modal fade" id="edit-debt-<?= $row['debtor_id'] ?>">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-success text-white">
                                                    <h5 class="modal-title">Edit Debt</h5>
                                                    <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="crud/update_debt.php" method="post">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="id" value="<?= $row['debtor_id'] ?>">
                                                        <div class="mb-3">
                                                            <label>Amount Owed</label>
                                                            <input class="form-control" type="number" step="0.01" name="amountOwed" value="<?= $row['debtor_amount_owed'] ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-success rounded-pill px-4" type="submit">
                                                            Save Changes
                                                        </button>
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
            </div>
        </div>
    </div>

    <!-- ADD DEBTOR MODAL -->
    <div class="modal fade" id="insertDebtor" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow border-0 rounded-4">

                <div class="modal-header bg-primary text-white rounded-top-4">
                    <h5 class="modal-title">Add New Debtor</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <form action="crud/insert_debtor.php" method="post">
                    <div class="modal-body p-4">

                        <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">First Name</label>
                            <input type="text" class="form-control rounded-3" name="firstName" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Last Name</label>
                            <input type="text" class="form-control rounded-3" name="lastName" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Contact Number</label>
                            <input type="text" class="form-control rounded-3" name="contactNumber" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email Address</label>
                            <input type="email" class="form-control rounded-3" name="emailAddress" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Amount Owed</label>
                            <input type="number" step="0.01" min="0" class="form-control rounded-3" name="amountOwed" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Start Date</label>
                            <input type="date" id="startDate" class="form-control rounded-3" name="startDate" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Due Date</label>
                            <input type="date" id="dueDate" class="form-control rounded-3" name="dueDate" required>
                        </div>

                    </div>

                    <div class="modal-footer border-0 px-4 pb-4">
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>

                        <button type="submit" class="btn btn-primary rounded-pill px-4">Add Debtor</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- ADD PRODUCT MODAL -->
    <div class="modal fade" id="insertProduct" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow border-0 rounded-4">

                <div class="modal-header bg-success text-white rounded-top-4">
                    <h5 class="modal-title">Add Product</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <form action="crud/insert_product.php" method="post">
                    <div class="modal-body p-4">

                        <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Product Name</label>
                            <input type="text" class="form-control rounded-3" name="productName" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Product Price</label>
                            <input type="number" step="0.01" min="0" class="form-control rounded-3" name="productPrice" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Product Unit Price <small class="text-muted">(optional)</small>
                            </label>
                            <input type="text" class="form-control rounded-3" name="productUnitPrice" placeholder="Ex. /kg, /oz">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Product Stock</label>
                            <input type="number" min="0" class="form-control rounded-3" name="productStock" required>
                        </div>

                    </div>

                    <div class="modal-footer border-0 px-4 pb-4">
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success rounded-pill px-4">Add Product</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {

        const today = new Date();

        const yyyy = today.getFullYear();
        const mm = String(today.getMonth() + 1).padStart(2,'0');
        const dd = String(today.getDate()).padStart(2,'0');

        const startDate = document.getElementById('startDate');
        if(startDate){
            startDate.value = `${yyyy}-${mm}-${dd}`;
        }

        const due = new Date();
        due.setDate(due.getDate() + 14);

        const dueY = due.getFullYear();
        const dueM = String(due.getMonth() + 1).padStart(2,'0');
        const dueD = String(due.getDate()).padStart(2,'0');

        const dueDate = document.getElementById('dueDate');
        if(dueDate){
            dueDate.value = `${dueY}-${dueM}-${dueD}`;
        }

    });
    </script>

    <?php include 'scripts.php'; ?>
</body>



</html>