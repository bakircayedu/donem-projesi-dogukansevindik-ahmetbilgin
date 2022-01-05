<?php

//veritabanı bağlantımızı yaptığımız kısım kutuphanesistemi adındaki veritabanımıza baglantıyı yapıyoruz

try {
$connect = new PDO("mysql:host=localhost; dbname=kutuphanesistemi", "root", "");
//echo "Veritabanina Baglandik";
} catch (Exception $e) {
    $e->getMessage();
}

session_start();

?>