<?php

include 'baglanti.php';

include 'function.php';


$message = '';

if(isset($_POST["giris_buton"]))
{

	$formdata = array();

	if(empty($_POST["admin_email"]))
	{
		$message .= '<li>E-Posta Adresi Gereklidir</li>';
	}
	else
	{
		if(!filter_var($_POST["admin_email"], FILTER_VALIDATE_EMAIL))
		{
			$message .= '<li>Geçersiz E-Posta Adresi</li>';
		}
		else
		{
			$formdata['admin_email'] = $_POST['admin_email'];
		}
	}

	if(empty($_POST['admin_sifre']))
	{
		$message .= '<li>Şifre Gereklidir</li>';
	}
	else
	{
		$formdata['admin_sifre'] = $_POST['admin_sifre'];
	}

	if($message == '')
	{
		$data = array(
			':admin_email'		=>	$formdata['admin_email']
		);

		$query = "
		SELECT * FROM admin 
        WHERE admin_email = :admin_email
		";

		$statement = $connect->prepare($query);

		$statement->execute($data);

		if($statement->rowCount() > 0)
		{
			foreach($statement->fetchAll() as $row)
			{
				if($row['admin_sifre'] == $formdata['admin_sifre'])
				{
					$_SESSION['admin_id'] = $row['admin_id'];

					header('location:admin_index.php');
				}
				else
				{
					$message = '<li>Şifre Yanlış</li>';
				}
			}
		}	
		else
		{
			$message = '<li>E-Posta Adresi Yanlış</li>';
		}
	}

}

include 'header.php';

?>

<div class="d-flex align-items-center justify-content-center" style="min-height:700px;">

	<div class="col-md-6">

		<?php 
		if($message != '')
		{
			echo '<div class="alert alert-danger"><ul>'.$message.'</ul></div>';
		}
		?>
		<div class="row justify-content-center">
				<div class="col-md-6 text-center mb-5">
					<h2 class="heading-section">Kütüphane Sistemi Admin Girişi</h2>
				</div>
			</div>

		<div class="card">

			<div class="card-body">

				<form method="POST">

					<div class="mb-3">
						<label class="form-label">Email Adresi</label>

						<input type="text" name="admin_email" id="admin_email" class="form-control" />
				
					</div>

					<div class="mb-3">
						<label class="form-label">Şifre</label>

						<input type="password" name="admin_sifre" id="admin_sifre" class="form-control" />

					</div>

					<div class="d-flex align-items-center justify-content-between mt-4 mb-0">

						<input type="submit" name="giris_buton" class="btn btn-primary" value="Giriş Yap" />

					</div>

				</form>

			</div>

		</div>

	</div>

</div>

<?php

include 'footer.php';

?>
