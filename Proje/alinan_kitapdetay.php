<?php



include 'baglanti.php';

include 'function.php';

if(!kullanici_girisimi())
{
	header('location:kullanici_giris.php');
}
$query = "
	SELECT * FROM alinan_kitap 
	INNER JOIN kitap 
	ON kitap.kitap_isbn_numarasi = alinan_kitap.kitap_id 
	WHERE alinan_kitap.kullanici_id = '".$_SESSION['kullanici_id']."' 
	ORDER BY alinan_kitap.alinan_kitap_id DESC
";

$statement = $connect->prepare($query);
$statement->execute();

include 'header.php';

?>
<div class="container-fluid py-4" style="min-height: 700px;">
	<h1>Alınan Kitap Detay</h1>
	<div class="card mb-4">
		<div class="card-header">
			<div class="row">
				<div class="col col-md-6">
					<i class="fas fa-table me-1"></i> Alınan Kitap Detay
				</div>
				<div class="col col-md-6" align="right">
				</div>
			</div>
		</div>
		<div class="card-body">
			<table id="datatablesSimple">
				<thead>
					<tr>
						<th>Kitap ISBN No</th>
						<th>Kitap Adı</th>
						<th>Alındığı Tarih</th>
						<th>İade Tarihi</th>
						<th>Para Cezaları</th>
						<th>Durum</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>Kitap ISBN No</th>
						<th>Kitap Adı</th>
						<th>Alındığı Tarih</th>
						<th>İade Tarihi</th>
						<th>Para Cezaları</th>
						<th>Durum</th>
					</tr>
				</tfoot>
				<tbody>
				<?php 
				if($statement->rowCount() > 0)
				{
					foreach($statement->fetchAll() as $row)
					{
						$durum = $row["alinan_kitap_durum"];
						if($durum == 'Alındı')
						{
							$durum = '<span class="badge bg-warning">Alındı</span>';
						}

						if($durum == 'Geri Donmedi')
						{
							$durum = '<span class="badge bg-danger">Geri Dönmedi</span>';
						}

						if($durum == 'Geri Dondu')
						{
							$durum = '<span class="badge bg-primary">Geri Döndü</span>';
						}

						echo '
						<tr>
							<td>'.$row["kitap_isbn_numarasi"].'</td>
							<td>'.$row["kitap_adi"].'</td>
							<td>'.$row["alinma_tarihi"].'</td>
							<td>'.$row["iade_edilen_tarih"].'</td>
							<td>'.get_parabirimi_sembol($connect).$row["para_cezasi"].'</td>
							<td>'.$durum.'</td>
						</tr>
						';
					}
				}
				?>
				</tbody>
			</table>
		</div>
	</div>

</div>

</div>

<?php 

include 'footer.php';

?>