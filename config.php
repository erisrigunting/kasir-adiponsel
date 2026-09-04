<?php
$host="localhost"; $user="root"; $pass=""; $db="voucher_app";
$conn=new mysqli($host,$user,$pass,$db);
if($conn->connect_error) die("Database gagal: ".$conn->connect_error);
$conn->set_charset("utf8mb4");
?>