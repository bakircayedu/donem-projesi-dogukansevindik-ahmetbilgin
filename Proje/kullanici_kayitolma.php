
<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

include 'baglanti.php';

include 'function.php';

if(kullanici_girisimi())
{
	header('location:alinan-kitapdetay.php');
}

$message = '';

$success = '';

if(isset($_POST["kayit_butonu"]))
{
	$formdata = array();

	if(empty($_POST["kullanici_email_adresi"]))
	{
		$message .= '<li>E-Posta Adresi Gereklidir</li>';
	}
	else
	{
		if(!filter_var($_POST["kullanici_email_adresi"], FILTER_VALIDATE_EMAIL))
		{
			$message .= '<li>Geçersiz E-Posta Adresi</li>';
		}
		else
		{
			$formdata['kullanici_email_adresi'] = trim($_POST['kullanici_email_adresi']);
		}
	}

	if(empty($_POST["kullanici_sifre"]))
	{
		$message .= '<li>Şifre Gereklidir</li>';
	}
	else
	{
		$formdata['kullanici_sifre'] = trim($_POST['kullanici_sifre']);
	}

	if(empty($_POST['kullanici_adi']))
	{
		$message .= '<li>Kullanıcı Adı Gereklidir</li>';
	}
	else
	{
		$formdata['kullanici_adi'] = trim($_POST['kullanici_adi']);
	}

	if(empty($_POST['kullanici_adresi']))
	{
		$message .= '<li>Kullanıcı Adresi Gereklidir</li>';
	}
	else
	{
		$formdata['kullanici_adresi'] = trim($_POST['kullanici_adresi']);
	}

	if(empty($_POST['kullanici_iletisim_no']))
	{
		$message .= '<li>Kullanıcı İletişim Numarası Gereklidir</li>';
	}
	else
	{
		$formdata['kullanici_iletisim_no'] = trim($_POST['kullanici_iletisim_no']);
	}

	if(!empty($_FILES['kullanici_profili']['name']))
	{
		$img_name = $_FILES['kullanici_profili']['name'];
		$img_type = $_FILES['kullanici_profili']['type'];
		$tmp_name = $_FILES['kullanici_profili']['tmp_name'];
		$fileinfo = @getimagesize($tmp_name);
		$width = $fileinfo[0];
		$height = $fileinfo[1];

		$image_size = $_FILES['kullanici_profili']['size'];

		$img_explode = explode(".", $img_name);

		$img_ext = strtolower(end($img_explode));

		$extensions = ["jpeg", "png", "jpg"];

		if(in_array($img_ext, $extensions))
		{
			if($image_size <= 2000000)
			{
				if($width == '225' && $height == '225')
				{
					$new_img_name = time() . '-' . rand() . '.' . $img_ext;
					if(move_uploaded_file($tmp_name, "upload/".$new_img_name))
					{
						$formdata['kullanici_profili'] = $new_img_name;
					}
				}
				else
				{
					$message .= '<li>Fotoğraf 225x225 boyutunda olmalıdır.</li>';
				}
			}
			else
			{
				$message .= '<li>Fotoğraf boyutu 2Mb ı aşamaz.</li>';
			}
		}
		else
		{
			$message .= '<li>Geçersiz Fotoğraf Dosyası</li>';
		}
	}
	else
	{
		$message .= '<li>Lütfen Profil Fotoğrafı Seçiniz</li>';
	}

	if($message == '')
	{
		$data = array(
			':kullanici_email_adresi'		=>	$formdata['kullanici_email_adresi']
		);

		$query = "
		SELECT * FROM kullanici 
        WHERE kullanici_email_adresi = :kullanici_email_adresi
		";

		$statement = $connect->prepare($query);

		$statement->execute($data);

		if($statement->rowCount() > 0)
		{
			$message = '<li>E-Posta Zaten Kayıtlı</li>';
		}
		else
		{
			$kullanici_dogrulama_kodu = md5(uniqid());

			$kullanici_unique_id = 'U' . rand(10000000,99999999);

			$data = array(
				':kullanici_adi'				=>	$formdata['kullanici_adi'],
				':kullanici_adresi'				=>	$formdata['kullanici_adresi'],
				':kullanici_iletisim_no'		=>	$formdata['kullanici_iletisim_no'],
				':kullanici_profili'			=>	$formdata['kullanici_profili'],
				':kullanici_email_adresi'		=>	$formdata['kullanici_email_adresi'],
				':kullanici_sifre'				=>	$formdata['kullanici_sifre'],
				':kullanici_dogrulama_kodu'		=>	$kullanici_dogrulama_kodu,
				':kullanici_dogrulama_durumu'	=>	'Hayır',
				':kullanici_unique_id'			=>	$kullanici_unique_id,
				':kullanici_durumu'				=>	'Enable',
				':kullanici_olusturuldu'		=>	get_date_time($connect)
			);

			$query = "
			INSERT INTO kullanici
            (kullanici_adi, kullanici_adresi, kullanici_iletisim_no, kullanici_profili, kullanici_email_adresi, kullanici_sifre, kullanici_dogrulama_kodu, kullanici_dogrulama_durumu, kullanici_unique_id, kullanici_durumu, kullanici_olusturuldu) 
            VALUES (:kullanici_adi, :kullanici_adresi, :kullanici_iletisim_no, :kullanici_profili, :kullanici_email_adresi, :kullanici_sifre, :kullanici_dogrulama_kodu, :kullanici_dogrulama_durumu, :kullanici_unique_id, :kullanici_durumu, :kullanici_olusturuldu)
			";

			$statement = $connect->prepare($query);

			$statement->execute($data);

			require 'vendor/autoload.php';

			$mail = new PHPMailer(true);

			$mail->isSMTP();

			$mail->Host = 'smtpout.secureserver.net';

			$mail->SMTPAuth = true;

			$mail->Username = 'xxxx';

			$mail->Password = 'xxxx';

			$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

			$mail->Port = 80;

			$mail->addAddress($formdata['kullanici_email_adresi'], $formdata['kullanici_adi']);

			$mail->isHTML(true);

			$mail->Subject = 'Kütüphane Yönetim Sistemi Kayıt Doğrulaması';

			$mail->Body = '
			 <p>Kütüphane Yönetim Sistemine Kayıt Olduğunuz İçin Teşekkür Ederiz,Kullanıcı Numaranız:<b>'.$kullanici_unique_id.'</b> kitap işlemlerinde kullanabilirsiniz.</p>

                <p>Bu bir doğrulama E-Postasıdır,lütfen doğrulama yapmak için bağlantıya tıklayınız.</p>
                <p><a href="'.base_url().'verify.php?code='.$kullanici_dogrulama_kodu.'">Doğrulamak İçin Tıklayın</a></p>
                <p>Teşekkür Ederiz</p>
			';

			$mail->send();

			$success = 'Doğrulama E-Postası Bu Adrese Yollandı' . $formdata['kullanici_email_adresi'] . ', giriş yapmadan önce doğrulama yapmalısınız.';
		}

	}
}

