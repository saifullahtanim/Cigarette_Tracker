<?php
session_start();
include('../db/config.php');

// Check if payment was made and member_id is set
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['payment_amount'], $_POST['member_id'])) {
    $payment_amount = floatval($_POST['payment_amount']);
    $member_id = intval($_POST['member_id']);

    // Update the members table with the new paid amount
    $con->query("UPDATE members SET paid_amount = paid_amount + $payment_amount, 
                                    due_amount = total_buy - paid_amount - $payment_amount
                                    WHERE id = $member_id");

    // Log the payment in the payment_logs table
    $con->query("INSERT INTO payment_logs (member_id, amount_paid) 
                VALUES ($member_id, $payment_amount)");

    // Redirect back to the admin page or member's page after payment
    header("Location: admin_index.php"); // Or redirect to any other page
    exit();
}
