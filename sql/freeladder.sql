DROP TABLE IF EXISTS `sessions`;
DROP TABLE IF EXISTS `rank_history`;
DROP TABLE IF EXISTS `matches`;
DROP TABLE IF EXISTS `ladder_users`;
DROP TABLE IF EXISTS `challenges`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `ladders`;

CREATE TABLE `ladders` (
  `id` INTEGER(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `code` VARCHAR(20) NOT NULL,
  `type` TINYINT(3) UNSIGNED NOT NULL,
  `status` TINYINT(3) UNSIGNED NOT NULL,
  `updated_at` INTEGER NOT NULL,
  `created_at` INTEGER NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `users` (
  `id` INTEGER(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` TEXT NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `password` CHAR(32) NOT NULL,
  `site_admin` TINYINT(1) NOT NULL,
  `ladder_id` INTEGER(10) UNSIGNED NOT NULL,
  `max_challenges` TINYINT UNSIGNED NOT NULL DEFAULT 255,
  `status` TINYINT UNSIGNED NOT NULL DEFAULT 0,
  `updated_at` INTEGER NOT NULL,
  `created_at` INTEGER NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `ladder_id_idx` (`ladder_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

CREATE TABLE `challenges` (
  `id` INTEGER(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ladder_id` INTEGER(10) UNSIGNED NOT NULL,
  `note` TEXT NOT NULL,
  `player1_id` INTEGER(10) UNSIGNED NOT NULL,
  `player2_id` INTEGER(10) UNSIGNED NOT NULL,
  `player1_result` TINYINT(3) NOT NULL,
  `player2_result` TINYINT(3) NOT NULL,
  `updated_at` INTEGER NOT NULL,
  `created_at` INTEGER NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ladder_id_idx` (`ladder_id`),
  KEY `player1_id_idx` (`player1_id`),
  KEY `player2_id_idx` (`player2_id`),
  CONSTRAINT `challenge_ladder_id_ladder_id` FOREIGN KEY (`ladder_id`) REFERENCES `ladders` (`id`),
  CONSTRAINT `challenge_player1_id_user_id` FOREIGN KEY (`player1_id`) REFERENCES `users` (`id`),
  CONSTRAINT `challenge_player2_id_user_id` FOREIGN KEY (`player2_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `ladder_users` (
  `id` INTEGER(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INTEGER(10) UNSIGNED NOT NULL,
  `ladder_id` INTEGER(10) UNSIGNED NOT NULL,
  `admin` TINYINT NOT NULL DEFAULT '0',
  `rank` INTEGER(10) UNSIGNED NOT NULL,
  `max_challenges` TINYINT UNSIGNED NOT NULL DEFAULT 255,
  `wins` INTEGER(10) UNSIGNED NOT NULL,
  `losses` INTEGER(10) UNSIGNED NOT NULL,
  `challenge_count` SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL,
  `created_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ladder_id_idx` (`ladder_id`),
  KEY `user_id_idx` (`user_id`),
  CONSTRAINT `ladder__user_ladder_id_ladder_id` FOREIGN KEY (`ladder_id`) REFERENCES `ladders` (`id`),
  CONSTRAINT `ladder__user_user_id_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

    
CREATE TABLE `matches` (
    `id` INTEGER PRIMARY KEY AUTO_INCREMENT,
    `ladder_id` INTEGER, 
    `date` INTEGER NOT NULL,
    `winner_id` INTEGER, 
    `loser_id` INTEGER,
    `forfeit` INTEGER NOT NULL DEFAULT 0,
    KEY `winner_id_idx` (`winner_id`),
    KEY `loser_id_idx` (`loser_id`)
    );

CREATE TABLE `rank_history` (
    `user_id` INTEGER NOT NULL,
    `ladder_id` INTEGER, 
    `date` INTEGER NOT NULL,
    `rank` INTEGER NOT NULL,
    KEY `user_id_idx` (`user_id`)
    );

CREATE TABLE IF NOT EXISTS  `sessions` (
    session_id varchar(40) DEFAULT '0' NOT NULL,
    ip_address varchar(16) DEFAULT '0' NOT NULL,
    user_agent varchar(50) NOT NULL,
    last_activity INTEGER(10) UNSIGNED DEFAULT 0 NOT NULL,
    user_data text NOT NULL,
    PRIMARY KEY (session_id)
);


INSERT INTO `users` VALUES 
(1,'Andy Sennheiser','a@a.com','ae2b134d94a1a0631a66c817ebb11a3b',0,1,255,0, UNIX_TIMESTAMP('2010-09-18'),UNIX_TIMESTAMP('2010-09-18')),
(2,'Robert Tannenbaum','b@b.com','ae2b134d94a1a0631a66c817ebb11a3b',0,1,255,0,UNIX_TIMESTAMP('2010-09-18'),UNIX_TIMESTAMP('2010-09-18')),
(3,'Chuck Bailey','c@c.com','ae2b134d94a1a0631a66c817ebb11a3b',0,1,255,0,UNIX_TIMESTAMP('2010-09-18'),UNIX_TIMESTAMP('2010-09-18'));

INSERT INTO `ladders`(id, name, code) VALUES
(1, 'Zulu', 'test'),
(2, 'Yankee', '');

INSERT INTO `challenges`(id, ladder_id, player1_id, player2_id) VALUES
(1, 1, 1, 2),
#(2, 1, 3, 1),
(3, 1, 3, 2);


INSERT INTO `ladder_users`(user_id, ladder_id, rank, wins, losses) VALUES
(1, 1, 3, 7, 3),
(2, 1, 2, 5, 4),
(3, 1, 1, 0, 4);

INSERT INTO `matches`(ladder_id, winner_id, loser_id, date) VALUES
(1, 1, 3, UNIX_TIMESTAMP('2010-09-18')),
(1, 1, 2, UNIX_TIMESTAMP('2010-09-18')),
(1, 1, 2, UNIX_TIMESTAMP('2010-09-18')),
(1, 1, 2, UNIX_TIMESTAMP('2010-09-18')),
(1, 2, 3, UNIX_TIMESTAMP('2010-09-18'));

