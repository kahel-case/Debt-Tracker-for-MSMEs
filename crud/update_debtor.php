<?php
session_start();

include '../db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $firstName = $_POST["firstName"];
    $middleName = $_POST["middleName"];
    $lastName = $_POST["lastName"];
    $contactNumber = $_POST["contactNumber"];
    $emailAddress = $_POST["emailAddress"];
    $amountOwed = $_POST["amountOwed"];
    $startDate = $_POST["startDate"];
    $dueDate = $_POST["dueDate"];
    $debtor_id = $_POST["id"];
    



    $sql = "UPDATE debtors SET
        debtor_first_name = ?, 
        debtor_middle_name = ?, 
        debtor_last_name = ?, 
        debtor_contact_number = ?, 
        -- debtor_email_address = ?, 
        -- debtor_amount_owed = ?, 
        debtor_start_date = ?,
        debtor_due_date = ?
        WHERE debtor_id = ?
        ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi",$firstName,$middleName,$lastName,$contactNumber,/* $emailAddress, *//* $amountOwed, */$startDate,$dueDate,$debtor_id);

    if($stmt->execute()){
        echo "<script>alert('Debtor Successfully Updated!'); window.location.href='../dashboard_debtor.php';</script>";
    }else{
        echo "<script>alert('Error occurred while updating.'); window.location.href='../dashboard_debtor.php';</script>";
    }
    
    $stmt->close();    
}