<?php


include 'baglanti.php';

include 'function.php';

if(!admin_girisimi())
{
	header('location:admingiris.php');
}

$message = '';

$error = '';

if(isset($_POST['kategori_ekle']))
{
	$formdata = array();

	if(empty($_POST['kategori_adi']))
	{
		$error .= '<li>Kategori Adı Gereklidir</li>';
	}
	else
	{
		$formdata['kategori_adi'] = trim($_POST['kategori_adi']);
	}

	if($error == '')
	{
		$query = "
		SELECT * FROM kategori 
        WHERE kategori_adi = '".$formdata['kategori_adi']."'
		";

		$statement = $connect->prepare($query);

		$statement->execute();

		if($statement->rowCount() > 0)
		{
			$error = '<li>Kategori Adı Zaten Var</li>';
		}
		else
		{
			$data = array(
				':kategori_adi'			=>	$formdata['kategori_adi'],
				':kategori_durum'			=>	'Enable',
				':kategori_olusturuldu'		=>	get_date_time($connect)
			);

			$query = "
			INSERT INTO kategori 
            (kategori_adi, kategori_durum,kategori_olusturuldu) 
            VALUES (:kategori_adi, :kategori_durum, :kategori_olusturuldu)
			";

			$statement = $connect->prepare($query);

			$statement->execute($data);

			header('location:admin_kategori.php?msg=add');
		}
	}
}

if(isset($_POST["kategori_duzenle"]))
{
	$formdata = array();

	if(empty($_POST["kategori_adi"]))
	{
		$error .= '<li>Kategori Adı Gereklidir</li>';
	}
	else
	{
		$formdata['kategori_adi'] = $_POST['kategori_adi'];
	}

	if($error == '')
	{
		$category_id = verileri_donustur($_POST['kategori_id'], 'decrypt');

		$query = "
		SELECT * FROM kategori
        WHERE kategori_adi = '".$formdata['kategori_adi']."' 
        AND kategori_id != '".$category_id."'
		";

		$statement = $connect->prepare($query);

		$statement->execute();

		if($statement->rowCount() > 0)
		{
			$error = '<li>Kategori Adı Zaten Var</li>';
		}
		else
		{
			$data = array(
				':kategori_adi'		=>	$formdata['kategori_adi'],
				':kategori_guncellendi'	=>	get_date_time($connect),
				':kategori_id'			=>	$category_id
			);

			$query = "
			UPDATE kategori 
            SET kategori_adi = :kategori_adi, 
            kategori_guncellendi = :kategori_guncellendi  
            WHERE kategori_id = :kategori_id
			";

			$statement = $connect->prepare($query);

			$statement->execute($data);

			header('location:admin_kategori.php?msg=edit');
		}
	}
}

if(isset($_GET["action"], $_GET["code"], $_GET["durum"]) && $_GET["action"] == 'delete')
{
	$category_id = $_GET["code"];
	$durum = $_GET["durum"];
	$data = array(
		':kategori_durum'			=>	$durum,
		':kategori_guncellendi'		=>	get_date_time($connect),
		':kategori_id'				=>	$category_id
	);
	$query = "
	UPDATE kategori 
    SET kategori_durum = :kategori_durum, 
    kategori_guncellendi = :kategori_guncellendi 
    WHERE kategori_id = :kategori_id
	";

	$statement = $connect->prepare($query);

	$statement->execute($data);

	header('location:admin_kategori.php?msg='.strtolower($durum).'');
}


$query = "
SELECT * FROM kategori 
    ORDER BY kategori_adi ASC
";

$statement = $connect->prepare($query);

$statement->execute();

include 'header.php';

?>

