<?php



include 'baglanti.php';

include 'function.php';

if(!admin_girisimi())
{
	header('location:admingiris.php');
}

$message = '';

$error = '';

if(isset($_POST["konumrafi_ekle"]))
{
	$formdata = array();

	if(empty($_POST["konum_rafi_adi"]))
	{
		$error .= '<li>Konum Rafı Adı Gereklidir</li>';
	}
	else
	{
		$formdata['konum_rafi_adi'] = trim($_POST["konum_rafi_adi"]);
	}

	if($error == '')
	{
		$query = "
		SELECT * FROM konum_rafi
        WHERE konum_rafi_adi = '".$formdata['konum_rafi_adi']."'
		";

		$statement = $connect->prepare($query);

		$statement->execute();

		if($statement->rowCount() > 0)
		{
			$error = '<li>Konum Rafı Zaten Bulunmakta</li>';
		}
		else
		{
			$data = array(
				':konum_rafi_adi'		    =>	$formdata['konum_rafi_adi'],
				':konum_rafi_durum'		    =>	'Enable',
				':konum_rafi_olusturuldu'	=>	get_date_time($connect)
			);

			$query = "
			INSERT INTO konum_rafi 
            (konum_rafi_adi, konum_rafi_durum, konum_rafi_olusturuldu) 
            VALUES (:konum_rafi_adi, :konum_rafi_durum, :konum_rafi_olusturuldu)
			";

			$statement = $connect->prepare($query);

			$statement->execute($data);

			header('location:admin_konumrafi.php?msg=add');
		}
	}
}

if(isset($_POST["konum_rafi_duzenle"]))
{
	$formdata = array();

	if(empty($_POST["konum_rafi_adi"]))
	{
		$error .= '<li>Konum Rafı Adı</li>';
	}
	else
	{
		$formdata['konum_rafi_adi'] = trim($_POST["konum_rafi_adi"]);
	}

	if($error == '')
	{
		$konum_rafi_id = verileri_donustur($_POST["konum_rafi_id"], 'decrypt');

		$query = "
		SELECT * FROM konum_rafi
	        WHERE konum_rafi_adi = '".$formdata['konum_rafi_adi']."' 
	        AND konum_rafi_id  != '".$konum_rafi_id."'
		";

		$statement = $connect->prepare($query);

		$statement->execute();

		if($statement->rowCount() > 0)
		{
			$error = '<li>Konum Rafı Adı Zaten Bulunmakta</li>';
		}
		else
		{
			$data = array(
				':konum_rafi_adi'		    =>	$formdata['konum_rafi_adi'],
				':konum_rafi_guncellendi'	=>	get_date_time($connect),
				':konum_rafi_id'			=>	$konum_rafi_id
			);

			$query = "
			UPDATE konum_rafi 
	            SET konum_rafi_adi = :konum_rafi_adi, 
	            konum_rafi_guncellendi = :konum_rafi_guncellendi  
	            WHERE konum_rafi_id  = :konum_rafi_id 
			";

			$statement = $connect->prepare($query);

			$statement->execute($data);

			header('location:admin_konumrafi.php?msg=edit');
		}
	}
}

if(isset($_GET["action"], $_GET["code"], $_GET["durum"]) && $_GET["action"]=='delete')
{
	$konum_rafi_id = $_GET["code"];

	$durum = $_GET["durum"];

	$data = array(
		':konum_rafi_durum'			    =>	$durum,
		':konum_rafi_guncellendi'		=>	get_date_time($connect),
		':konum_rafi_id'				=>	$konum_rafi_id
	);
	$query = "
	UPDATE konum_rafi 
    SET konum_rafi_durum = :konum_rafi_durum, 
    konum_rafi_guncellendi = :konum_rafi_guncellendi 
    WHERE konum_rafi_id  = :konum_rafi_id 
	";

	$statement = $connect->prepare($query);

	$statement->execute($data);

	header('location:admin_konumrafi.php?msg='.strtolower($durum).'');

}


$query = "
	SELECT * FROM konum_rafi 
    ORDER BY konum_rafi_adi ASC
";

$statement = $connect->prepare($query);

$statement->execute();

include 'header.php';

?>

