
<?php


include 'baglanti.php';

include 'function.php';


if(!admin_girisimi())
{
	header('location:admingiris.php');
}

$message = '';

$error = '';

if(isset($_POST["kitap_ekle"]))
{
	$formdata = array();

	if(empty($_POST["kitap_adi"]))
	{
		$error .= '<li>Kitap Adı Gereklidir</li>';
	}
	else
	{
		$formdata['kitap_adi'] = trim($_POST["kitap_adi"]);
	}

	if(empty($_POST["kitap_kategori"]))
	{
		$error .= '<li>Kitap Kategorisi Gereklidir</li>';
	}
	else
	{
		$formdata['kitap_kategori'] = trim($_POST["kitap_kategori"]);
	}

	if(empty($_POST["kitap_yazar"]))
	{
		$error .= '<li>Kitap Yazarı Gereklidir</li>';
	}
	else
	{
		$formdata['kitap_yazar'] = trim($_POST["kitap_yazar"]);
	}

	if(empty($_POST["kitap_rafi"]))
	{
		$error .= '<li>Kitap Raf Bilgisi Gereklidir</li>';
	}
	else
	{
		$formdata['kitap_rafi'] = trim($_POST["kitap_rafi"]);
	}

	if(empty($_POST["kitap_isbn_numarasi"]))
	{
		$error .= '<li>ISBN No Gereklidir</li>';
	}
	else
	{
		$formdata['kitap_isbn_numarasi'] = trim($_POST["kitap_isbn_numarasi"]);
	}
	if(empty($_POST["kitap_no_kopyasi"]))
	{
		$error .= '<li>Kitaptan Kaç Adet Olduğu Gereklidir</li>';
	}
	else
	{
		$formdata['kitap_no_kopyasi'] = trim($_POST["kitap_no_kopyasi"]);
	}

	if($error == '')
	{
		$data = array(
			':kitap_kategori'		=>	$formdata['kitap_kategori'],
			':kitap_yazar'			=>	$formdata['kitap_yazar'],
			':kitap_rafi'			=>	$formdata['kitap_rafi'],
			':kitap_adi'			=>	$formdata['kitap_adi'],
			':kitap_isbn_numarasi'	=>	$formdata['kitap_isbn_numarasi'],
			':kitap_no_kopyasi'		=>	$formdata['kitap_no_kopyasi'],
			':kitap_durum'			=>	'Enable',
			':kitap_ekleme'			=>	get_date_time($connect)

			

			
		);

		$query = "
		INSERT INTO kitap 
        (kitap_kategori, kitap_yazar, kitap_rafi,kitap_adi, kitap_isbn_numarasi, kitap_no_kopyasi, kitap_durum, kitap_ekleme) 
        VALUES (:kitap_kategori, :kitap_yazar, :kitap_rafi, :kitap_adi, :kitap_isbn_numarasi, :kitap_no_kopyasi, :kitap_durum, :kitap_ekleme)
		";

		$statement = $connect->prepare($query);

		$statement->execute($data);

		header('location:admin_kitap.php?msg=add');
	}
}

