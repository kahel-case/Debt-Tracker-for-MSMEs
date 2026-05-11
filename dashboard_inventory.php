<!DOCTYPE html>
<html lang="en">

<?php 
    include 'validate_session.php';
    include 'db_connection.php';
    include 'head.php';

    $stmt1 = $conn->prepare("SELECT * FROM products WHERE user_id = ?");
    $stmt1->bind_param("i", $_SESSION["user_id"]);
    $stmt1->execute();
    $result = $stmt1->get_result();

    $stmt2 = $conn->prepare("SELECT debtor_first_name, debtor_last_name, debtor_id FROM debtors WHERE user_id = ?;");
    $stmt2->bind_param("i", $_SESSION["user_id"]);
    $stmt2->execute();
    $debtors = $stmt2->get_result();

    $debtors_list = [];

    while ($row = $debtors->fetch_assoc()) {
        $debtors_list[] = $row;
    }
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

    <div>
        <?php include 'misc/sidebar_nav.php'; ?>
    </div>

    <div class="mx-3">
        <table id="myTable" class="table table-responsive table-striped table-hover table-bordered">
            <thead class="thead-dark text-center">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Product Name</th>
                    <th scope="col">Product Price</th>
                    <th scope="col">Product Unit Price</th>
                    <th scope="col">Product Stock</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <th scope="row"><?= $row['product_id'] ?></th>
                    <td><?= $row['product_name'] ?></td>
                    <td><?= $row['product_price'] ?></td>
                    <td><?= $row['product_unit_price'] ?></td>
                    <td><?= $row['product_stock'] ?></td>
                    <td>
                        <button data-bs-toggle="modal" data-bs-target="#edit-product-info-<?= $row['product_id'] ?>" class="btn btn-primary">Edit Info</button>
                        <div class="modal fade" id="edit-product-info-<?= $row['product_id'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Product Information</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="crud/update_product.php" method="post" id="update-product-info-<?= $row['product_id'] ?>">
                                        <div class="modal-body">
                                            <input type="hidden" class="form-control" name="productID" value="<?= $row['product_id'] ?>" required>
                                            <div class="mb-3">
                                                <label class="form-label">Product Name</label>
                                                <input type="text" class="form-control" name="productName" value="<?= $row['product_name'] ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Product Price</label>
                                                <input type="number" step="0.01" min="0" class="form-control" name="productPrice" value="<?= $row['product_price'] ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Product Unit Price (Optional)</label>
                                                <input type="text" class="form-control" name="productUnitPrice" value="<?= $row['product_unit_price'] ?>" placeholder="Ex. (per Kilo), (/oz)">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Product Stock</label>
                                                <input type="number" step="1" min="0" class="form-control" name="productStock" value="<?= $row['product_stock'] ?>" required>
                                            </div>
                                        </div>
                                    </form>
                                    <form action="crud/delete_product.php" method="post" id="delete-product-<?= $row['product_id'] ?>"><input type="hidden" class="form-control" name="id-delete" value="<?= $row['product_id'] ?>" required></form>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-danger" form="delete-product-<?= $row['product_id'] ?>">Delete Product</button>
                                        <button type="submit" class="btn btn-primary" form="update-product-info-<?= $row['product_id'] ?>">Save Changes</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button data-bs-toggle="modal" data-bs-target="#loan-<?= $row['product_id'] ?>" class="btn btn-primary">Loan Product</button>
                        <div class="modal fade" id="loan-<?= $row['product_id'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Loan Product to Debtor</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="crud/loan_product.php" method="post" id="loan-product-<?= $row['product_id'] ?>">
                                        <div class="modal-body">
                                            <input type="hidden" class="form-control" name="productID" value="<?= $row['product_id'] ?>" required>
                                            <div class="mb-3">
                                                <label for="targetDebtor">Target Debtor: </label>
                                                <select name="targetDebtor" id="targetDebtor-<?= $row['product_id'] ?>">
                                                    <?php foreach ($debtors_list as $debtor): ?>
                                                        <option value="<?= $debtor['debtor_id'] ?>">
                                                            <?= $debtor['debtor_first_name'] ?>
                                                            <?= $debtor['debtor_last_name'] ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Amount</label>
                                                <input type="number" step="1" min="0" class="form-control" name="productAmountToLoan" value="1" required>
                                            </div>
                                            <hr>
                                            <div class="mb-3">
                                                <label class="form-label">Product Name</label>
                                                <input type="text" class="form-control" name="productName" value="<?= $row['product_name'] ?>" disabled>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Product Price</label>
                                                <input type="number" step="0.01" min="0" class="form-control" name="productPrice" value="<?= $row['product_price'] ?>" disabled>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Product Unit Price (Optional)</label>
                                                <input type="text" class="form-control" name="productUnitPrice" value="<?= $row['product_unit_price'] ?>" placeholder="" disabled>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Product Stock</label>
                                                <input type="number" step="1" min="0" class="form-control" value="<?= $row['product_stock'] ?>" disabled>
                                                <input type="hidden" step="1" min="0" class="form-control" name="productStock" value="<?= $row['product_stock'] ?>">
                                            </div>
                                        </div>
                                    </form>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success" form="loan-product-<?= $row['product_id'] ?>">Loan Product</button>
                                    </div>
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