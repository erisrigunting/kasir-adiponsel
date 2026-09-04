<?php
header("Content-Type: application/json"); require "../config.php";
$d=json_decode(file_get_contents("php://input"),true);
$items=$d["items"]??[];$bayar=(float)($d["bayar"]??0);$metode=$d["metode"]??"Tunai";
if(!$items){echo json_encode(["status"=>"error","message"=>"Keranjang kosong"]);exit;}
$conn->begin_transaction();
try{
$total=0;foreach($items as $x)$total+=(float)$x["harga_jual"];
if($metode==="Tunai" && $bayar<$total) throw new Exception("Pembayaran kurang");
if($metode!=="Tunai")$bayar=$total;
$kembalian=$bayar-$total;$kode="TRX".date("YmdHis").rand(10,99);
$s=$conn->prepare("INSERT INTO transaksi(kode_transaksi,total,bayar,kembalian,metode) VALUES(?,?,?,?,?)");
$s->bind_param("sddds",$kode,$total,$bayar,$kembalian,$metode);$s->execute();$trx=$conn->insert_id;
$detail=$conn->prepare("INSERT INTO transaksi_detail(transaksi_id,voucher_id,harga) VALUES(?,?,?)");
$update=$conn->prepare("UPDATE voucher_stok SET status='terjual',sold_at=NOW() WHERE id=? AND status='tersedia'");
foreach($items as $x){
$id=(int)$x["id"];$harga=(float)$x["harga_jual"];
$update->bind_param("i",$id);$update->execute();if($update->affected_rows!==1)throw new Exception("Voucher sudah terjual");
$detail->bind_param("iid",$trx,$id,$harga);$detail->execute();
}
$conn->commit();echo json_encode(["status"=>"success","kode"=>$kode,"total"=>$total,"kembalian"=>$kembalian]);
}catch(Exception $e){$conn->rollback();echo json_encode(["status"=>"error","message"=>$e->getMessage()]);}
?>