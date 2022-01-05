
<?php


include 'baglanti.php';

include 'function.php';

include 'header.php';

if(isset($_GET['code']))
{
	$data = array(
		':kullanici_dogrulama_kodu'		=>	trim($_GET['code'])
	);

	$query = "
	SELECT 	kullanici_dogrulama_durumu FROM kullanici 
	WHERE kullanici_dogrulama_kodu = :kullanici_dogrulama_kodu
	";

	$statement = $connect->prepare($query);

	$statement->execute($data);

	if($statement->rowCount() > 0)
	{
		foreach($statement->fetchAll() as $row)
		{
			if($row['kullanici_dogrulama_durumu'] == 'Hayır')
			{
				$data = array(
					':kullanici_dogrulama_durumu'		=>	'Evet',
					':kullanici_dogrulama_kodu'			=>	trim($_GET['code'])
				);

				$query = "
				UPDATE kullanici 
				SET kullanici_dogrulama_durumu = :kullanici_dogrulama_durumu
				WHERE kullanici_dogrulama_kodu = :kullanici_dogrulama_kodu
				";

				$statement = $connect->prepare($query);

				$statement->execute($data);

				echo '<div class="alert alert-success">E-Postanız Başarıyla Doğrulandı <a href="kullanici_giris.php">Giriş</a>Sisteme Giriş Yapabilirsiz</div>';
			}
			else
			{
				echo '<div class="alert alert-info">E-Postanız Zaten Doğrulandı</div>';
			}
		}
	}
	else
	{
		echo '<div class="alert alert-danger">Geçersiz URL</div>';
	}
}

include 'footer.php';

?>
