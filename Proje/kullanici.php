<?php


include 'baglanti.php';

include 'function.php';

if(!admin_girisimi())
{
	header('location:admingiris.php');
}

if(isset($_GET["action"], $_GET['durum'], $_GET['code']) && $_GET["action"] == 'delete')
{
	$kullanici_id = $_GET["code"];
	$durum = $_GET["durum"];

	$data = array(
		':kullanici_durumu'			=>	$durum,
		':kullanici_guncellendi'	=>	get_date_time($connect),
		':kullanici_id'				=>	$kullanici_id
	);

	$query = "
	UPDATE kullanici 
    SET kullanici_durumu = :kullanici_durumu, 
    kullanici_guncellendi = :kullanici_guncellendi 
    WHERE kullanici_id = :kullanici_id
	";

	$statement = $connect->prepare($query);

	$statement->execute($data);

	header('location:kullanici.php?msg='.strtolower($durum).'');
}

$query = "
	SELECT * FROM kullanici 
    ORDER BY kullanici_id DESC
";

$statement = $connect->prepare($query);

$statement->execute();

include 'header.php';

?>

<div class="container-fluid py-4" style="min-height: 700px;">
	<h1>Kullanıcı Yönetimi</h1>
	<ol class="breadcrumb mt-4 mb-4 bg-light p-2 border">
		<li class="breadcrumb-item"><a href="index.php">Gösterge Paneli</a></li>
        <li class="breadcrumb-item active">Kullanıcı Yönetimi</li>
    </ol>
    <?php 
 	
 	if(isset($_GET["msg"]))
 	{
 		if($_GET["msg"] == 'disable')
 		{
 			echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Devre Dışı Bırakılacak Kategori Durum Değişikliği<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
 		}

 		if($_GET["msg"] == 'enable')
 		{
 			echo '
 			<div class="alert alert-success alert-dismissible fade show" role="alert">Etkinleştirilecek Kategori Durum Değişikliği <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
 			';
 		}
 	}

    ?>
    <div class="card mb-4">
    	<div class="card-header">
    		<div class="row">
    			<div class="col col-md-6">
    				<i class="fas fa-table me-1"></i> Kullanıcı Yönetimi
    			</div>
    			<div class="col col-md-6" align="right">
    			</div>
    		</div>
    	</div>
    	<div class="card-body">
    		<table id="datatablesSimple">
    			<thead>
    				<tr>
    					<th>Fotoğraf</th>
                        <th>Kullanıcı Eşsiz İd</th>
                        <th>Kullanıcı Adı</th>
                        <th>Email Adresi</th>
                        <th>Şifre</th>
                        <th>İletişim Numarası</th>
                        <th>Adres</th>
                        <th>E-Posta Doğrulama</th>
                        <th>Durum</th>
                        <th>Oluşturulma Tarihi</th>
                        <th>Güncellenme Tarihi</th>
                        <th>Eylem</th>
    				</tr>
    			</thead>
    			<?php 
    			if($statement->rowCount() > 0)
    			{
    				foreach($statement->fetchAll() as $row)
    				{
    					$kullanici_durumu = '';
    					if($row['kullanici_durumu'] == 'Enable')
    					{
    						$kullanici_durumu = '<div class="badge bg-success">Etkinleştirildi</div>';
    					}
    					else
    					{
    						$kullanici_durumu = '<div class="badge bg-danger">Devre Dışı Bırakıldı</div>';
    					}
    					echo '
    					<tr>
    						<td><img src="../upload/'.$row["kullanici_profili"].'" class="img-thumbnail" width="75" /></td>
    						<td>'.$row["kullanici_unique_id"].'</td>
    						<td>'.$row["kullanici_adi"].'</td>
    						<td>'.$row["kullanici_email_adresi"].'</td>
    						<td>'.$row["kullanici_sifre"].'</td>
    						<td>'.$row["kullanici_iletisim_no"].'</td>
    						<td>'.$row["kullanici_adresi"].'</td>
    						<td>'.$row["kullanici_dogrulama_durumu"].'</td>
    						<td>'.$kullanici_durumu.'</td>
    						<td>'.$row["kullanici_olusturuldu"].'</td>
    						<td>'.$row["kullanici_guncellendi"].'</td>
    						<td><button type="button" name="silme_butonu" class="btn btn-danger btn-sm" onclick="veri_sil(`'.$row["kullanici_id"].'`, `'.$row["kullanici_durumu"].'`)">Durumu Değiştir</td>
    					</tr>
    					';
    				}
    			}
    			else
    			{
    				echo '

    				<tr>
    					<td colspan="12" class="text-center">Veri Bulunamadı</td>
    				</tr>
    				';
    			}
    			?>
    			</tbody>
    		</table>
    	</div>
    </div>
</div>

<script>

	function veri_sil(code, durum)
	{
		var yeni_durum = 'Enable';

		if(durum == 'Enable')
		{
			yeni_durum = 'Disable';
		}

		if(confirm("Bu kullanıcıyı "+yeni_durum+" etmek istediğinize emin misiniz?"))
		{
			window.location.href = "kullanici.php?action=delete&code="+code+"&durum="+yeni_durum+"";
		}
	}

</script>

<?php 

include 'footer.php';

?>