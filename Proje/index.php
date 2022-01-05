<?php

include 'baglanti.php';
include 'function.php';

if(kullanici_girisimi())
{
	header('location:alinan_kitapdetay.php');
}

include 'header.php';



?>


<div class="p-5 mb-4 bg- "style="background-color: #D67734;">

<div class="container-fluid py-5">

	<h1 class="display-5 fw-bold">Kütüphane Yönetim Sistemi</h1>

	<p class="fs-4">Bu basit kütüphane yönetim sistemi ödev amaçlı yapılmıştır eksikleri olabilir.Temel bir kütüphanede olabilecek temel işlemlerin yapılması hedeflenmiştir.İşlemler Admin tarafından yapılacaktır.
	Kullanıcılar ise daha çok kendi üzerine yapılan işlemleri görebileceklerdir.Temelde içerik bundan ibarettir.
	</p>
</div>

</div>

<div class="row align-items-md-stretch">

<div class="col-md-6">

	<div class="h-100 p-5 text-white bg-dark rounded-3">

		<h2>Admin Giriş</h2>
		<p></p>
		<a href="admingiris.php" class="btn btn-outline-danger btn-lg">Admin Giriş</a>

	</div>

</div>

<div class="col-md-6">

	<div class="h-100 p-5 text-white bg-dark rounded-3">

		<h2>Kullanıcı Giriş</h2>

		<p></p>

		<a href="kullanici_giris.php" class="btn btn-outline-success btn-lg">Kullanıcı Giriş</a>

		<a href="kullanici_kayitolma.php" class="btn btn-outline-primary btn-lg">Kullanıcı Kayıt</a>

	</div>

</div>

</div>

<?php

include 'footer.php';

?>
