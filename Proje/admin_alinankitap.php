<?php

include 'baglanti.php';

include 'function.php';

if(!admin_girisimi())
{
	header('location:admingiris.php');
}

$error = '';

if(isset($_POST["kitap_verme_butonu"]))
{
    $formdata = array();

    if(empty($_POST["kitap_id"]))
    {
        $error .= '<li>ISBN Numarası Gereklidir</li>';
    }
    else
    {
        $formdata['kitap_id'] = trim($_POST['kitap_id']);
    }

    if(empty($_POST["kullanici_id"]))
    {
        $error .= '<li>Kullanıcı Unique Numarası Gereklidir</li>';
    }
    else
    {
        $formdata['kullanici_id'] = trim($_POST['kullanici_id']);
    }

    if($error == '')
    {
        

        $query = "
        SELECT * FROM kitap 
        WHERE kitap_isbn_numarasi = '".$formdata['kitap_id']."'
        ";

        $statement = $connect->prepare($query);

        $statement->execute();

        if($statement->rowCount() > 0)
        {
            foreach($statement->fetchAll() as $kitap_row)
            {
                
                if($kitap_row['kitap_durum'] == 'Enable' && $kitap_row['kitap_no_kopyasi'] > 0)
                {
                    

                    $query = "
                    SELECT kullanici_id, kullanici_durumu FROM kullanici 
                    WHERE kullanici_unique_id = '".$formdata['kullanici_id']."'
                    ";

                    $statement = $connect->prepare($query);

                    $statement->execute();

                    if($statement->rowCount() > 0)
                    {
                        foreach($statement->fetchAll() as $kullanici_row)
                        {
                            if($kullanici_row['kullanici_durumu'] == 'Enable')
                            {
                                

                                $kitap_verme_limiti = get_kisi_basina_verilebilecek_kitap($connect);

                                $toplam_verilen_kitap = get_kisi_basina_verilen_kitap_toplami($connect, $formdata['kullanici_id']);

                                if($toplam_verilen_kitap < $kitap_verme_limiti)
                                {
                                    $iade_gun_limiti_toplamı = get_kitap_iade_gun_limiti($connect);

                                    $bugunun_tarihi = get_date_time($connect);

                                    $beklenen_iade_tarihi = date('Y-m-d H:i:s', strtotime($bugunun_tarihi. ' + '.$iade_gun_limiti_toplamı.' days'));

                                    $data = array(
                                        ':kitap_id'      			=>  $formdata['kitap_id'],
                                        ':kullanici_id'      		=>  $formdata['kullanici_id'],
                                        ':alinma_tarihi'  			=>  $bugunun_tarihi,
                                        ':beklenen_iade_tarihi' 	=> 	$beklenen_iade_tarihi,
                                        ':iade_edilen_tarih' 		=>  '',
                                        ':para_cezasi'       		=>  0,
                                        ':alinan_kitap_durum'    	=>  'Alındı'
                                    );

                                    $query = "
                                    INSERT INTO alinan_kitap
                                    (kitap_id, kullanici_id, alinma_tarihi, beklenen_iade_tarihi, iade_edilen_tarih, para_cezasi, alinan_kitap_durum) 
                                    VALUES (:kitap_id, :kullanici_id, :alinma_tarihi, :beklenen_iade_tarihi, :iade_edilen_tarih, :para_cezasi, :alinan_kitap_durum)
                                    ";

                                    $statement = $connect->prepare($query);

                                    $statement->execute($data);

                                    $query = "
                                    UPDATE kitap 
                                    SET kitap_no_kopyasi = kitap_no_kopyasi - 1, 
                                    kitap_guncelleme = '".$bugunun_tarihi."' 
                                    WHERE kitap_isbn_numarasi = '".$formdata['kitap_id']."' 
                                    ";

                                    $connect->query($query);

                                    header('location:admin_alinankitap.php?msg=add');
                                }
                                else
                                {
                                    $error .= 'Kullanıcı zaten alabileceği kitap limitine ulaşmıştır,kitap alabilmek için öncelikle aldığı kitaplardan birini geri vermelidir.';
                                }
                            }
                            else
                            {
                                $error .= '<li>Kullanıcı hesabı devre dışıdır,Lütfen adminlerle temasa geçiniz.</li>';
                            }
                        }
                    }
                    else
                    {
                        $error .= '<li>Kullanıcı bulunamadı</li>';
                    }
                }
                else
                {
                    $error .= '<li>Kitap mevcut değil</li>';
                }
            }
        }
        else
        {
            $error .= '<li>Kitap bulunamadı</li>';
        }
    }
}

