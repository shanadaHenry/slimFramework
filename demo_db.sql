-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 18, 2018 at 11:34 AM
-- Server version: 10.1.35-MariaDB
-- PHP Version: 7.2.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `demo_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id_banners` int(11) NOT NULL,
  `nama_banners` varchar(250) NOT NULL,
  `imageBanners` varchar(250) NOT NULL,
  `url` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id_banners`, `nama_banners`, `imageBanners`, `url`) VALUES
(5, 'page1', 'uploads/page1498.png', 'my tech banners'),
(6, 'page2', 'uploads/page21298.svg', 'my tech banners');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id_cust` int(11) NOT NULL,
  `email_cust` varchar(125) NOT NULL,
  `password` varchar(125) NOT NULL,
  `nama_cust` varchar(125) NOT NULL,
  `nama_akhir` varchar(25) NOT NULL,
  `tahun_lahir` varchar(25) NOT NULL,
  `telp_cust` varchar(25) NOT NULL,
  `home_address` varchar(250) NOT NULL,
  `home_no` varchar(25) NOT NULL,
  `office_address` varchar(250) NOT NULL,
  `office_no` varchar(25) NOT NULL,
  `status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id_cust`, `email_cust`, `password`, `nama_cust`, `nama_akhir`, `tahun_lahir`, `telp_cust`, `home_address`, `home_no`, `office_address`, `office_no`, `status`) VALUES
