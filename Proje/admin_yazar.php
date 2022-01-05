<?php

include 'baglanti.php';

include 'function.php';

if(!admin_girisimi())
{
	header('location:admingiris.php');
}

$message = '';

$error = '';

if(isset($_POST["yazar_ekle"]))
{
	$formdata = array();

	if(empty($_POST["yazar_adi"]))
	{
		$error .= '<li>Yazar Adı Gereklidir</li>';
	}
	else
	{
		$formdata['yazar_adi'] = trim($_POST["yazar_adi"]);
	}

	if($error == '')
	{
		$query = "
		SELECT * FROM yazar 
        WHERE yazar_adi = '".$formdata['yazar_adi']."'
		";

		$statement = $connect->prepare($query);

		$statement->execute();

		if($statement->rowCount() > 0)
		{
			$error = '<li>Yazar Adı Zaten Bulunuyor</li>';
		}
		else
		{
			$data = array(
				':yazar_adi'				=>	$formdata['yazar_adi'],
				':yazar_durumu'				=>	'Enable',
				':yazar_olusturma_tarihi'	=>	get_date_time($connect)
			);

			$query = "
			INSERT INTO yazar 
            (yazar_adi, yazar_durumu, yazar_olusturma_tarihi) 
            VALUES (:yazar_adi, :yazar_durumu , :yazar_olusturma_tarihi)
			";

			$statement = $connect->prepare($query);

			$statement->execute($data);

			header('location:admin_yazar.php?msg=add');
		}
	}
}

if(isset($_POST["yazar_duzenle"]))
{
	$formdata = array();

	if(empty($_POST["yazar_adi"]))
	{
		$error .= '<li>Yazar Adı Gereklidir</li>';
	}
	else
	{
		$formdata['yazar_adi'] = trim($_POST['yazar_adi']);
	}

	if($error == '')
	{
		$yazar_id  = verileri_donustur($_POST['yazar_id'], 'decrypt');

		$query = "
		SELECT * FROM yazar 
        WHERE yazar_adi = '".$formdata['yazar_adi']."' 
        AND yazar_id != '".$yazar_id ."'
		";

		$statement = $connect->prepare($query);

		$statement->execute();

		if($statement->rowCount() > 0)
		{
			$error = '<li>Yazar Adı Zaten Bulunuyor</li>';
		}
		else
		{
			$data = array(
				':yazar_adi'				=>	$formdata['yazar_adi'],
				':yazar_guncelleme_tarihi'	=>	get_date_time($connect),
				':yazar_id'					=>	$yazar_id 
			);	

			$query = "
			UPDATE yazar
            SET yazar_adi = :yazar_adi, 
            yazar_guncelleme_tarihi = :yazar_guncelleme_tarihi  
            WHERE yazar_id = :yazar_id
			";

			$statement = $connect->prepare($query);

			$statement->execute($data);

			header('location:admin_yazar.php?msg=edit');
		}
	}
}

if(isset($_GET["action"], $_GET["code"], $_GET["durum"]) && $_GET["action"] == 'delete')
{
	$yazar_id  = $_GET["code"];

	$durum = $_GET["durum"];

	$data = array(
		':yazar_durumu'					=>	$durum,
		':yazar_guncelleme_tarihi'		=>	get_date_time($connect),
		':yazar_id '					=>	$yazar_id 
	);

	$query = "
	 UPDATE yazar 
    SET yazar_durumu = :yazar_durumu, 
    yazar_guncelleme_tarihi = :yazar_guncelleme_tarihi 
    WHERE yazar_id  = :yazar_id 
	";

	$statement = $connect->prepare($query);

	$statement->execute($data);

	header('location:admin_yazar.php?msg='.strtolower($durum).'');
}


$query = "
	SELECT * FROM yazar
    ORDER BY yazar_adi ASC
";

$statement = $connect->prepare($query);

$statement->execute();

include 'header.php';

?>