if(isset($_POST["kitap_iade_butonu"]))
{
    if(isset($_POST["kitap_iade_onayi"]))
    {
        $data = array(
            ':iade_edilen_tarih'      =>   get_date_time($connect),
            ':alinan_kitap_durum'     =>   'Geri Dondu',
            ':alinan_kitap_id'        =>   $_POST['alinan_kitap_id']
        );  

        $query = "
        UPDATE alinan_kitap 
        SET iade_edilen_tarih = :iade_edilen_tarih, 
        alinan_kitap_durum = :alinan_kitap_durum 
        WHERE alinan_kitap_id = :alinan_kitap_id
        ";

        $statement = $connect->prepare($query);

        $statement->execute($data);

		$query = "
        UPDATE kitap 
        SET kitap_no_kopyasi = kitap_no_kopyasi + 1 
        WHERE kitap_isbn_numarasi = '".$_POST["kitap_isbn_numarasi"]."'
        ";

        $connect->query($query);

        header("location:admin_alinankitap.php?msg=return");
    }
    else
    {
        $error = 'Lütfen önce onay kutusuna basarak alınan kitabın iadesini onaylayınız.';
    }
}   

$query = "
	SELECT * FROM alinan_kitap
    ORDER BY alinan_kitap_id DESC
";

$statement = $connect->prepare($query);

$statement->execute();

include 'header.php';

