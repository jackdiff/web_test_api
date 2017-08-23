
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_masters`;

CREATE TABLE `user_masters` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(45) NOT NULL,
  `password` varchar(64) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `user_masters` (`user_id`, `email`, `password`, `created_at`, `updated_at`)
VALUES
    (1,'truongpxc@gmail.com','32e8512c007faaf409e0845a6f6a980d','2017-08-22 10:48:42','2017-08-22 10:48:42');

# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_params`;

CREATE TABLE `user_params` (
  `user_id` int(10) unsigned NOT NULL,
  `name` varchar(45) NOT NULL,
  `address` varchar(150) DEFAULT NULL,
  `phone_number` varchar(150) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `user_params` (`user_id`, `name`, `address`, `phone_number`, `dob`, `created_at`, `updated_at`)
VALUES
    (1,'Trường Xuân Phạm','241 Xuan Thuy, Cau Giay','0993653324','1990-01-21','2017-08-22 10:49:17','2017-08-23 01:57:53');

# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_tokens`;

CREATE TABLE `user_tokens` (
  `user_id` int(11) NOT NULL,
  `token_seq` text NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `user_tokens` (`user_id`, `token_seq`, `created_at`, `updated_at`)
VALUES
    (1,'37d18cc5548933fe13400cce0418785e','2017-08-22 06:58:03','2017-08-22 15:30:07');