<div class="container-fluid py-4" style="min-height: 700px;">
	<h1>Yazar Yönetimi</h1>
	<?php 

	if(isset($_GET["action"]))
	{
		if($_GET["action"] == "add")
		{
	?>

	<ol class="breadcrumb mt-4 mb-4 bg-light p-2 border">
		<li class="breadcrumb-item"><a href="admin_index.php">Panel</a></li>
        <li class="breadcrumb-item"><a href="admin_yazar.php">Yazar Yönetimi</a></li>
        <li class="breadcrumb-item active">Yazar Ekle</li>
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
    				<i class="fas fa-user-plus"></i> Yeni Yazar Ekle
                </div>
                <div class="card-body">
                	<form method="post">
                		<div class="mb-3">
                			<label class="form-label">Yazar Adı</label>
                			<input type="text" name="yazar_adi" id="yazar_adi" class="form-control" />
                		</div>
                		<div class="mt-4 mb-0">
                			<input type="submit" name="yazar_ekle" class="btn btn-success" value="Ekle" />
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
			$yazar_id  = verileri_donustur($_GET["code"], 'decrypt');

			if($yazar_id  > 0)
			{
				$query = "
				SELECT * FROM yazar 
                WHERE yazar_id  = '$yazar_id '
				";

				$author_result = $connect->query($query);

				foreach($author_result as $author_row)
				{
	?>

	<ol class="breadcrumb mt-4 mb-4 bg-light p-2 border">
		<li class="breadcrumb-item"><a href="admin_index.php">Panel</a></li>
        <li class="breadcrumb-item"><a href="admin_yazar.php">Yazar Yönetimi</a></li>
        <li class="breadcrumb-item active">Yazar Düzenle</li>
    </ol>

    <div class="row">
    	<div class="col-md-6">
    		<div class="card mb-4">
    			<div class="card-header">
    				<i class="fas fa-user-edit"></i> Yazar Detaylarını Düzenle
    			</div>
    			<div class="card-body">
    				<form method="post">
    					<div class="mb-3">
    						<label class="form-label">Yazar Adı</label>
    						<input type="text" name="yazar_adi" id="yazar_adi" class="form-control" value="<?php echo $author_row['yazar_adi']; ?>" />
    					</div>
    					<div class="mt-4 mb-0">
    						<input type="hidden" name="yazar_id " value="<?php echo $_GET['code']; ?>" />
    						<input type="submit" name="yazar_duzenle" class="btn btn-primary" value="Düzenle" />
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
		<li class="breadcrumb-item active">Yazar Yönetimi</li>
	</ol>
	<?php 

	if(isset($_GET["msg"]))
	{
		if($_GET["msg"] == 'add')
		{
			echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Yeni Yazar Eklendi<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
		}
		if($_GET['msg'] == 'edit')
		{
			echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Yazar Verileri Düzenlendi<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
		}
		if($_GET["msg"] == 'disable')
		{
			echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Yazar Durumu Devre Dışı Haline Getirildi<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
		}

		if($_GET["msg"] == 'enable')
		{
			echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Yazar Durumu Etkin Hale Getirildi<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
		}
	}

	?>
	<div class="card mb-4">
		<div class="card-header">
			<div class="row">
				<div class="col col-md-6">
					<i class="fas fa-table me-1"></i> Yazar Yönetimi
				</div>
				<div class="col col-md-6" align="right">
					<a href="admin_yazar.php?action=add" class="btn btn-success btn-sm">Ekle</a>
				</div>
			</div>
		</div>
		<div class="card-body">
			<table id="datatablesSimple">
				<thead>
					<tr>
						<th>Yazar Adı</th>
						<th>Durum</th>
						<th>Oluşturulduğu Tarih</th>
						<th>Güncellendiği Tarih</th>
						<th>Eylem</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>Yazar Adı</th>
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
						$yazar_durumu = '';
						if($row['yazar_durumu'] == 'Enable')
						{
							$yazar_durumu = '<div class="badge bg-success">Etkin</div>';
						}
						else
						{
							$yazar_durumu = '<div class="badge bg-danger">Devre Dışı</div>';
						}
						
						echo '
						<tr>
							<td>'.$row["yazar_adi"].'</td>
							<td>'.$yazar_durumu.'</td>
							<td>'.$row["yazar_olusturma_tarihi"].'</td>
							<td>'.$row["yazar_guncelleme_tarihi"].'</td>
							<td>
								<a href="admin_yazar.php?action=edit&code='.verileri_donustur($row["yazar_id"]).'" class="btn btn-sm btn-primary">Düzenle</a>
								<button type="button" name="silme_butonu" class="btn btn-danger btn-sm" onclick="delete_data(`'.$row["yazar_id"].'`, `'.$row["yazar_durumu"].'`)">Durumu Değiştir</button>
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
		</div>
	</div>

	<script>

		function delete_data(code, durum)
		{
			var yeni_durum = 'Enable';

			if(durum == 'Enable')
			{
				yeni_durum = 'Disable';
			}

			if(confirm("Bu yazarı "+yeni_durum+" etmek istediğinize emin misiniz?"))
			{
				window.location.href = "admin_yazar.php?action=delete&code="+code+"&durum="+yeni_durum+"";
			}
		}

	</script>

	<?php 

	}

	?>
</div>

<?php 

include 'footer.php';

?>
