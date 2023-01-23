-- --------------------------------------------------------
-- Hôte:                         127.0.0.1
-- Version du serveur:           5.7.33 - MySQL Community Server (GPL)
-- SE du serveur:                Win64
-- HeidiSQL Version:             11.2.0.6213
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Listage des données de la table todo_test.task : ~8 rows (environ)
/*!40000 ALTER TABLE `task` DISABLE KEYS */;
INSERT INTO `task` (`id`, `user_id`, `created_at`, `title`, `content`, `is_done`, `updated_at`) VALUES
	(1, 2, '2012-10-18 18:42:52', 'Test success', 'Test success', 0, '2023-01-01 16:02:36'),
	(2, 4, '2012-10-18 18:42:52', 'Test Anonymous', 'Test Anonymous', 1, '2012-10-18 18:42:52'),
	(3, 2, '2012-10-18 18:42:52', 'Test Ownership', 'You don\'t own this', 0, '2012-10-18 18:42:52'),
	(4, 2, '2012-10-18 18:42:52', 'testc7Qb85T', 'Test DOM', 1, '2023-01-01 16:02:36'),
	(5, 2, '2022-12-24 16:08:11', 'Test db', 'testAKHbHXU', 0, '2023-01-01 16:02:36'),
	(17, 2, '2022-12-24 16:25:08', 'Test edit', 'test578hnGJ', 0, '2022-12-29 19:07:16'),
	(18, 2, '2022-12-24 16:25:09', 'TestTitle', 'Test toggle', 1, '2022-12-24 16:25:09');
/*!40000 ALTER TABLE `task` ENABLE KEYS */;

-- Listage des données de la table todo_test.user : ~6 rows (environ)
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` (`id`, `email`, `roles`, `password`, `username`, `updated_at`, `created_at`) VALUES
	(1, 'admin@test.com', '["ROLE_ADMIN"]', '$2y$13$P1rA19Apz4qh.kffSn.qNu5jTg5aTJXiU4C3HRl1UYDWSz.9xlOUa', 'admin', '2022-12-18 11:50:20', '2012-12-18 11:50:22'),
	(2, 'user@test.com', '[]', '$2y$13$dRPC/0M1t6k6HE2PFlonTOsDj1lUa6gDAvatDZaARv6AHqCoiHEM6', 'user', '2021-12-18 11:50:20', '2012-12-18 11:50:22'),
	(3, 'noOwner@test.com', '[]', '$2y$13$dRPC/0M1t6k6HE2PFlonTOsDj1lUa6gDAvatDZaARv6AHqCoiHEM6', 'noOwner', '2012-12-18 11:50:22', '2012-12-18 11:50:22'),
	(4, 'anonymous', '[]', '$2y$13$dRPC/0M1t6k6HE2PFlonTOsDj1lUa6gDAvatDZaARv6AHqCoiHEM6', 'ano', '2012-12-18 11:50:22', '2012-12-18 11:50:22'),
	(5, 'modify', '[]', '$2y$13$dRPC/0M1t6k6HE2PFlonTOsDj1lUa6gDAvatDZaARv6AHqCoiHEM6', 'modify', '2012-12-18 11:50:22', '2012-12-18 11:50:22'),
	(6, 'edit@test.com', '[]', '$2y$04$.Gscp5SCLINxWLojUb5ntO2h9EB0m98Z/pWjlqj8Yxh5QKh.lZ3K2', 'I5xpNMA', '2023-01-01 16:02:37', '2012-12-18 11:50:22');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