if(isset($_POST["kitap_duzenle"]))
{
	$formdata = array();

	if(empty($_POST["kitap_adi"]))
	{
		$error .= '<li>Kitap Adı Gereklidir</li>';
	}
	else
	{
		$formdata['kitap_adi'] = trim($_POST["kitap_adi"]);
	}

	if(empty($_POST["kitap_kategori"]))
	{
		$error .= '<li>Kitap Kategorisi Gereklidir</li>';
	}
	else
	{
		$formdata['kitap_kategori'] = trim($_POST["kitap_kategori"]);
	}

	if(empty($_POST["kitap_yazar"]))
	{
		$error .= '<li>Kitap Kategorisi Gereklidir</li>';
	}
	else
	{
		$formdata['kitap_yazar'] = trim($_POST["kitap_yazar"]);
	}

	if(empty($_POST["kitap_rafi"]))
	{
		$error .= '<li>Kitap Konum Rafı Gereklidir</li>';
	}
	else
	{
		$formdata['kitap_rafi'] = trim($_POST["kitap_rafi"]);
	}

	if(empty($_POST["kitap_isbn_numarasi"]))
	{
		$error .= '<li>ISBN No Gereklidir</li>';
	}
	else
	{
		$formdata['kitap_isbn_numarasi'] = trim($_POST["kitap_isbn_numarasi"]);
	}
	if(empty($_POST["kitap_no_kopyasi"]))
	{
		$error .= '<li>Kitaptan Kaç Adet Olduğu Gereklidir</li>';
	}
	else
	{
		$formdata['kitap_no_kopyasi'] = trim($_POST["kitap_no_kopyasi"]);
	}

	if($error == '')
	{
		$data = array(
			':kitap_kategori'		=>	$formdata['kitap_kategori'],
			':kitap_yazar'			=>	$formdata['kitap_yazar'],
			':kitap_rafi'			=>	$formdata['kitap_rafi'],
			':kitap_adi'			=>	$formdata['	kitap_adi'],
			':kitap_isbn_numarasi'	=>	$formdata['kitap_isbn_numarasi'],
			':kitap_no_kopyasi'		=>	$formdata['kitap_no_kopyasi'],
			':kitap_guncelleme'		=>	get_date_time($connect),
			':kitap_id '			=>	$_POST["kitap_id"]
		);
		$query = "
		UPDATE kitap 
        SET kitap_kategori = :kitap_kategori, 
        kitap_yazar = :kitap_yazar, 
        kitap_rafi = :kitap_rafi, 
		kitap_adi = :kitap_adi, 
        kitap_isbn_numarasi = :kitap_isbn_numarasi, 
        kitap_no_kopyasi = :kitap_no_kopyasi, 
        kitap_guncelleme = :kitap_guncelleme 
        WHERE kitap_id  = :kitap_id 
		";

		$statement = $connect->prepare($query);

		$statement->execute($data);

		header('location:admin_kitap.php?msg=edit');
	}
}

if(isset($_GET["action"], $_GET["code"], $_GET["durum"]) && $_GET["action"] == 'delete')
{
	$kitap_id  = $_GET["code"];
	$durum = $_GET["durum"];

	$data = array(
		':kitap_durum'		=>	$durum,
		':kitap_guncelleme'	=>	get_date_time($connect),
		':kitap_id '		=>	$kitap_id 
	);

	$query = "
	UPDATE kitap 
    SET kitap_durum = :kitap_durum, 
    kitap_guncelleme = :kitap_guncelleme 
    WHERE kitap_id  = :kitap_id 
	";

	$statement = $connect->prepare($query);

	$statement->execute($data);

	header('location:admin_kitap.php?msg='.strtolower($durum).'');
}


$query = "
	SELECT * FROM kitap
    ORDER BY kitap_id DESC
";

$statement = $connect->prepare($query);

$statement->execute();


include 'header.php';

?>