<div class="container-fluid py-4" style="min-height: 700px;">
	<h1>Konum Rafı Yönetimi</h1>
	<?php 

	if(isset($_GET["action"]))
	{
		if($_GET["action"] == 'add')
		{
		?>
	
	<ol class="breadcrumb mt-4 mb-4 bg-light p-2 border">
		<li class="breadcrumb-item"><a href="admin_index.php">Panel</a></li>
		<li class="breadcrumb-item"><a href="admin_konumrafi.php">Konum Rafı Yönetimi</a></li>
		<li class="breadcrumb-item active">Konum Rafı Ekle</li>
	</ol>

	<div class="row">
		<div class="col-md-6">
			<?php 

			if($error != '')
			{
				echo '
				<div class="alert alert-danger alert-dismissible fade show" role="alert"><ul class="list-unstyled">'.$error.'</ul> <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
				';
			}

			?>
			<div class="card mb-4">
				<div class="card-header">
					<i class="fas fa-user-plus"></i> Yeni Konum Rafı Ekle
                </div>
                <div class="card-body">
                	<form method="post">
                		<div class="mb-3">
                			<label class="form-label">Konum Rafı Adı</label>
                			<input type="text" name="konum_rafi_adi" id="konum_rafi_adi" class="form-control" />
                		</div>
                		<div class="mt-4 mb-0">
                			<input type="submit" name="konumrafi_ekle" class="btn btn-success" value="Ekle" />
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
			$konum_rafi_id = verileri_donustur($_GET["code"], 'decrypt');

			if($konum_rafi_id > 0)
			{
				$query = "
				SELECT * FROM konum_rafi 
                WHERE konum_rafi_id  = '$konum_rafi_id'
				";

				$konum_rafi_sonuc = $connect->query($query);

				foreach($konum_rafi_sonuc as $konum_rafi_row)
				{
	?>

	<ol class="breadcrumb mt-4 mb-4 bg-light p-2 border">
		<li class="breadcrumb-item"><a href="admin_index.php">Panel</a></li>
        <li class="breadcrumb-item"><a href="admin_konumrafi.php">Konum Rafı Yönetimi</a></li>
        <li class="breadcrumb-item active">Konum Rafı Düzenle</li>
    </ol>
    <div class="row">
    	<div class="col-md-6">
    		<div class="card mb-4">
    			<div class="card-header">
    				<i class="fas fa-user-edit"></i> Konum Rafı Detaylarını Düzenle
                </div>
                <div class="card-body">
                	<form method="post">
                		<div class="mb-3">
                			<label class="form-label">Konum Rafı Adı</label>
                			<input type="text" name="konum_rafi_adi" id="konum_rafi_adi" class="form-control" value="<?php echo $konum_rafi_row["konum_rafi_adi"]; ?>" />
                		</div>
                		<div class="mt-4 mb-0">
                			<input type="hidden" name="konum_rafi_id" value="<?php echo $_GET['code']; ?>" />
                			<input type="submit" name="konum_rafi_duzenle" class="btn btn-primary" value="Düzenle" />
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
		<li class="breadcrumb-item active">Konum Rafı Yönetimi</li>
	</ol>
		<?php 

		if(isset($_GET["msg"]))
		{
			if($_GET["msg"] == 'add')
			{
				echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Yeni Konum Rafı Eklendi<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
			}

			if($_GET["msg"] == 'edit')
			{
				echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Konum Rafı Verileri Düzenlendi<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
			}

			if($_GET["msg"] == 'disable')
			{
				echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Konum Rafı Devre Dışı Bırakıldı<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
			}

			if($_GET["msg"] == 'enable')
			{
				echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Konum Rafı Aktif Hale Getirildi<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
			}
		}

		?>
	<div class="card mb-4">
		<div class="card-header">
			<div class="row">
				<div class="col col-md-6">
					<i class="fas fa-table me-1"></i> Konum Rafı Yönetimi
				</div>
				<div class="col col-md-6" align="right">
					<a href="admin_konumrafi.php?action=add" class="btn btn-success btn-sm">Ekle</a>
				</div>
			</div>
		</div>
		<div class="card-body">
			<table id="datatablesSimple">
				<thead>
					<tr>
						<th>Konum Rafı Adı</th>
                        <th>Durum</th>
                        <th>Oluşturulduğu Tarih</th>
                        <th>Güncellendiği Tarih</th>
                        <th>Eylem</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
                        <th>Konum Rafı Adı</th>
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
						$konum_rafi_durum = '';
						if($row['konum_rafi_durum'] == 'Enable')
						{
							$konum_rafi_durum = '<div class="badge bg-success">Aktif</div>';
						}
						else
						{
							$konum_rafi_durum = '<div class="badge bg-danger">Devre Dışı</div>';
						}

						echo '
						<tr>
							<td>'.$row["konum_rafi_adi"].'</td>
							<td>'.$konum_rafi_durum.'</td>
							<td>'.$row["konum_rafi_olusturuldu"].'</td>
							<td>'.$row["konum_rafi_guncellendi"].'</td>
							<td>
								<a href="admin_konumrafi.php?action=edit&code='.verileri_donustur($row["konum_rafi_id"]).'" class="btn btn-sm btn-primary">Düzenle</a>
								<button type="button" name="silme_butonu" class="btn btn-danger btn-sm" onclick="delete_data(`'.$row["konum_rafi_id"].'`, `'.$row["konum_rafi_durum"].'`)">Durumu Değiştir</button>
							</td>
						</tr>
						';

					}
				}
				else
				{
					echo '
					<tr>
						<td colspan="5" class="text-center">Veri Bulunamadı</td>
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

			if(confirm("Bu kategoriyi " +yeni_durum+" etmek istediğinize emin misiniz?"))
			{
				window.location.href = "admin_konumrafi.php?action=delete&code="+code+"&durum="+yeni_durum+""
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
