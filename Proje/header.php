
<?php

?>

<!doctype html>
<html lang="tr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name="generator" content="">
        <title>Kütüphane Sistemi</title>
        <link rel="canonical" href="">
        <link href="<?php echo base_url(); ?>css/simple-datatables-style.css" rel="stylesheet" />
        <link href="<?php echo base_url(); ?>css/styles.css" rel="stylesheet" />
        <script src="<?php echo base_url(); ?>js/font-awesome-5-all.min.js" crossorigin="anonymous"></script>
        <link rel="apple-touch-icon" href="" sizes="180x180">
        <link rel="icon" href="" sizes="32x32" type="image/png">
        <link rel="icon" href="" sizes="16x16" type="image/png">
        <link rel="manifest" href="">
        <link rel="mask-icon" href="" color="#052D92">
        <link rel="icon" href="">
        <meta name="theme-color" content="#052D92">
        <style>
            .bd-placeholder-img {
                font-size: 1.125rem;
                text-anchor: middle;
                -webkit-user-select: none;
                -moz-user-select: none;
                user-select: none;
            }
            @media (min-width: 768px) {
                .bd-placeholder-img-lg {
                    font-size: 3.5rem;
                }
            }
        </style>
    </head>

    <?php 

    if(admin_girisimi())
    {

    ?>
    <body class="sb-nav-fixed">

            <nav class="sb-topnav navbar navbar-expand navbar-light bg-" style="background-color: #D67734;">
            <a class="navbar-brand ps-3" href="admin_index.php">Kütüphane Sistemi</a>
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
            <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
                
            </form>
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="admin_profil.php">Profil</a></li>
                        <li><a class="dropdown-item" href="admin_ayarlar.php">Ayarlar</a></li>
                        <li><a class="dropdown-item" href="admin_cikis.php">Çıkış Yap</a></li>
                    </ul>
                </li>
            </ul>
        </nav>

        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-light" id="sidenavAccordion" style="background-color: #D67734;" >
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <a class="nav-link" href="admin_profil.php">Profil</a>
                            <a class="nav-link" href="admin_kategori.php">Kategori</a>
                            <a class="nav-link" href="admin_yazar.php">Yazar</a>
                            <a class="nav-link" href="admin_konumrafi.php">Konum Rafı</a>
                            <a class="nav-link" href="admin_kitap.php">Kitap</a>
                            <a class="nav-link" href="kullanici.php">Kullanıcı</a>
                            <a class="nav-link" href="admin_alinankitap.php">Alınan Kitap</a>
                            <a class="nav-link" href="admin_ayarlar.php">Ayarlar</a>
                            <a class="nav-link" href="admin_cikis.php">Çıkış</a>

                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                       
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
               

    <?php 
    }
    else
    {

    ?>

    <body>

    	<main>

    		<div class="container py-4">

    			<header class="pb-3 mb-4 border-bottom">
                    <div class="row">
        				<div class="col-md-6">
                            <a href="index.php" class="d-flex align-items-center text-warning text-decoration-none">
                                <span class="fs-4">Kütüphane Yönetim Sistemi</span>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <?php 

                            if(kullanici_girisimi())
                            {
                            ?>
                            <ul class="list-inline mt-4 float-end">
                                <li class="list-inline-item">Kullanıcı Unique Id:<?php echo $_SESSION['kullanici_id']; ?></li>
                                <li class="list-inline-item"><a href="alinan_kitapdetay.php">Alınan Kitap</a></li>
                                <li class="list-inline-item"><a href="kitap_ara.php">Kitap Arama</a></li>
                                <li class="list-inline-item"><a href="profil.php">Profil</a></li>
                                <li class="list-inline-item"><a href="cikis.php">Çıkış</a></li>
                            </ul>
                            <?php 
                            }

                            ?>
                        </div>
                    </div>

    			</header>
    <?php 
    }
    ?>