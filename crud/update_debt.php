<?php
session_start();

include '../db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $debtor_id = $_POST["id"];
    $amountOwed = $_POST["amountOwed"];

    

    $sql = "SELECT debtor_amount_owed FROM debtors WHERE debtor_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s",$debtor_id);
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows>0){
        $sql = "UPDATE debtors SET
            debtor_amount_owed = ? 
            WHERE debtor_id = ?
            ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si",$amountOwed,$debtor_id);

        if($stmt->execute()){
            echo "<script>alert('Debtor Successfully Updated!'); window.location.href='../dashboard_debtor.php';</script>";
        }else{
            echo "<script>alert('Error occurred while updating.'); window.location.href='../dashboard_debtor.php';</script>";
        }
    } else {
        echo "<script>alert('That person does not exists!'); window.location.href='../dashboard_debtor.php';</script>";
    }
    $stmt->close();    
}