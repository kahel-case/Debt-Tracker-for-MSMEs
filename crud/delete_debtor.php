<?php
session_start();

include '../db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $debtor_id = $_POST["id-delete"];
    
    $sql = "DELETE FROM debtors WHERE debtor_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i",$debtor_id);

    if($stmt->execute()){
        echo "<script>alert('Debtor Successfully Deleted!'); window.location.href='../dashboard_debtor.php';</script>";
    }else{
        echo "<script>alert('Error occurred while deleting.'); window.location.href='../dashboard_debtor.php';</script>";
    }
    
    $stmt->close();    
}