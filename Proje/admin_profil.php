<?php



include 'baglanti.php';

include 'function.php';

if(!admin_girisimi())
{
	header('location:admingiris.php');
}

$message = '';

$error = '';

if(isset($_POST['admin_duzenle']))
{

	$formdata = array();

	if(empty($_POST['admin_email']))
	{
		$error .= '<li>E-Posta Adresi Gereklidir</li>';
	}
	else
	{
		if(!filter_var($_POST["admin_email"], FILTER_VALIDATE_EMAIL))
		{
			$error .= '<li>Geçersiz E-Posta Adresi</li>';
		}
		else
		{
			$formdata['admin_email'] = $_POST['admin_email'];
		}
	}

	if(empty($_POST['admin_sifre']))
	{
		$error .= '<li>Sifre Gereklidir</li>';
	}
	else
	{
		$formdata['admin_sifre'] = $_POST['admin_sifre'];
	}

	if($error == '')
	{
		$admin_id = $_SESSION['admin_id'];

		$data = array(
			':admin_email'		=>	$formdata['admin_email'],
			':admin_sifre'		=>	$formdata['admin_sifre'],
			':admin_id'			=>	$admin_id
		);

		$query = "
		UPDATE admin 
            SET admin_email = :admin_email,
            admin_sifre = :admin_sifre 
            WHERE admin_id = :admin_id
		";

		$statement = $connect->prepare($query);

		$statement->execute($data);

		$message = 'Kullanıcı Verileri Düzenlendi';
	}
}

$query = "
	SELECT * FROM admin
    WHERE admin_id = '".$_SESSION["admin_id"]."'
";

$result = $connect->query($query);


include 'header.php';

?>

<div class="container-fluid px-4">
	<h1 class="mt-4">Profil</h1>
	<ol class="breadcrumb mt-4 mb-4 bg-light p-2 border">
		<li class="breadcrumb-item"><a href="admin_index.php">Panel</a></li>
		<li class="breadcrumb-item active">Profil</a></li>
	</ol>
	<div class="row">
		<div class="col-md-6">
			<?php 

			if($error != '')
			{
				echo '<div class="alert alert-danger alert-dismissible fade show" role="alert"><ul class="list-unstyled">'.$error.'</ul> <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
			}

			if($message != '')
			{
				echo '<div class="alert alert-success alert-dismissible fade show" role="alert">'.$message.' <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
			}

			?>
			<div class="card mb-4">
				<div class="card-header">
					<i class="fas fa-user-edit"></i> Profil Ayrıntılarını Düzenle
				</div>
				<div class="card-body">

				<?php 

				foreach($result as $row)
				{
				?>

					<form method="post">
						<div class="mb-3">
							<label class="form-label">E-Posta Adresi</label>
							<input type="text" name="admin_email" id="admin_email" class="form-control" value="<?php echo $row['admin_email']; ?>" />
						</div>
						<div class="mb-3">
							<label class="form-label">Şifre</label>
							<input type="password" name="admin_sifre" id="admin_sifre" class="form-control" value="<?php echo $row['admin_sifre']; ?>" />
						</div>
						<div class="mt-4 mb-0">
							<input type="submit" name="admin_duzenle" class="btn btn-primary" value="Düzenle" />
						</div>
					</form>

				<?php 
				}

				?>

				</div>
			</div>

		</div>
	</div>
</div>

<?php 

include 'footer.php';

?>
