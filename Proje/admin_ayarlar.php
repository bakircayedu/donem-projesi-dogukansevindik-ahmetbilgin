<?php

include 'baglanti.php';

include 'function.php';

if(!admin_girisimi())
{
	header('location:admingiris.php');
}

$message = '';

if(isset($_POST['ayarlari_duzenle']))
{
	$data = array(
		':kutuphane_adi'						=>	$_POST['kutuphane_adi'],
		':kutuphane_adresi'						=>	$_POST['kutuphane_adresi'],
		':kutuphane_iletisim_numarasi'			=>	$_POST['kutuphane_iletisim_numarasi'],
		':kutuphane_email_adresi'				=>	$_POST['kutuphane_email_adresi'],
		':kitap_iade_gun_limiti'				=>	$_POST['kitap_iade_gun_limiti'],
		':kitap_gec_donus_gunluk_ceza'			=>	$_POST['kitap_gec_donus_gunluk_ceza'],
		':kutuphane_para_birimi'				=>	$_POST['kutuphane_para_birimi'],
		':kutuphane_zaman_dilimi'				=>	$_POST['kutuphane_zaman_dilimi'],
		':kisi_basina_verilebilecek_kitap'		=>	$_POST['kisi_basina_verilebilecek_kitap']
	);

	$query = "
	UPDATE ayarlar 
        SET kutuphane_adi = :kutuphane_adi,
        kutuphane_adresi = :kutuphane_adresi, 
        kutuphane_iletisim_numarasi = :kutuphane_iletisim_numarasi, 
        kutuphane_email_adresi = :kutuphane_email_adresi, 
        kitap_iade_gun_limiti = :kitap_iade_gun_limiti, 
        kitap_gec_donus_gunluk_ceza = :kitap_gec_donus_gunluk_ceza, 
		kutuphane_para_birimi = :kutuphane_para_birimi, 
        kutuphane_zaman_dilimi = :kutuphane_zaman_dilimi, 
		kisi_basina_verilebilecek_kitap	 = :kisi_basina_verilebilecek_kitap	
	";

	$statement = $connect->prepare($query);

	$statement->execute($data);

	$message = '
	<div class="alert alert-success alert-dismissible fade show" role="alert">Veri Düzenlendi<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
	';
}

$query = "
SELECT * FROM ayarlar 
LIMIT 1
";

$result = $connect->query($query);

include 'header.php';

?>

<div class="container-fluid px-4">
	<h1 class="mt-4">Ayarlar</h1>

	<ol class="breadcrumb mt-4 mb-4 bg-light p-2 border">
		<li class="breadcrumb-item"><a href="admin_index.php">Panel</a></li>
		<li class="breadcrumb-item active">Ayarlar</a></li>
	</ol>
	<?php 

	if($message != '')	
	{
		echo $message;
	}

	?>
	<div class="card mb-4">
		<div class="card-header">
			<i class="fas fa-user-edit"></i> Kütüphane Ayarları
		</div>
		<div class="card-body">

			<form method="post">
				<?php 
				foreach($result as $row)
				{
				?>
				<div class="row">
					<div class="col-md-12">
						<div class="mb-3">
							<label class="form-label">Kütüphane Adı</label>
							<input type="text" name="kutuphane_adi" id="kutuphane_adi" class="form-control" value="<?php echo $row['kutuphane_adi']; ?>" />
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="mb-3">
							<label class="form-label">Adres</label>
							<textarea name="kutuphane_adresi" id="kutuphane_adresi" class="form-control"><?php echo $row["kutuphane_adresi"]; ?></textarea>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="mb-3">
							<label class="form-label">İletişim Numarası</label>
							<input type="text" name="kutuphane_iletisim_numarasi" id="kutuphane_iletisim_numarasi" class="form-control" value="<?php echo $row['kutuphane_iletisim_numarasi']; ?>" />
						</div>
					</div>
					<div class="col-md-6">
						<div class="mb-3">
							<label class="form-label">Email Adresi</label>
							<input type="text" name="kutuphane_email_adresi" id="kutuphane_email_adresi" class="form-control" value="<?php echo $row['kutuphane_email_adresi']; ?>" />
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="mb-3">
							<label class="form-label">Kitap İadesi Gün Limiti</label>
							<input type="number" name="kitap_iade_gun_limiti" id="kitap_iade_gun_limiti" class="form-control" value="<?php echo $row['kitap_iade_gun_limiti']; ?>" />
						</div>
					</div>
					<div class="col-md-6">
						<div class="mb-3">
							<label class="form-label">Geç Dönen Kitap İçin Günlük Ceza</label>
							<input type="number" name="kitap_gec_donus_gunluk_ceza" id="kitap_gec_donus_gunluk_ceza" class="form-control" value="<?php echo $row['kitap_gec_donus_gunluk_ceza']; ?>" />
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="mb-3">
							<label class="form-label">Para Birimi</label>
							<select name="	kutuphane_para_birimi" id="	kutuphane_para_birimi" class="form-control">
								<?php echo Parabirimi_listesi(); ?>
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="mb-3">
							<label class="form-label">Zaman Dilimi</label>
							<select name="kutuphane_zaman_dilimi" id="kutuphane_zaman_dilimi" class="form-control">
								<?php echo Zamandilimi_listesi(); ?>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<label class="form-label">Kullanıcı Başına Kitap Sayısı Limiti</label>
						<input type="number" name="kisi_basina_verilebilecek_kitap" id="kisi_basina_verilebilecek_kitap" class="form-control" value="<?php echo $row['kisi_basina_verilebilecek_kitap']; ?>" />
					</div>
				</div>
				<div class="mt-4 mb-0">
					<input type="submit" name="ayarlari_duzenle" class="btn btn-primary" value="Kaydet" />
				</div>
				<script type="text/javascript">

				document.getElementById('kutuphane_para_birimi').value = "<?php echo $row['kutuphane_para_birimi']; ?>";

				document.getElementById('kutuphane_zaman_dilimi').value="<?php echo $row['kutuphane_zaman_dilimi']; ?>"; 

				</script>
				<?php 
				}
				?>
			</form>

		</div>
	</div>
</div>

<?php 

include 'footer.php';

?>
