<?php $title="Kasir"; include "config.php"; include "header.php"; ?>
<div class="row g-3">
<div class="col-lg-7"><div class="card shadow-sm p-3">
<h4>🛒 Transaksi Penjualan</h4>
<input id="barcode" class="form-control scan-box mb-3" placeholder="Scan barcode voucher atau ketik lalu Enter" autofocus>
<div id="alert"></div>
<div class="table-responsive"><table class="table"><thead><tr><th>Produk</th><th>Barcode</th><th>Harga</th><th></th></tr></thead><tbody id="cart"></tbody></table></div>
</div></div>
<div class="col-lg-5"><div class="card shadow-sm p-3">
<h4>💰 Pembayaran</h4>
<div class="display-6 mb-3" id="total">Rp 0</div>
<label>Metode</label><select id="metode" class="form-select mb-2"><option>Tunai</option><option>QRIS</option><option>Transfer</option></select>
<label>Bayar</label><input type="number" id="bayar" class="form-control mb-3" min="0" value="0">
<button class="btn btn-success w-100 btn-lg" onclick="tutupTransaksi()">🔒 TUTUP TRANSAKSI</button>
<button class="btn btn-outline-danger w-100 mt-2" onclick="resetCart()">Kosongkan</button>
</div></div></div>
<script>
let items=[];
const rp=n=>new Intl.NumberFormat('id-ID',{style:'currency',currency:'IDR',maximumFractionDigits:0}).format(n);
function render(){
 let total=items.reduce((s,x)=>s+Number(x.harga_jual),0);
 document.getElementById('total').textContent=rp(total);
 document.getElementById('cart').innerHTML=items.map((x,i)=>`<tr><td>${x.nama_produk}</td><td>${x.barcode}</td><td>${rp(x.harga_jual)}</td><td><button class="btn btn-sm btn-danger" onclick="hapus(${i})">×</button></td></tr>`).join('');
}
function msg(t,c='danger'){document.getElementById('alert').innerHTML=`<div class="alert alert-${c}">${t}</div>`}
async function scan(){
 let b=document.getElementById('barcode').value.trim(); if(!b)return;
 if(items.some(x=>x.barcode===b)){msg('Voucher sudah ada di transaksi');return}
 let f=new FormData();f.append('barcode',b);
 let r=await fetch('api/scan.php',{method:'POST',body:f});let d=await r.json();
 if(d.status==='success'){items.push(d.data);render();msg('Voucher ditambahkan','success')}else msg(d.message);
 document.getElementById('barcode').value='';document.getElementById('barcode').focus();
}
document.getElementById('barcode').addEventListener('keydown',e=>{if(e.key==='Enter'){e.preventDefault();scan()}});
function hapus(i){items.splice(i,1);render()}
function resetCart(){items=[];render();document.getElementById('barcode').focus()}
async function tutupTransaksi(){
 if(!items.length){msg('Belum ada voucher');return}
 let total=items.reduce((s,x)=>s+Number(x.harga_jual),0), bayar=Number(document.getElementById('bayar').value);
 if(document.getElementById('metode').value==='Tunai' && bayar<total){msg('Uang pembayaran kurang');return}
 let r=await fetch('api/tutup.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({items,bayar,metode:document.getElementById('metode').value})});
 let d=await r.json(); if(d.status==='success'){alert(`Transaksi berhasil!\\nKode: ${d.kode}\\nTotal: ${rp(d.total)}\\nKembalian: ${rp(d.kembalian)}`);resetCart();document.getElementById('bayar').value=0}else msg(d.message);
}
</script><?php include "footer.php"; ?>