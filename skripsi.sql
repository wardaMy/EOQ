-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 29 Jul 2024 pada 07.42
-- Versi server: 11.3.2-MariaDB
-- Versi PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `skripsi`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `biaya_lain`
--

CREATE TABLE `biaya_lain` (
  `id_biayalain` int(11) NOT NULL,
  `biaya_kebersihan` int(11) NOT NULL,
  `biaya_listrik` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data untuk tabel `biaya_lain`
--

INSERT INTO `biaya_lain` (`id_biayalain`, `biaya_kebersihan`, `biaya_listrik`) VALUES
(1, 300000, 300000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_beli`
--

CREATE TABLE `data_beli` (
  `id_pembelian` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `tgl_pembelian` date NOT NULL,
  `id_material` int(11) NOT NULL,
  `jumlah_pembelian` int(11) NOT NULL,
  `harga_beli` int(11) NOT NULL,
  `total_biaya` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data untuk tabel `data_beli`
--

INSERT INTO `data_beli` (`id_pembelian`, `id_user`, `tgl_pembelian`, `id_material`, `jumlah_pembelian`, `harga_beli`, `total_biaya`) VALUES
(3, 1, '2024-07-01', 4, 100, 30000, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `eoq`
--

CREATE TABLE `eoq` (
  `id_eoq` int(11) NOT NULL,
  `biaya_simpan` int(11) NOT NULL,
  `biaya_pesan` int(11) NOT NULL,
  `tahun_eoq` int(11) NOT NULL,
  `rop` int(11) NOT NULL,
  `safety_stock` int(11) NOT NULL,
  `frekuensi` int(11) NOT NULL,
  `hasil_eoq` int(11) NOT NULL,
  `total_biaya` int(11) NOT NULL,
  `id_material` int(11) NOT NULL,
  `id_permintaan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Struktur dari tabel `forecast_penjualan`
--

CREATE TABLE `forecast_penjualan` (
  `id_forecast` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `month` int(11) NOT NULL,
  `predicted_production` int(11) NOT NULL,
  `id_product` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data untuk tabel `forecast_penjualan`
--

INSERT INTO `forecast_penjualan` (`id_forecast`, `year`, `month`, `predicted_production`, `id_product`) VALUES
(1, 2024, 1, 66, 1),
(2, 2024, 1, 41, 2),
(3, 2024, 1, 16, 3),
(4, 2024, 2, 67, 1),
(5, 2024, 2, 42, 2),
(6, 2024, 2, 16, 3),
(7, 2024, 3, 69, 1),
(8, 2024, 3, 43, 2),
(9, 2024, 3, 17, 3),
(10, 2024, 4, 71, 1),
(11, 2024, 4, 44, 2),
(12, 2024, 4, 17, 3),
(13, 2024, 5, 72, 1),
(14, 2024, 5, 45, 2),
(15, 2024, 5, 18, 3),
(16, 2024, 6, 74, 1),
(17, 2024, 6, 45, 2),
(18, 2024, 6, 18, 3),
(19, 2024, 7, 76, 1),
(20, 2024, 7, 46, 2),
(21, 2024, 7, 18, 3),
(22, 2024, 8, 77, 1),
(23, 2024, 8, 47, 2),
(24, 2024, 8, 19, 3),
(25, 2024, 9, 79, 1),
(26, 2024, 9, 48, 2),
(27, 2024, 9, 19, 3),
(28, 2024, 10, 81, 1),
(29, 2024, 10, 49, 2),
(30, 2024, 10, 20, 3),
(31, 2024, 11, 82, 1),
(32, 2024, 11, 50, 2),
(33, 2024, 11, 20, 3),
(34, 2024, 12, 84, 1),
(35, 2024, 12, 51, 2),
(36, 2024, 12, 20, 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `material`
--

CREATE TABLE `material` (
  `id_material` int(11) NOT NULL,
  `nama_material` varchar(32) NOT NULL,
  `satuan_material` varchar(32) NOT NULL,
  `stock_material` int(11) NOT NULL,
  `biaya_transport` int(11) NOT NULL,
  `biaya_telepon` int(11) NOT NULL,
  `penggunaan` int(11) NOT NULL,
  `lead_time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data untuk tabel `material`
--

INSERT INTO `material` (`id_material`, `nama_material`, `satuan_material`, `stock_material`, `biaya_transport`, `biaya_telepon`, `penggunaan`, `lead_time`) VALUES
(1, 'Kain katun oblong', 'gulung', 0, 10000, 0, 15, 1),
(2, 'Kain katun jempol', 'gulung', 0, 10000, 0, 15, 1),
(3, 'Kain katun lar', 'gulung', 0, 10000, 0, 15, 1),
(4, 'Malam', 'kg', 0, 39000, 10000, 2, 2),
(5, 'Pewarna', 'liter', 0, 5000, 0, 12, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `penjualan`
--

CREATE TABLE `penjualan` (
  `id_penjualan` int(11) NOT NULL,
  `tgl_penjualan` date NOT NULL,
  `jml_penjualan` int(11) NOT NULL,
  `id_product` int(11) NOT NULL,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data untuk tabel `penjualan`
--

INSERT INTO `penjualan` (`id_penjualan`, `tgl_penjualan`, `jml_penjualan`, `id_product`, `id_user`) VALUES
(6, '2021-01-31', 10, 1, 2),
(7, '2021-02-28', 10, 1, 2),
(8, '2021-03-30', 12, 1, 2),
(9, '2021-04-30', 12, 1, 2),
(10, '2021-05-30', 12, 1, 2),
(11, '2021-06-30', 12, 1, 2),
(12, '2021-07-30', 15, 1, 2),
(13, '2021-08-30', 18, 1, 2),
(14, '2021-09-30', 20, 1, 2),
(15, '2021-10-30', 20, 1, 2),
(16, '2021-11-30', 20, 1, 2),
(17, '2021-12-30', 20, 1, 2),
(18, '2022-01-30', 25, 1, 2),
(19, '2022-02-28', 25, 1, 2),
(20, '2022-03-30', 30, 1, 2),
(21, '2022-04-30', 30, 1, 2),
(22, '2022-05-30', 30, 1, 2),
(23, '2022-06-30', 35, 1, 2),
(24, '2022-07-30', 35, 1, 2),
(25, '2022-08-30', 35, 1, 2),
(26, '2022-09-30', 35, 1, 2),
(27, '2022-10-30', 45, 1, 2),
(28, '2022-11-30', 40, 1, 2),
(29, '2022-12-30', 45, 1, 2),
(30, '2023-01-30', 45, 1, 2),
(31, '2023-02-28', 45, 1, 2),
(32, '2023-03-30', 50, 1, 2),
(33, '2023-04-30', 50, 1, 2),
(34, '2023-05-30', 55, 1, 2),
(35, '2023-06-30', 55, 1, 2),
(36, '2023-07-30', 55, 1, 2),
(37, '2023-08-30', 55, 1, 2),
(38, '2023-09-30', 60, 1, 2),
(39, '2023-10-30', 60, 1, 2),
(40, '2023-11-30', 65, 1, 2),
(41, '2023-12-30', 65, 1, 2),
(42, '2022-01-31', 6, 2, 2),
(43, '2021-02-28', 7, 2, 2),
(44, '2021-03-30', 7, 2, 2),
(45, '2021-04-30', 8, 2, 2),
(46, '2021-05-30', 8, 2, 2),
(47, '2021-06-30', 10, 2, 2),
(48, '2021-07-30', 13, 2, 2),
(49, '2021-08-30', 13, 2, 2),
(50, '2021-09-30', 13, 2, 2),
(51, '2021-10-30', 15, 2, 2),
(52, '2021-11-30', 16, 2, 2),
(53, '2021-12-30', 16, 2, 2),
(54, '2022-01-30', 18, 2, 2),
(55, '2022-02-28', 18, 2, 2),
(56, '2022-03-30', 20, 2, 2),
(57, '2022-04-30', 20, 2, 2),
(58, '2022-05-30', 22, 2, 2),
(59, '2022-06-30', 23, 2, 2),
(60, '2022-07-30', 25, 2, 2),
(61, '2022-08-30', 25, 2, 2),
(62, '2022-09-30', 26, 2, 2),
(63, '2022-10-30', 26, 2, 2),
(64, '2022-11-30', 28, 2, 2),
(65, '2022-12-30', 28, 2, 2),
(66, '2023-01-30', 29, 2, 2),
(67, '2023-02-28', 30, 2, 2),
(68, '2023-03-30', 30, 2, 2),
(69, '2023-04-30', 32, 2, 2),
(70, '2023-05-30', 32, 2, 2),
(71, '2023-06-30', 32, 2, 2),
(72, '2023-07-30', 35, 2, 2),
(73, '2023-08-30', 35, 2, 2),
(74, '2023-09-30', 35, 2, 2),
(75, '2023-10-30', 35, 2, 2),
(76, '2023-11-30', 40, 2, 2),
(77, '2023-12-30', 40, 2, 2),
(78, '2021-01-31', 1, 3, 2),
(79, '2021-02-28', 1, 3, 2),
(80, '2021-03-30', 2, 3, 2),
(81, '2021-04-30', 2, 3, 2),
(82, '2021-05-30', 3, 3, 2),
(83, '2021-06-30', 3, 3, 2),
(84, '2021-07-30', 3, 3, 2),
(85, '2021-08-30', 4, 3, 2),
(86, '2021-09-30', 4, 3, 2),
(87, '2021-10-30', 4, 3, 2),
(88, '2021-11-30', 5, 3, 2),
(89, '2021-12-30', 5, 3, 2),
(90, '2022-01-30', 6, 3, 2),
(91, '2022-02-28', 6, 3, 2),
(92, '2022-03-30', 6, 3, 2),
(93, '2022-04-30', 6, 3, 2),
(94, '2022-05-30', 7, 3, 2),
(95, '2022-06-30', 7, 3, 2),
(96, '2022-07-30', 9, 3, 2),
(97, '2022-08-30', 9, 3, 2),
(98, '2022-09-30', 9, 3, 2),
(99, '2022-10-30', 9, 3, 2),
(100, '2022-11-30', 10, 3, 2),
(101, '2022-12-30', 10, 3, 2),
(102, '2023-01-30', 11, 3, 2),
(103, '2023-02-28', 11, 3, 2),
(104, '2023-03-30', 12, 3, 2),
(105, '2023-04-30', 12, 3, 2),
(106, '2023-05-30', 12, 3, 2),
(107, '2023-06-30', 12, 3, 2),
(108, '2023-07-30', 12, 3, 2),
(109, '2023-08-30', 12, 3, 2),
(110, '2023-09-30', 15, 3, 2),
(111, '2023-10-30', 15, 3, 2),
(112, '2023-11-30', 15, 3, 2),
(113, '2023-12-30', 15, 3, 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `permintaan`
--

CREATE TABLE `permintaan` (
  `id_permintaan` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `jumlah_permintaan` int(11) NOT NULL,
  `id_material` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data untuk tabel `permintaan`
--

INSERT INTO `permintaan` (`id_permintaan`, `year`, `jumlah_permintaan`, `id_material`) VALUES
(26, 2024, 60, 1),
(27, 2024, 37, 2),
(28, 2024, 15, 3),
(29, 2024, 834, 4),
(30, 2024, 139, 5);

-- --------------------------------------------------------

--
-- Struktur dari tabel `product`
--

CREATE TABLE `product` (
  `id_product` int(11) NOT NULL,
  `nama_product` varchar(32) NOT NULL,
  `satuan` varchar(11) NOT NULL,
  `stock_product` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data untuk tabel `product`
--

INSERT INTO `product` (`id_product`, `nama_product`, `satuan`, `stock_product`) VALUES
(1, 'Batik Oblong', 'pcs', 0),
(2, 'Batik Jempol', 'pcs', 0),
(3, 'Batik Lar', 'pcs', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `username` varchar(32) NOT NULL,
  `pass` varchar(64) NOT NULL,
  `email` varchar(32) NOT NULL,
  `role` varchar(32) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id_user`, `username`, `pass`, `email`, `role`, `created_at`) VALUES
(1, 'admin', '$2y$10$.haR5ZOwVAg4qWXNolOhTuCk.zD/Aq/I6IiiGp74PgqqSjcAxG47W', 'warda.milana@gmail.com', 'Admin', '2024-07-07 10:48:32'),
(2, 'Intana', '$2y$10$GfgLoPUyuEii1CddfJpSS.ESUQSMvLv1ZLdGSAq0eDUhMGa9neW8q', 'Intana.bajumi@gmail.com', 'Owner', '2024-07-16 03:34:00'),
(3, 'ikawati', '$2y$10$rb4rJ28gS0nn47D9RD3xduolvQCk/TIhJB7N/KHqTi5u/TCA9iNnW', 'ikawatiarini@gmail.com', 'Admin', '2024-07-18 04:35:16');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `biaya_lain`
--
ALTER TABLE `biaya_lain`
  ADD PRIMARY KEY (`id_biayalain`);

--
-- Indeks untuk tabel `data_beli`
--
ALTER TABLE `data_beli`
  ADD PRIMARY KEY (`id_pembelian`),
  ADD KEY `id_material` (`id_material`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `eoq`
--
ALTER TABLE `eoq`
  ADD PRIMARY KEY (`id_eoq`),
  ADD KEY `id_material` (`id_material`),
  ADD KEY `id_permintaan` (`id_permintaan`);

--
-- Indeks untuk tabel `forecast_penjualan`
--
ALTER TABLE `forecast_penjualan`
  ADD PRIMARY KEY (`id_forecast`),
  ADD KEY `id_product` (`id_product`);

--
-- Indeks untuk tabel `material`
--
ALTER TABLE `material`
  ADD PRIMARY KEY (`id_material`);

--
-- Indeks untuk tabel `penjualan`
--
ALTER TABLE `penjualan`
  ADD PRIMARY KEY (`id_penjualan`),
  ADD KEY `id_product` (`id_product`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `permintaan`
--
ALTER TABLE `permintaan`
  ADD PRIMARY KEY (`id_permintaan`),
  ADD KEY `id_material` (`id_material`);

--
-- Indeks untuk tabel `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id_product`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `biaya_lain`
--
ALTER TABLE `biaya_lain`
  MODIFY `id_biayalain` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `data_beli`
--
ALTER TABLE `data_beli`
  MODIFY `id_pembelian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `eoq`
--
ALTER TABLE `eoq`
  MODIFY `id_eoq` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `forecast_penjualan`
--
ALTER TABLE `forecast_penjualan`
  MODIFY `id_forecast` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT untuk tabel `material`
--
ALTER TABLE `material`
  MODIFY `id_material` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `penjualan`
--
ALTER TABLE `penjualan`
  MODIFY `id_penjualan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;

--
-- AUTO_INCREMENT untuk tabel `permintaan`
--
ALTER TABLE `permintaan`
  MODIFY `id_permintaan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT untuk tabel `product`
--
ALTER TABLE `product`
  MODIFY `id_product` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `data_beli`
--
ALTER TABLE `data_beli`
  ADD CONSTRAINT `data_beli_ibfk_1` FOREIGN KEY (`id_material`) REFERENCES `material` (`id_material`),
  ADD CONSTRAINT `data_beli_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);

--
-- Ketidakleluasaan untuk tabel `eoq`
--
ALTER TABLE `eoq`
  ADD CONSTRAINT `eoq_ibfk_1` FOREIGN KEY (`id_material`) REFERENCES `material` (`id_material`),
  ADD CONSTRAINT `eoq_ibfk_2` FOREIGN KEY (`id_permintaan`) REFERENCES `permintaan` (`id_permintaan`);

--
-- Ketidakleluasaan untuk tabel `forecast_penjualan`
--
ALTER TABLE `forecast_penjualan`
  ADD CONSTRAINT `forecast_penjualan_ibfk_1` FOREIGN KEY (`id_product`) REFERENCES `product` (`id_product`);

--
-- Ketidakleluasaan untuk tabel `penjualan`
--
ALTER TABLE `penjualan`
  ADD CONSTRAINT `penjualan_ibfk_1` FOREIGN KEY (`id_product`) REFERENCES `product` (`id_product`),
  ADD CONSTRAINT `penjualan_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);

--
-- Ketidakleluasaan untuk tabel `permintaan`
--
ALTER TABLE `permintaan`
  ADD CONSTRAINT `permintaan_ibfk_1` FOREIGN KEY (`id_material`) REFERENCES `material` (`id_material`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
