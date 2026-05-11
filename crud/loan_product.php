<?php
session_start();

include '../db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $product_id = $_POST["productID"];
    $debtor_id = $_POST["targetDebtor"];
    $loaned_amount = $_POST["productAmountToLoan"];

       
    $conn->begin_transaction();

    try {
        $sql1 = "UPDATE products
                SET product_stock = product_stock - ?
                WHERE product_id = ?
                AND product_stock >= ?";
                
        $stmt1 = $conn->prepare($sql1);
        $stmt1->bind_param("iii",
            $loaned_amount,
            $product_id,
            $loaned_amount
        );
        $stmt1->execute();

        if ($stmt1->affected_rows <= 0) {
            $conn->rollback();
            echo "<script>alert('Stock to loan cannot be greater than current stock!'); window.location.href='../dashboard_inventory.php';</script>";
            throw new Exception("Insufficient stock.");
        }

        $sql2 = "UPDATE debtors d
                JOIN products p ON p.product_id = ?
                SET d.debtor_amount_owed = d.debtor_amount_owed + (p.product_price * ?)
                WHERE d.debtor_id = ?";
                
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("iii",
            $product_id,
            $loaned_amount,
            $debtor_id
        );
        $stmt2->execute();

        $conn->commit();
        echo "<script>alert('Product Successfully Loaned!'); window.location.href='../dashboard_inventory.php';</script>";

    } catch (Exception $e) {
        $conn->rollback();
        echo "<script>alert('Error occurred while loaning: $e'); window.location.href='../dashboard_inventory.php';</script>";
    }

}