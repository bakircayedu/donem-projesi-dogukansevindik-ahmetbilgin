
<?php 

include 'baglanti.php';

if(isset($_POST["action"]))
{
	if($_POST["action"] == 'kitap_isbn_ara')
	{
		$query = "
		SELECT kitap_isbn_numarasi, kitap_adi FROM kitap 
		WHERE kitap_isbn_numarasi LIKE '%".$_POST["request"]."%' 
		AND kitap_durum = 'Enable'
		";

		$result = $connect->query($query);

		$data = array();

		foreach($result as $row)
		{
			$data[] = array(
				'isbn_no'		=>	str_replace($_POST["request"], '<b>'.$_POST["request"].'</b>', $row["kitap_isbn_numarasi"]),
				'kitap_adi'		=>	$row['kitap_adi']
			);
		}
		echo json_encode($data);
	}

	if($_POST["action"] == 'kullanici_id_ara')
	{
		$query = "
		SELECT kullanici_unique_id, kullanici_adi FROM kullanici 
		WHERE kullanici_unique_id LIKE '%".$_POST["request"]."%' 
		AND kullanici_durumu = 'Enable'
		";

		$result = $connect->query($query);

		$data = array();

		foreach($result as $row)
		{
			$data[] = array(
				'kullanici_unique_id'	=>	str_replace($_POST["request"], '<b>'.$_POST["request"].'</b>', $row["kullanici_unique_id"]),
				'kullanici_adi'			=>	$row["kullanici_adi"]
			);
		}

		echo json_encode($data);
	}
}

?>