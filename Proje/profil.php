<?php


include 'baglanti.php';

include 'function.php';

if(!kullanici_girisimi())
{
	header('location:kullanici_giris.php');
}

$message = '';

$success = '';

if(isset($_POST['kayit_butonu']))
{
	$formdata = array();

	if(empty($_POST['kullanici_email_adresi']))
	{
		$message .= '<li>Email Adresi Gereklidir</li>';
	}
	else
	{
		if(!filter_var($_POST["kullanici_email_adresi"], FILTER_VALIDATE_EMAIL))
		{
			$message .= '<li>Geçersiz E-Posta Adresi</li>';
		}
		else
		{
			$formdata['kullanici_email_adresi'] = trim($_POST['kullanici_email_adresi']);
		}
	}

	if(empty($_POST['kullanici_sifre']))
	{
		$message .= '<li>Şifre Gereklidir</li>';
	}
	else
	{
		$formdata['kullanici_sifre'] = trim($_POST['kullanici_sifre']);
	}

	if(empty($_POST['kullanici_adi']))
	{
		$message .= '<li>Kullanıcı Adı Gereklidir</li>';
	}
	else
	{
		$formdata['kullanici_adi'] = trim($_POST['kullanici_adi']);
	}

	if(empty($_POST['kullanici_adresi']))
	{
		$message .= '<li>Kullanıcı Adres Detayı Gereklidir</li>';
	}
	else
	{
		$formdata['kullanici_adresi'] = trim($_POST['kullanici_adresi']);
	}

	if(empty($_POST['kullanici_iletisim_no']))
	{
		$message .= '<li>İletişim No Gereklidir</li>';
	}
	else
	{
		$formdata['kullanici_iletisim_no'] = $_POST['kullanici_iletisim_no'];
	}

	$formdata['kullanici_profili'] = $_POST['hidden_kullanici_profili'];

	if(!empty($_FILES['kullanici_profili']['name']))
	{
		$img_name = $_FILES['kullanici_profili']['name'];
		$img_type = $_FILES['kullanici_profili']['type'];
		$tmp_name = $_FILES['kullanici_profili']['tmp_name'];
		$fileinfo = @getimagesize($tmp_name);
		$width = $fileinfo[0];
		$height = $fileinfo[1];
		$image_size = $_FILES['kullanici_profili']['size'];
		$img_explode = explode(".", $img_name);
		$img_ext = strtolower(end($img_explode));
		$extensions = ["jpeg", "png", "jpg"];
		if(in_array($img_ext, $extensions))
		{
			if($image_size <= 2000000)
			{
				if($width == 225 && $height == 225)
				{
					$new_img_name = time() . '-' . rand() . '.'  . $img_ext;

					if(move_uploaded_file($tmp_name, "upload/" . $new_img_name))
					{
						$formdata['kullanici_profili'] = $new_img_name;
					}
				}
				else
				{
					$message .= '<li>Resim boyutu 225 X 225 olmalıdır</li>';
				}
			}
			else
			{
				$message .= '<li>Resim boyutu 2 MB ı aşıyor</li>';
			}
		}
		else
		{
			$message .= '<li>Geçersiz Resim Dosyası</li>';
		}
	}

	if($message == '')
	{
		$data = array(
			':kullanici_adi'				=>	$formdata['kullanici_adi'],
			':kullanici_adresi'				=>	$formdata['kullanici_adresi'],
			':kullanici_iletisim_no'		=>	$formdata['kullanici_iletisim_no'],
			':kullanici_profili'			=>	$formdata['kullanici_profili'],
			':kullanici_email_adresi'		=>	$formdata['kullanici_email_adresi'],
			':kullanici_sifre'				=>	$formdata['kullanici_sifre'],
			':kullanici_guncellendi'		=>	get_date_time($connect),
			':kullanici_unique_id'			=>	$_SESSION['kullanici_unique_id']
		);

		$query = "
		UPDATE kullanici 
            SET kullanici_adi = :kullanici_adi, 
            kullanici_adresi = :kullanici_adresi, 
            kullanici_iletisim_no = :kullanici_iletisim_no, 
            kullanici_profili = :kullanici_profili, 
            kullanici_email_adresi = :kullanici_email_adresi, 
            kullanici_sifre = :kullanici_sifre, 
            kullanici_guncellendi = :kullanici_guncellendi 
            WHERE kullanici_unique_id = :kullanici_unique_id
		";

		$statement = $connect->prepare($query);

		$statement->execute($data);

		$success = 'Veri Başarıyla Değiştirildi';
	}
}


$query = "
	SELECT * FROM kullanici 
	WHERE kullanici_unique_id = '".$_SESSION['kullanici_id']."'
";

$result = $connect->query($query);

include 'header.php';

?>

<div class="d-flex align-items-center justify-content-center mt-5 mb-5" style="min-height:700px;">
	<div class="col-md-6">
		<?php 
		if($message != '')
		{
			echo '<div class="alert alert-danger"><ul>'.$message.'</ul></div>';
		}

		if($success != '')
		{
			echo '<div class="alert alert-success">'.$success.'</div>';
		}
		?>
		<div class="card">
			<div class="card-header">Profil</div>
			<div class="card-body">
			<?php 
			foreach($result as $row)
			{
			?>
				<form method="POST" enctype="multipart/form-data">
					<div class="mb-3">
						<label class="form-label">Email Adresi</label>
						<input type="text" name="kullanici_email_adresi" id="kullanici_email_adresi" class="form-control" value="<?php echo $row['kullanici_email_adresi']; ?>" />
					</div>
					<div class="mb-3">
						<label class="form-label">Şifre</label>
						<input type="password" name="kullanici_sifre" id="kullanici_sifre" class="form-control" value="<?php echo $row['kullanici_sifre']; ?>" />
					</div>
					<div class="mb-3">
						<label class="form-label">Kullanıcı Adı</label>
						<input type="text" name="kullanici_adi" id="kullanici_adi" class="form-control" value="<?php echo $row['kullanici_adi']; ?>" />
					</div>
					<div class="mb-3">
						<label class="form-label">Kullanıcı İletişim Numarası</label>
						<input type="text" name="kullanici_iletisim_no" id="kullanici_iletisim_no" class="form-control" value="<?php echo $row['kullanici_iletisim_no']; ?>" />
					</div>
					<div class="mb-3">
						<label class="form-label">Kullanıcı Adresi</label>
						<textarea name="kullanici_adresi" id="kullanici_adresi" class="form-control"><?php echo $row['kullanici_adresi']; ?></textarea>
					</div>
					<div class="mb-3">
						<label class="form-label">Kullanıcı Fotoğrafı</label><br />
						<input type="file" name="kullanici_profili" id="kullanici_profili" />
						<br />
						<span class="text-muted">Yalnızca .jpg ve .png formatında fotoğraflara izin verilir.Resim boyutu 225 x 225 olmalıdır</span>
						<br />
						<input type="hidden" name="hidden_kullanici_profili" value="<?php echo $row['kullanici_profili']; ?>" />
						<img src="upload/<?php echo $row['kullanici_profili']; ?>" width="100" class="img-thumbnail" />
					</div>
					<div class="text-center mt-4 mb-2">
						<input type="submit" name="kayit_butonu" class="btn btn-primary" value="Kaydet" />
					</div>
				</form>

			<?php
			}
			?>
			</div>
		</div>
	</div>
</div>

<?php 

include 'footer.php';

?>