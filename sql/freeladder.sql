DROP TABLE IF EXISTS `ladder__user`;
DROP TABLE IF EXISTS `challenge`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `ladder`;

CREATE TABLE `ladder` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `access` text NOT NULL,
  `type` tinyint(3) unsigned NOT NULL,
  `status` tinyint(3) unsigned NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` char(32) NOT NULL,
  `site_admin` tinyint(1) NOT NULL,
  `ladder_id` int(10) unsigned NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `ladder_id_idx` (`ladder_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

CREATE TABLE `challenge` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ladder_id` int(10) unsigned NOT NULL,
  `note` text NOT NULL,
  `player1_id` int(10) unsigned NOT NULL,
  `player2_id` int(10) unsigned NOT NULL,
  `player1_result` tinyint(3) unsigned NOT NULL,
  `player2_result` tinyint(3) unsigned NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ladder_id_idx` (`ladder_id`),
  KEY `player1_id_idx` (`player1_id`),
  KEY `player2_id_idx` (`player2_id`),
  CONSTRAINT `challenge_ladder_id_ladder_id` FOREIGN KEY (`ladder_id`) REFERENCES `ladder` (`id`),
  CONSTRAINT `challenge_player1_id_user_id` FOREIGN KEY (`player1_id`) REFERENCES `users` (`id`),
  CONSTRAINT `challenge_player2_id_user_id` FOREIGN KEY (`player2_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `ladder__user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `ladder_id` int(10) unsigned NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `rank` int(10) unsigned NOT NULL,
  `max_challenges` int(10) unsigned NOT NULL DEFAULT '999',
  `wins` int(10) unsigned NOT NULL,
  `losses` int(10) unsigned NOT NULL,
  `challenge_count` smallint(5) unsigned NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ladder_id_idx` (`ladder_id`),
  KEY `user_id_idx` (`user_id`),
  CONSTRAINT `ladder__user_ladder_id_ladder_id` FOREIGN KEY (`ladder_id`) REFERENCES `ladder` (`id`),
  CONSTRAINT `ladder__user_user_id_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;







LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES 
(1,'test','a@a.com','ecae42e7e7db55dab930b56c286e2605',3,9,'0000-00-00 00:00:00','0000-00-00 00:00:00'),
(8,'','b@b.com','ae2b134d94a1a0631a66c817ebb11a3b',0,0,'0000-00-00 00:00:00','0000-00-00 00:00:00'),
(15,'','c@c.com','ae2b134d94a1a0631a66c817ebb11a3b',0,0,'0000-00-00 00:00:00','0000-00-00 00:00:00');
UNLOCK TABLES;
