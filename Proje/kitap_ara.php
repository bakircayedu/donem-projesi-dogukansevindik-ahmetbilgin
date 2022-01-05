<?php

include 'baglanti.php';

include 'function.php';

if(!kullanici_girisimi())
{
	header('location:kullanici_giris.php');
}

$query = "
	SELECT * FROM kitap
    WHERE kitap_durum = 'Enable' 
    ORDER BY kitap_id DESC
";

$statement = $connect->prepare($query);

$statement->execute();


include 'header.php';

?>

<div class="container-fluid py-4" style="min-height: 700px;">

	<h1>Kitap Ara</h1>

	<div class="card mb-4">
		<div class="card-header">
			<div class="row">
				<div class="col col-md-6">
					<i class="fas fa-table me-1"></i> Kitap Listesi
				</div>
				<div class="col col-md-6" align="right">

				</div>
			</div>
		</div>
		<div class="card-body">
			<table id="datatablesSimple">
				<thead>
					<tr>
						<th>Kitap Adı</th>
						<th>ISBN No.</th>
						<th>Kategori</th>
						<th>Yazar</th>
						<th>Kitap Konum Rafı</th>
						<th>Miktar</th>
						<th>Durum</th>
						<th>Eklendi</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>Kitap Adı</th>
						<th>ISBN No.</th>
						<th>Kategori</th>
						<th>Yazar</th>
						<th>Kitap Konum Rafı</th>
						<th>Miktar</th>
						<th>Durum</th>
						<th>Eklendi</th>
					</tr>
				</tfoot>
				<tbody>
				<?php 

				if($statement->rowCount() > 0)
				{
					foreach($statement->fetchAll() as $row)
					{
						$kitap_durum = '';
						if($row['kitap_no_kopyasi'] > 0)
						{
							$kitap_durum = '<div class="badge bg-success">Mevcut</div>';
						}
						else
						{
							$kitap_durum = '<div class="badge bg-danger">Mevcut Değil</div>';
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
							</tr>
						';
					}
				}
				else
				{
					echo '
					<tr>
						<td colspan="8" class="text-center">Veri Bulunamadı</td>
					</tr>
					';
				}

				?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<?php 

include 'footer.php';

?>