(1, 'customers1@gmail.com', '5f4dcc3b5aa765d61d8327deb882cf99', 'customers-1', '', '', '', '', '', '', '', 'admin'),
(2, 'customers2@gmail.com', 'd27317413102cd418365ae6b10c0332a', 'customers-2', '', '', '081291349800', '', '', '', '', 'admin'),
(3, 'customers3@gmail.com', '5f4dcc3b5aa765d61d8327deb882cf99', 'mujahidin', 'amri', '', '678678678', '', '', '', '', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `data_order`
--

CREATE TABLE `data_order` (
  `id_data_order` int(5) NOT NULL,
  `id_order_menu` int(5) NOT NULL,
  `id_menu` int(3) NOT NULL,
  `kuantitas` int(2) NOT NULL,
  `total_harga` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `data_order`
--

INSERT INTO `data_order` (`id_data_order`, `id_order_menu`, `id_menu`, `kuantitas`, `total_harga`) VALUES
(1, 1, 2, 4, 60000),
(2, 1, 3, 8, 80000),
(3, 1, 1, 2, 20000),
(4, 2, 3, 10, 100000),
(10, 21, 1, 5, 50000);

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int(3) NOT NULL,
  `nama_kategori` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`) VALUES
(1, 'Kamar Mandi'),
(2, 'Pengecatan'),
(3, 'Dinding'),
(4, 'Pemindahan'),
(5, 'Lainnya'),
(6, 'Pembersihan'),
(7, 'AC'),
(8, 'Ledeng/Pompa Air'),
(9, 'Atap'),
(10, 'Tukang'),
(11, 'Lantai'),
(12, 'Parabola'),
(13, 'Internet/Wifi'),
(14, 'Listrik'),
(15, 'Mesin Cuci'),
(16, 'TV'),
(17, 'Kulkas'),
(18, 'Printer'),
(19, 'Komputer'),
(20, 'Mobil Derek'),
(21, 'Dapur'),
(22, 'Service Mobil');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id_menu` int(3) NOT NULL,
  `nama` varchar(30) NOT NULL,
  `harga` varchar(6) NOT NULL,
  `keterangan` text NOT NULL,
  `id_kategori` int(3) NOT NULL,
  `foto_menu` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id_menu`, `nama`, `harga`, `keterangan`, `id_kategori`, `foto_menu`) VALUES
(40, 'teh manis', '300', 'seger', 2, ''),
(41, 'seblak ', '8000', 'seblak maknyos', 1, ''),
(42, 'Nasi Goreng Gila', '8000', 'Mask gorengnya pedas dan gurih', 1, ''),
(43, 'Es Teh Manis', '3000', 'Gulanya tanpaa bahaan pengawet', 2, ''),
(44, 'Chiki Chuba', '1000', 'chuba enak bergizi', 3, '');

-- --------------------------------------------------------

--
-- Table structure for table `menu_favorit`
--

CREATE TABLE `menu_favorit` (
  `id_menu_favorit` int(3) NOT NULL,
  `id_menu` int(3) NOT NULL,
  `status` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `menu_favorit`
--

INSERT INTO `menu_favorit` (`id_menu_favorit`, `id_menu`, `status`) VALUES
(1, 1, 'aktif'),
(2, 2, 'nonaktif'),
(3, 3, 'aktif');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `id_kategori` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`id`, `title`, `message`, `date`, `id_kategori`) VALUES
(1, 'Hi Customers', 'Kita adalah myTech yang baru , selamat bergabung dan enjoy :)', '2018-10-18 08:08:12', '1'),
(2, 'hi technisi', 'Kita Sedang ada promo loh , Mau lihat klik link dibawah ini', '2018-10-18 07:28:23', '2'),
(3, 'pesan untuk customers', 'haii para customers', '2018-10-18 07:27:59', '1');

-- --------------------------------------------------------

--
-- Table structure for table `order_menu`
--

CREATE TABLE `order_menu` (
  `id_order_menu` int(5) NOT NULL,
  `id_user` int(3) NOT NULL,
  `status` varchar(10) NOT NULL,
  `datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `order_menu`
--

INSERT INTO `order_menu` (`id_order_menu`, `id_user`, `status`, `datetime`) VALUES
(1, 1, 'menunggu', '2018-08-11 00:00:00'),
(3, 1, 'selesai', '2018-08-11 00:00:00'),
(4, 1, 'diproses', '2018-08-11 00:00:00'),
(21, 1, 'menunggu', '2018-08-13 20:21:40');

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id_pembayaran` int(6) NOT NULL,
  `id_user` int(3) NOT NULL,
  `id_order_menu` int(6) NOT NULL,
  `total_harga` int(8) NOT NULL,
  `status` varchar(10) NOT NULL,
  `datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`id_pembayaran`, `id_user`, `id_order_menu`, `total_harga`, `status`, `datetime`) VALUES
(1, 1, 1, 160000, 'lunas', '2018-08-11 00:00:00'),
(2, 1, 2, 100000, 'menunggu', '2018-08-11 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `technisi`
--

CREATE TABLE `technisi` (
  `id_tech` int(11) NOT NULL,
  `id_kategori` int(3) NOT NULL,
  `telp_tech` varchar(25) NOT NULL,
  `nama_tech` varchar(125) NOT NULL,
  `nama_akhir` varchar(25) NOT NULL,
  `email_tech` varchar(125) NOT NULL,
  `password` varchar(125) NOT NULL,
  `tahun_lahir` varchar(5) NOT NULL,
  `pengalaman` varchar(10) NOT NULL,
  `harga` varchar(10) NOT NULL,
  `desk_tech` varchar(250) NOT NULL,
  `work_address` varchar(250) NOT NULL,
  `status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `technisi`
--

INSERT INTO `technisi` (`id_tech`, `id_kategori`, `telp_tech`, `nama_tech`, `nama_akhir`, `email_tech`, `password`, `tahun_lahir`, `pengalaman`, `harga`, `desk_tech`, `work_address`, `status`) VALUES
(1, 1, '08561333535', 'Technisi1', 'sukses', 'Technisi1@gmail.com', '5f4dcc3b5aa765d61d8327deb882cf99', '', '10', '75000', 'Pengalaman Service , setting, installasi printer, jaringan, hardware dan software pc/laptop/software windows', 'Erporate Insan Teknologi', 'user'),
(2, 1, '081291349800', 'Technisi2', 'semangat', 'technisi2@gmail.com', 'password', '', '5', '250000', 'Rajin Terampil berpengalaman . Bisa bergerak disemua medan ', 'Pademangan ', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id_tickets` int(11) NOT NULL,
  `id_kategori` int(11) NOT NULL,
  `cust_id` int(11) NOT NULL,
  `address` varchar(250) NOT NULL,
  `lokasi` varchar(250) NOT NULL,
  `no_rumah` varchar(125) NOT NULL,
  `masalah` varchar(250) NOT NULL,
  `desk_masalah` varchar(250) NOT NULL,
  `order_date` date NOT NULL,
  `checkIn` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`id_tickets`, `id_kategori`, `cust_id`, `address`, `lokasi`, `no_rumah`, `masalah`, `desk_masalah`, `order_date`, `checkIn`) VALUES
(1, 1, 1, 'jalan jeruk 1', 'bojong gede', '1', 'gambar jeelek', 'gambar runyek terus pas ada hujan', '0000-00-00', '2018-09-19 06:23:19'),
(2, 1, 2, 'Jalan Jeruk 2', 'tanaka electronic solutins ', '-', 'parabola jelek sangat', 'parabola selalu jelek ketika ada angin kencang ', '0000-00-00', '2018-09-19 06:23:19'),
(3, 2, 1, 'Jalan Jeruk 3', 'bojong gede', '1', 'gambar jeelek', 'gambar runyek terus pas ada hujan', '0000-00-00', '2018-09-19 06:23:19'),
(4, 1, 2, 'Jalan Jeruk 4', 'bojong gede', '1', 'gambar jeelek', 'gambar runyek terus pas ada hujan', '0000-00-00', ''),
(6, 2, 1, 'Jalan Jeruk 6', 'bojong gede', '1', 'gambar jeelek', 'gambar runyek terus pas ada hujan', '0000-00-00', ''),
(7, 1, 2, 'Jalan Jeruk 6', 'bojong gede', '1', 'gambar jeelek', 'gambar runyek terus pas ada hujan', '0000-00-00', '');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(3) NOT NULL,
  `username` varchar(15) NOT NULL,
  `nama_lengkap` varchar(20) NOT NULL,
  `password` varchar(35) NOT NULL,
  `status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `username`, `nama_lengkap`, `password`, `status`) VALUES
(1, 'admin', 'John Doe', '5F4DCC3B5AA765D61D8327DEB882CF99', 'admin'),
(2, 'user1', 'Meja 1', '5F4DCC3B5AA765D61D8327DEB882CF99', 'user'),
(3, 'user2', 'Meja 2', '5F4DCC3B5AA765D61D8327DEB882CF99', 'user'),
(4, 'user3', 'Meja 3', '5F4DCC3B5AA765D61D8327DEB882CF99', 'user'),
(5, 'user4', 'Meja 4', '5F4DCC3B5AA765D61D8327DEB882CF99', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id_banners`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id_cust`);

--
-- Indexes for table `data_order`
--
ALTER TABLE `data_order`
  ADD PRIMARY KEY (`id_data_order`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id_menu`);

--
-- Indexes for table `menu_favorit`
--
ALTER TABLE `menu_favorit`
  ADD PRIMARY KEY (`id_menu_favorit`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_menu`
--
ALTER TABLE `order_menu`
  ADD PRIMARY KEY (`id_order_menu`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`);

--
-- Indexes for table `technisi`
--
ALTER TABLE `technisi`
  ADD PRIMARY KEY (`id_tech`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id_tickets`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id_banners` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id_cust` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `data_order`
--
ALTER TABLE `data_order`
  MODIFY `id_data_order` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id_menu` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `menu_favorit`
--
ALTER TABLE `menu_favorit`
  MODIFY `id_menu_favorit` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `order_menu`
--
ALTER TABLE `order_menu`
  MODIFY `id_order_menu` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id_pembayaran` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `technisi`
--
ALTER TABLE `technisi`
  MODIFY `id_tech` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id_tickets` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