<div class="container-fluid py-4" style="min-height: 700px;">
	<h1>Kitap Yönetimi</h1>
	<?php 
	if(isset($_GET["action"]))
	{
		if($_GET["action"] == 'add')
		{
	?>

	<ol class="breadcrumb mt-4 mb-4 bg-light p-2 border">
		<li class="breadcrumb-item"><a href="admin_index.php">Panel</a></li>
        <li class="breadcrumb-item"><a href="admin_kitap.php">Kitap Yönetimi</a></li>
        <li class="breadcrumb-item active">Kitap Ekle</li>
    </ol>

    <?php 

    if($error != '')
    {
    	echo '<div class="alert alert-danger alert-dismissible fade show" role="alert"><ul class="list-unstyled">'.$error.'</ul> <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    }

    ?>

    <div class="card mb-4">
    	<div class="card-header">
    		<i class="fas fa-user-plus"></i> Yeni Kitap Ekle
        </div>
        <div class="card-body">
        	<form method="post">
        		<div class="row">
        			<div class="col-md-6">
        				<div class="mb-3">
        					<label class="form-label">Kitap Adı</label>
        					<input type="text" name="kitap_adi" id="kitap_adi" class="form-control" />
        				</div>
        			</div>
        			<div class="col-md-6">
        				<div class="mb-3">
        					<label class="form-label">Yazar Seç</label>
        					<select name="kitap_yazar" id="kitap_yazar" class="form-control">
        						<?php echo yazari_doldur($connect); ?>
        					</select>
        				</div>
        			</div>
        		</div>
        		<div class="row">
        			<div class="col-md-6">
        				<div class="mb-3">
        					<label class="form-label">Kategori Seç</label>
        					<select name="kitap_kategori" id="kitap_kategori" class="form-control">
        						<?php echo kategoriyi_doldur($connect); ?>
        					</select>
        				</div>
        			</div>
        			<div class="col-md-6">
        				<div class="mb-3">
        					<label class="form-label">Konum Rafını Seçin</label>
        					<select name="kitap_rafi" id="kitap_rafi" class="form-control">
        						<?php echo konum_rafini_doldur($connect); ?>
        					</select>
        				</div>
        			</div>
        		</div>
        		<div class="row">
        			<div class="col-md-6">
        				<div class="mb-3">
        					<label class="form-label">Kitap ISBN Numarası</label>
        					<input type="text" name="kitap_isbn_numarasi" id="kitap_isbn_numarasi" class="form-control" />
        				</div>
        			</div>
        			<div class="col-md-6">
        				<div class="mb-3">
        					<label class="form-label">Kitap Kopya Sayısı</label>
        					<input type="number" name="kitap_no_kopyasi" id="kitap_no_kopyasi" step="1" class="form-control" />
        				</div>
        			</div>
        		</div>
        		<div class="mt-4 mb-3 text-center">
        			<input type="submit" name="kitap_ekle" class="btn btn-success" value="Ekle" />
        		</div>
        	</form>
        </div>
    </div>

	<?php 
		}
		else if($_GET["action"] == 'edit')
		{
			$kitap_id  = verileri_donustur($_GET["code"], 'decrypt');

			if($kitap_id  > 0)
			{
				$query = "
				SELECT * FROM kitap 
                WHERE kitap_id = '$kitap_id '
				";

				$book_result = $connect->query($query);

				foreach($book_result as $book_row)
				{
	?>
	<ol class="breadcrumb mt-4 mb-4 bg-light p-2 border">
		<li class="breadcrumb-item"><a href="admin_index.php">Panel</a></li>
        <li class="breadcrumb-item"><a href="admin_kitap.php">Kitap Yönetimi</a></li>
        <li class="breadcrumb-item active">Kitap Düzenle</li>
    </ol>
    <div class="card mb-4">
    	<div class="card-header">
    		<i class="fas fa-user-plus"></i> Kitap Detaylarını Düzenle
       	</div>
       	<div class="card-body">
       		<form method="post">
       			<div class="row">
       				<div class="col-md-6">
       					<div class="mb-3">
       						<label class="form-label">Kitap Adı</label>
       						<input type="text" name="kitap_adi" id="kitap_adi" class="form-control" value="<?php echo $book_row['kitap_adi']; ?>" />
       					</div>
       				</div>
       				<div class="col-md-6">
       					<div class="mb-3">
       						<label class="form-label">Yazar Seç</label>
       						<select name="kitap_yazar" id="kitap_yazar" class="form-control">
       							<?php echo yazari_doldur($connect); ?>
       						</select>
       					</div>
       				</div>
       			</div>
       			<div class="row">
       				<div class="col-md-6">
       					<div class="mb-3">
       						<label class="form-label">Kategori Seç</label>
       						<select name="kitap_kategori" id="kitap_kategori" class="form-control">
       							<?php echo kategoriyi_doldur($connect); ?>
       						</select>
       					</div>
       				</div>
       				<div class="col-md-6">
       					<div class="mb-3">
       						<label class="form-label">Kitap Rafı Seç</label>
       						<select name="kitap_rafi" id="kitap_rafi" class="form-control">
       							<?php echo konum_rafini_doldur($connect); ?>
       						</select>
       					</div>
       				</div>
       			</div>
       			<div class="row">
       				<div class="col-md-6">
       					<div class="mb-3">
       						<label class="form-label">Kitap ISBN No</label>
       						<input type="text" name="kitap_isbn_numarasi" id="kitap_isbn_numarasi" class="form-control" value="<?php echo $book_row['kitap_isbn_numarasi']; ?>" />
       					</div>
       				</div>
       				<div class="col-md-6">
       					<div class="mb-3">
       						<label class="form-label">Kitap Adet Sayısı</label>
       						<input type="number" name="kitap_no_kopyasi" id="kitap_no_kopyasi" class="form-control" step="1" value="<?php echo $book_row['kitap_no_kopyasi']; ?>" />
       					</div>
       				</div>
       			</div>
       			<div class="mt-4 mb-3 text-center">
       				<input type="hidden" name="kitap_id" value="<?php echo $book_row['kitap_id']; ?>" />
       				<input type="submit" name="kitap_duzenle" class="btn btn-primary" value="Duzenle" />
       			</div>
       		</form>
       		<script>
       			document.getElementById('kitap_yazar').value = "<?php echo $book_row['kitap_yazar']; ?>";
       			document.getElementById('kitap_kategori').value = "<?php echo $book_row['kitap_kategori']; ?>";
       			document.getElementById('kitap_rafi').value = "<?php echo $book_row['kitap_rafi']; ?>";
       		</script>
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
		<li class="breadcrumb-item active">Kitap Yönetimi</li>
	</ol>
	<?php 

	if(isset($_GET["msg"]))
	{
		if($_GET["msg"] == 'add')
		{
			echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Yeni Kitap Eklendi<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
		}
		if($_GET['msg'] == 'edit')
		{
			echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Kitap Verileri Düzenlendi<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
		}
		if($_GET["msg"] == 'disable')
		{
			echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Kitap Durumunu Devre Dışı Hale Getir <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
		}
		if($_GET['msg'] == 'enable')
		{
			echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Kitap Durumunu Etkin Hale Getir <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
		}
	}

	?>
	<div class="card mb-4">
		<div class="card-header">
			<div class="row">
				<div class="col col-md-6">
					<i class="fas fa-table me-1"></i> Kitap Yönetimi
                </div>
                <div class="col col-md-6" align="right">
                	<a href="admin_kitap.php?action=add" class="btn btn-success btn-sm">Ekle</a>
                </div>
            </div>
        </div>
        <div class="card-body">
        	<table id="datatablesSimple">
        		<thead> 
        			<tr> 
        				<th>Kitap Adı</th>
        				<th>ISBN No</th>
        				<th>Kategori</th>
        				<th>Yazar</th>
        				<th>Konum Rafı</th>
        				<th>Kopya Sayısı</th>
        				<th>Durum</th>
        				<th>Oluşturulduğu Tarih</th>
        				<th>Güncellendiği Tarih</th>
        				<th>Eylem</th>
        			</tr>
        		</thead>
        		<tfoot>
        			<tr>
						<th>Kitap Adı</th>
        				<th>ISBN No</th>
        				<th>Kategori</th>
        				<th>Yazar</th>
        				<th>Konum Rafı</th>
        				<th>Kopya Sayısı</th>
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
        				$kitap_durum = '';
        				if($row['kitap_durum'] == 'Enable')
        				{
        					$kitap_durum = '<div class="badge bg-success">Etkinleştirildi</div>';
        				}
        				else
        				{
        					$kitap_durum = '<div class="badge bg-danger">Devre Dışı Bırakıldı</div>';
        				}
        				echo '
        				<tr>
        					<td>'.$row["kitap_adi"].'</td>
        					<td>'.$row["kitap_isbn_numarasi"].'</td>
        					<td>'.$row["kitap_kategori"].'</td>
        					<td>'.$row["kitap_yazar"].'</td>
        					<td>'.$row["kitap_rafi"].'</td>
        					<td>'.$row["kitap_no_kopyasi"].'</td>
        					<td>'.$kitap_durum.'</td>
        					<td>'.$row["kitap_ekleme"].'</td>
        					<td>'.$row["kitap_guncelleme"].'</td>
        					<td>
        						<a href="admin_kitap.php?action=edit&code='.verileri_donustur($row["kitap_id"]).'" class="btn btn-sm btn-primary">Düzenle</a>
        						<button type="button" name="silme_butonu" class="btn btn-danger btn-sm" onclick="delete_data(`'.$row["kitap_id"].'`, `'.$row["kitap_durum"].'`)">Durumu Değiştir</button>
        					</td>
        				</tr>
        				';
        			}
        		}
        		else
        		{
        			echo '
        			<tr>
        				<td colspan="10" class="text-center">Veri Bulunamadı</td>
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

    		if(confirm("Bu kategoriyi "+yeni_durum+" etmek istediğinize emin misiniz?"))
    		{
    			window.location.href = "admin_kitap.php?action=delete&code="+code+"&durum="+yeni_durum+"";
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
