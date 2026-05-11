<?php
session_start();

include '../db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $productID = $_POST["productID"];
    $productName = $_POST["productName"];
    $productPrice = $_POST["productPrice"];
    $productUnitPrice = $_POST["productUnitPrice"];
    $productStock = $_POST["productStock"];

    $sql = "UPDATE products SET
        product_name = ?, 
        product_price = ?, 
        product_unit_price = ?,
        product_stock = ?
        WHERE product_id = ?
        ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdsii",$productName,$productPrice,$productUnitPrice,$productStock,$productID);

    if($stmt->execute()){
        echo "<script>alert('Product Successfully Updated!'); window.location.href='../dashboard_inventory.php';</script>";
    }else{
        echo "<script>alert('Error occurred while updating.'); window.location.href='../dashboard_inventory.php';</script>";
    }
    
    $stmt->close();    
}