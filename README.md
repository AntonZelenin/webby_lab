# webby_lab

CREATE DATABASE webby_lab_task;
USE DATABASE webby_lab_task;

CREATE TABLE `actors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unq` (`first_name`,`last_name`)
);

CREATE TABLE `formats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `format` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `format` (`format`)
);

CREATE TABLE `movies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `year` int(11) NOT NULL,
  `format` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unq` (`name`,`year`,`format`),
  FOREIGN KEY (`format`) REFERENCES `formats` (`id`) ON DELETE RESTRICT
);

CREATE TABLE `movies_actors` (
  `movie_id` int(11) NOT NULL,
  `actor_id` int(11) NOT NULL,
  UNIQUE KEY `unq` (`movie_id`,`actor_id`),
  KEY `actor_id` (`actor_id`),
  FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`actor_id`) REFERENCES `actors` (`id`) ON DELETE RESTRICT
);
