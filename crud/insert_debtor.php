<?php
session_start();

include '../db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_id = $_POST["user_id"];
    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $contactNumber = $_POST["contactNumber"];
    $emailAddress = $_POST["emailAddress"];
    $amountOwed = $_POST["amountOwed"];
    $startDate = $_POST["startDate"];
    $dueDate = $_POST["dueDate"];
    

    $sql = "SELECT debtor_first_name, debtor_last_name FROM debtors WHERE debtor_first_name = ? AND debtor_last_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss",$firstName,$lastName);
    $stmt->execute();
    $stmt->store_result();

    if(!$stmt->num_rows>0){
        $sql = "INSERT INTO debtors(
            user_id,
            debtor_first_name,
            debtor_last_name, 
            debtor_contact_number, 
            debtor_email_address, 
            debtor_amount_owed, 
            debtor_start_date,
            debtor_due_date
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issssdss",$user_id,$firstName,$lastName,$contactNumber,$emailAddress,$amountOwed,$startDate,$dueDate);

        if($stmt->execute()){
            echo "<script>alert('Debtor Successfully Added!'); window.location.href='../dashboard_debtor.php';</script>";
        }else{
            echo "<script>alert('Error occurred while inserting.'); window.location.href='../dashboard_debtor.php';</script>";
        }
    } else {
        echo "<script>alert('That person already exists!'); window.location.href='../dashboard_debtor.php';</script>";
    }
    $stmt->close();    
}