<div class="container-fluid py-4" style="min-height: 700px;">
	<h1>Kategori Yönetimi</h1>
	<?php 

	if(isset($_GET['action']))
	{
		if($_GET['action'] == 'add')
		{
	?>

	<ol class="breadcrumb mt-4 mb-4 bg-light p-2 border">
		<li class="breadcrumb-item"><a href="admin_index.php">Panel</a></li>
		<li class="breadcrumb-item"><a href="admin_kategori.php">Kategori Yönetimi</a></li>
		<li class="breadcrumb-item active">Kategori Ekle</li>
	</ol>
	<div class="row">
		<div class="col-md-6">
			<?php 

			if($error != '')
			{
				echo '<div class="alert alert-danger alert-dismissible fade show" role="alert"><ul class="list-unstyled">'.$error.'</ul> <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
			}

			?>
			<div class="card mb-4">
				<div class="card-header">
					<i class="fas fa-user-plus"></i> Yeni Kategori Ekle
                </div>
                <div class="card-body">

                	<form method="POST">

                		<div class="mb-3">
                			<label class="form-label">Kategori Adı</label>
                			<input type="text" name="kategori_adi" id="kategori_adi" class="form-control" />
                		</div>

                		<div class="mt-4 mb-0">
                			<input type="submit" name="kategori_ekle" value="Ekle" class="btn btn-success" />
                		</div>

                	</form>

                </div>
            </div>
		</div>
	</div>


	<?php 
		}
		else if($_GET["action"] == 'edit')
		{
			$category_id = verileri_donustur($_GET["code"],'decrypt');

			if($category_id > 0)
			{
				$query = "
				SELECT * FROM kategori
                WHERE kategori_id = '$category_id'
				";

				$category_result = $connect->query($query);

				foreach($category_result as $category_row)
				{
				?>
	
	<ol class="breadcrumb mt-4 mb-4 bg-light p-2 border">
		<li class="breadcrumb-item"><a href="admin_index.php">Panel</a></li>
		<li class="breadcrumb-item"><a href="admin_kategori.php">Kategori Yönetimi</a></li>
		<li class="breadcrumb-item active">Kategoriyi Düzenle</li>
	</ol>
	<div class="row">
		<div class="col-md-6">
			<div class="card mb-4">
				<div class="card-header">
					<i class="fas fa-user-edit"></i> Kategori Detaylarını Düzenle
				</div>
				<div class="card-body">

					<form method="post">

						<div class="mb-3">
							<label class="form-label">Kategori Adı</label>
							<input type="text" name="kategori_adi" id="kategori_adi" class="form-control" value="<?php echo $category_row['kategori_adi']; ?>" />
						</div>

						<div class="mt-4 mb-0">
							<input type="hidden" name="kategori_id" value="<?php echo $_GET['code']; ?>" />
							<input type="submit" name="kategori_duzenle" class="btn btn-primary" value="Düzenle" />
						</div>

					</form>

				</div>
			</div>

		</div>
	</div>

				<?php 
				}
			}
		}
	}
	else
	{	

	?>
	<ol class="breadcrumb mt-4 mb-4 bg-light p-2 border">
		<li class="breadcrumb-item"><a href="admin_index.php">Panel</a></li>
		<li class="breadcrumb-item active">Kategori Yönetimi</li>
	</ol>

	<?php 

	if(isset($_GET['msg']))
	{
		if($_GET['msg'] == 'add')
		{
			echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Yeni Kategori Eklendi<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
		}

		if($_GET["msg"] == 'edit')
		{
			echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Kategori Verisi Düzenlendi <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
		}
		if($_GET["msg"] == 'disable')
		{
			echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Kategori Durumu Devre Dışı Bırakıldı<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
		}

		if($_GET['msg'] == 'enable')
		{
			echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Kategori Durumu Etkin Hale Getirildi<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
		}
	}	

	?>

	<div class="card mb-4">
		<div class="card-header">
			<div class="row">
				<div class="col col-md-6">
					<i class="fas fa-table me-1"></i> Kategori Yönetimi
				</div>
				<div class="col col-md-6" align="right">
					<a href="admin_kategori.php?action=add" class="btn btn-success btn-sm">Ekle</a>
				</div>
			</div>
		</div>
		<div class="card-body">

			<table id="datatablesSimple">
				<thead>
					<tr>
						<th>Kategori Adı</th>
						<th>Durum</th>
						<th>Oluşturulduğu Tarih</th>
						<th>Güncellendiği Tarih</th>
						<th>Eylem</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>Kategori Adı</th>
						<th>Durum</th>
						<th>Oluşturulduğu Tarih</th>
						<th>Güncellendiği Tarih</th>
						<th>Eylem</th>
					</tr>
				</tfoot>
				<tbody>
				<?php 

				if($statement->rowCount() > 0)
				{
					foreach($statement->fetchAll() as $row)
					{
						$kategori_durum = '';
						if($row['kategori_durum'] == 'Enable')
						{
							$kategori_durum = '<div class="badge bg-success">Etkin</div>';
						}
						else
						{
							$kategori_durum = '<div class="badge bg-danger">Devre Dışı</div>';
						}

						echo '
						<tr>
							<td>'.$row["kategori_adi"].'</td>
							<td>'.$kategori_durum.'</td>
							<td>'.$row["kategori_olusturuldu"].'</td>
							<td>'.$row["kategori_guncellendi"].'</td>
							<td>
								<a href="admin_kategori.php?action=edit&code='.verileri_donustur($row["kategori_id"]).'" class="btn btn-sm btn-primary">Düzenle</a>
								<button name="delete_button" class="btn btn-danger btn-sm" onclick="delete_data(`'.$row["kategori_id"].'`, `'.$row["kategori_durum"].'`)">Durumu Degistir</button>
							</td>
						</tr>
						';
					}
				}
				else
				{
					echo '
					<tr>
						<td colspan="4" class="text-center">Veri Bulunamadı</td>
					</tr>
					';
				}

				?>
				</tbody>
			</table>

			<script>

				function delete_data(code, durum)
				{
					var yeni_durum = 'Enable';

					if(durum == 'Enable')
					{
						yeni_durum = 'Disable';
					}

					if(confirm("Bu kategoriyi "+yeni_durum+" etmek istediğinizden emin misiniz?"))
					{
						window.location.href="admin_kategori.php?action=delete&code="+code+"&durum="+yeni_durum+"";
					}
				}

			</script>

		</div>
	</div>
	<?php 
	}
	?>

</div>

<?php 

include 'footer.php';

?>
