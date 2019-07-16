-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 16, 2019 at 02:35 PM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dblearning`
--

-- --------------------------------------------------------

--
-- Table structure for table `bank_soal`
--

CREATE TABLE `bank_soal` (
  `id` int(11) NOT NULL,
  `pengajar_id` int(11) NOT NULL,
  `mapel_id` int(11) DEFAULT NULL,
  `pertanyaan` text NOT NULL,
  `pilihan_a` text,
  `pilihan_b` text,
  `pilihan_c` text,
  `pilihan_d` text,
  `pilihan_e` text,
  `kunci` char(1) DEFAULT NULL COMMENT 'a,b,c,d,e'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `bank_soal`
--

INSERT INTO `bank_soal` (`id`, `pengajar_id`, `mapel_id`, `pertanyaan`, `pilihan_a`, `pilihan_b`, `pilihan_c`, `pilihan_d`, `pilihan_e`, `kunci`) VALUES
(1, 1, 6, '<p>Apaan sih?</p>\r\n', '<p>Gak apa-apa</p>\r\n', '<p>ya&nbsp; gitu</p>\r\n', '<p>ntah</p>\r\n', '<p>hemm</p>\r\n', '<p>kepo</p>\r\n', 'a'),
(2, 1, 6, '<p>apa hayo?</p>\r\n', NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `field_tambahan`
--

CREATE TABLE `field_tambahan` (
  `id` varchar(255) NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `value` longtext
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `field_tambahan`
--

INSERT INTO `field_tambahan` (`id`, `nama`, `value`) VALUES
('check-urgent-info', 'Check Urgent Info', '{\"info\":false,\"last_check\":\"2019-07-09 19:48:28\"}'),
('sukses-create-table-tugas-kelompok', 'sukses create table tugas kelompok', '1'),
('pengaturan-bank-soal', 'Pengaturan plugin bank_soal', '{\"tampil_diadmin\":\"1\",\"tampil_dipengajar\":\"2\"}'),
('link_terkait', 'Daftar Link Terkait', '[{\"link\":\"https:\\/\\/belajarpsikologi.com\\/cara-belajar-yang-baik\\/\",\"label\":\"TIPS BELAJAR\"},{\"link\":\"https:\\/\\/www.zenius.net\\/blog\\/11014\\/tips-strategi-jawab-soal-ujian\",\"label\":\"TIPS UJIAN\"}]'),
('ct-cron-register_action_terbitkan', 'register_action_terbitkan', '[]'),
('ct-cron-register_action_tutup', 'register_action_tutup', '[]'),
('pengaturan-tugas-1', 'Pengaturan tambahan tugas', '{\"max_jml_soal\":\"20\",\"model_urutan_soal\":\"1\",\"tampil_soal_perhalaman\":\"5\",\"tampil_nilai_kesiswa\":\"2\",\"terbitkan_pada\":\"2019-07-09 08:45\",\"tutup_pada\":\"2019-07-10 07:35\",\"model_urutan_pilihan\":\"2\"}'),
('laporkan-komentar', 'Laporan Komentar', '{\"5d24655aed39e1562666330\":{\"materi_id\":\"4\",\"komentar_id\":\"2\",\"alasan\":\"gak\",\"login_id\":\"1\",\"tgl_lapor\":\"2019-07-09 16:58:50\",\"view_admin\":1}}'),
('pengaturan-tugas-2', 'Pengaturan Tambahan Tugas', '{\"max_jml_soal\":\"2\",\"model_urutan_soal\":\"1\",\"tampil_soal_perhalaman\":\"0\",\"tampil_nilai_kesiswa\":\"2\",\"terbitkan_pada\":\"2019-07-10 20:45\",\"tutup_pada\":\"2019-07-11 21:00\",\"model_urutan_pilihan\":\"2\"}'),
('pengaturan-tugas-3', 'Pengaturan Tambahan Tugas', '{\"max_jml_soal\":\"2\",\"model_urutan_soal\":\"2\",\"tampil_soal_perhalaman\":\"0\",\"tampil_nilai_kesiswa\":\"2\",\"terbitkan_pada\":\"2019-07-10 21:00\",\"tutup_pada\":\"2019-07-11 21:00\"}'),
('pengaturan-tugas-4', 'Pengaturan Tambahan Tugas', '{\"tampil_nilai_kesiswa\":\"2\"}'),
('history-mengerjakan-3-2', 'History pengerjaan tugas', '{\"mulai\":\"2019-07-10 21:38:20\",\"selesai\":\"2019-07-10 23:38:20\",\"uri_string\":\"plugins\\/custom_tugas\\/kerjakan\\/2\",\"valid_route\":[\"\\/plugins\\/custom_tugas\\/kerjakan\",\"\\/tugas\\/finish\",\"\\/tugas\\/submit_essay\",\"\\/tugas\\/submit_upload\",\"\\/plugins\\/custom_tugas\\/finish\",\"\\/plugins\\/custom_tugas\\/submit_essay\"],\"tugas\":{\"id\":\"2\",\"mapel_id\":\"6\",\"pengajar_id\":\"1\",\"type_id\":\"3\",\"judul\":\"Ulangan Harian ke-1\",\"durasi\":\"120\",\"info\":\"<p>Ulangan Harian ke-1<\\/p>\\r\\n\",\"aktif\":\"1\",\"tgl_buat\":\"2019-07-10 20:43:55\",\"tampil_siswa\":\"1\",\"max_jml_soal\":\"2\",\"model_urutan_soal\":\"1\",\"tampil_soal_perhalaman\":\"0\",\"tampil_nilai_kesiswa\":\"2\",\"terbitkan_pada\":\"2019-07-10 20:45\",\"tutup_pada\":\"2019-07-11 21:00\",\"model_urutan_pilihan\":\"2\"},\"unix_id\":\"bfb0f813ef2c997245f7651a0cd45fba133705\",\"ip\":\"::1\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/75.0.3770.100 Safari\\/537.36\",\"pertanyaan_id\":{\"1\":\"1\",\"2\":\"2\"},\"1\":[\"1\",\"2\",\"3\",\"4\",\"5\"],\"2\":[\"6\",\"7\",\"8\",\"9\",\"10\"],\"jawaban\":{\"1\":\"1\",\"2\":\"8\"},\"nilai\":100,\"jml_benar\":2,\"jml_salah\":0,\"tgl_submit\":\"2019-07-10 21:39:07\",\"total_waktu\":\"47 detik\"}');

-- --------------------------------------------------------

--
-- Table structure for table `kd_mapel`
--

CREATE TABLE `kd_mapel` (
  `id` int(11) NOT NULL,
  `mapel_id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kd_mapel`
--

INSERT INTO `kd_mapel` (`id`, `mapel_id`, `nama`, `aktif`) VALUES
(1, 6, 'Coba', 1);

-- --------------------------------------------------------

--
-- Table structure for table `kd_tugas`
--

CREATE TABLE `kd_tugas` (
  `id` int(11) NOT NULL,
  `tugas_id` int(11) NOT NULL,
  `nilai_lulus` decimal(5,2) NOT NULL DEFAULT '0.00'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kd_tugas`
--

INSERT INTO `kd_tugas` (`id`, `tugas_id`, `nilai_lulus`) VALUES
(2, 2, '70.00');

-- --------------------------------------------------------

--
-- Table structure for table `kd_tugas_kd`
--

