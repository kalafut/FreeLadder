DROP TABLE IF EXISTS `sessions`;
DROP TABLE IF EXISTS `rank_history`;
DROP TABLE IF EXISTS `matches`;
DROP TABLE IF EXISTS `ladder_users`;
DROP TABLE IF EXISTS `challenges`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `ladders`;

CREATE TABLE `ladders` (
  `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  `code` VARCHAR(20) NOT NULL,
  `type` TINYINT UNSIGNED NOT NULL,
  `challenge_window` TINYINT UNSIGNED NOT NULL DEFAULT 3,
  `window_direction` TINYINT UNSIGNED NOT NULL DEFAULT 0,
  `status` TINYINT UNSIGNED NOT NULL,
  `created_at` INTEGER NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `users` (
  `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `password` CHAR(32) NOT NULL,
  `site_admin` TINYINT NOT NULL,
  `ladder_id` INTEGER UNSIGNED,
  `max_challenges` TINYINT UNSIGNED NOT NULL DEFAULT 255,
  `status` TINYINT UNSIGNED NOT NULL DEFAULT 0,
  `created_at` INTEGER NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `ladder_id_idx` (`ladder_id`),
  FOREIGN KEY (`ladder_id`) REFERENCES `ladders` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `challenges` (
  `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  `ladder_id` INTEGER UNSIGNED NOT NULL,
  `note` TEXT NOT NULL,
  `player1_id` INTEGER UNSIGNED NOT NULL,
  `player2_id` INTEGER UNSIGNED NOT NULL,
  `player1_result` TINYINT NOT NULL,
  `player2_result` TINYINT NOT NULL,
  `updated_at` INTEGER NOT NULL,
  `created_at` INTEGER NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ladder_id_idx` (`ladder_id`),
  KEY `player1_id_idx` (`player1_id`),
  KEY `player2_id_idx` (`player2_id`),
  FOREIGN KEY (`ladder_id`) REFERENCES `ladders` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`player1_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`player2_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `ladder_users` (
  `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INTEGER UNSIGNED NOT NULL,
  `ladder_id` INTEGER UNSIGNED NOT NULL,
  `admin` TINYINT NOT NULL DEFAULT '0',
  `rank` INTEGER UNSIGNED NOT NULL,
  `max_challenges` TINYINT UNSIGNED NOT NULL DEFAULT 255,
  `wins` INTEGER UNSIGNED NOT NULL,
  `losses` INTEGER UNSIGNED NOT NULL,
  `challenge_count` SMALLINT UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ladder_id_idx` (`ladder_id`),
  KEY `user_id_idx` (`user_id`),
  FOREIGN KEY (`ladder_id`) REFERENCES `ladders` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

    
CREATE TABLE `matches` (
    `id` INTEGER PRIMARY KEY AUTO_INCREMENT,
    `ladder_id` INTEGER UNSIGNED NOT NULL, 
    `date` INTEGER NOT NULL,
    `winner_id` INTEGER UNSIGNED NOT NULL, 
    `loser_id` INTEGER UNSIGNED NOT NULL,
    `forfeit` INTEGER NOT NULL DEFAULT 0,
    KEY `ladder_id_idx` (`ladder_id`),
    KEY `winner_id_idx` (`winner_id`),
    KEY `loser_id_idx` (`loser_id`),
    FOREIGN KEY (`ladder_id`) REFERENCES `ladders` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`winner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`loser_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `rank_history` (
    `user_id` INTEGER UNSIGNED NOT NULL,
    `ladder_id` INTEGER UNSIGNED NOT NULL, 
    `date` INTEGER NOT NULL,
    `rank` INTEGER NOT NULL,
    KEY `user_id_idx` (`user_id`),
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS  `sessions` (
    session_id varchar(40) DEFAULT '0' NOT NULL,
    ip_address varchar(16) DEFAULT '0' NOT NULL,
    user_agent varchar(50) NOT NULL,
    last_activity INTEGER UNSIGNED DEFAULT 0 NOT NULL,
    user_data text NOT NULL,
    PRIMARY KEY (session_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


INSERT INTO `ladders`(id, name, code, challenge_window, window_direction) VALUES
(1, 'Zulu Table Tennis Ladder', 'test', 2, 0),
(2, 'Yankee', '', 2, 0);

INSERT INTO `users`(id, name, email, password, site_admin, ladder_id, max_challenges, status, created_at) VALUES 
(1,'Andy Sennheiser','a@a.com','ae2b134d94a1a0631a66c817ebb11a3b',0,1,255,0, UNIX_TIMESTAMP('2010-09-18')),
(2,'Bob Tannenbaum','b@b.com','ae2b134d94a1a0631a66c817ebb11a3b',0,1,255,0,UNIX_TIMESTAMP('2010-09-18')),
(3,'Chuck Bailey','c@c.com','ae2b134d94a1a0631a66c817ebb11a3b',0,1,255,0,UNIX_TIMESTAMP('2010-09-18')),
(4,'Dale Court','d@d.com','ae2b134d94a1a0631a66c817ebb11a3b',0,1,255,0,UNIX_TIMESTAMP('2010-09-18')),
(5,'Edward Jones','e@e.com','ae2b134d94a1a0631a66c817ebb11a3b',0,1,255,0,UNIX_TIMESTAMP('2010-09-18')),
(6,'Frank Kennedy','f@f.com','ae2b134d94a1a0631a66c817ebb11a3b',0,1,255,0,UNIX_TIMESTAMP('2010-09-18'));

INSERT INTO `challenges`(id, ladder_id, player1_id, player2_id) VALUES
(1, 1, 1, 2),
#(2, 1, 3, 1),
(3, 1, 3, 2);


INSERT INTO `ladder_users`(user_id, ladder_id, rank, wins, losses) VALUES
(1, 1, 3, 1, 3),
(2, 1, 2, 2, 4),
(3, 1, 1, 3, 4),
(4, 1, 4, 4, 4),
(5, 1, 5, 5, 4),
(6, 1, 6, 6, 4);

INSERT INTO `matches`(ladder_id, winner_id, loser_id, date) VALUES
(1, 1, 3, UNIX_TIMESTAMP('2010-09-18')),
(1, 1, 2, UNIX_TIMESTAMP('2010-09-18')),
(1, 1, 2, UNIX_TIMESTAMP('2010-09-18')),
(1, 1, 2, UNIX_TIMESTAMP('2010-09-18')),
(1, 2, 3, UNIX_TIMESTAMP('2010-09-18'));

