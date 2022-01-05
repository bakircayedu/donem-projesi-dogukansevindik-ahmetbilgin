<?php

function base_url()
{
	return 'http://localhost/Proje/';
}

function admin_girisimi()
{
	if(isset($_SESSION['admin_id']))
	{
		return true;
	}
	return false;
}

function kullanici_girisimi()
{
	if(isset($_SESSION['kullanici_id']))
	{
		return true;
	}
	return false;
}

function zamandilimini_ayarla($connect)
{
	$query = "
	SELECT kutuphane_zaman_dilimi FROM ayarlar
	LIMIT 1
	";

	$result = $connect->query($query);

	foreach($result as $row)
	{
		date_default_timezone_set($row["kutuphane_zaman_dilimi"]);
	}
}

function get_date_time($connect)
{
	zamandilimini_ayarla($connect);

	return date("Y-m-d H:i:s",  STRTOTIME(date('h:i:sa')));
}

function gec_donus_gunluk_ceza($connect)
{
	$output = 0;
	$query = "
	SELECT kitap_gec_donus_gunluk_ceza FROM ayarlar 
	LIMIT 1
	";
	$result = $connect->query($query);
	foreach($result as $row)
	{
		$output = $row["kitap_gec_donus_gunluk_ceza"];
	}
	return $output;
}

function get_parabirimi_sembol($connect)
{
	$output = '';
	$query = "
	SELECT kutuphane_para_birimi FROM ayarlar 
	LIMIT 1
	";
	$result = $connect->query($query);
	foreach($result as $row)
	{
		$parabirimi_data = parabirimi_dizisi();
		foreach($parabirimi_data as $parabirimi)
		{
			if($parabirimi["kod"] == $row['kutuphane_para_birimi'])
			{
				$output = '<span style="font-family: DejaVu Sans;">' . $parabirimi["sembol"] . '</span>&nbsp;';
			}
		}		
	}
	return $output;
}

function verileri_donustur($string, $action = 'encrypt')
{
	$encrypt_method = "AES-256-CBC";
	$secret_key = 'AA74CDCC2BBRT935136HH7B63C27'; // kullanıcı tanımlı özel anahtar
	$secret_iv = '5fgf5HJ5g27'; // kullanıcı tanımlı gizli anahtar
	$key = hash('sha256', $secret_key);
	$iv = substr(hash('sha256', $secret_iv), 0, 16); // sha256 is hash_hmac_algo
	if ($action == 'encrypt') 
	{
		$output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
	    $output = base64_encode($output);
	} 
	else if ($action == 'decrypt') 
	{
		$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
	}
	return $output;
}

function parabirimi_dizisi()
	{
		$parabirimleri = array(
			array('kod'=> 'EUR',
			    'ulkeadi'=> 'European Union, Italy, Belgium, Bulgaria, Croatia, Cyprus, Czechia, Denmark, Estonia, Finland, France, Germany, Greece, Hungary, Ireland, Latvia, Lithuania, Luxembourg, Malta, Netherlands, Poland, Portugal, Romania, Slovakia, Slovenia, Spain, Sweden',
			    'isim'=> 'Euro',
			    'sembol'=> '&#8364;'),

			
			array('kod'=> 'TRY',
			    'ulkeadi'=> 'Turkey, Turkish Republic of Northern Cyprus',
			    'isim'=> 'Turkey Lira',
			    'sembol'=> '&#8378;'),

			array('kod'=> 'USD',
			    'ulkeadi'=> 'United States',
			    'isim'=> 'United States dollar',
			    'sembol'=> '&#36;'),

		);
		
		return $parabirimleri;
	}

	function Parabirimi_listesi()
	{
		$html = '
			<option value="">Para Birimi Seçiniz</option>
		';
		$data = parabirimi_dizisi();
		foreach($data as $row)
		{
			$html .= '<option value="'.$row["kod"].'">'.$row["isim"].'</option>';
		}
		return $html;
	}

	function Zamandilimi_listesi()
	{
		$zamandilimleri = array(
			'America/Los_Angeles' => '(GMT-8:00) America/Los_Angeles (Pacific Standard Time)',
			'America/Chicago' => '(GMT-6:00) America/Chicago (Central Standard Time)',
			'America/Mexico_City' => '(GMT-6:00) America/Mexico_City (Central Standard Time)',
			'Canada/Central' => '(GMT-6:00) Canada/Central (Central Standard Time)',
			'America/New_York' => '(GMT-5:00) America/New_York (Eastern Standard Time)',
			'America/Buenos_Aires' => '(GMT-3:00) America/Buenos_Aires (Argentine Time)',
			'America/Sao_Paulo' => '(GMT-3:00) America/Sao_Paulo (Brasilia Time)',
			'Europe/Jersey' => '(GMT+0:00) Europe/Jersey (Greenwich Mean Time)',
			'Europe/Lisbon' => '(GMT+0:00) Europe/Lisbon (Western European Time)',
			'Europe/London' => '(GMT+0:00) Europe/London (Greenwich Mean Time)',
			'Europe/Amsterdam' => '(GMT+1:00) Europe/Amsterdam (Central European Time)',
			'Europe/Belgrade' => '(GMT+1:00) Europe/Belgrade (Central European Time)',
			'Europe/Berlin' => '(GMT+1:00) Europe/Berlin (Central European Time)',
			'Europe/Madrid' => '(GMT+1:00) Europe/Madrid (Central European Time)',
			'Europe/Paris' => '(GMT+1:00) Europe/Paris (Central European Time)',
			'Europe/Rome' => '(GMT+1:00) Europe/Rome (Central European Time)',
			'Asia/Istanbul' => '(GMT+2:00) Asia/Istanbul (Eastern European Time)',
			'Europe/Istanbul' => '(GMT+2:00) Europe/Istanbul (Eastern European Time)',
			
			
		);

		$html = '<option value="">Zaman Dilimi Seç</option>';
		foreach($zamandilimleri as $keys => $values)
		{
			$html .= '<option value="'.$keys.'">'.$values.'</option>';
		}
		
		return $html;
	}

