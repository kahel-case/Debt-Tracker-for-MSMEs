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

<button data-bs-toggle="modal" data-bs-target="#insertProduct">Add Product</button>
<div class="modal fade" id="insertProduct" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="crud/insert_product.php" method="post">
                <div class="modal-body">
                    <input type="hidden" class="form-control" name="user_id" value="<?= $_SESSION['user_id'] ?>" required>
                    <div class="mb-3">
                        <label class="form-label">Product Name</label>
                        <input type="text" class="form-control" name="productName" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Product Price</label>
                        <input type="number" step="0.01" min="0" class="form-control" name="productPrice" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Product Unit Price (Optional)</label>
                        <input type="text" class="form-control" name="productUnitPrice" placeholder="Ex. (per Kilo), (/oz)">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Product Stock</label>
                        <input type="number" step="1" min="0" class="form-control" name="productStock" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

