<?php
session_start();

include '../db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_id = $_POST["user_id"];
    $productName = $_POST["productName"];
    $productPrice = $_POST["productPrice"];
    $productUnitPrice = $_POST["productUnitPrice"];
    $productStock = $_POST["productStock"];
    

    $sql = "SELECT product_name, user_id FROM products WHERE product_name = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss",$productName,$user_id);
    $stmt->execute();
    $stmt->store_result();

    if(!$stmt->num_rows>0){
        $sql = "INSERT INTO products (
            user_id,
            product_name, 
            product_price, 
            product_unit_price,
            product_stock
            ) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isdsi",$user_id,$productName,$productPrice,$productUnitPrice,$productStock);

        if($stmt->execute()){
            echo "<script>alert('Product Successfully Added!'); window.location.href='../dashboard_inventory.php';</script>";
        }else{
            echo "<script>alert('Error occurred while inserting.'); window.location.href='../dashboard_inventory.php';</script>";
        }
    } else {
        echo "<script>alert('That product already exists!'); window.location.href='../dashboard_inventory.php';</script>";
    }
    $stmt->close();    
}