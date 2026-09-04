<?php $title="Input Stok";include "config.php";
$msg="";if($_SERVER["REQUEST_METHOD"]==="POST"){ $b=trim($_POST["barcode"]);$p=(int)$_POST["produk_id"];$s=$conn->prepare("INSERT INTO voucher_stok(barcode,produk_id) VALUES(?,?)");$s->bind_param("si",$b,$p);$msg=$s->execute()?"Voucher berhasil ditambahkan":"Barcode sudah ada / gagal disimpan";}
$produk=$conn->query("SELECT * FROM produk ORDER BY nama_produk");include "header.php";?>
<div class="card shadow-sm p-3 col-lg-7 mx-auto"><h4>📥 Input Stok Voucher</h4><?php if($msg):?><div class="alert alert-info"><?=$msg?></div><?php endif;?>
<form method="post"><label>Jenis Produk</label><select name="produk_id" class="form-select mb-3" required><?php while($p=$produk->fetch_assoc()):?><option value="<?=$p["id"]?>"><?=htmlspecialchars($p["nama_produk"])?></option><?php endwhile;?></select>
<label>Scan Barcode</label><input id="barcode" name="barcode" class="form-control scan-box mb-3" placeholder="Scan barcode..." autofocus required>
<button class="btn btn-primary w-100">Simpan Stok</button></form></div>
<script>document.getElementById('barcode').addEventListener('keydown',e=>{if(e.key==='Enter'){e.preventDefault();e.target.form.submit()}})</script><?php include "footer.php";?>