function yazari_doldur($connect)
{
	$query = "
	SELECT yazar_adi FROM yazar
	WHERE yazar_durumu = 'Enable' 
	ORDER BY yazar_adi ASC
	";

	$result = $connect->query($query);

	$output = '<option value="">Yazar Seç</option>';

	foreach($result as $row)
	{
		$output .= '<option value="'.$row["yazar_adi"].'">'.$row["yazar_adi"].'</option>';
	}

	return $output;
}

function kategoriyi_doldur($connect)
{
	$query = "
	SELECT kategori_adi FROM kategori
	WHERE kategori_durum = 'Enable' 
	ORDER BY kategori_adi ASC
	";

	$result = $connect->query($query);

	$output = '<option value="">Kategori Seç</option>';

	foreach($result as $row)
	{
		$output .= '<option value="'.$row["kategori_adi"].'">'.$row["kategori_adi"].'</option>';
	}

	return $output;
}

function konum_rafini_doldur($connect)
{
	$query = "
	SELECT konum_rafi_adi FROM konum_rafi
	WHERE konum_rafi_durum = 'Enable' 
	ORDER BY konum_rafi_adi ASC
	";

	$result = $connect->query($query);

	$output = '<option value="">Konum Rafı Seçiniz</option>';

	foreach($result as $row)
	{
		$output .= '<option value="'.$row["konum_rafi_adi"].'">'.$row["konum_rafi_adi"].'</option>';
	}

	return $output;
}

function get_kisi_basina_verilebilecek_kitap($connect)
{
	$output = '';
	$query = "
	SELECT kisi_basina_verilebilecek_kitap FROM ayarlar 
	LIMIT 1
	";
	$result = $connect->query($query);
	foreach($result as $row)
	{
		$output = $row["kisi_basina_verilebilecek_kitap"];
	}
	return $output;
}

function get_kisi_basina_verilen_kitap_toplami($connect, $kullanici_unique_id)
{
	$output = 0;

	$query = "
	SELECT COUNT(alinan_kitap_id) AS Total FROM alinan_kitap
	WHERE kullanici_id = '".$kullanici_unique_id."' 
	AND alinan_kitap_durum = 'Alındı'
	";

	$result = $connect->query($query);

	foreach($result as $row)
	{
		$output = $row["Total"];
	}
	return $output;
}

function get_kitap_iade_gun_limiti($connect)
{
	$output = 0;

	$query = "
	SELECT kitap_iade_gun_limiti FROM ayarlar 
	LIMIT 1
	";

	$result = $connect->query($query);

	foreach($result as $row)
	{
		$output = $row["kitap_iade_gun_limiti"];
	}
	return $output;
}

?>