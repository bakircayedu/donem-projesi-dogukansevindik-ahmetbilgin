<?php 

//kullanıcı cıkışının yapıldığı kısım
session_start();

session_destroy();

header('location:kullanici_giris.php');
?>