?>
<div class="container-fluid py-4" style="min-height: 700px;">
	<h1>Alınan Kitap Yönetimi</h1>
	<?php 

	if(isset($_GET["action"]))
    {
        if($_GET["action"] == 'add')
	    {
	?>
    <ol class="breadcrumb mt-4 mb-4 bg-light p-2 border">
        <li class="breadcrumb-item"><a href="admin_index.php">Panel</a></li>
        <li class="breadcrumb-item"><a href="admin_alinankitap.php">Alınan Kitap Yönetimi</a></li>
        <li class="breadcrumb-item active">Yeni Kitap Ver</li>
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
                    <i class="fas fa-user-plus"></i> Yeni Kitap Ver
                </div>
                <div class="card-body">
                    <form method="post">
                        <div class="mb-3">
                            <label class="form-label">Kitap ISBN No</label>
                            <input type="text" name="kitap_id" id="kitap_id" class="form-control" />
                            <span id="kitap_isbn_result"></span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kullanıcı Eşsiz Id</label>
                            <input type="text" name="kullanici_id" id="kullanici_id" class="form-control" />
                            <span id="kullanici_unique_id_result"></span>
                        </div>
                        <div class="mt-4 mb-0">
                            <input type="submit" name="kitap_verme_butonu" class="btn btn-success" value="Ver" />
                        </div>  
                    </form>
                    <script>
                    var kitap_id = document.getElementById('kitap_id');

                    kitap_id.onkeyup = function()
                    {
                        if(this.value.length > 2)
                        {
                            var form_data = new FormData();

                            form_data.append('action', 'kitap_isbn_ara');

                            form_data.append('request', this.value);

                            fetch('admin_eylem.php', {
                                method:"POST",
                                body:form_data
                            }).then(function(response){
                                return response.json();
                            }).then(function(responseData){
                                var html = '<div class="list-group" style="position:absolute; width:95%">';

                                if(responseData.length > 0)
                                {
                                    for(var count = 0; count < responseData.length; count++)
                                    {
                                        html += '<a href="#" class="list-group-item list-group-item-action"><span onclick="get_text(this)">'+responseData[count].isbn_no+'</span> - <span class="text-muted">'+responseData[count].kitap_adi+'</span></a>';
                                    }
                                }
                                else
                                {
                                    html += '<a href="#" class="list-group-item list-group-item-action">Kitap Bulunamadı</a>';
                                }

                                html += '</div>';

                                document.getElementById('kitap_isbn_result').innerHTML = html;
                            });
                        }
                        else
                        {
                            document.getElementById('kitap_isbn_result').innerHTML = '';
                        }
                    }

                    function get_text(event)
                    {
                        document.getElementById('kitap_isbn_result').innerHTML = '';

                        document.getElementById('kitap_id').value = event.textContent;
                    }

                    var kullanici_id = document.getElementById('kullanici_id');

                    kullanici_id.onkeyup = function(){
                        if(this.value.length > 2)
                        {   
                            var form_data = new FormData();

                            form_data.append('action', 'kullanici_id_ara');

                            form_data.append('request', this.value);

                            fetch('admin_eylem.php', {
                                method:"POST",
                                body:form_data
                            }).then(function(response){
                                return response.json();
                            }).then(function(responseData){
                                var html = '<div class="list-group" style="position:absolute;width:93%">';

                                if(responseData.length > 0)
                                {
                                    for(var count = 0; count < responseData.length; count++)
                                    {
                                        html += '<a href="#" class="list-group-item list-group-item-action"><span onclick="get_text1(this)">'+responseData[count].kullanici_unique_id+'</span> - <span class="text-muted">'+responseData[count].kullanici_adi+'</span></a>';
                                    }
                                }
                                else
                                {
                                    html += '<a href="#" class="list-group-item list-group-item-action">Kullanıcı Bulunamadı</a>';
                                }
                                html += '</div>';

                                document.getElementById('kullanici_unique_id_result').innerHTML = html;
                            });
                        }
                        else
                        {
                            document.getElementById('kullanici_unique_id_result').innerHTML = '';
                        }
                    }

                    function get_text1(event)
                    {
                        document.getElementById('kullanici_unique_id_result').innerHTML = '';

                        document.getElementById('kullanici_id').value = event.textContent;
                    }

                    </script>
                </div>
            </div>
        </div>
    </div>
    <?php 
        }
        else if($_GET["action"] == 'view')
        {
            $alinan_kitap_id = verileri_donustur($_GET["code"], 'decrypt');

            if($alinan_kitap_id > 0)
            {
                $query = "
                SELECT * FROM alinan_kitap 
                WHERE alinan_kitap_id = '$alinan_kitap_id'
                ";

                $result = $connect->query($query);

                foreach($result as $row)
                {
                    $query = "
                    SELECT * FROM kitap
                    WHERE kitap_isbn_numarasi = '".$row["kitap_id"]."'
                    ";

                    $kitap_result = $connect->query($query);

                    $query = "
                    SELECT * FROM kullanici 
                    WHERE kullanici_unique_id = '".$row["kullanici_id"]."'
                    ";

                    $kullanici_result = $connect->query($query);

                    echo '
                    <ol class="breadcrumb mt-4 mb-4 bg-light p-2 border">
                        <li class="breadcrumb-item"><a href="admin_index.php">Panel</a></li>
                        <li class="breadcrumb-item"><a href="admin_alinankitap.php">Alınan Kitap Yönetimi</a></li>
                        <li class="breadcrumb-item active">Alınan Kitap Ayrıntılarını Görüntüle</li>
                    </ol>
                    ';

                    if($error != '')
                    {
                        echo '<div class="alert alert-danger">'.$error.'</div>';
                    }

                    foreach($kitap_result as $kitap_data)
                    {
                        echo '
                        <h2>Kitap Detayları</h2>
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">Kitap ISBN No</th>
                                <td width="70%">'.$kitap_data["kitap_isbn_numarasi"].'</td>
                            </tr>
                            <tr>
                                <th width="30%">Kitap Başlığı</th>
                                <td width="70%">'.$kitap_data["kitap_adi"].'</td>
                            </tr>
                            <tr>
                                <th width="30%">Yazar</th>
                                <td width="70%">'.$kitap_data["kitap_yazar"].'</td>
                            </tr>
                        </table>
                        <br />
                        ';
                    }

                    foreach($kullanici_result as $kullanici_data)
                    {
                        echo '
                        <h2>Kullanıcı Detayları</h2>
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">Kullanıcı Unique Id</th>
                                <td width="70%">'.$kullanici_data["kullanici_unique_id"].'</td>
                            </tr>
                            <tr>
                                <th width="30%">Kullanıcı Adı</th>
                                <td width="70%">'.$kullanici_data["kullanici_adi"].'</td>
                            </tr>
                            <tr>
                                <th width="30%">Kullanıcı Adresi</th>
                                <td width="70%">'.$kullanici_data["kullanici_adresi"].'</td>
                            </tr>
                            <tr>
                                <th width="30%">Kullanıcı İletişim No</th>
                                <td width="70%">'.$kullanici_data["kullanici_iletisim_no"].'</td>
                            </tr>
                            <tr>
                                <th width="30%">Kullanıcı E-Posta Adresi</th>
                                <td width="70%">'.$kullanici_data["kullanici_email_adresi"].'</td>
                            </tr>
                            <tr>
                                <th width="30%">Kullanıcı Fotoğrafı</th>
                                <td width="70%"><img src="'.base_url().'upload/' . $kullanici_data["kullanici_profili"].'" class="img-thumbnail" width="100" /></td>
                            </tr>
                        </table>
                        <br />
                        ';
                    }

                    $durum = $row["alinan_kitap_durum"];

                    $form_item = '';

                    if($durum == "Alındı")
                    {
                        $durum = '<span class="badge bg-warning">Alındı</span>';

                        $form_item = '
                        <label><input type="checkbox" name="kitap_iade_onayi" value="Yes" />Bu kütüphaneden kitap aldığımı kabul ediyorum.</label>
                        <br />
                        <div class="mt-4 mb-4">
                            <input type="submit" name="kitap_iade_butonu" value="Kitap İadesi" class="btn btn-primary" />
                        </div>
                        ';
                    }

                    if($durum == 'Geri Donmedi')
                    {
                        $durum = '<span class="badge bg-danger">Geri Donmedi</span>';

                        $form_item = '
                        <label><input type="checkbox" name="kitap_iade_onayi" value="Yes" />Bu kütüphaneden kitap aldığımı kabul ediyorum.</label><br />
                        <div class="mt-4 mb-4">
                            <input type="submit" name="kitap_iade_butonu" value="Kitap İadesi" class="btn btn-primary" />
                        </div>
                        ';
                    }

                    if($durum == 'Geri Dondu')
                    {
                        $durum = '<span class="badge bg-primary">Geri Dondu</span>';
                    }

                    echo '
                    <h2>Alınan Kitap Detayı</h2>
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">Kitap Verme Tarihi</th>
                            <td width="70%">'.$row["alinma_tarihi"].'</td>
                        </tr>
                        <tr>
                            <th width="30%">İade Edilen Tarih</th>
                            <td width="70%">'.$row["iade_edilen_tarih"].'</td>
                        </tr>
                        <tr>
                            <th width="30%">Kitap Verme Durumu</th>
                            <td width="70%">'.$durum.'</td>
                        </tr>
                        <tr>
                            <th width="30%">Toplam Ceza</th>
                            <td width="70%">'.get_parabirimi_sembol($connect).' '.$row["para_cezasi"].'</td>
                        </tr>
                    </table>
                    <form method="POST">
                        <input type="hidden" name="alinan_kitap_id" value="'.$alinan_kitap_id.'" />
                        <input type="hidden" name="kitap_isbn_numarasi" value="'.$row["kitap_id"].'" />
                        '.$form_item.'
                    </form>
                    <br />
                    ';

                }
            }
        }
    }
    else
    {
    ?>
	<ol class="breadcrumb mt-4 mb-4 bg-light p-2 border">
		<li class="breadcrumb-item"><a href="admin_index.php">Panel</a></li>
        <li class="breadcrumb-item active">Alınan Kitap Yönetimi</li>
    </ol>

    <?php 
    if(isset($_GET['msg']))
    {
        if($_GET['msg'] == 'add')
        {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Yeni Kitap Verme İşlemi Başarıyla Tamamlandı<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        }

        if($_GET["msg"] == 'return')
        {
            echo '
            <div class="alert alert-success alert-dismissible fade show" role="alert">Verilen Kitap Başarıyla Kütüphaneye İade Edildi<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
            ';
        }
    }
    ?>

    <div class="card mb-4">
    	<div class="card-header">
    		<div class="row">
    			<div class="col col-md-6">
    				<i class="fas fa-table me-1"></i> Alınan Kitap Yönetimi
                </div>
                <div class="col col-md-6" align="right">
                    <a href="admin_alinankitap.php?action=add" class="btn btn-success btn-lg">Ekle</a>
                </div>
            </div>
        </div>
        <div class="card-body">
        	<table id="datatablesSimple">
        		<thead>
        			<tr>
        				<th>Kitap ISBN No</th>
                        <th>Kullanıcı Unique Id</th>
                        <th>Verildiği Tarih</th>
                        <th>İade Tarihi</th>
                        <th>Geç İade Cezaları</th>
                        <th>Durum</th>
                        <th>Eylem</th>
        			</tr>
        		</thead>
        		<tfoot>
        			<tr>
						<th>Kitap ISBN No</th>
						<th>Kullanıcı Unique Id</th>
                        <th>Verildiği Tarih</th>
                        <th>İade Tarihi</th>
                        <th>Geç İade Cezaları</th>
                        <th>Durum</th>
                        <th>Eylem</th>
        			</tr>
        		</tfoot>
        		<tbody>
        		<?php
        		if($statement->rowCount() > 0)
        		{
        			$gunluk_ceza = gec_donus_gunluk_ceza($connect);

        			$parabirimi_sembol = get_parabirimi_sembol($connect);

        			zamandilimini_ayarla($connect);

        			foreach($statement->fetchAll() as $row)
        			{
        				$durum = $row["alinan_kitap_durum"];

        				$para_cezasi = $row["para_cezasi"];

        				if($row["alinan_kitap_durum"] == "Alındı")
        				{
        					$current_date_time = new DateTime(get_date_time($connect));
        					$beklenen_iade_tarihi = new DateTime($row["beklenen_iade_tarihi"]);

        					if($current_date_time > $beklenen_iade_tarihi)
        					{
        						$interval = $current_date_time->diff($beklenen_iade_tarihi);

        						$toplam_gun = $interval->d;

        						$para_cezasi = $toplam_gun * $gunluk_ceza;

        						$durum = 'Geri Donmedi';

        						$query = "
        						UPDATE alinan_kitap 
									SET para_cezasi = '".$para_cezasi."', 
									alinan_kitap_durum = '".$durum."' 
									WHERE alinan_kitap_id = '".$row["alinan_kitap_id"]."'
        						";

        						$connect->query($query);
        					}
        				}

        				if($durum == 'Alındı')
        				{
        					$durum = '<span class="badge bg-warning">Verildi</span>';
        				}

        				if($durum == 'Geri Donmedi')
        				{
        					$durum = '<span class="badge bg-danger">Geri Dönmedi</span>';
        				}

        				if($durum == 'Geri Dondu')
        				{
        					$durum = '<span class="badge bg-primary">İade Edildi</span>';
        				}

        				echo '
        				<tr>
        					<td>'.$row["kitap_id"].'</td>
        					<td>'.$row["kullanici_id"].'</td>
        					<td>'.$row["alinma_tarihi"].'</td>
        					<td>'.$row["iade_edilen_tarih"].'</td>
        					<td>'.$parabirimi_sembol.$para_cezasi.'</td>
        					<td>'.$durum.'</td>
        					<td>
                                <a href="admin_alinankitap.php?action=view&code='.verileri_donustur($row["alinan_kitap_id"]).'" class="btn btn-info btn-sm">Görünüm</a>
                            </td>
        				</tr>
        				';
        			}
        		}
        		else
        		{
        			echo '
        			<tr>
        				<td colspan="7" class="text-center">Veri Bulunamadı</td>
        			</tr>
        			';
        		}
        		?>
        		</tbody>
        	</table>
        </div>
    </div>
    <?php 
	}
    ?>
</div>

<?php 
	include 'footer.php';
?>