<!DOCTYPE html>
<html lang="en">

<?php 
    include 'validate_session.php';
    include 'db_connection.php';
    include 'head.php';

    $stmt1 = $conn->prepare("SELECT * FROM products WHERE user_id = ?");
    $stmt1->bind_param("i", $_SESSION["user_id"]);
    $stmt1->execute();
    $products = $stmt1->get_result();

    $stmt2 = $conn->prepare("SELECT * FROM debtors WHERE user_id = ?;");
    $stmt2->bind_param("i", $_SESSION["user_id"]);
    $stmt2->execute();
    $debtors = $stmt2->get_result();

    $products_list = [];
    $debtors_list = [];

    while ($row = $products->fetch_assoc()) {
        $products_list[] = $row;
    }

    while ($row = $debtors->fetch_assoc()) {
        $debtors_list[] = $row;
    }
?>

<body>
    <header class="sticky-top">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container py-2">
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
                    <h2 class="mb-4">Product Inventory</h2>
                    <table id="myTable" class="table table-striped table-hover table-bordered align-middle">
                        <thead class="table-dark text-center">
                            <tr>
                                <th>#</th>
                                <th>Product Name</th>
                                <th>Price</th>
                                <th>Unit Price</th>
                                <th>Stock</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($products_list as $product): ?>
                            <tr>
                                <td><?= $product['product_id'] ?></td>
                                <td><?= $product['product_name'] ?></td>
                                <td>₱<?= number_format($product['product_price'],2) ?></td>
                                <td><?= $product['product_unit_price'] ?: 'N/A' ?></td>
                                <td>
                                    <span class="badge bg-secondary">
                                        <?= $product['product_stock'] ?>
                                    </span>
                                </td>

                                <td class="d-flex gap-2 flex-wrap">

                                    <!-- Action Buttons -->
                                    <div class="d-flex w-100">
                                        <button class="btn btn-sm btn-outline-primary flex-fill me-1" data-bs-toggle="modal" data-bs-target="#edit-product-<?= $product['product_id'] ?>">Edit</button>
                                        <button class="btn btn-sm btn-success flex-fill ms-1" data-bs-toggle="modal" data-bs-target="#loan-product-<?= $product['product_id'] ?>">Loan</button>
                                    </div>

                                    <!-- EDIT MODAL -->
                                    <div class="modal fade" id="edit-product-<?= $product['product_id'] ?>">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary text-white">
                                                    <h5 class="modal-title">Edit Product</h5>
                                                    <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="crud/update_product.php" method="post">
                                                    <div class="modal-body p-4">
                                                        <input type="hidden" name="productID" value="<?= $product['product_id'] ?>">
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Product Name</label>
                                                            <input type="text" class="form-control rounded-3" name="productName" value="<?= $product['product_name'] ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Price</label>
                                                            <input type="number" step="0.01" class="form-control rounded-3" name="productPrice" value="<?= $product['product_price'] ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Unit Price</label>
                                                            <input type="text" class="form-control rounded-3" name="productUnitPrice" value="<?= $product['product_unit_price'] ?>">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Stock</label>
                                                            <input type="number" class="form-control rounded-3" name="productStock" value="<?= $product['product_stock'] ?>" required>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer border-0 px-4 pb-4">
                                                        <button type="submit" class="btn btn-primary rounded-pill px-4">Save</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- LOAN MODAL -->
                                    <div class="modal fade" id="loan-product-<?= $product['product_id'] ?>">
                                        <div class="modal-dialog">
                                            <div class="modal-content">

                                                <div class="modal-header bg-success text-white">
                                                    <h5 class="modal-title">Loan Product</h5>
                                                    <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="crud/loan_product.php" method="post">
                                                    <div class="modal-body p-4">
                                                        <input type="hidden" name="productID" value="<?= $product['product_id'] ?>">
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Select Debtor</label>
                                                            <select class="form-select rounded-3" name="targetDebtor" required>
                                                                <?php foreach ($debtors_list as $debtor): ?>
                                                                    <option value="<?= $debtor['debtor_id'] ?>">
                                                                        <?= $debtor['debtor_first_name'] ?>
                                                                        <?= $debtor['debtor_last_name'] ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Quantity</label>
                                                            <input type="number" min="1" class="form-control rounded-3" name="productAmountToLoan" value="1" required>
                                                        </div>
                                                    <hr>
                                                        <div class="small text-muted">Stock available: <strong><?= $product['product_stock'] ?></strong></div>
                                                    </div>
                                                    <div class="modal-footer border-0 px-4 pb-4">
                                                        <button type="submit" class="btn btn-success rounded-pill px-4">Confirm Loan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                </td>
                            </tr>

                            <?php endforeach; ?>
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