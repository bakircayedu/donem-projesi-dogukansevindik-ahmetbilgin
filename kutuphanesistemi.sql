-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 05 Oca 2022, 14:05:40
-- Sunucu sürümü: 10.4.22-MariaDB
-- PHP Sürümü: 8.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `kutuphanesistemi`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `admin_email` varchar(256) COLLATE utf8mb4_turkish_ci NOT NULL,
  `admin_sifre` varchar(100) COLLATE utf8mb4_turkish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `admin`
--

INSERT INTO `admin` (`admin_id`, `admin_email`, `admin_sifre`) VALUES
(1, 'sametsevindik7@gmail.com', 'samet4470'),
(2, 'ahmetbilgin@gmail.com', 'ahmet2001');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `alinan_kitap`
--

CREATE TABLE `alinan_kitap` (
  `alinan_kitap_id` int(11) NOT NULL,
  `kitap_id` varchar(30) COLLATE utf8mb4_turkish_ci NOT NULL,
  `kullanici_id` varchar(30) COLLATE utf8mb4_turkish_ci NOT NULL,
  `alinma_tarihi` varchar(30) COLLATE utf8mb4_turkish_ci NOT NULL,
  `beklenen_iade_tarihi` varchar(30) COLLATE utf8mb4_turkish_ci NOT NULL,
  `iade_edilen_tarih` varchar(30) COLLATE utf8mb4_turkish_ci NOT NULL,
  `para_cezasi` varchar(30) COLLATE utf8mb4_turkish_ci NOT NULL,
  `alinan_kitap_durum` enum('Alındı','Geri Dondu','Geri Donmedi') COLLATE utf8mb4_turkish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `ayarlar`
--

CREATE TABLE `ayarlar` (
  `ayarlar_id` int(11) NOT NULL,
  `kutuphane_adi` varchar(200) COLLATE utf8mb4_turkish_ci NOT NULL,
  `kutuphane_adresi` text COLLATE utf8mb4_turkish_ci NOT NULL,
  `kutuphane_iletisim_numarasi` varchar(30) COLLATE utf8mb4_turkish_ci NOT NULL,
  `kutuphane_email_adresi` varchar(100) COLLATE utf8mb4_turkish_ci NOT NULL,
  `kutuphane_para_birimi` varchar(30) COLLATE utf8mb4_turkish_ci NOT NULL,
  `kutuphane_zaman_dilimi` varchar(100) COLLATE utf8mb4_turkish_ci NOT NULL,
  `kisi_basina_verilebilecek_kitap` int(3) NOT NULL,
  `kitap_iade_gun_limiti` int(5) NOT NULL,
  `kitap_gec_donus_gunluk_ceza` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `ayarlar`
--

INSERT INTO `ayarlar` (`ayarlar_id`, `kutuphane_adi`, `kutuphane_adresi`, `kutuphane_iletisim_numarasi`, `kutuphane_email_adresi`, `kutuphane_para_birimi`, `kutuphane_zaman_dilimi`, `kisi_basina_verilebilecek_kitap`, `kitap_iade_gun_limiti`, `kitap_gec_donus_gunluk_ceza`) VALUES
(1, 'Gebze Halk Kütüphanesi', 'Ulus Mahallesi,2156 sokak, No:7/5,GEBZE/KOCAELİ', '02323244551', 'gebzekutuphanesi@gmail.com', '', 'Europe/Istanbul', 3, 90, '4.00');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kategori`
--

CREATE TABLE `kategori` (
  `kategori_id` int(11) NOT NULL,
  `kategori_adi` varchar(256) COLLATE utf8mb4_turkish_ci NOT NULL,
  `kategori_durum` enum('Enable','Disable') COLLATE utf8mb4_turkish_ci NOT NULL,
  `kategori_olusturuldu` varchar(30) COLLATE utf8mb4_turkish_ci NOT NULL,
  `kategori_guncellendi` varchar(30) COLLATE utf8mb4_turkish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `kategori`
--

INSERT INTO `kategori` (`kategori_id`, `kategori_adi`, `kategori_durum`, `kategori_olusturuldu`, `kategori_guncellendi`) VALUES
(1, 'Anı Kitapları', 'Enable', '2022-01-04 17:07:35', '2022-01-05 14:11:46'),
(2, 'Roman', 'Enable', '2022-01-04 17:08:59', '2022-01-04 20:17:10'),
(3, 'Hikaye Kitabı', 'Enable', '2022-01-04 17:09:12', '2022-01-04 20:17:08');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kitap`
--

CREATE TABLE `kitap` (
  `kitap_id` int(11) NOT NULL,
  `kitap_kategori` varchar(256) COLLATE utf8mb4_turkish_ci NOT NULL,
  `kitap_yazar` varchar(256) COLLATE utf8mb4_turkish_ci NOT NULL,
  `kitap_adi` text COLLATE utf8mb4_turkish_ci NOT NULL,
  `kitap_isbn_numarasi` varchar(30) COLLATE utf8mb4_turkish_ci NOT NULL,
  `kitap_durum` enum('Enable','Disable') COLLATE utf8mb4_turkish_ci NOT NULL,
  `kitap_ekleme` varchar(30) COLLATE utf8mb4_turkish_ci NOT NULL,
  `kitap_guncelleme` varchar(30) COLLATE utf8mb4_turkish_ci NOT NULL,
  `kitap_no_kopyasi` int(5) NOT NULL,
  `kitap_rafi` varchar(100) COLLATE utf8mb4_turkish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `kitap`
--

INSERT INTO `kitap` (`kitap_id`, `kitap_kategori`, `kitap_yazar`, `kitap_adi`, `kitap_isbn_numarasi`, `kitap_durum`, `kitap_ekleme`, `kitap_guncelleme`, `kitap_no_kopyasi`, `kitap_rafi`) VALUES
(1, 'Roman', 'Fyodor Dostoyevski', 'Suç ve Ceza', '9783863523756', 'Enable', '2022-01-05 13:08:06', '', 8, 'A1');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `konum_rafi`
--

CREATE TABLE `konum_rafi` (
  `konum_rafi_id` int(11) NOT NULL,
  `konum_rafi_adi` varchar(256) COLLATE utf8mb4_turkish_ci NOT NULL,
  `konum_rafi_durum` enum('Enable','Disable') COLLATE utf8mb4_turkish_ci NOT NULL,
  `konum_rafi_olusturuldu` varchar(30) COLLATE utf8mb4_turkish_ci NOT NULL,
  `konum_rafi_guncellendi` varchar(30) COLLATE utf8mb4_turkish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `konum_rafi`
--

INSERT INTO `konum_rafi` (`konum_rafi_id`, `konum_rafi_adi`, `konum_rafi_durum`, `konum_rafi_olusturuldu`, `konum_rafi_guncellendi`) VALUES
(1, 'A1', 'Enable', '', '2022-01-05 10:04:56'),
(2, 'A2', 'Enable', '2022-01-05 13:25:33', ''),
(3, 'A3', 'Enable', '2022-01-05 13:25:48', ''),
(4, 'A4', 'Enable', '2022-01-05 13:25:53', ''),
(5, 'B1', 'Enable', '2022-01-05 13:25:57', ''),
(6, 'B2', 'Enable', '2022-01-05 13:26:05', ''),
(7, 'B3', 'Enable', '2022-01-05 13:26:11', ''),
(8, 'B4', 'Enable', '2022-01-05 13:26:16', '');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kullanici`
--

CREATE TABLE `kullanici` (
  `kullanici_id` int(11) NOT NULL,
  `kullanici_adi` varchar(200) COLLATE utf8mb4_turkish_ci NOT NULL,
  `kullanici_adresi` text COLLATE utf8mb4_turkish_ci NOT NULL,
  `kullanici_iletisim_no` varchar(30) COLLATE utf8mb4_turkish_ci NOT NULL,
  `kullanici_profili` varchar(100) COLLATE utf8mb4_turkish_ci NOT NULL,
  `kullanici_email_adresi` varchar(200) COLLATE utf8mb4_turkish_ci NOT NULL,
  `kullanici_sifre` varchar(30) COLLATE utf8mb4_turkish_ci NOT NULL,
  `kullanici_dogrulama_kodu` varchar(100) COLLATE utf8mb4_turkish_ci NOT NULL,
  `kullanici_dogrulama_durumu` enum('Evet','Hayır') COLLATE utf8mb4_turkish_ci NOT NULL,
  `kullanici_unique_id` varchar(30) COLLATE utf8mb4_turkish_ci NOT NULL,
  `kullanici_durumu` enum('Enable','Disable') COLLATE utf8mb4_turkish_ci NOT NULL,
  `kullanici_olusturuldu` varchar(30) COLLATE utf8mb4_turkish_ci NOT NULL,
  `kullanici_guncellendi` varchar(30) COLLATE utf8mb4_turkish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `kullanici`
--

INSERT INTO `kullanici` (`kullanici_id`, `kullanici_adi`, `kullanici_adresi`, `kullanici_iletisim_no`, `kullanici_profili`, `kullanici_email_adresi`, `kullanici_sifre`, `kullanici_dogrulama_kodu`, `kullanici_dogrulama_durumu`, `kullanici_unique_id`, `kullanici_durumu`, `kullanici_olusturuldu`, `kullanici_guncellendi`) VALUES
(1, 'DogukanS', 'Ulus Mahallesi,2150 Sokak,No:26/1,GEBZE/KOCAELİ', '05447442941', '1641318012-1199065696.jpg', 'samet_sevindik-2001@hotmail.com', 'samet44701907', 'fffe9541f8901c08658be4ba652cab10', 'Hayır', 'U59390267', 'Enable', '2022-01-04 18:40:12', ''),
(2, 'SalihS', 'Ulus Mahallesi,2150 sokak,No:26/1,GEBZE/KOCAELİ', '05458546457', '1641380581-1152484368.jpg', 'salihsevindik0@gmail.com', 'patate1992', 'ef236e1b21bbecf668fe29a2336e3e1d', 'Hayır', 'U67341944', 'Enable', '2022-01-05 14:03:01', '');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `yazar`
--

CREATE TABLE `yazar` (
  `yazar_id` int(11) NOT NULL,
  `yazar_adi` varchar(256) COLLATE utf8mb4_turkish_ci NOT NULL,
  `yazar_durumu` enum('Enable','Disable') COLLATE utf8mb4_turkish_ci NOT NULL,
  `yazar_olusturma_tarihi` varchar(50) COLLATE utf8mb4_turkish_ci NOT NULL,
  `yazar_guncelleme_tarihi` varchar(50) COLLATE utf8mb4_turkish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `yazar`
--

INSERT INTO `yazar` (`yazar_id`, `yazar_adi`, `yazar_durumu`, `yazar_olusturma_tarihi`, `yazar_guncelleme_tarihi`) VALUES
(1, 'Fyodor Dostoyevski', 'Enable', '2022-01-04 19:29:56', ''),
(2, 'Victor Hugo', 'Enable', '2022-01-04 19:31:18', '');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Tablo için indeksler `ayarlar`
--
ALTER TABLE `ayarlar`
  ADD PRIMARY KEY (`ayarlar_id`);

--
-- Tablo için indeksler `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`kategori_id`);

--
-- Tablo için indeksler `kitap`
--
ALTER TABLE `kitap`
  ADD PRIMARY KEY (`kitap_id`);

--
-- Tablo için indeksler `konum_rafi`
--
ALTER TABLE `konum_rafi`
  ADD PRIMARY KEY (`konum_rafi_id`);

--
-- Tablo için indeksler `kullanici`
--
ALTER TABLE `kullanici`
  ADD PRIMARY KEY (`kullanici_id`);

--
-- Tablo için indeksler `yazar`
--
ALTER TABLE `yazar`
  ADD PRIMARY KEY (`yazar_id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Tablo için AUTO_INCREMENT değeri `ayarlar`
--
ALTER TABLE `ayarlar`
  MODIFY `ayarlar_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `kategori`
--
ALTER TABLE `kategori`
  MODIFY `kategori_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Tablo için AUTO_INCREMENT değeri `kitap`
--
ALTER TABLE `kitap`
  MODIFY `kitap_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `konum_rafi`
--
ALTER TABLE `konum_rafi`
  MODIFY `konum_rafi_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Tablo için AUTO_INCREMENT değeri `kullanici`
--
ALTER TABLE `kullanici`
  MODIFY `kullanici_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Tablo için AUTO_INCREMENT değeri `yazar`
--
ALTER TABLE `yazar`
  MODIFY `yazar_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
