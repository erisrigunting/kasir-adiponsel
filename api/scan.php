<?php
header("Content-Type: application/json"); require "../config.php";
$b=$_POST["barcode"]??"";
$stmt=$conn->prepare("SELECT v.id,v.barcode,p.nama_produk,p.harga_jual FROM voucher_stok v JOIN produk p ON p.id=v.produk_id WHERE v.barcode=? AND v.status='tersedia'");
$stmt->bind_param("s",$b);$stmt->execute();$r=$stmt->get_result();
if($r->num_rows) echo json_encode(["status"=>"success","data"=>$r->fetch_assoc()]);
else echo json_encode(["status"=>"error","message"=>"Barcode tidak ditemukan atau voucher sudah terjual"]);
?>