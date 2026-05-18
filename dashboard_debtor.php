<!DOCTYPE html>
<html lang="en">

<?php 
    include 'validate_session.php';
    include 'db_connection.php';
    require 'head.php';

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

<body>
    <header class="sticky-top shadow-sm">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container py-2">

                <!-- Brand -->
                <a class="navbar-brand fw-bold" href="#">Debt Manager</a>

                <!-- Toggle -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Nav content -->
                <div class="collapse navbar-collapse" id="navbarNav">

                    <!-- Left nav -->
                    <ul class="navbar-nav me-auto gap-lg-2">
                        <li class="nav-item">
                            <a class="nav-link active" href="dashboard_debtor.php"><strong>Debtors</strong></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard_inventory.php">Inventory</a>
                        </li>
                    </ul>

                    <!-- Right side -->
                    <div class="d-flex align-items-center gap-3">
                        <span class="text-light small">User: <span class="fw-semibold"><?= $_SESSION['username']; ?></span></span>
                        <button onclick="window.location.href='logout.php'" class="btn btn-danger btn-sm rounded-pill px-4">Logout</button>
                    </div>

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
                <div class="table-responsive content-card mb-4">
                    <table id="myTable" class="table table-hover table-bordered align-middle border shadow-sm">
                        <thead class="table-dark text-center">
                            <tr>
                                <th>#</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Contact</th>
                                <th>Email</th>
                                <th>Amount</th>
                                <th>Start</th>
                                <th>Due</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <td><?= $row['debtor_id'] ?></td>
                                <td><?= $row['debtor_first_name'] ?></td>
                                <td><?= $row['debtor_last_name'] ?></td>
                                <td><?= $row['debtor_contact_number'] ?></td>
                                <td><?= $row['debtor_email_address'] ?></td>
                                <td>₱<?= number_format($row['debtor_amount_owed'],2) ?></td>
                                <td><?= $row['debtor_start_date'] ?></td>
                                <td><?= $row['debtor_due_date'] ?></td>
                                <td class="text-center">
                                    <span class="badge rounded-pill px-4 py-2
                                        <?= $row['debtor_debt_status']=='OVERDUE' ? 'bg-danger' : '' ?>
                                        <?= $row['debtor_debt_status']=='DUE' ? 'bg-warning text-dark' : '' ?>
                                        <?= $row['debtor_debt_status']=='PAID' ? 'bg-success' : '' ?>
                                        <?= $row['debtor_debt_status']=='PENDING' ? 'bg-secondary' : '' ?>">
                                        <?= $row['debtor_debt_status'] ?>
                                    </span>
                                </td>

                                <td>
                                    <!-- Action Buttons -->
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-primary flex-fill" data-bs-toggle="modal" data-bs-target="#edit-info-<?= $row['debtor_id'] ?>">Edit Info</button>
                                        <button class="btn btn-sm btn-success flex-fill" data-bs-toggle="modal" data-bs-target="#edit-debt-<?= $row['debtor_id'] ?>">Edit Debt</button>
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