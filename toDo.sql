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

-- Listage des données de la table todo.task : ~21 rows (environ)
/*!40000 ALTER TABLE `task` DISABLE KEYS */;
INSERT INTO `task` (`id`, `user_id`, `created_at`, `title`, `content`, `is_done`, `updated_at`) VALUES
	(1, 1, '2023-01-23 17:23:58', 'Titre de la tâche 0', 'Contenu de la tâche n° 0 pour le user Jane.', 0, '2023-01-23 17:23:58'),
	(2, 1, '2023-01-23 17:23:58', 'Titre de la tâche 1', 'Contenu de la tâche n° 1 pour le user Jane.', 0, '2023-01-23 17:23:58'),
	(3, 1, '2023-01-23 17:23:58', 'Titre de la tâche 2', 'Contenu de la tâche n° 2 pour le user Jane.', 0, '2023-01-23 17:23:58'),
	(4, 2, '2023-01-23 17:23:58', 'Titre de la tâche 3', 'Contenu de la tâche n° 3 pour le user Jane.', 0, '2023-01-23 17:23:58'),
	(5, 2, '2023-01-23 17:23:58', 'Titre de la tâche 4', 'Contenu de la tâche n° 4 pour le user Stan.', 0, '2023-01-23 17:23:58'),
	(6, 2, '2023-01-23 17:23:58', 'Titre de la tâche 5', 'Contenu de la tâche n° 5 pour le user Stan.', 0, '2023-01-23 17:23:58'),
	(7, 3, '2023-01-23 17:23:58', 'Titre de la tâche 6', 'Contenu de la tâche n° 6 pour le user Stan.', 1, '2023-01-23 17:23:58'),
	(8, 3, '2023-01-23 17:23:58', 'Titre de la tâche 7', 'Contenu de la tâche n° 7 pour le user Stan.', 0, '2023-01-23 17:23:58'),
	(9, 3, '2023-01-23 17:23:58', 'Titre de la tâche 8', 'Contenu de la tâche n° 8 pour le user Stan.', 0, '2023-01-23 17:23:58'),
	(10, 4, '2023-01-23 17:23:58', 'Titre de la tâche 9', 'Contenu de la tâche n° 9 pour le user Stan.', 0, '2023-01-23 17:23:58'),
	(11, 4, '2023-01-23 17:23:58', 'Titre de la tâche 10', 'Contenu de la tâche n° 10 pour le user Stan.', 0, '2023-01-23 17:23:58'),
	(12, 4, '2023-01-23 17:23:58', 'Titre de la tâche 11', 'Contenu de la tâche n° 11 pour le user Stan.', 0, '2023-01-23 17:23:58'),
	(13, 5, '2023-01-23 17:23:58', 'Titre de la tâche 12', 'Contenu de la tâche n° 12 pour le user Stan.', 0, '2023-01-23 17:23:58'),
	(14, 5, '2023-01-23 17:23:58', 'Titre de la tâche 13', 'Contenu de la tâche n° 13 pour le user Stan.', 0, '2023-01-23 17:23:58'),
	(15, 5, '2023-01-23 17:23:58', 'Titre de la tâche 14', 'Contenu de la tâche n° 14 pour le user Stan.', 0, '2023-01-23 17:23:58'),
	(16, 6, '2023-01-23 17:23:58', 'Titre de la tâche 15', 'Contenu de la tâche n° 15 pour le user Stan.', 0, '2023-01-23 17:23:58'),
	(17, 6, '2023-01-23 17:23:58', 'Titre de la tâche 16', 'Contenu de la tâche n° 16 pour le user Stan.', 0, '2023-01-23 17:23:58'),
	(18, 6, '2023-01-23 17:23:58', 'Titre de la tâche 17', 'Contenu de la tâche n° 17 pour le user Stan.', 0, '2023-01-23 17:23:58'),
	(19, 4, '2023-01-23 17:23:58', 'Titre de la tâche 18', 'Contenu de la tâche n° 18 pour le user Stan.', 0, '2023-01-23 17:23:58'),
	(20, 4, '2023-01-23 17:23:58', 'Titre de la tâche 19', 'Contenu de la tâche n° 19 pour le user Stan.', 0, '2023-01-23 17:23:58'),
	(21, 4, '2023-01-23 17:23:58', 'Titre de la tâche 20', 'Contenu de la tâche n° 20 pour le user Stan.', 0, '2023-01-23 17:23:58');
/*!40000 ALTER TABLE `task` ENABLE KEYS */;

-- Listage des données de la table todo.user : ~6 rows (environ)
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` (`id`, `email`, `roles`, `password`, `username`, `updated_at`, `created_at`) VALUES
	(1, 'jane@doe.com', '[]', '$2y$13$imVJx/5BUP0Ch0rzdnqlteNjGDifHBgAfU.UWsS/2L4weYWuZUfRm', 'Jane', '2022-12-18 11:48:10', '2021-12-18 11:48:11'),
	(2, 'Stanislas@lol.com', '[]', '$2y$13$imVJx/5BUP0Ch0rzdnqlteNjGDifHBgAfU.UWsS/2L4weYWuZUfRm', 'Stan', '2021-12-18 11:48:11', '2021-12-18 11:48:11'),
	(3, 'Eric@job.com', '[]', '$2y$13$imVJx/5BUP0Ch0rzdnqlteNjGDifHBgAfU.UWsS/2L4weYWuZUfRm', 'Eric', '2022-12-18 11:48:12', '2021-12-18 11:48:11'),
	(4, 'anonymous', '[]', 'passDoe', 'anonymous', '2022-12-18 11:48:13', '2021-12-18 11:48:11'),
	(5, 'admin@gmail.com', '["ROLE_ADMIN"]', '$2y$13$imVJx/5BUP0Ch0rzdnqlteNjGDifHBgAfU.UWsS/2L4weYWuZUfRm', 'Bossy', '2022-12-18 11:48:13', '2022-12-18 11:48:09'),
	(6, 'Fred@gmail.com', '[]', '$2y$13$imVJx/5BUP0Ch0rzdnqlteNjGDifHBgAfU.UWsS/2L4weYWuZUfRm', 'Fred', '2022-12-24 13:47:48', '2022-12-24 13:47:48');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
