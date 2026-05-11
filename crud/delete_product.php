<?php
session_start();

include '../db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $product_id = $_POST["id-delete"];
    
    $sql = "DELETE FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i",$product_id);

    if($stmt->execute()){
        echo "<script>alert('Product Successfully Deleted!'); window.location.href='../dashboard_inventory.php';</script>";
    }else{
        echo "<script>alert('Error occurred while deleting.'); window.location.href='../dashboard_inventory.php';</script>";
    }
    
    $stmt->close();    
}