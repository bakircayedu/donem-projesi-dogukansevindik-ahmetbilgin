
<?php

//Admin Çıkış İşlemi

session_start();

session_destroy();

header('location:admingiris.php');

?>