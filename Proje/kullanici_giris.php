
<?php

include 'baglanti.php';

include 'function.php';

if(kullanici_girisimi())
{
	header('location:alinan-kitapdetay.php');
}

$message = '';

if(isset($_POST["giris_butonu"]))
{
	$formdata = array();

	if(empty($_POST["kullanici_email_adresi"]))
	{
		$message .= '<li>E-Posta Adresi Gereklidir</li>';
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

	if($message == '')
	{
		$data = array(
			':kullanici_email_adresi'		=>	$formdata['kullanici_email_adresi']
		);

		$query = "
		SELECT * FROM kullanici
        WHERE kullanici_email_adresi = :kullanici_email_adresi
		";

		$statement = $connect->prepare($query);

		$statement->execute($data);

		if($statement->rowCount() > 0)
		{
			foreach($statement->fetchAll() as $row)
			{
				if($row['kullanici_durumu'] == 'Enable')
				{
					if($row['kullanici_sifre'] == $formdata['kullanici_sifre'])
					{
						$_SESSION['kullanici_id'] = $row['kullanici_unique_id'];
						header('location:alinan_kitapdetay.php');
					}
					else
					{
						$message = '<li>Şifre Yanlış</li>';
					}
				}
				else
				{
					$message = '<li>Hesabınız Engellendi</li>';	
				}
			}
		}
		else
		{
			$message = '<li>Yanlış E-Posta Adresi</li>';
		}
	}
}

include 'header.php';

?>

<div class="d-flex align-items-center justify-content-center" style="height:700px;">
	<div class="col-md-6">
		<?php 

		if($message != '')
		{
			echo '<div class="alert alert-danger"><ul>'.$message.'</ul></div>';
		}

		?>

	<div class="row justify-content-center">
				<div class="col-md-6 text-center mb-5">
					<h2 class="heading-section">Kütüphane Sistemi Kullanıcı Girişi</h2>
				</div>
			</div>
		<div class="card">
			<div class="card-body">
				<form method="POST">
					<div class="mb-3">
						<label class="form-label">E-Posta Adresi</label>
						<input type="text" name="kullanici_email_adresi" id="kullanici_email_adresi" class="form-control" />
					</div>
					<div class="mb-3">
						<label class="form-label">Şifre</label>
						<input type="password" name="kullanici_sifre" id="kullanici_sifre" class="form-control" />
					</div>
					<div class="d-flex align-items-center justify-content-between mt-4 mb-0">
						<input type="submit" name="giris_butonu" class="btn btn-primary" value="Giriş" />
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<?php 

include 'footer.php';

?>