include 'header.php';

?>


<div class="d-flex align-items-center justify-content-center mt-5 mb-5" style="min-height:700px;">
	<div class="col-md-6">
		<?php 

		if($message != '')
		{
			echo '<div class="alert alert-danger"><ul>'.$message.'</ul></div>';
		}

		if($success != '')
		{
			echo '<div class="alert alert-success">'.$success.'</div>';
		}

		?>
		<div class="card">
			<div class="card-header">Yeni Kullanıcı Kaydı</div>
			<div class="card-body">
				<form method="POST" enctype="multipart/form-data">
					<div class="mb-3">
						<label class="form-label">E-Posta Adresi</label>
						<input type="text" name="kullanici_email_adresi" id="kullanici_email_adresi" class="form-control" />
					</div>
					<div class="mb-3">
						<label class="form-label">Şifre</label>
						<input type="password" name="kullanici_sifre" id="kullanici_sifre" class="form-control" />
					</div>
					<div class="mb-3">
						<label class="form-label">Kullanıcı Adı</label>
                        <input type="text" name="kullanici_adi" class="form-control" id="kullanici_adi" value="" />
                    </div>
					<div class="mb-3">
						<label class="form-label">Kullanıcı İletişim Numarası</label>
						<input type="text" name="kullanici_iletisim_no" id="kullanici_iletisim_no" class="form-control" />
					</div>
					<div class="mb-3">
						<label class="form-label">Kullanıcı Adresi</label>
						<textarea name="kullanici_adresi" id="kullanici_adresi" class="form-control"></textarea>
					</div>
					<div class="mb-3">
						<label class="form-label">Kullanıcı Fotoğrafı</label><br />
						<input type="file" name="kullanici_profili" id="kullanici_profili" />
						<br />
						<span class="text-muted">Fotoğraf 225x225 boyutunda olmalıdır.</span>
					</div>
					<div class="text-center mt-4 mb-2">
						<input type="submit" name="kayit_butonu" class="btn btn-primary" value="Kaydol" />
					</div>
				</form>
			</div>
		</div>
	</div>
</div>


<?php 


include 'footer.php';

?>
