<?php $title="Produk";include "config.php";
if($_SERVER["REQUEST_METHOD"]==="POST"){ $s=$conn->prepare("INSERT INTO produk(nama_produk,kategori,operator,nominal,harga_modal,harga_jual) VALUES(?,?,?,?,?,?)");$s->bind_param("ssssdd",$_POST["nama"],$_POST["kategori"],$_POST["operator"],$_POST["nominal"],$_POST["modal"],$_POST["jual"]);$s->execute();header("Location: produk.php");exit;}
include "header.php";$rows=$conn->query("SELECT * FROM produk ORDER BY id DESC");?>
<div class="row g-3"><div class="col-lg-4"><div class="card p-3 shadow-sm"><h4>Tambah Produk</h4><form method="post">
<input name="nama" class="form-control mb-2" placeholder="Nama produk" required>
<select name="kategori" class="form-select mb-2"><option>Pulsa</option><option>Internet</option></select>
<input name="operator" class="form-control mb-2" placeholder="Operator" required><input name="nominal" class="form-control mb-2" placeholder="Nominal/Paket">
<input name="modal" type="number" class="form-control mb-2" placeholder="Harga modal" required><input name="jual" type="number" class="form-control mb-2" placeholder="Harga jual" required>
<button class="btn btn-primary w-100">Simpan</button></form></div></div>
<div class="col-lg-8"><div class="card p-3 shadow-sm"><h4>Daftar Produk</h4><div class="table-responsive"><table class="table"><tr><th>Produk</th><th>Kategori</th><th>Harga Jual</th></tr><?php while($r=$rows->fetch_assoc()):?><tr><td><?=htmlspecialchars($r["nama_produk"])?></td><td><?=$r["kategori"]?></td><td>Rp <?=number_format($r["harga_jual"],0,",",".")?></td></tr><?php endwhile;?></table></div></div></div></div><?php include "footer.php";?>