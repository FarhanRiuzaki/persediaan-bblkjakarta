-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 11 Apr 2019 pada 02.43
-- Versi server: 5.7.24
-- Versi PHP: 7.2.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `z_base`
--

DELIMITER $$
--
-- Fungsi
--
CREATE DEFINER=`root`@`localhost` FUNCTION `SPLIT_STRING` (`str` VARCHAR(255), `delim` VARCHAR(12), `pos` INT) RETURNS VARCHAR(255) CHARSET latin1 RETURN REPLACE(SUBSTRING(SUBSTRING_INDEX(str, delim, pos),
       LENGTH(SUBSTRING_INDEX(str, delim, pos-1)) + 1),
       delim, '')$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `acl_phinxlog`
--

CREATE TABLE `acl_phinxlog` (
  `version` bigint(20) NOT NULL,
  `migration_name` varchar(100) DEFAULT NULL,
  `start_time` timestamp NULL DEFAULT NULL,
  `end_time` timestamp NULL DEFAULT NULL,
  `breakpoint` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data untuk tabel `acl_phinxlog`
--

INSERT INTO `acl_phinxlog` (`version`, `migration_name`, `start_time`, `end_time`, `breakpoint`) VALUES
(20141229162641, 'CakePhpDbAcl', '2018-02-01 03:04:00', '2018-02-01 03:04:01', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `acos`
--

CREATE TABLE `acos` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `name` varchar(225) CHARACTER SET utf8 DEFAULT NULL,
  `model` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `foreign_key` int(11) DEFAULT NULL,
  `alias` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `lft` int(11) DEFAULT NULL,
  `rght` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0',
  `sort` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data untuk tabel `acos`
--

INSERT INTO `acos` (`id`, `parent_id`, `name`, `model`, `foreign_key`, `alias`, `lft`, `rght`, `status`, `sort`) VALUES
(1, NULL, 'controllers', NULL, NULL, 'controllers', 1, 112, 0, 0),
(2, 1, 'Error', NULL, NULL, 'Error', 2, 3, 1, 0),
(3, 1, 'Pages', NULL, NULL, 'Pages', 4, 15, 1, 0),
(19, 1, 'Acl', NULL, NULL, 'Acl', 16, 17, 1, 0),
(20, 1, 'Bake', NULL, NULL, 'Bake', 18, 19, 1, 0),
(21, 1, 'DebugKit', NULL, NULL, 'DebugKit', 20, 47, 1, 0),
(22, 21, 'Composer', NULL, NULL, 'Composer', 21, 24, 1, 0),
(23, 22, 'checkDependencies', NULL, NULL, 'checkDependencies', 22, 23, 1, 0),
(24, 21, 'MailPreview', NULL, NULL, 'MailPreview', 25, 32, 1, 0),
(25, 24, 'index', NULL, NULL, 'index', 26, 27, 1, 0),
(26, 24, 'sent', NULL, NULL, 'sent', 28, 29, 1, 0),
(27, 24, 'email', NULL, NULL, 'email', 30, 31, 1, 0),
(28, 21, 'Panels', NULL, NULL, 'Panels', 33, 38, 1, 0),
(29, 28, 'index', NULL, NULL, 'index', 34, 35, 1, 0),
(30, 28, 'view', NULL, NULL, 'view', 36, 37, 1, 4),
(31, 21, 'Requests', NULL, NULL, 'Requests', 39, 42, 1, 0),
(32, 31, 'view', NULL, NULL, 'view', 40, 41, 1, 4),
(33, 21, 'Toolbar', NULL, NULL, 'Toolbar', 43, 46, 1, 0),
(34, 33, 'clearCache', NULL, NULL, 'clearCache', 44, 45, 1, 0),
(35, 1, 'Migrations', NULL, NULL, 'Migrations', 48, 49, 1, 0),
(56, 1, 'AuditStash', NULL, NULL, 'AuditStash', 50, 51, 1, 0),
(59, 1, 'Josegonzalez\\Upload', NULL, NULL, 'Josegonzalez\\Upload', 52, 53, 1, 0),
(60, 1, 'AppSettings', NULL, NULL, 'AppSettings', 54, 57, 0, 1000),
(61, 60, 'index', NULL, NULL, 'index', 55, 56, 0, 0),
(62, 1, 'Dashboard', NULL, NULL, 'Dashboard', 58, 61, 0, 0),
(63, 62, 'index', NULL, NULL, 'index', 59, 60, 0, 0),
(64, 1, 'Errors', NULL, NULL, 'Errors', 62, 65, 1, 0),
(65, 64, 'unauthorized', NULL, NULL, 'unauthorized', 63, 64, 1, 0),
(66, 1, 'Master Golongan', NULL, NULL, 'Groups', 66, 79, 0, 100),
(67, 66, 'index', NULL, NULL, 'index', 67, 68, 0, 0),
(68, 66, 'view', NULL, NULL, 'view', 69, 70, 0, 4),
(69, 66, 'add', NULL, NULL, 'add', 71, 72, 0, 1),
(70, 66, 'edit', NULL, NULL, 'edit', 73, 74, 0, 2),
(71, 66, 'delete', NULL, NULL, 'delete', 75, 76, 0, 3),
(72, 66, 'configure', NULL, NULL, 'configure', 77, 78, 1, 5),
(73, 3, 'index', NULL, NULL, 'index', 5, 6, 1, 0),
(74, 3, 'logout', NULL, NULL, 'logout', 7, 8, 1, 0),
(75, 3, 'editProfile', NULL, NULL, 'editProfile', 9, 10, 1, 0),
(76, 3, 'activitiesLog', NULL, NULL, 'activitiesLog', 11, 12, 1, 0),
(77, 1, 'Master Pegawai', NULL, NULL, 'Users', 80, 93, 0, 101),
(78, 77, 'index', NULL, NULL, 'index', 81, 82, 0, 0),
(79, 77, 'view', NULL, NULL, 'view', 83, 84, 0, 4),
(80, 77, 'add', NULL, NULL, 'add', 85, 86, 0, 1),
(81, 77, 'edit', NULL, NULL, 'edit', 87, 88, 0, 2),
(82, 77, 'delete', NULL, NULL, 'delete', 89, 90, 0, 3),
(83, 77, 'configure', NULL, NULL, 'configure', 91, 92, 0, 5),
(89, 3, NULL, NULL, NULL, 'uploadMedia', 13, 14, 1, 0),
(357, 1, 'User Group', NULL, NULL, 'UserGroups', 100, 111, 0, 0),
(358, 357, 'index', NULL, NULL, 'index', 101, 102, 0, 0),
(359, 357, 'view', NULL, NULL, 'view', 103, 104, 0, 0),
(360, 357, 'add', NULL, NULL, 'add', 105, 106, 0, 0),
(361, 357, 'edit', NULL, NULL, 'edit', 107, 108, 0, 0),
(362, 357, 'delete', NULL, NULL, 'delete', 109, 110, 0, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `app_settings`
--

CREATE TABLE `app_settings` (
  `id` int(11) NOT NULL,
  `keyField` varchar(225) CHARACTER SET latin1 NOT NULL,
  `valueField` varchar(225) CHARACTER SET latin1 NOT NULL,
  `type` enum('text','long text','image','select') CHARACTER SET latin1 NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data untuk tabel `app_settings`
--

INSERT INTO `app_settings` (`id`, `keyField`, `valueField`, `type`, `status`) VALUES
(1, 'App.Name', 'Z-APP-BASE APPLICATION', 'text', 0),
(2, 'App.Logo', '/webroot/img/logo.png', 'image', 0),
(3, 'App.Logo.Login', '/webroot/img/logo_login.png', 'image', 0),
(4, 'App.Logo.Width', '160', 'text', 0),
(5, 'App.Logo.Height', '28', 'text', 0),
(6, 'App.Logo.Login.Width', '400', 'text', 0),
(7, 'App.Logo.Login.Height', '71', 'text', 0),
(8, 'App.Login.Cover', '/webroot/assets/img/cover_login.jpg', 'image', 0),
(9, 'App.Description', 'Management application', 'long text', 0),
(10, 'App.Favico', '/webroot/img/favico.png', 'long text', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `aros`
--

CREATE TABLE `aros` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `model` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `foreign_key` int(11) DEFAULT NULL,
  `alias` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `lft` int(11) DEFAULT NULL,
  `rght` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data untuk tabel `aros`
--

INSERT INTO `aros` (`id`, `parent_id`, `model`, `foreign_key`, `alias`, `lft`, `rght`) VALUES
(1, NULL, 'UserGroups', 1, NULL, 1, 6),
(2, 1, 'Users', 1, NULL, 2, 3),
(4, 1, 'Users', 2, NULL, 4, 5);

-- --------------------------------------------------------

--
-- Struktur dari tabel `aros_acos`
--

CREATE TABLE `aros_acos` (
  `id` int(11) NOT NULL,
  `aro_id` int(11) NOT NULL,
  `aco_id` int(11) NOT NULL,
  `_create` varchar(2) CHARACTER SET utf8 NOT NULL DEFAULT '0',
  `_read` varchar(2) CHARACTER SET utf8 NOT NULL DEFAULT '0',
  `_update` varchar(2) CHARACTER SET utf8 NOT NULL DEFAULT '0',
  `_delete` varchar(2) CHARACTER SET utf8 NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data untuk tabel `aros_acos`
--

INSERT INTO `aros_acos` (`id`, `aro_id`, `aco_id`, `_create`, `_read`, `_update`, `_delete`) VALUES
(1, 1, 62, '1', '1', '1', '1'),
(2, 1, 63, '1', '1', '1', '1'),
(3, 1, 66, '1', '1', '1', '1'),
(4, 1, 67, '1', '1', '1', '1'),
(5, 1, 69, '1', '1', '1', '1'),
(6, 1, 70, '1', '1', '1', '1'),
(7, 1, 71, '1', '1', '1', '1'),
(8, 1, 68, '1', '1', '1', '1'),
(9, 1, 77, '1', '1', '1', '1'),
(10, 1, 78, '1', '1', '1', '1'),
(11, 1, 80, '1', '1', '1', '1'),
(12, 1, 81, '1', '1', '1', '1'),
(13, 1, 82, '1', '1', '1', '1'),
(14, 1, 79, '1', '1', '1', '1'),
(15, 1, 83, '1', '1', '1', '1'),
(16, 1, 60, '1', '1', '1', '1'),
(17, 1, 61, '1', '1', '1', '1'),
(18, 2, 62, '1', '1', '1', '1'),
(19, 2, 63, '1', '1', '1', '1'),
(20, 2, 66, '1', '1', '1', '1'),
(21, 2, 67, '1', '1', '1', '1'),
(22, 2, 69, '1', '1', '1', '1'),
(23, 2, 70, '1', '1', '1', '1'),
(24, 2, 71, '1', '1', '1', '1'),
(25, 2, 68, '1', '1', '1', '1'),
(26, 2, 77, '1', '1', '1', '1'),
(27, 2, 78, '1', '1', '1', '1'),
(28, 2, 80, '1', '1', '1', '1'),
(29, 2, 81, '1', '1', '1', '1'),
(30, 2, 82, '1', '1', '1', '1'),
(31, 2, 79, '1', '1', '1', '1'),
(32, 2, 83, '1', '1', '1', '1'),
(33, 2, 60, '1', '1', '1', '1'),
(34, 2, 61, '1', '1', '1', '1'),
(35, 1, 357, '1', '1', '1', '1'),
(36, 1, 360, '1', '1', '1', '1'),
(37, 1, 359, '1', '1', '1', '1'),
(38, 1, 358, '1', '1', '1', '1'),
(39, 1, 362, '1', '1', '1', '1'),
(40, 1, 361, '1', '1', '1', '1'),
(41, 2, 357, '1', '1', '1', '1'),
(42, 2, 360, '1', '1', '1', '1'),
(43, 2, 359, '1', '1', '1', '1'),
(44, 2, 358, '1', '1', '1', '1'),
(45, 2, 362, '1', '1', '1', '1'),
(46, 2, 361, '1', '1', '1', '1'),
(47, 4, 357, '1', '1', '1', '1'),
(48, 4, 360, '1', '1', '1', '1'),
(49, 4, 359, '1', '1', '1', '1'),
(50, 4, 358, '1', '1', '1', '1'),
(51, 4, 362, '1', '1', '1', '1'),
(52, 4, 361, '1', '1', '1', '1'),
(53, 4, 62, '1', '1', '1', '1'),
(54, 4, 63, '1', '1', '1', '1'),
(55, 4, 66, '1', '1', '1', '1'),
(56, 4, 67, '1', '1', '1', '1'),
(57, 4, 69, '1', '1', '1', '1'),
(58, 4, 70, '1', '1', '1', '1'),
(59, 4, 71, '1', '1', '1', '1'),
(60, 4, 68, '1', '1', '1', '1'),
(61, 4, 77, '1', '1', '1', '1'),
(62, 4, 78, '1', '1', '1', '1'),
(63, 4, 80, '1', '1', '1', '1'),
(64, 4, 81, '1', '1', '1', '1'),
(65, 4, 82, '1', '1', '1', '1'),
(66, 4, 79, '1', '1', '1', '1'),
(67, 4, 83, '1', '1', '1', '1'),
(68, 4, 60, '1', '1', '1', '1'),
(69, 4, 61, '1', '1', '1', '1');

-- --------------------------------------------------------

--
-- Struktur dari tabel `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_id` int(11) DEFAULT NULL,
  `controller` varchar(225) CHARACTER SET latin1 DEFAULT NULL,
  `_action` varchar(225) CHARACTER SET latin1 DEFAULT NULL,
  `type` varchar(250) CHARACTER SET latin1 DEFAULT NULL,
  `primary_key` int(11) DEFAULT NULL,
  `source` varchar(250) CHARACTER SET latin1 DEFAULT NULL,
  `parent_source` varchar(250) CHARACTER SET latin1 DEFAULT NULL,
  `original` text CHARACTER SET latin1,
  `changed` text CHARACTER SET latin1,
  `meta` text CHARACTER SET latin1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data untuk tabel `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `timestamp`, `user_id`, `controller`, `_action`, `type`, `primary_key`, `source`, `parent_source`, `original`, `changed`, `meta`) VALUES
(1, '2019-03-18 08:26:49', 1, 'delete', NULL, 'delete', 59964, 'users', NULL, NULL, NULL, '[]'),
(2, '2019-03-18 08:26:49', 1, 'delete', NULL, 'delete', 15, 'groups', NULL, NULL, NULL, '[]'),
(3, '2019-03-18 08:26:56', 1, 'delete', NULL, 'delete', 59963, 'users', NULL, NULL, NULL, '[]'),
(4, '2019-03-18 08:26:56', 1, 'delete', NULL, 'delete', 14, 'groups', NULL, NULL, NULL, '[]'),
(5, '2019-03-18 08:27:02', 1, 'delete', NULL, 'delete', 59962, 'users', NULL, NULL, NULL, '[]'),
(6, '2019-03-18 08:27:02', 1, 'delete', NULL, 'delete', 11, 'groups', NULL, NULL, NULL, '[]'),
(7, '2019-03-18 08:27:10', 1, 'delete', NULL, 'delete', 13, 'groups', NULL, NULL, NULL, '[]'),
(8, '2019-03-18 08:27:20', 1, 'delete', NULL, 'delete', 12, 'groups', NULL, NULL, NULL, '[]'),
(9, '2019-04-10 14:50:20', 1, 'add', NULL, 'create', 3, 'user_groups', NULL, '{\"id\":3,\"name\":\"Mantap\",\"status\":true,\"created_by\":1}', '{\"id\":3,\"name\":\"Mantap\",\"status\":true,\"created_by\":1}', '[]'),
(10, '2019-04-10 14:50:27', 1, 'delete', '3', 'delete', 3, 'user_groups', NULL, NULL, NULL, '[]'),
(11, '2019-04-10 15:03:10', 1, 'add', NULL, 'create', 2, 'users', NULL, '{\"id\":2,\"username\":\"farhanriuzaki\",\"name\":\"Farhan Riuzaki\",\"email\":\"riuzakif@gmail.com\",\"user_group_id\":1,\"status\":true,\"created_by\":1}', '{\"id\":2,\"username\":\"farhanriuzaki\",\"name\":\"Farhan Riuzaki\",\"email\":\"riuzakif@gmail.com\",\"user_group_id\":1,\"status\":true,\"created_by\":1}', '[]');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` char(60) NOT NULL,
  `name` varchar(225) DEFAULT NULL,
  `email` varchar(225) NOT NULL,
  `user_group_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `name`, `email`, `user_group_id`, `status`, `created_by`, `created`, `modified_by`, `modified`) VALUES
(1, 'administrator', '$2y$10$eZFD.eY6CfjVDcPtl6XBy.1Y/5726/ZQz9XKaWP7Jbw/8WXU440be', 'administrator', 'administrator@email.com', 1, 1, 1, '2019-03-26 12:54:54', 1, '2019-03-26 12:54:54'),
(2, 'farhanriuzaki', '$2y$10$R.JVOl97JlNHVOK6jFE89eDiWuBsnqRieFUGqEG1cmqoRtaWPVY82', 'Farhan Riuzaki', 'riuzakif@gmail.com', 1, 1, 1, '2019-04-10 15:03:09', NULL, '2019-04-10 15:03:09');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_groups`
--

CREATE TABLE `user_groups` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `user_groups`
--

INSERT INTO `user_groups` (`id`, `name`, `status`, `created_by`, `created`, `modified_by`, `modified`) VALUES
(1, 'ADMINISTRATOR', 1, 1, '2019-03-26 12:44:05', 1, '2019-03-26 12:44:22'),
(2, 'SPV', 0, 1, '2019-03-27 12:56:44', NULL, '2019-03-27 12:56:44');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `acl_phinxlog`
--
ALTER TABLE `acl_phinxlog`
  ADD PRIMARY KEY (`version`) USING BTREE;

--
-- Indeks untuk tabel `acos`
--
ALTER TABLE `acos`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `lft` (`lft`,`rght`) USING BTREE,
  ADD KEY `alias` (`alias`) USING BTREE;

--
-- Indeks untuk tabel `app_settings`
--
ALTER TABLE `app_settings`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indeks untuk tabel `aros`
--
ALTER TABLE `aros`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `lft` (`lft`,`rght`) USING BTREE,
  ADD KEY `alias` (`alias`) USING BTREE;

--
-- Indeks untuk tabel `aros_acos`
--
ALTER TABLE `aros_acos`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `aro_id` (`aro_id`,`aco_id`) USING BTREE,
  ADD KEY `aco_id` (`aco_id`) USING BTREE;

--
-- Indeks untuk tabel `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `username` (`username`) USING BTREE,
  ADD KEY `user_group` (`user_group_id`);

--
-- Indeks untuk tabel `user_groups`
--
ALTER TABLE `user_groups`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `acos`
--
ALTER TABLE `acos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=363;

--
-- AUTO_INCREMENT untuk tabel `app_settings`
--
ALTER TABLE `app_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `aros`
--
ALTER TABLE `aros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `aros_acos`
--
ALTER TABLE `aros_acos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT untuk tabel `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `user_groups`
--
ALTER TABLE `user_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
