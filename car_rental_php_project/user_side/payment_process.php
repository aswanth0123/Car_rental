<?php

session_start();
include('../db.php');
if(isset($_POST['amount']) && isset($_POST['rent_id']) && isset($_POST['total'])){
    $amt = $_POST['amount'];
    $rent_id = $_POST['rent_id'];
    $total = $_POST['total'];
    $payment_status = 'Pending';
    $user_id = $_SESSION['user_id'];
    $pay_date = date('Y-m-d H:i:s');
    $bal = $total-$amt;
    mysqli_query($conn,"insert into payment (rent_id,total_amount,payment_status,payment_date,advance_amount,remaining_amount) values 
    ('$rent_id','$total','$payment_status','$pay_date','$amt','$bal')");
    $_SESSION['OID']=mysqli_insert_id($conn);

}
if(isset($_POST['payment_id']) && isset($_SESSION['OID'])){
    $payment_id = $_POST['payment_id'];
    mysqli_query($conn,"update payment set payment_status='success',payment_id='$payment_id' where id='".$_SESSION['OID']."'");
}


?>