CREATE TABLE `kd_tugas_kd` (
  `id` int(11) NOT NULL,
  `kd_tugas_id` int(11) NOT NULL,
  `kd_mapel_id` int(11) NOT NULL,
  `no_soal` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kd_tugas_kd`
--

INSERT INTO `kd_tugas_kd` (`id`, `kd_tugas_id`, `kd_mapel_id`, `no_soal`) VALUES
(2, 2, 1, '{\"pertanyaan_id\":[\"1\",\"2\"]}');

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id` int(11) NOT NULL,
  `nama` varchar(45) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `urutan` int(11) NOT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=aktif 0=tidak'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`id`, `nama`, `parent_id`, `urutan`, `aktif`) VALUES
(1, 'KELAS X', NULL, 1, 1),
(2, 'KELAS X A', 1, 2, 1),
(3, 'KELAS X B', 1, 3, 1),
(4, 'KELAS X C', 1, 4, 1),
(5, 'KELAS X D', 1, 5, 1),
(6, 'KELAS XI IPA', NULL, 6, 1),
(7, 'KELAS XI IPA 1', 6, 7, 1),
(8, 'KELAS XI IPA 2', 6, 8, 1),
(9, 'KELAS XI IPS 1', 16, 10, 1),
(10, 'KELAS XI IPS 2', 16, 11, 1),
(11, 'KELAS XII IPA', NULL, 12, 1),
(12, 'KELAS XII IPA 1', 11, 13, 1),
(13, 'KELAS XII IPA 2', 11, 14, 1),
(14, 'KELAS XII IPS 1', 17, 16, 1),
(15, 'KELAS XII IPS 2', 17, 17, 1),
(16, 'KELAS XI IPS', NULL, 9, 1),
(17, 'KELAS XII IPS', NULL, 15, 1);

-- --------------------------------------------------------

--
-- Table structure for table `kelas_siswa`
--

CREATE TABLE `kelas_siswa` (
  `id` int(11) NOT NULL,
  `kelas_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `aktif` tinyint(1) NOT NULL COMMENT '0 jika bukan, 1 jika ya'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `kelas_siswa`
--

INSERT INTO `kelas_siswa` (`id`, `kelas_id`, `siswa_id`, `aktif`) VALUES
(1, 3, 1, 0),
(2, 12, 2, 0),
(3, 8, 3, 0),
(4, 7, 3, 1),
(5, 7, 2, 1),
(6, 7, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `komentar`
--

CREATE TABLE `komentar` (
  `id` int(11) NOT NULL,
  `login_id` int(11) NOT NULL,
  `materi_id` int(11) NOT NULL,
  `tampil` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0=tidak,1=tampil',
  `konten` text,
  `tgl_posting` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `komentar`
--

INSERT INTO `komentar` (`id`, `login_id`, `materi_id`, `tampil`, `konten`, `tgl_posting`) VALUES
(2, 1, 4, 1, '<p>gauahdjd</p>\r\n', '2019-07-09 16:24:30'),
(3, 6, 2, 1, '<p>ini apa?</p>\r\n', '2019-07-11 06:59:16'),
(4, 3, 5, 1, '<p>komentar 1</p>\r\n', '2019-07-11 07:37:22'),
(5, 1, 4, 1, '<p>agagga</p>\r\n', '2019-07-11 15:01:13');

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `siswa_id` int(11) DEFAULT NULL,
  `pengajar_id` int(11) DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL COMMENT '0=tidak,1=ya',
  `reset_kode` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`id`, `username`, `password`, `siswa_id`, `pengajar_id`, `is_admin`, `reset_kode`) VALUES
(1, 'zhenistiyan25@gmail.com', '21232f297a57a5a743894a0e4a801fc3', NULL, 1, 1, '632da4f4946c1e80e484dfcff9f7b331'),
(2, 'bima@domain.com', '827ccb0eea8a706c4c34a16891f84e7b', 1, NULL, 0, NULL),
(3, '001@domain.com', '827ccb0eea8a706c4c34a16891f84e7b', NULL, 2, 0, NULL),
(4, 'siswa@domain.com', '827ccb0eea8a706c4c34a16891f84e7b', 2, NULL, 0, NULL),
(5, 'pengajar@domain.com', '827ccb0eea8a706c4c34a16891f84e7b', NULL, 3, 0, NULL),
(6, 'yudi@domain.com', '827ccb0eea8a706c4c34a16891f84e7b', 3, NULL, 0, NULL),
(7, 'mardhiana@domain.com', '827ccb0eea8a706c4c34a16891f84e7b', NULL, 4, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `login_log`
--

CREATE TABLE `login_log` (
  `id` int(11) NOT NULL,
  `login_id` int(11) NOT NULL,
  `lasttime` datetime NOT NULL,
  `agent` text NOT NULL,
  `last_activity` int(10) UNSIGNED DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `login_log`
--

INSERT INTO `login_log` (`id`, `login_id`, `lasttime`, `agent`, `last_activity`) VALUES
(1, 1, '2018-07-12 13:12:04', '{\"is_mobile\":0,\"browser\":\"Chrome 58.0.3029.110\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 6.1) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/58.0.3029.110 Safari\\/537.36\",\"ip\":\"::1\"}', 1531376101),
(2, 1, '2018-07-28 14:54:34', '{\"is_mobile\":0,\"browser\":\"Chrome 63.0.3239.84\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 6.1) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/63.0.3239.84 Safari\\/537.36\",\"ip\":\"180.253.83.63\"}', 1532764373),
(3, 1, '2018-07-28 16:42:14', '{\"is_mobile\":0,\"browser\":\"Chrome 63.0.3239.84\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 6.1) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/63.0.3239.84 Safari\\/537.36\",\"ip\":\"180.253.83.63\"}', 1532771061),
(4, 1, '2018-07-28 22:15:13', '{\"is_mobile\":0,\"browser\":\"Chrome 63.0.3239.84\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 6.1) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/63.0.3239.84 Safari\\/537.36\",\"ip\":\"180.253.83.63\"}', 1532790846),
(5, 1, '2018-07-28 22:45:41', '{\"is_mobile\":0,\"browser\":\"Firefox 61.0\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 6.1; rv:61.0) Gecko\\/20100101 Firefox\\/61.0\",\"ip\":\"180.253.83.63\"}', 1562374514),
(6, 1, '2018-08-03 12:03:06', '{\"is_mobile\":0,\"browser\":\"Chrome 63.0.3239.84\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 6.1) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/63.0.3239.84 Safari\\/537.36\",\"ip\":\"125.164.139.15\"}', 1533272471),
(7, 1, '2018-08-03 12:47:02', '{\"is_mobile\":0,\"browser\":\"Firefox 61.0\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64; rv:61.0) Gecko\\/20100101 Firefox\\/61.0\",\"ip\":\"125.161.106.170\"}', 1533275431),
(8, 1, '2018-08-06 17:03:24', '{\"is_mobile\":1,\"browser\":\"Chrome 67.0.3396.87\",\"platform\":\"Linux\",\"agent_string\":\"Mozilla\\/5.0 (Linux; Android 6.0; ASUS_X008DA Build\\/MRA58K) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/67.0.3396.87 Mobile Safari\\/537.36\",\"ip\":\"114.5.146.251\"}', 1533549746),
(9, 1, '2018-08-07 20:42:35', '{\"is_mobile\":0,\"browser\":\"Chrome 63.0.3239.84\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 6.1) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/63.0.3239.84 Safari\\/537.36\",\"ip\":\"125.164.139.15\"}', 1533650302),
(10, 1, '2018-08-07 20:59:34', '{\"is_mobile\":0,\"browser\":\"Chrome 63.0.3239.84\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 6.1) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/63.0.3239.84 Safari\\/537.36\",\"ip\":\"125.164.139.15\"}', 1533650336),
(11, 1, '2018-08-09 08:32:05', '{\"is_mobile\":0,\"browser\":\"Chrome 58.0.3029.110\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 6.1) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/58.0.3029.110 Safari\\/537.36\",\"ip\":\"36.84.218.177\"}', 1533778250),
(12, 1, '2018-08-21 12:18:18', '{\"is_mobile\":1,\"browser\":\"Chrome 68.0.3440.91\",\"platform\":\"Linux\",\"agent_string\":\"Mozilla\\/5.0 (Linux; Android 7.1.2; Redmi 5 Plus Build\\/N2G47H) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/68.0.3440.91 Mobile Safari\\/537.36\",\"ip\":\"120.188.64.47\"}', 1534828601),
(13, 1, '2018-08-24 18:40:34', '{\"is_mobile\":0,\"browser\":\"Firefox 47.0\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64; rv:47.0) Gecko\\/20100101 Firefox\\/47.0\",\"ip\":\"125.167.86.228\"}', 1535114258),
(14, 1, '2018-08-24 21:33:06', '{\"is_mobile\":0,\"browser\":\"Firefox 47.0\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64; rv:47.0) Gecko\\/20100101 Firefox\\/47.0\",\"ip\":\"125.167.86.228\"}', 1535121082),
(15, 1, '2018-08-24 21:33:40', '{\"is_mobile\":0,\"browser\":\"Firefox 47.0\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64; rv:47.0) Gecko\\/20100101 Firefox\\/47.0\",\"ip\":\"125.167.86.228\"}', 1535121322),
(16, 3, '2018-08-24 21:37:31', '{\"is_mobile\":0,\"browser\":\"Firefox 47.0\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64; rv:47.0) Gecko\\/20100101 Firefox\\/47.0\",\"ip\":\"125.167.86.228\"}', 1535121344),
(17, 2, '2018-08-24 21:37:53', '{\"is_mobile\":0,\"browser\":\"Firefox 47.0\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64; rv:47.0) Gecko\\/20100101 Firefox\\/47.0\",\"ip\":\"125.167.86.228\"}', 1535121401),
(18, 1, '2018-09-01 09:36:44', '{\"is_mobile\":1,\"browser\":\"Chrome 68.0.3440.91\",\"platform\":\"Linux\",\"agent_string\":\"Mozilla\\/5.0 (Linux; Android 8.1.0; Redmi 5 Plus Build\\/OPM1.171019.019) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/68.0.3440.91 Mobile Safari\\/537.36\",\"ip\":\"110.139.79.6\"}', 1535769710),
(19, 1, '2018-09-06 19:00:15', '{\"is_mobile\":1,\"browser\":\"Chrome 68.0.3440.91\",\"platform\":\"Linux\",\"agent_string\":\"Mozilla\\/5.0 (Linux; Android 8.1.0; Redmi 5 Plus Build\\/OPM1.171019.019) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/68.0.3440.91 Mobile Safari\\/537.36\",\"ip\":\"114.125.84.0\"}', 1536235625),
(20, 1, '2018-09-06 19:09:18', '{\"is_mobile\":0,\"browser\":\"Chrome 68.0.3440.106\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/68.0.3440.106 Safari\\/537.36\",\"ip\":\"114.125.84.0\"}', 1536235974),
(21, 1, '2018-09-08 19:11:09', '{\"is_mobile\":1,\"browser\":\"Chrome 61.0.3163.128\",\"platform\":\"Linux\",\"agent_string\":\"Mozilla\\/5.0 (Linux; U; Android 7.1.2; id-id; Redmi 5A Build\\/N2G47H) AppleWebKit\\/537.36 (KHTML, like Gecko) Version\\/4.0 Chrome\\/61.0.3163.128 Mobile Safari\\/537.36 XiaoMi\\/MiuiBrowser\\/9.8.4\",\"ip\":\"36.74.237.227\"}', 1536408868),
(22, 1, '2018-09-19 14:08:17', '{\"is_mobile\":1,\"browser\":\"Chrome 50.0.2661.94\",\"platform\":\"Linux\",\"agent_string\":\"Mozilla\\/5.0 (Linux; Android 5.1; A1601 Build\\/LMY47I) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/50.0.2661.94 Mobile Safari\\/537.36 OPR\\/37.0.2192.112031\",\"ip\":\"140.213.8.82\"}', 1537371081),
(23, 1, '2018-09-25 19:37:50', '{\"is_mobile\":1,\"browser\":\"Chrome 69.0.3497.100\",\"platform\":\"Linux\",\"agent_string\":\"Mozilla\\/5.0 (Linux; Android 8.1.0; Redmi 5 Plus Build\\/OPM1.171019.019) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/69.0.3497.100 Mobile Safari\\/537.36\",\"ip\":\"120.188.5.40\"}', 1537878955),
(24, 1, '2018-10-02 09:10:31', '{\"is_mobile\":0,\"browser\":\"Chrome 68.0.3440.106\",\"platform\":\"Linux\",\"agent_string\":\"Mozilla\\/5.0 (X11; Linux x86_64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/68.0.3440.106 Safari\\/537.36\",\"ip\":\"139.255.1.62\"}', 1538446315),
(25, 1, '2018-10-06 09:37:31', '{\"is_mobile\":0,\"browser\":\"Firefox 62.0\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 6.1; Win64; x64; rv:62.0) Gecko\\/20100101 Firefox\\/62.0\",\"ip\":\"180.254.51.62\"}', 1538793334),
(26, 1, '2018-10-07 10:36:04', '{\"is_mobile\":0,\"browser\":\"Chrome 69.0.3497.100\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/69.0.3497.100 Safari\\/537.36\",\"ip\":\"103.115.99.3\"}', 1538883379),
(27, 1, '2018-10-07 21:26:47', '{\"is_mobile\":0,\"browser\":\"Chrome 69.0.3497.100\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/69.0.3497.100 Safari\\/537.36\",\"ip\":\"125.161.136.243\"}', 1538925592),
(28, 1, '2018-10-09 14:03:37', '{\"is_mobile\":1,\"browser\":\"Chrome 62.0.3202.84\",\"platform\":\"Linux\",\"agent_string\":\"Mozilla\\/5.0 (Linux; Android 8.1.0; vivo 1727 Build\\/OPM1.171019.011; wv) AppleWebKit\\/537.36 (KHTML, like Gecko) Version\\/4.0 Chrome\\/62.0.3202.84 Mobile Safari\\/537.36 VivoBrowser\\/5.6.0.0\",\"ip\":\"115.178.215.40\"}', 1539068800),
(29, 1, '2018-10-12 23:50:28', '{\"is_mobile\":0,\"browser\":\"Chrome 69.0.3497.100\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/69.0.3497.100 Safari\\/537.36\",\"ip\":\"112.215.220.15\"}', 1539363442),
(30, 2, '2018-10-12 23:53:40', '{\"is_mobile\":0,\"browser\":\"Chrome 69.0.3497.100\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/69.0.3497.100 Safari\\/537.36\",\"ip\":\"112.215.220.15\"}', 1539365455),
(31, 1, '2018-10-17 13:24:23', '{\"is_mobile\":0,\"browser\":\"Firefox 62.0\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 6.1; Win64; x64; rv:62.0) Gecko\\/20100101 Firefox\\/62.0\",\"ip\":\"36.81.102.240\"}', 1539757348),
(32, 1, '2019-07-06 07:55:58', '{\"is_mobile\":0,\"browser\":\"Firefox 67.0\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64; rv:67.0) Gecko\\/20100101 Firefox\\/67.0\",\"ip\":\"::1\"}', 1562374510),
(33, 1, '2019-07-06 12:36:50', '{\"is_mobile\":0,\"browser\":\"Firefox 67.0\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64; rv:67.0) Gecko\\/20100101 Firefox\\/67.0\",\"ip\":\"::1\"}', 1562391676),
(34, 1, '2019-07-06 14:20:21', '{\"is_mobile\":0,\"browser\":\"Firefox 67.0\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64; rv:67.0) Gecko\\/20100101 Firefox\\/67.0\",\"ip\":\"::1\"}', 1562402339),
(35, 1, '2019-07-06 21:53:57', '{\"is_mobile\":0,\"browser\":\"Firefox 67.0\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64; rv:67.0) Gecko\\/20100101 Firefox\\/67.0\",\"ip\":\"::1\"}', 1562425038),
(36, 1, '2019-07-06 21:59:38', '{\"is_mobile\":0,\"browser\":\"Firefox 67.0\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64; rv:67.0) Gecko\\/20100101 Firefox\\/67.0\",\"ip\":\"::1\"}', 1562460404),
(37, 1, '2019-07-07 07:47:23', '{\"is_mobile\":0,\"browser\":\"Firefox 67.0\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64; rv:67.0) Gecko\\/20100101 Firefox\\/67.0\",\"ip\":\"::1\"}', 1562464210),
(38, 1, '2019-07-07 08:57:56', '{\"is_mobile\":0,\"browser\":\"Firefox 67.0\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64; rv:67.0) Gecko\\/20100101 Firefox\\/67.0\",\"ip\":\"::1\"}', 1562473828),
(39, 1, '2019-07-07 13:17:32', '{\"is_mobile\":0,\"browser\":\"Firefox 67.0\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64; rv:67.0) Gecko\\/20100101 Firefox\\/67.0\",\"ip\":\"::1\"}', 1562480244),
(40, 1, '2019-07-07 14:37:47', '{\"is_mobile\":0,\"browser\":\"Firefox 67.0\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64; rv:67.0) Gecko\\/20100101 Firefox\\/67.0\",\"ip\":\"::1\"}', 1562485117),
(41, 1, '2019-07-07 15:00:28', '{\"is_mobile\":0,\"browser\":\"Firefox 67.0\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64; rv:67.0) Gecko\\/20100101 Firefox\\/67.0\",\"ip\":\"::1\"}', 1562486380),
(42, 1, '2019-07-07 21:06:11', '{\"is_mobile\":0,\"browser\":\"Firefox 67.0\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64; rv:67.0) Gecko\\/20100101 Firefox\\/67.0\",\"ip\":\"::1\"}', 1562514664),
(43, 1, '2019-07-08 08:51:09', '{\"is_mobile\":0,\"browser\":\"Firefox 67.0\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64; rv:67.0) Gecko\\/20100101 Firefox\\/67.0\",\"ip\":\"::1\"}', 1562558210),
(44, 1, '2019-07-08 12:30:18', '{\"is_mobile\":0,\"browser\":\"Firefox 67.0\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64; rv:67.0) Gecko\\/20100101 Firefox\\/67.0\",\"ip\":\"::1\"}', 1562565034),
(45, 1, '2019-07-08 12:57:19', '{\"is_mobile\":0,\"browser\":\"Firefox 67.0\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64; rv:67.0) Gecko\\/20100101 Firefox\\/67.0\",\"ip\":\"::1\"}', 1562571971),
(46, 1, '2019-07-08 19:39:48', '{\"is_mobile\":0,\"browser\":\"Firefox 67.0\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64; rv:67.0) Gecko\\/20100101 Firefox\\/67.0\",\"ip\":\"::1\"}', 1562598026),
(47, 1, '2019-07-08 22:09:04', '{\"is_mobile\":0,\"browser\":\"Firefox 67.0\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64; rv:67.0) Gecko\\/20100101 Firefox\\/67.0\",\"ip\":\"::1\"}', 1562628155),
(48, 1, '2019-07-09 06:51:06', '{\"is_mobile\":0,\"browser\":\"Firefox 67.0\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64; rv:67.0) Gecko\\/20100101 Firefox\\/67.0\",\"ip\":\"::1\"}', 1562652854),
(49, 1, '2019-07-09 13:14:58', '{\"is_mobile\":0,\"browser\":\"Firefox 67.0\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64; rv:67.0) Gecko\\/20100101 Firefox\\/67.0\",\"ip\":\"::1\"}', 1562657017),
(50, 1, '2019-07-09 14:25:52', '{\"is_mobile\":0,\"browser\":\"Firefox 67.0\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64; rv:67.0) Gecko\\/20100101 Firefox\\/67.0\",\"ip\":\"::1\"}', 1562663965),
(51, 1, '2019-07-09 16:19:49', '{\"is_mobile\":0,\"browser\":\"Firefox 67.0\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64; rv:67.0) Gecko\\/20100101 Firefox\\/67.0\",\"ip\":\"::1\"}', 1562670548),
(52, 1, '2019-07-09 18:11:23', '{\"is_mobile\":0,\"browser\":\"Firefox 67.0\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64; rv:67.0) Gecko\\/20100101 Firefox\\/67.0\",\"ip\":\"::1\"}', 1562671051),
(53, 1, '2019-07-09 18:19:40', '{\"is_mobile\":0,\"browser\":\"Chrome 75.0.3770.100\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/75.0.3770.100 Safari\\/537.36\",\"ip\":\"::1\"}', 1562680207),
(54, 3, '2019-07-09 18:34:04', '{\"is_mobile\":0,\"browser\":\"Firefox 67.0\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64; rv:67.0) Gecko\\/20100101 Firefox\\/67.0\",\"ip\":\"::1\"}', 1562676958),
(55, 3, '2019-07-09 19:56:45', '{\"is_mobile\":0,\"browser\":\"Firefox 67.0\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64; rv:67.0) Gecko\\/20100101 Firefox\\/67.0\",\"ip\":\"::1\"}', 1562677205),
(56, 1, '2019-07-09 21:13:03', '{\"is_mobile\":0,\"browser\":\"Firefox 67.0\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64; rv:67.0) Gecko\\/20100101 Firefox\\/67.0\",\"ip\":\"::1\"}', 1562716961),
(57, 1, '2019-07-10 07:03:13', '{\"is_mobile\":0,\"browser\":\"Firefox 67.0\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64; rv:67.0) Gecko\\/20100101 Firefox\\/67.0\",\"ip\":\"::1\"}', 1562725864),
(58, 1, '2019-07-10 09:31:26', '{\"is_mobile\":0,\"browser\":\"Firefox 67.0\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64; rv:67.0) Gecko\\/20100101 Firefox\\/67.0\",\"ip\":\"::1\"}', 1562727692),
(59, 3, '2019-07-10 10:03:49', '{\"is_mobile\":0,\"browser\":\"Firefox 67.0\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64; rv:67.0) Gecko\\/20100101 Firefox\\/67.0\",\"ip\":\"::1\"}', 1562729062),
(60, 1, '2019-07-10 10:27:04', '{\"is_mobile\":0,\"browser\":\"Firefox 67.0\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64; rv:67.0) Gecko\\/20100101 Firefox\\/67.0\",\"ip\":\"::1\"}', 1562735780),
(61, 1, '2019-07-10 12:16:50', '{\"is_mobile\":0,\"browser\":\"Firefox 67.0\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64; rv:67.0) Gecko\\/20100101 Firefox\\/67.0\",\"ip\":\"::1\"}', 1562739953),
(62, 1, '2019-07-10 13:26:17', '{\"is_mobile\":0,\"browser\":\"Firefox 67.0\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64; rv:67.0) Gecko\\/20100101 Firefox\\/67.0\",\"ip\":\"::1\"}', 1562753701),
(63, 1, '2019-07-10 18:41:32', '{\"is_mobile\":0,\"browser\":\"Firefox 68.0\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64; rv:68.0) Gecko\\/20100101 Firefox\\/68.0\",\"ip\":\"::1\"}', 1562760396),
(64, 1, '2019-07-10 19:11:09', '{\"is_mobile\":0,\"browser\":\"Firefox 68.0\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64; rv:68.0) Gecko\\/20100101 Firefox\\/68.0\",\"ip\":\"::1\"}', 1562760848),
(65, 1, '2019-07-10 19:23:04', '{\"is_mobile\":0,\"browser\":\"Firefox 68.0\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64; rv:68.0) Gecko\\/20100101 Firefox\\/68.0\",\"ip\":\"::1\"}', 1562775030),
(66, 6, '2019-07-10 20:05:20', '{\"is_mobile\":0,\"browser\":\"Chrome 75.0.3770.100\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/75.0.3770.100 Safari\\/537.36\",\"ip\":\"::1\"}', 1562775220),
(67, 1, '2019-07-10 23:12:51', '{\"is_mobile\":0,\"browser\":\"Firefox 68.0\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64; rv:68.0) Gecko\\/20100101 Firefox\\/68.0\",\"ip\":\"::1\"}', 1562775202),
(68, 1, '2019-07-11 05:59:51', '{\"is_mobile\":0,\"browser\":\"Firefox 68.0\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64; rv:68.0) Gecko\\/20100101 Firefox\\/68.0\",\"ip\":\"::1\"}', 1562800948),
(69, 6, '2019-07-11 06:26:14', '{\"is_mobile\":0,\"browser\":\"Firefox 68.0\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64; rv:68.0) Gecko\\/20100101 Firefox\\/68.0\",\"ip\":\"::1\"}', 1562804303),
(70, 3, '2019-07-11 06:32:45', '{\"is_mobile\":0,\"browser\":\"Chrome 75.0.3770.100\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/75.0.3770.100 Safari\\/537.36\",\"ip\":\"::1\"}', 1562805982),
(71, 6, '2019-07-11 07:54:23', '{\"is_mobile\":0,\"browser\":\"Chrome 75.0.3770.100\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/75.0.3770.100 Safari\\/537.36\",\"ip\":\"::1\"}', 1562806390),
(72, 1, '2019-07-11 07:55:54', '{\"is_mobile\":0,\"browser\":\"Chrome 75.0.3770.100\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/75.0.3770.100 Safari\\/537.36\",\"ip\":\"::1\"}', 1562806514),
(73, 3, '2019-07-11 07:57:35', '{\"is_mobile\":0,\"browser\":\"Firefox 68.0\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64; rv:68.0) Gecko\\/20100101 Firefox\\/68.0\",\"ip\":\"::1\"}', 1562806585),
(74, 6, '2019-07-11 07:57:51', '{\"is_mobile\":0,\"browser\":\"Chrome 75.0.3770.100\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/75.0.3770.100 Safari\\/537.36\",\"ip\":\"::1\"}', 1562806574),
(75, 1, '2019-07-11 14:56:44', '{\"is_mobile\":0,\"browser\":\"Firefox 68.0\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64; rv:68.0) Gecko\\/20100101 Firefox\\/68.0\",\"ip\":\"::1\"}', 1562844289),
(76, 1, '2019-07-11 18:32:36', '{\"is_mobile\":0,\"browser\":\"Chrome 75.0.3770.100\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/75.0.3770.100 Safari\\/537.36\",\"ip\":\"::1\"}', 1562848057),
(77, 1, '2019-07-12 16:31:15', '{\"is_mobile\":0,\"browser\":\"Firefox 68.0\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64; rv:68.0) Gecko\\/20100101 Firefox\\/68.0\",\"ip\":\"::1\"}', 1562924982),
(78, 1, '2019-07-14 11:09:30', '{\"is_mobile\":0,\"browser\":\"Firefox 68.0\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64; rv:68.0) Gecko\\/20100101 Firefox\\/68.0\",\"ip\":\"::1\"}', 1563086584),
(79, 1, '2019-07-14 13:43:32', '{\"is_mobile\":0,\"browser\":\"Firefox 68.0\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64; rv:68.0) Gecko\\/20100101 Firefox\\/68.0\",\"ip\":\"::1\"}', 1563101603),
(80, 6, '2019-07-14 16:31:23', '{\"is_mobile\":0,\"browser\":\"Chrome 75.0.3770.100\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/75.0.3770.100 Safari\\/537.36\",\"ip\":\"::1\"}', 1563097340),
(81, 1, '2019-07-14 19:28:21', '{\"is_mobile\":0,\"browser\":\"Firefox 68.0\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64; rv:68.0) Gecko\\/20100101 Firefox\\/68.0\",\"ip\":\"127.0.0.1\"}', 1563109875),
(82, 6, '2019-07-14 20:13:42', '{\"is_mobile\":0,\"browser\":\"Firefox 68.0\",\"platform\":\"Unknown Windows OS\",\"agent_string\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64; rv:68.0) Gecko\\/20100101 Firefox\\/68.0\",\"ip\":\"127.0.0.1\"}', 1563111948);

-- --------------------------------------------------------

--
-- Table structure for table `mapel`
--

CREATE TABLE `mapel` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `info` text,
  `aktif` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 = ya, 0 = tidak'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `mapel`
--

INSERT INTO `mapel` (`id`, `nama`, `info`, `aktif`) VALUES
(1, 'Quran Hadis', '', 1),
(2, 'Fiqih', '', 1),
(3, 'SKI (Sejarah Kebudayaan Islam)', '', 1),
(4, 'Aqidah Akhlak', '', 1),
(5, 'Bahasa Arab', '', 1),
(6, 'Bahasa Inggris', '', 1),
(7, 'Bahasa Indonesia', '', 1),
(8, 'Matematika', '', 1),
(9, 'Fisika', NULL, 1),
(10, 'Kimia', NULL, 1),
(11, 'Biologi', '', 1),
(12, 'Ekonomi', '', 1),
(13, 'Sosiologi', '', 1),
(14, 'Geografi', '', 1),
(15, 'Sejarah', '', 1),
(16, 'PKn (Pendidikan Kewarganegaraan)', '', 1),
(17, 'Penjaskes (Pendidikan Jasmani dan Kesehatan)', '', 1),
(18, 'TIK (Teknologi Informasi dan Komunikasi)', '', 1),
(19, 'Seni Budaya', '', 1),
(20, 'KBA (Keterampilan Bahasa Asing)', '', 1),
(21, 'Pengembangan Diri', '', 1),
(22, 'Mulok', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `mapel_ajar`
--

CREATE TABLE `mapel_ajar` (
  `id` int(11) NOT NULL,
  `hari_id` tinyint(1) NOT NULL COMMENT '1=senin,2=selasa,3=rabu,4=kamis,5=jum''at,6=sabtu,7=minggu',
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `pengajar_id` int(11) NOT NULL,
  `mapel_kelas_id` int(11) NOT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 = aktif 0 = tidak'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `mapel_ajar`
--

INSERT INTO `mapel_ajar` (`id`, `hari_id`, `jam_mulai`, `jam_selesai`, `pengajar_id`, `mapel_kelas_id`, `aktif`) VALUES
(1, 1, '08:30:00', '09:45:00', 1, 1, 1),
(2, 1, '08:30:00', '09:30:00', 2, 94, 1);

-- --------------------------------------------------------

--
-- Table structure for table `mapel_kelas`
--

CREATE TABLE `mapel_kelas` (
  `id` int(11) NOT NULL,
  `kelas_id` int(11) NOT NULL,
  `mapel_id` int(11) NOT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `mapel_kelas`
--

INSERT INTO `mapel_kelas` (`id`, `kelas_id`, `mapel_id`, `aktif`) VALUES
(1, 2, 1, 1),
(2, 2, 2, 1),
(3, 2, 3, 1),
(4, 2, 4, 1),
(5, 2, 5, 1),
(6, 2, 6, 1),
(7, 2, 7, 1),
(8, 2, 8, 1),
(9, 2, 9, 1),
(10, 2, 10, 1),
(11, 2, 11, 1),
(12, 2, 12, 1),
(13, 2, 13, 1),
(14, 2, 14, 1),
(15, 2, 15, 1),
(16, 2, 16, 1),
(17, 2, 17, 1),
(18, 2, 18, 1),
(19, 2, 19, 1),
(20, 2, 20, 1),
(21, 2, 21, 1),
(22, 2, 22, 1),
(23, 3, 1, 1),
(24, 3, 2, 1),
(25, 3, 3, 1),
(26, 3, 4, 1),
(27, 3, 5, 1),
(28, 3, 6, 1),
(29, 3, 7, 1),
(30, 3, 8, 1),
(31, 3, 9, 1),
(32, 3, 10, 1),
(33, 3, 11, 1),
(34, 3, 12, 1),
(35, 3, 13, 1),
(36, 3, 14, 1),
(37, 3, 15, 1),
(38, 3, 16, 1),
(39, 3, 17, 1),
(40, 3, 18, 1),
(41, 3, 19, 1),
(42, 3, 20, 1),
(43, 3, 21, 1),
(44, 3, 22, 1),
(45, 4, 1, 1),
(46, 4, 2, 1),
(47, 4, 3, 1),
(48, 4, 4, 1),
(49, 4, 5, 1),
(50, 4, 6, 1),
(51, 4, 7, 1),
(52, 4, 8, 1),
(53, 4, 9, 1),
(54, 4, 10, 1),
(55, 4, 11, 1),
(56, 4, 12, 1),
(57, 4, 13, 1),
(58, 4, 14, 1),
(59, 4, 15, 1),
(60, 4, 16, 1),
(61, 4, 17, 1),
(62, 4, 18, 1),
(63, 4, 19, 1),
(64, 4, 20, 1),
(65, 4, 21, 1),
(66, 4, 22, 1),
(67, 5, 1, 1),
(68, 5, 2, 1),
(69, 5, 3, 1),
(70, 5, 4, 1),
(71, 5, 5, 1),
(72, 5, 6, 1),
(73, 5, 7, 1),
(74, 5, 8, 1),
(75, 5, 9, 1),
(76, 5, 10, 1),
(77, 5, 11, 1),
(78, 5, 12, 1),
(79, 5, 13, 1),
(80, 5, 14, 1),
(81, 5, 15, 1),
(82, 5, 16, 1),
(83, 5, 17, 1),
(84, 5, 18, 1),
(85, 5, 19, 1),
(86, 5, 20, 1),
(87, 5, 21, 1),
(88, 5, 22, 1),
(89, 7, 1, 1),
(90, 7, 2, 1),
(91, 7, 3, 1),
(92, 7, 4, 1),
(93, 7, 5, 1),
(94, 7, 6, 1),
(95, 7, 7, 1),
(96, 7, 8, 1),
(97, 7, 9, 1),
(98, 7, 10, 1),
(99, 7, 11, 1),
(100, 7, 15, 1),
(101, 7, 16, 1),
(102, 7, 17, 1),
(103, 7, 18, 1),
(104, 7, 19, 1),
(105, 7, 20, 1),
(106, 7, 21, 1),
(107, 7, 22, 1),
(108, 8, 1, 1),
(109, 8, 2, 1),
(110, 8, 3, 1),
(111, 8, 4, 1),
(112, 8, 5, 1),
(113, 8, 6, 1),
(114, 8, 7, 1),
(115, 8, 8, 1),
(116, 8, 9, 1),
(117, 8, 10, 1),
(118, 8, 11, 1),
(119, 8, 15, 1),
(120, 8, 16, 1),
(121, 8, 17, 1),
(122, 8, 18, 1),
(123, 8, 19, 1),
(124, 8, 20, 1),
(125, 8, 21, 1),
(126, 8, 22, 1),
(127, 9, 1, 1),
(128, 9, 2, 1),
(129, 9, 3, 1),
(130, 9, 4, 1),
(131, 9, 5, 1),
(132, 9, 6, 1),
(133, 9, 7, 1),
(134, 9, 8, 1),
(135, 9, 12, 1),
(136, 9, 13, 1),
(137, 9, 14, 1),
(138, 9, 15, 1),
(139, 9, 16, 1),
(140, 9, 17, 1),
(141, 9, 18, 1),
(142, 9, 19, 1),
(143, 9, 20, 1),
(144, 9, 21, 1),
(145, 9, 22, 1),
(146, 10, 1, 1),
(147, 10, 2, 1),
(148, 10, 3, 1),
(149, 10, 4, 1),
(150, 10, 5, 1),
(151, 10, 6, 1),
(152, 10, 7, 1),
(153, 10, 8, 1),
(154, 10, 12, 1),
(155, 10, 13, 1),
(156, 10, 14, 1),
(157, 10, 15, 1),
(158, 10, 16, 1),
(159, 10, 17, 1),
(160, 10, 18, 1),
(161, 10, 19, 1),
(162, 10, 20, 1),
(163, 10, 21, 1),
(164, 10, 22, 1),
(165, 12, 1, 1),
(166, 12, 2, 1),
(167, 12, 3, 1),
(168, 12, 4, 1),
(169, 12, 5, 1),
(170, 12, 6, 1),
(171, 12, 7, 1),
(172, 12, 8, 1),
(173, 12, 12, 1),
(174, 12, 13, 1),
(175, 12, 14, 1),
(176, 12, 15, 1),
(177, 12, 16, 1),
(178, 12, 17, 1),
(179, 12, 18, 1),
(180, 12, 19, 1),
(181, 12, 20, 1),
(182, 12, 21, 1),
(183, 12, 22, 1),
(184, 13, 1, 1),
(185, 13, 2, 1),
(186, 13, 3, 1),
(187, 13, 4, 1),
(188, 13, 5, 1),
(189, 13, 6, 1),
(190, 13, 7, 1),
(191, 13, 8, 1),
(192, 13, 12, 1),
(193, 13, 13, 1),
(194, 13, 14, 1),
(195, 13, 15, 1),
(196, 13, 16, 1),
(197, 13, 17, 1),
(198, 13, 18, 1),
(199, 13, 19, 1),
(200, 13, 20, 1),
(201, 13, 21, 1),
(202, 13, 22, 1),
(203, 14, 1, 1),
(204, 14, 2, 1),
(205, 14, 3, 1),
(206, 14, 4, 1),
(207, 14, 5, 1),
(208, 14, 6, 1),
(209, 14, 7, 1),
(210, 14, 8, 1),
(211, 14, 12, 1),
(212, 14, 13, 1),
(213, 14, 14, 1),
(214, 14, 15, 1),
(215, 14, 16, 1),
(216, 14, 17, 1),
(217, 14, 18, 1),
(218, 14, 19, 1),
(219, 14, 20, 1),
(220, 14, 21, 1),
(221, 14, 22, 1);

-- --------------------------------------------------------

--
-- Table structure for table `materi`
--

CREATE TABLE `materi` (
  `id` int(11) NOT NULL,
  `mapel_id` int(11) NOT NULL,
  `pengajar_id` int(11) DEFAULT NULL,
  `siswa_id` int(11) DEFAULT NULL,
  `judul` varchar(255) NOT NULL,
  `konten` text,
  `file` text,
  `tgl_posting` datetime NOT NULL,
  `publish` tinyint(1) NOT NULL DEFAULT '0',
  `views` int(11) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `materi`
--

INSERT INTO `materi` (`id`, `mapel_id`, `pengajar_id`, `siswa_id`, `judul`, `konten`, `file`, `tgl_posting`, `publish`, `views`) VALUES
(1, 1, 1, NULL, 'Tes', NULL, 'tes_1537341122.pdf', '2018-09-19 14:12:02', 1, 2),
(2, 4, 1, NULL, 'Video', '<div>\n<video controls=\"controls\" height=\"300\" id=\"video2018819141423\" poster=\"https://elearning.sapoycorp.com/userfiles/VID20180919141534.mp4?time=1537341413420\" width=\"400\">Your browser doesn&#39;t support video.<br />\nPlease download the file:</video>\n</div>\n\n<p>&nbsp;</p>\n', NULL, '2018-09-19 14:19:33', 1, 6),
(3, 6, 1, NULL, 'Materi Procedure Text', NULL, 'materi_procedure_text_1562632735.png', '2019-07-09 07:38:55', 1, 1),
(4, 6, 1, NULL, 'Materi Procedure Text 2', '<p>test</p>\r\n', NULL, '2019-07-09 07:59:53', 0, 4),
(5, 6, 2, NULL, 'Materi 1', NULL, 'materi_1_1562805185.jpg', '2019-07-11 07:33:08', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `materi_kelas`
--

CREATE TABLE `materi_kelas` (
  `id` int(11) NOT NULL,
  `materi_id` int(11) NOT NULL,
  `kelas_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `materi_kelas`
--

INSERT INTO `materi_kelas` (`id`, `materi_id`, `kelas_id`) VALUES
(1, 1, 1),
(2, 2, 6),
(3, 3, 1),
(4, 3, 11),
(5, 4, 16),
(6, 5, 6);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `type_id` tinyint(1) NOT NULL COMMENT '1=inbox,2=outbox',
  `content` text NOT NULL,
  `owner_id` int(11) NOT NULL,
  `sender_receiver_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `opened` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=belum,1=sudah'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `type_id`, `content`, `owner_id`, `sender_receiver_id`, `date`, `opened`) VALUES
(1, 2, '<p>Haloo</p>\r\n', 1, 2, '2019-07-08 19:56:22', 1),
(2, 1, '<p>Haloo</p>\r\n', 2, 1, '2019-07-08 19:56:22', 0),
(3, 2, '<p>Besok kantor</p>\r\n', 1, 2, '2019-07-10 19:27:27', 1),
(4, 1, '<p>Besok kantor</p>\r\n', 2, 1, '2019-07-10 19:27:27', 0),
(5, 2, '<p>Besok kantor</p>\r\n', 1, 6, '2019-07-10 19:27:27', 1),
(6, 1, '<p>Besok kantor</p>\r\n', 6, 1, '2019-07-10 19:27:27', 1),
(7, 2, '<p>Bima</p>\r\n', 6, 2, '2019-07-10 20:11:32', 1),
(8, 1, '<p>Bima</p>\r\n', 2, 6, '2019-07-10 20:11:32', 0),
(9, 2, '<p>baik bu</p>\r\n', 6, 1, '2019-07-11 06:28:37', 1),
(10, 1, '<p>baik bu</p>\r\n', 1, 6, '2019-07-11 06:28:37', 0);

-- --------------------------------------------------------

--
-- Table structure for table `nilai_tugas`
--

CREATE TABLE `nilai_tugas` (
  `id` int(11) NOT NULL,
  `nilai` float NOT NULL,
  `tugas_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `nilai_tugas`
--

INSERT INTO `nilai_tugas` (`id`, `nilai`, `tugas_id`, `siswa_id`) VALUES
(1, 100, 2, 3);

-- --------------------------------------------------------

--
-- Table structure for table `pengajar`
--

CREATE TABLE `pengajar` (
  `id` int(11) NOT NULL,
  `nip` varchar(45) DEFAULT NULL,
  `nama` varchar(100) NOT NULL,
  `jenis_kelamin` varchar(9) NOT NULL,
  `tempat_lahir` varchar(45) NOT NULL,
  `tgl_lahir` date DEFAULT NULL,
  `alamat` varchar(255) NOT NULL,
  `foto` text,
  `status_id` tinyint(1) NOT NULL COMMENT '0=pending, 1=aktif, 2=blok'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pengajar`
--

INSERT INTO `pengajar` (`id`, `nip`, `nama`, `jenis_kelamin`, `tempat_lahir`, `tgl_lahir`, `alamat`, `foto`, `status_id`) VALUES
(1, '12345', 'Nur Istiyan', 'Perempuan', 'Rasau Jaya', '1995-09-25', 'Jl. Mt. Haryono, RT 003 RW 001 No. 16', NULL, 1),
(2, '198501062009121008', 'Salman, S.Pd', 'Laki-Laki', '', NULL, '', NULL, 1),
(3, '1234567890', 'Pengajar Coba', 'Perempuan', '', NULL, '', NULL, 1),
(4, '000123', 'Mardhiana', 'Perempuan', '', NULL, '', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pengaturan`
--

CREATE TABLE `pengaturan` (
  `id` varchar(255) NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `value` text
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pengaturan`
--

INSERT INTO `pengaturan` (`id`, `nama`, `value`) VALUES
('email-server', 'Email server', 'no-reply@domain.com'),
('email-template-approve-pengajar', 'Approve pengajar (email pengajar)', '{\"subject\":\"Akun Anda Sudah di Aktifkan...\",\"body\":\"<p>Hallo {$nama},<\\/p>\\r\\n\\r\\n<p>Akun Anda sebagai <em><strong>Pengajar<\\/strong><\\/em> di E-Learning {$nama_sekolah} telah kami <em><strong>Atifkan<\\/strong><\\/em>...<\\/p>\\r\\n\\r\\n<p>Berikut data informasi akun Anda:<\\/p>\\r\\n\\r\\n<p>{$tabel_profil}<\\/p>\\r\\n\\r\\n<p>Silahkan Login di : {$url_login}<\\/p>\\r\\n\\r\\n<p>Masukkan <em><strong>Username<\\/strong><\\/em> dan <em><strong>Password<\\/strong><\\/em> yang telah anda buat saat pendaftaran untuk masuk ke sistem E-Learning {$nama_sekolah}.<\\/p>\\r\\n\\r\\n<p>&nbsp;<\\/p>\\r\\n\\r\\n<p>Regards, Administrator<\\/p>\\r\\n\"}'),
('email-template-approve-siswa', 'Approve siswa (email siswa)', '{\"subject\":\"Akun Anda Sudah di Aktifkan...\",\"body\":\"<p>Hallo {$nama},<\\/p>\\r\\n\\r\\n<p>Akun Anda sebagai <em><strong>Siswa<\\/strong><\\/em> di E-Learning {$nama_sekolah} telah kami <em><strong>Atifkan<\\/strong><\\/em>..<\\/p>\\r\\n\\r\\n<p>Berikut data informasi akun Anda:<\\/p>\\r\\n\\r\\n<p>{$tabel_profil}<\\/p>\\r\\n\\r\\n<p>Silahkan Login di : {$url_login}<\\/p>\\r\\n\\r\\n<p>Masukkan <em><strong>Username<\\/strong><\\/em> dan <em><strong>Password<\\/strong><\\/em> yang telah anda buat saat pendaftaran untuk masuk ke sistem E-Learning {$nama_sekolah}.<\\/p>\\r\\n\\r\\n<p>&nbsp;<\\/p>\\r\\n\\r\\n<p>Regards, Administrator<\\/p>\\r\\n\"}'),
('email-template-link-reset', 'Link Reset Password', '{\"subject\":\"Reset Password Akun...\",\"body\":\"<p><strong cwidth=\\\"0\\\" eza=\\\"cwidth:0px;;cheight:0px;;wcalc_source:child;wcalc:131px;wocalc:131px;hcalc:72px;rend_px_area:0;\\\" style=\\\"text-align:center;background-color:transparent;background-size:auto;font-weight:bold;nodepath:\\/html\\/body\\/div[1]\\/div[3]\\/div\\/div[1]\\/main\\/article\\/div\\/ul\\/li[1]\\/strong;pagepos:205;cwidth:131;cheight:0px;wcalc_source:child;wcalc:131px;wocalc:131px;hcalc:72;rend_px_area:0;minimum_paddings:0px 0px 0px 0px;rcnt:2;ez_min_text_wdth:131;req_px_area:15720;obj_px_area:0;req_px_height:24;req_margin_and_padding:0;req_ns_height:;vertical_margin:0;margin-for-scale:0px 0px 0px 0px;padding-for-scale:0px 0px 0px 0px;\\\"><span cwidth=\\\"0\\\" eza=\\\"cwidth:0px;;cheight:0px;;wcalc_source:child;wcalc:131px;wocalc:131px;hcalc:72px;rend_px_area:0;\\\" style=\\\"color:rgb(0, 0, 0);background-color:transparent;background-size:auto;nodepath:\\/html\\/body\\/div[1]\\/div[3]\\/div\\/div[1]\\/main\\/article\\/div\\/ul\\/li[1]\\/strong\\/span;pagepos:206;cwidth:131;cheight:0px;wcalc_source:child;wcalc:131px;wocalc:131px;hcalc:72;rend_px_area:0;rcnt:1;ez_min_text_wdth:131;req_px_area:15720;obj_px_area:0;req_px_height:24;req_margin_and_padding:0;req_ns_height:;vertical_margin:0;margin-for-scale:0px 0px 0px 0px;padding-for-scale:0px 0px 0px 0px;\\\"><em cwidth=\\\"0\\\" eza=\\\"cwidth:0px;;cheight:0px;;wcalc_source:child;wcalc:131px;wocalc:131px;hcalc:72px;rend_px_area:0;\\\" style=\\\"background-color:transparent;background-size:auto;font-style:italic;nodepath:\\/html\\/body\\/div[1]\\/div[3]\\/div\\/div[1]\\/main\\/article\\/div\\/ul\\/li[1]\\/strong\\/span\\/em;pagepos:207;cwidth:131;cheight:0px;wcalc_source:child;wcalc:131px;wocalc:131px;hcalc:72;rend_px_area:0;rcnt:1;ez_min_text_wdth:131;req_px_area:15720;obj_px_area:0;req_px_height:24;req_margin_and_padding:0;req_ns_height:;vertical_margin:0;margin-for-scale:0px 0px 0px 0px;padding-for-scale:0px 0px 0px 0px;\\\">Assalamu&rsquo;alaikum Warahmatullahi Wabarakaatuh<\\/em><\\/span><\\/strong><\\/p>\\r\\n\\r\\n<p>Hallo {$nama},<\\/p>\\r\\n\\r\\n<p>Anda mengirimkan permintaan untuk reset password Anda, silahkan klik link berikut untuk reset password : {$link_reset}<\\/p>\\r\\n\\r\\n<p>&nbsp;<\\/p>\\r\\n\\r\\n<p>Regards, Administrator<\\/p>\\r\\n\"}'),
('email-template-register-pengajar', 'Register pengajar (email pengajar)', '{\"subject\":\"Pendaftaran Akun Berhasil...\",\"body\":\"<p>Hallo {$nama},<\\/p>\\r\\n\\r\\n<p>Terimakasih telah melakukan pendaftaran sebagai <strong><em>Pengajar<\\/em><\\/strong> di E-Learning {$nama_sekolah}. Mohon menunggu, akun Anda akan segera diaktifkan.<\\/p>\\r\\n\\r\\n<p>&nbsp;<\\/p>\\r\\n\\r\\n<p>Regards, Administrator<\\/p>\\r\\n\"}'),
('email-template-register-siswa', 'Register siswa (email siswa)', '{\"subject\":\"Pendaftaran Akun Berhasil...\",\"body\":\"<p><strong cwidth=\\\"0\\\" eza=\\\"cwidth:0px;;cheight:0px;;wcalc_source:child;wcalc:131px;wocalc:131px;hcalc:72px;rend_px_area:0;\\\" style=\\\"text-align:center;background-color:transparent;background-size:auto;font-weight:bold;nodepath:\\/html\\/body\\/div[1]\\/div[3]\\/div\\/div[1]\\/main\\/article\\/div\\/ul\\/li[1]\\/strong;pagepos:205;cwidth:131;cheight:0px;wcalc_source:child;wcalc:131px;wocalc:131px;hcalc:72;rend_px_area:0;minimum_paddings:0px 0px 0px 0px;rcnt:2;ez_min_text_wdth:131;req_px_area:15720;obj_px_area:0;req_px_height:24;req_margin_and_padding:0;req_ns_height:;vertical_margin:0;margin-for-scale:0px 0px 0px 0px;padding-for-scale:0px 0px 0px 0px;\\\"><span cwidth=\\\"0\\\" eza=\\\"cwidth:0px;;cheight:0px;;wcalc_source:child;wcalc:131px;wocalc:131px;hcalc:72px;rend_px_area:0;\\\" style=\\\"color:rgb(0, 0, 0);background-color:transparent;background-size:auto;nodepath:\\/html\\/body\\/div[1]\\/div[3]\\/div\\/div[1]\\/main\\/article\\/div\\/ul\\/li[1]\\/strong\\/span;pagepos:206;cwidth:131;cheight:0px;wcalc_source:child;wcalc:131px;wocalc:131px;hcalc:72;rend_px_area:0;rcnt:1;ez_min_text_wdth:131;req_px_area:15720;obj_px_area:0;req_px_height:24;req_margin_and_padding:0;req_ns_height:;vertical_margin:0;margin-for-scale:0px 0px 0px 0px;padding-for-scale:0px 0px 0px 0px;\\\"><em cwidth=\\\"0\\\" eza=\\\"cwidth:0px;;cheight:0px;;wcalc_source:child;wcalc:131px;wocalc:131px;hcalc:72px;rend_px_area:0;\\\" style=\\\"background-color:transparent;background-size:auto;font-style:italic;nodepath:\\/html\\/body\\/div[1]\\/div[3]\\/div\\/div[1]\\/main\\/article\\/div\\/ul\\/li[1]\\/strong\\/span\\/em;pagepos:207;cwidth:131;cheight:0px;wcalc_source:child;wcalc:131px;wocalc:131px;hcalc:72;rend_px_area:0;rcnt:1;ez_min_text_wdth:131;req_px_area:15720;obj_px_area:0;req_px_height:24;req_margin_and_padding:0;req_ns_height:;vertical_margin:0;margin-for-scale:0px 0px 0px 0px;padding-for-scale:0px 0px 0px 0px;\\\">Assalamu&rsquo;alaikum Warahmatullahi Wabarakaatuh<\\/em><\\/span><\\/strong><\\/p>\\r\\n\\r\\n<p>Hallo {$nama},<\\/p>\\r\\n\\r\\n<p>Terimakasih telah melakukan pendaftaran sebagai <strong><em>Pengajar<\\/em><\\/strong> di E-Learning {$nama_sekolah}. Mohon menunggu, akun Anda akan segera diaktifkan.<\\/p>\\r\\n\\r\\n<p>&nbsp;<\\/p>\\r\\n\\r\\n<p>Regards, Administrator<\\/p>\\r\\n\"}'),
('info-registrasi', 'Info Registrasi', '<p>Silahkan mengisi formulir registrasi E-Learning dengan memilih tab Siswa jika mendaftar sebagai siswa (peserta didik), atau tab Pengajar jika mendaftar sebagai guru (pengajar). Isikan data diri Anda dengan Lengkap &amp; Benar. Jika ditemukan data yang tidak sesuai maka akan diberikan sanksi.</p>\r\n'),
('peraturan-elearning', 'Peraturan E-learning', '<p>Test</p>\r\n'),
('registrasi-pengajar', 'Registrasi Pengajar', '1'),
('registrasi-siswa', 'Registrasi Siswa', '1'),
('versi', 'Versi', '2.0'),
('jenjang', 'jenjang', 'SMA'),
('nama-sekolah', 'nama-sekolah', 'MAN Kubu Raya'),
('alamat', 'alamat', 'Jl.Sultan Agung Kec. Rasau Jaya Kab. Kubu Raya 78382'),
('telp', 'telp', '0561 6595791'),
('install-success', 'install-success', '1'),
('status-registrasi-siswa', 'status-registrasi-siswa', '0'),
('status-registrasi-pengajar', 'status-registrasi-pengajar', '0'),
('smtp-host', 'smtp-host', ''),
('smtp-username', 'smtp-username', ''),
('smtp-pass', 'smtp-pass', ''),
('smtp-port', 'smtp-port', ''),
('edit-username-siswa', 'edit-username-siswa', '1'),
('edit-foto-siswa', 'edit-foto-siswa', '1'),
('info-slide-1', 'info-slide-1', ''),
('info-slide-2', 'info-slide-2', ''),
('info-slide-3', 'info-slide-3', ''),
('info-slide-4', 'info-slide-4', ''),
('img-slide-1', 'img-slide-1', 'img-slide-1.jpg'),
('img-slide-2', 'img-slide-2', 'img-slide-2.jpeg'),
('logo-sekolah', 'logo-sekolah', 'logo-sekolah1.png'),
('img-slide-3', 'img-slide-3', 'img-slide-3.jpg'),
('img-slide-4', 'img-slide-4', '');

-- --------------------------------------------------------

--
-- Table structure for table `pengumuman`
--

CREATE TABLE `pengumuman` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `konten` text NOT NULL,
  `tgl_tampil` date NOT NULL,
  `tgl_tutup` date NOT NULL,
  `tampil_siswa` tinyint(1) NOT NULL DEFAULT '1',
  `tampil_pengajar` tinyint(1) NOT NULL DEFAULT '1',
  `pengajar_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pengumuman`
--

INSERT INTO `pengumuman` (`id`, `judul`, `konten`, `tgl_tampil`, `tgl_tutup`, `tampil_siswa`, `tampil_pengajar`, `pengajar_id`) VALUES
(1, 'TES-PENGUMUMAN LOMBA TARI', '<p>Sekolah kita akan mengadakan lomba menari yang akan dilaksanakan pada 17 Agustus 2018 dalam rangka HUT RI Ke-75.</p>\r\n\r\n<p>Pendaftran akan dibuka pada 11 Juli - 16 Agustus 2019 di Ruang OSIS.&nbsp;</p>\r\n\r\n<p>Pendaftaran <strong>GRATIS</strong> dan banyak hadiahnya.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Informasi lebih lanjut hubungi:</p>\r\n\r\n<p>CP : Indah (0852358993)</p>\r\n\r\n<p>&nbsp;</p>\r\n', '2019-07-08', '2019-08-16', 1, 1, 1),
(2, 'Ulangan Semester', '<p>Ulangan semester akhir 2019/2020</p>\r\n', '2019-07-10', '2019-07-19', 1, 1, 1),
(3, 'Pengumuman 2', '<p>pengumuman</p>\r\n', '2019-07-11', '2019-07-13', 1, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `pilihan`
--

CREATE TABLE `pilihan` (
  `id` int(11) NOT NULL,
  `pertanyaan_id` int(11) NOT NULL,
  `konten` text NOT NULL,
  `kunci` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=tidak',
  `urutan` int(11) NOT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pilihan`
--

INSERT INTO `pilihan` (`id`, `pertanyaan_id`, `konten`, `kunci`, `urutan`, `aktif`) VALUES
(1, 1, '<p>Gak apa-apa</p>\r\n', 1, 1, 1),
(2, 1, '<p>ya&nbsp; gitu</p>\r\n', 0, 2, 1),
(3, 1, '<p>ntah</p>\r\n', 0, 3, 1),
(4, 1, '<p>hemm</p>\r\n', 0, 4, 1),
(5, 1, '<p>kepo</p>\r\n', 0, 5, 1),
(6, 2, '<p>mie</p>\r\n', 0, 1, 1),
(7, 2, '<p>ayam</p>\r\n', 0, 2, 1),
(8, 2, '<p>terserah</p>\r\n', 1, 3, 1),
(9, 2, '<p>bakso</p>\r\n', 0, 4, 1),
(10, 2, '<p>nasi</p>\r\n', 0, 5, 1),
(11, 4, '<p>mie</p>\r\n', 0, 1, 1),
(12, 4, '<p>ayam</p>\r\n', 0, 2, 1),
(13, 4, '<p>terserah</p>\r\n', 1, 3, 1),
(14, 4, '<p>bakso</p>\r\n', 0, 4, 1),
(15, 4, '<p>nasi</p>\r\n', 0, 5, 1);

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `id` int(11) NOT NULL,
  `nis` varchar(45) DEFAULT NULL,
  `nama` varchar(100) NOT NULL,
  `jenis_kelamin` varchar(9) NOT NULL COMMENT 'Laki-Laki dan Perempuan',
  `tempat_lahir` varchar(45) NOT NULL,
  `tgl_lahir` date DEFAULT NULL,
  `agama` char(7) DEFAULT NULL,
  `alamat` varchar(255) NOT NULL,
  `tahun_masuk` year(4) NOT NULL,
  `foto` text,
  `status_id` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=pending, 1=aktif, 2=blok, 3=alumni, 4=deleted'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`id`, `nis`, `nama`, `jenis_kelamin`, `tempat_lahir`, `tgl_lahir`, `agama`, `alamat`, `tahun_masuk`, `foto`, `status_id`) VALUES
(1, '12345', 'Bima Alvarendra', 'Laki-Laki', 'Rasu Jaya', '2004-05-17', 'ISLAM', 'Jl. Mt. Haryono, RT 003 RW 001 No. 16', 2018, NULL, 1),
(2, '12345678', 'Siswa Coba', 'Perempuan', '', NULL, '', '', 2017, NULL, 3),
(3, '000111', 'Yudi Susilo', 'Laki-Laki', '', NULL, '', '', 2016, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tk_anggota`
--

CREATE TABLE `tk_anggota` (
  `id` int(11) NOT NULL,
  `kelompok_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tk_anggota`
--

INSERT INTO `tk_anggota` (`id`, `kelompok_id`, `siswa_id`) VALUES
(1, 1, 2),
(2, 1, 1),
(3, 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `tk_kelompok`
--

CREATE TABLE `tk_kelompok` (
  `id` int(11) NOT NULL,
  `tugas_id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `intruksi` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tk_kelompok`
--

INSERT INTO `tk_kelompok` (`id`, `tugas_id`, `nama`, `intruksi`) VALUES
(1, 1, 'Kelompok A', '<p>Kerjakan bersama</p>');

-- --------------------------------------------------------

--
-- Table structure for table `tk_kerjaan`
--

CREATE TABLE `tk_kerjaan` (
  `id` int(11) NOT NULL,
  `anggota_id` int(11) NOT NULL,
  `konten` text NOT NULL,
  `tgl_input` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tk_nilai_anggota`
--

CREATE TABLE `tk_nilai_anggota` (
  `id` int(11) NOT NULL,
  `kelompok_id` int(11) NOT NULL,
  `anggota_id` int(11) NOT NULL,
  `nilai` float NOT NULL,
  `catatan` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tk_nilai_kelompok`
--

CREATE TABLE `tk_nilai_kelompok` (
  `id` int(11) NOT NULL,
  `kelompok_id` int(11) NOT NULL,
  `nilai` float NOT NULL,
  `catatan` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tk_penilaian_anggota`
--

CREATE TABLE `tk_penilaian_anggota` (
  `id` int(11) NOT NULL,
  `kelompok_id` int(11) NOT NULL,
  `penilai_anggota_id` int(11) NOT NULL,
  `anggota_id` int(11) NOT NULL,
  `nilai` varchar(3) NOT NULL,
  `alasan` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tk_tugas`
--

CREATE TABLE `tk_tugas` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `mapel_id` int(11) NOT NULL,
  `kelas_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL COMMENT '1=draft, 2=terbit, 3=tutup',
  `pengajar_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tk_tugas`
--

INSERT INTO `tk_tugas` (`id`, `judul`, `mapel_id`, `kelas_id`, `status`, `pengajar_id`) VALUES
(1, 'Tugas Kelompok 1', 6, 7, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tugas`
--

CREATE TABLE `tugas` (
  `id` int(11) NOT NULL,
  `mapel_id` int(11) NOT NULL,
  `pengajar_id` int(11) NOT NULL,
  `type_id` tinyint(1) NOT NULL COMMENT '1=upload,2=essay,3=ganda',
  `judul` varchar(255) NOT NULL,
  `durasi` int(11) DEFAULT NULL COMMENT 'lama pengerjaan dalam menit',
  `info` text,
  `aktif` tinyint(1) NOT NULL DEFAULT '0',
  `tgl_buat` datetime DEFAULT NULL,
  `tampil_siswa` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=tidak tampil di siswa, 1=tampil siswa'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tugas`
--

INSERT INTO `tugas` (`id`, `mapel_id`, `pengajar_id`, `type_id`, `judul`, `durasi`, `info`, `aktif`, `tgl_buat`, `tampil_siswa`) VALUES
(2, 6, 1, 3, 'Ulangan Harian ke-1', 120, '<p>Ulangan Harian ke-1</p>\r\n', 0, '2019-07-10 20:43:55', 1),
(3, 6, 1, 2, 'ESSAY1', 15, '<p>Essay 1</p>\r\n', 0, '2019-07-10 20:54:03', 1),
(4, 6, 1, 1, 'Portofolio', NULL, '<p>Kumpul Tugas</p>\r\n', 0, '2019-07-10 20:58:49', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tugas_kelas`
--

CREATE TABLE `tugas_kelas` (
  `id` int(11) NOT NULL,
  `tugas_id` int(11) NOT NULL,
  `kelas_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tugas_kelas`
--

INSERT INTO `tugas_kelas` (`id`, `tugas_id`, `kelas_id`) VALUES
(2, 2, 7),
(3, 3, 7),
(4, 4, 7);

-- --------------------------------------------------------

--
-- Table structure for table `tugas_pertanyaan`
--

CREATE TABLE `tugas_pertanyaan` (
  `id` int(11) NOT NULL,
  `pertanyaan` text NOT NULL,
  `urutan` int(11) NOT NULL,
  `tugas_id` int(11) NOT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tugas_pertanyaan`
--

INSERT INTO `tugas_pertanyaan` (`id`, `pertanyaan`, `urutan`, `tugas_id`, `aktif`) VALUES
(1, '<p>Apaan sih?</p>\r\n', 1, 2, 1),
(2, '<p>Makan apa?</p>\r\n', 2, 2, 1),
(3, '<p>apa hayo?</p>\r\n', 1, 3, 1),
(4, '<p>Makan apa?</p>\r\n', 2, 3, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bank_soal`
--
ALTER TABLE `bank_soal`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `field_tambahan`
--
ALTER TABLE `field_tambahan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kd_mapel`
--
ALTER TABLE `kd_mapel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kd_tugas`
--
ALTER TABLE `kd_tugas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kd_tugas_kd`
--
ALTER TABLE `kd_tugas_kd`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`),
  ADD KEY `parent_id_2` (`parent_id`);

--
-- Indexes for table `kelas_siswa`
--
ALTER TABLE `kelas_siswa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kelas_id` (`kelas_id`,`siswa_id`),
  ADD KEY `kelas_id_2` (`kelas_id`,`siswa_id`);

--
-- Indexes for table `komentar`
--
ALTER TABLE `komentar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `login_id` (`login_id`,`materi_id`),
  ADD KEY `login_id_2` (`login_id`,`materi_id`),
  ADD KEY `login_id_3` (`login_id`,`materi_id`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`,`siswa_id`,`pengajar_id`),
  ADD KEY `username_2` (`username`,`siswa_id`,`pengajar_id`);

--
-- Indexes for table `login_log`
--
ALTER TABLE `login_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `login_id` (`login_id`),
  ADD KEY `login_id_2` (`login_id`),
  ADD KEY `login_id_3` (`login_id`);

--
-- Indexes for table `mapel`
--
ALTER TABLE `mapel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mapel_ajar`
--
ALTER TABLE `mapel_ajar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hari_id` (`hari_id`,`pengajar_id`,`mapel_kelas_id`),
  ADD KEY `hari_id_2` (`hari_id`,`pengajar_id`,`mapel_kelas_id`);

--
-- Indexes for table `mapel_kelas`
--
ALTER TABLE `mapel_kelas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kelas_id` (`kelas_id`,`mapel_id`),
  ADD KEY `kelas_id_2` (`kelas_id`,`mapel_id`);

--
-- Indexes for table `materi`
--
ALTER TABLE `materi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mapel_id` (`mapel_id`,`pengajar_id`,`siswa_id`),
  ADD KEY `mapel_id_2` (`mapel_id`,`pengajar_id`,`siswa_id`);

--
-- Indexes for table `materi_kelas`
--
ALTER TABLE `materi_kelas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `materi_id` (`materi_id`,`kelas_id`),
  ADD KEY `materi_id_2` (`materi_id`,`kelas_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type_id` (`type_id`,`owner_id`,`sender_receiver_id`),
  ADD KEY `type_id_2` (`type_id`,`owner_id`,`sender_receiver_id`);

--
-- Indexes for table `nilai_tugas`
--
ALTER TABLE `nilai_tugas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tugas_id` (`tugas_id`,`siswa_id`),
  ADD KEY `tugas_id_2` (`tugas_id`,`siswa_id`);

--
-- Indexes for table `pengajar`
--
ALTER TABLE `pengajar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nip` (`nip`),
  ADD KEY `nip_2` (`nip`);

--
-- Indexes for table `pengaturan`
--
ALTER TABLE `pengaturan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pengumuman`
--
ALTER TABLE `pengumuman`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pengajar_id` (`pengajar_id`),
  ADD KEY `pengajar_id_2` (`pengajar_id`),
  ADD KEY `pengajar_id_3` (`pengajar_id`);

--
-- Indexes for table `pilihan`
--
ALTER TABLE `pilihan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pertanyaan_id` (`pertanyaan_id`),
  ADD KEY `pertanyaan_id_2` (`pertanyaan_id`,`kunci`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nis` (`nis`,`nama`,`status_id`),
  ADD KEY `nis_2` (`nis`,`nama`,`status_id`);

--
-- Indexes for table `tk_anggota`
--
ALTER TABLE `tk_anggota`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tk_kelompok`
--
ALTER TABLE `tk_kelompok`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tk_kerjaan`
--
ALTER TABLE `tk_kerjaan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tk_nilai_anggota`
--
ALTER TABLE `tk_nilai_anggota`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tk_nilai_kelompok`
--
ALTER TABLE `tk_nilai_kelompok`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tk_penilaian_anggota`
--
ALTER TABLE `tk_penilaian_anggota`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tk_tugas`
--
ALTER TABLE `tk_tugas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tugas`
--
ALTER TABLE `tugas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mapel_id` (`mapel_id`,`pengajar_id`,`type_id`),
  ADD KEY `mapel_id_2` (`mapel_id`,`pengajar_id`,`type_id`);

--
-- Indexes for table `tugas_kelas`
--
ALTER TABLE `tugas_kelas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tugas_id` (`tugas_id`,`kelas_id`),
  ADD KEY `tugas_id_2` (`tugas_id`,`kelas_id`);

--
-- Indexes for table `tugas_pertanyaan`
--
ALTER TABLE `tugas_pertanyaan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tugas_id` (`tugas_id`),
  ADD KEY `tugas_id_2` (`tugas_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bank_soal`
--
ALTER TABLE `bank_soal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `kd_mapel`
--
ALTER TABLE `kd_mapel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `kd_tugas`
--
ALTER TABLE `kd_tugas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `kd_tugas_kd`
--
ALTER TABLE `kd_tugas_kd`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `kelas_siswa`
--
ALTER TABLE `kelas_siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `komentar`
--
ALTER TABLE `komentar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `login_log`
--
ALTER TABLE `login_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;
--
-- AUTO_INCREMENT for table `mapel`
--
ALTER TABLE `mapel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT for table `mapel_ajar`
--
ALTER TABLE `mapel_ajar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `mapel_kelas`
--
ALTER TABLE `mapel_kelas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=222;
--
-- AUTO_INCREMENT for table `materi`
--
ALTER TABLE `materi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `materi_kelas`
--
ALTER TABLE `materi_kelas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `nilai_tugas`
--
ALTER TABLE `nilai_tugas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `pengajar`
--
ALTER TABLE `pengajar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `pengumuman`
--
ALTER TABLE `pengumuman`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `pilihan`
--
ALTER TABLE `pilihan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `tk_anggota`
--
ALTER TABLE `tk_anggota`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `tk_kelompok`
--
ALTER TABLE `tk_kelompok`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tk_kerjaan`
--
ALTER TABLE `tk_kerjaan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tk_nilai_anggota`
--
ALTER TABLE `tk_nilai_anggota`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tk_nilai_kelompok`
--
ALTER TABLE `tk_nilai_kelompok`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tk_penilaian_anggota`
--
ALTER TABLE `tk_penilaian_anggota`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tk_tugas`
--
ALTER TABLE `tk_tugas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tugas`
--
ALTER TABLE `tugas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `tugas_kelas`
--
ALTER TABLE `tugas_kelas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `tugas_pertanyaan`
--
ALTER TABLE `tugas_pertanyaan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
