DROP TABLE IF EXISTS `ladder_users`;
DROP TABLE IF EXISTS `challenges`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `ladders`;

CREATE TABLE `ladders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `access` text NOT NULL,
  `type` tinyint(3) unsigned NOT NULL,
  `status` tinyint(3) unsigned NOT NULL,
  `updated_at` timestamp NOT NULL,
  `created_at` timestamp NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` char(32) NOT NULL,
  `site_admin` tinyint(1) NOT NULL,
  `ladder_id` int(10) unsigned NOT NULL,
  `updated_at` timestamp NOT NULL,
  `created_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `ladder_id_idx` (`ladder_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

CREATE TABLE `challenges` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ladder_id` int(10) unsigned NOT NULL,
  `note` text NOT NULL,
  `player1_id` int(10) unsigned NOT NULL,
  `player2_id` int(10) unsigned NOT NULL,
  `player1_result` tinyint(3) unsigned NOT NULL,
  `player2_result` tinyint(3) unsigned NOT NULL,
  `updated_at` timestamp NOT NULL,
  `created_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ladder_id_idx` (`ladder_id`),
  KEY `player1_id_idx` (`player1_id`),
  KEY `player2_id_idx` (`player2_id`),
  CONSTRAINT `challenge_ladder_id_ladder_id` FOREIGN KEY (`ladder_id`) REFERENCES `ladders` (`id`),
  CONSTRAINT `challenge_player1_id_user_id` FOREIGN KEY (`player1_id`) REFERENCES `users` (`id`),
  CONSTRAINT `challenge_player2_id_user_id` FOREIGN KEY (`player2_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `ladder_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `ladder_id` int(10) unsigned NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `rank` int(10) unsigned NOT NULL,
  `max_challenges` int(10) unsigned NOT NULL DEFAULT '999',
  `wins` int(10) unsigned NOT NULL,
  `losses` int(10) unsigned NOT NULL,
  `challenge_count` smallint(5) unsigned NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL,
  `created_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ladder_id_idx` (`ladder_id`),
  KEY `user_id_idx` (`user_id`),
  CONSTRAINT `ladder__user_ladder_id_ladder_id` FOREIGN KEY (`ladder_id`) REFERENCES `ladders` (`id`),
  CONSTRAINT `ladder__user_user_id_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


INSERT INTO `users` VALUES 
(1,'Andy','a@a.com','ae2b134d94a1a0631a66c817ebb11a3b',0,1,'0000-00-00 00:00:00','0000-00-00 00:00:00'),
(2,'Bob','b@b.com','ae2b134d94a1a0631a66c817ebb11a3b',0,1,'0000-00-00 00:00:00','0000-00-00 00:00:00'),
(3,'Chuck','c@c.com','ae2b134d94a1a0631a66c817ebb11a3b',0,1,'0000-00-00 00:00:00','0000-00-00 00:00:00');

INSERT INTO `ladders`(id, name) VALUES
(1, 'Zulu'),
(2, 'Yankee');

INSERT INTO `challenges`(id, ladder_id, player1_id, player2_id) VALUES
(1, 1, 1, 2),
(2, 1, 3, 1),
(3, 1, 3, 2);
